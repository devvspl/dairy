<?php

namespace App\Services;

use App\Models\ShiprocketSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShiprocketService
{
    protected string $baseUrl = 'https://apiv2.shiprocket.in/v1/external';

    protected function settings(): ShiprocketSetting
    {
        return ShiprocketSetting::instance();
    }

    /**
     * Get a valid auth token.
     * Token is valid for 240 hours (10 days) per Shiprocket docs.
     * We cache for 239 hours to refresh slightly before expiry.
     * Null is never cached — failed auth retries on next call.
     */
    protected function token(): ?string
    {
        $cached = Cache::get('shiprocket_token');
        if ($cached) {
            return $cached;
        }

        $email    = config('services.shiprocket.email');
        $password = config('services.shiprocket.password');

        if (!$email || !$password) {
            Log::error('Shiprocket auth failed: SHIPROCKET_EMAIL or SHIPROCKET_PASSWORD not set in .env');
            return null;
        }

        $response = Http::post("{$this->baseUrl}/auth/login", [
            'email'    => $email,
            'password' => $password,
        ]);

        if ($response->successful() && $token = $response->json('token')) {
            // Cache for 239 hours (just under the 240h expiry)
            Cache::put('shiprocket_token', $token, 3600 * 239);
            return $token;
        }

        Log::error('Shiprocket auth failed', [
            'status'   => $response->status(),
            'response' => $response->body(),
        ]);
        return null;
    }

    public function isEnabled(): bool
    {
        return (bool) $this->settings()->enabled;
    }

    /**
     * Full Shiprocket flow per API docs:
     * 1. Create order  → get order_id + shipment_id
     * 2. Assign AWB    → get awb_code + courier_name
     * 3. Generate pickup
     */
    public function createOrder(\App\Models\ProductOrder $order): array
    {
        $token = $this->token();
        if (!$token) {
            return ['success' => false, 'message' => 'Shiprocket authentication failed. Check credentials in settings.'];
        }

        $s = $this->settings();

        $orderItems = collect($order->items)->map(fn($item) => [
            'name'          => $item['name'],
            'sku'           => $item['sku'] ?? 'SKU-' . ($item['id'] ?? rand(1000, 9999)),
            'units'         => $item['quantity'],
            'selling_price' => $item['price'],
        ])->values()->toArray();

        $payload = [
            'order_id'              => $order->order_id,
            'order_date'            => $order->created_at->format('Y-m-d H:i'),
            'pickup_location'       => $s->pickup_location ?: 'Primary',
            'channel_id'            => '',
            'billing_customer_name' => $order->customer_name,
            'billing_last_name'     => '',
            'billing_address'       => $order->delivery_address ?: 'N/A',
            'billing_address_2'     => '',
            'billing_city'          => $s->default_city ?: 'Mumbai',
            'billing_pincode'       => $s->default_pincode ?: '400001',
            'billing_state'         => $s->default_state ?: 'Maharashtra',
            'billing_country'       => 'India',
            'billing_email'         => $order->customer_email ?? '',
            'billing_phone'         => $order->customer_phone,
            'shipping_is_billing'   => true,
            'order_items'           => $orderItems,
            'payment_method'        => $order->payment_method === 'cod' ? 'COD' : 'Prepaid',
            'sub_total'             => $order->amount,
            'length'                => $s->pkg_length,
            'breadth'               => $s->pkg_breadth,
            'height'                => $s->pkg_height,
            'weight'                => $s->pkg_weight,
        ];

        // Step 1: Create order
        $response = Http::withToken($token)->post("{$this->baseUrl}/orders/create/adhoc", $payload);

        $data = $response->json();

        Log::info('Shiprocket create order response', ['order' => $order->order_id, 'response' => $data]);

        // Shiprocket sometimes returns 200 with an error message and no IDs
        $apiMessage = $data['message'] ?? null;

        if (!$response->successful() || isset($data['errors'])) {
            Log::error('Shiprocket create order failed', [
                'order'    => $order->order_id,
                'status'   => $response->status(),
                'response' => $data,
            ]);
            return ['success' => false, 'message' => $apiMessage ?? 'Failed to create Shiprocket order.'];
        }

        $srOrderId  = $data['order_id']    ?? $data['payload']['order_id']    ?? null;
        $shipmentId = $data['shipment_id'] ?? $data['payload']['shipment_id'] ?? null;

        if (!$srOrderId || !$shipmentId) {
            Log::error('Shiprocket create order: missing order_id or shipment_id', ['response' => $data]);
            // Surface Shiprocket's own message if present (e.g. wrong pickup location)
            return ['success' => false, 'message' => $apiMessage ?? 'Shiprocket order created but missing order/shipment ID.'];
        }

        // Step 2: Assign AWB
        $awbResponse = Http::withToken($token)->post("{$this->baseUrl}/courier/assign/awb", [
            'shipment_id' => $shipmentId,
        ]);

        $awbCode     = null;
        $courierName = null;

        if ($awbResponse->successful()) {
            $awbData     = $awbResponse->json();
            $awbCode     = $awbData['response']['data']['awb_code']      ?? $awbData['awb_code']      ?? null;
            $courierName = $awbData['response']['data']['courier_name']  ?? $awbData['courier_name']  ?? null;
        } else {
            Log::warning('Shiprocket AWB assignment failed', [
                'order'    => $order->order_id,
                'status'   => $awbResponse->status(),
                'response' => $awbResponse->body(),
            ]);
        }

        // Step 3: Generate pickup (best-effort, don't fail the whole flow)
        if ($awbCode) {
            $pickupResponse = Http::withToken($token)->post("{$this->baseUrl}/courier/generate/pickup", [
                'shipment_id' => [$shipmentId],
            ]);

            if (!$pickupResponse->successful()) {
                Log::warning('Shiprocket pickup generation failed', [
                    'order'    => $order->order_id,
                    'response' => $pickupResponse->body(),
                ]);
            }
        }

        return [
            'success'     => true,
            'order_id'    => $srOrderId,
            'shipment_id' => $shipmentId,
            'status'      => $data['status'] ?? 'NEW',
            'awb_code'    => $awbCode,
            'courier'     => $courierName,
        ];
    }

    public function trackOrder(string $awb): array
    {
        $token = $this->token();
        if (!$token) return ['success' => false, 'message' => 'Auth failed'];

        $response = Http::withToken($token)->get("{$this->baseUrl}/courier/track/awb/{$awb}");

        if ($response->successful()) {
            return ['success' => true, 'data' => $response->json()];
        }
        return ['success' => false, 'message' => $response->json('message') ?? 'Tracking failed.'];
    }

    public function cancelOrder(string $shiprocketOrderId): array
    {
        $token = $this->token();
        if (!$token) return ['success' => false, 'message' => 'Auth failed'];

        $response = Http::withToken($token)->post("{$this->baseUrl}/orders/cancel", [
            'ids' => [$shiprocketOrderId],
        ]);

        if ($response->successful()) return ['success' => true];
        return ['success' => false, 'message' => $response->json('message') ?? 'Cancel failed.'];
    }

    /**
     * Generate and return the shipping label PDF URL.
     */
    public function generateLabel(string $shipmentId): array
    {
        $token = $this->token();
        if (!$token) return ['success' => false, 'message' => 'Auth failed'];

        $response = Http::withToken($token)->post("{$this->baseUrl}/courier/generate/label", [
            'shipment_id' => [$shipmentId],
        ]);

        if ($response->successful()) {
            return ['success' => true, 'label_url' => $response->json('label_url') ?? null];
        }
        return ['success' => false, 'message' => $response->json('message') ?? 'Label generation failed.'];
    }

    /**
     * Generate and return the invoice PDF URL.
     */
    public function generateInvoice(string $orderId): array
    {
        $token = $this->token();
        if (!$token) return ['success' => false, 'message' => 'Auth failed'];

        $response = Http::withToken($token)->post("{$this->baseUrl}/orders/print/invoice", [
            'ids' => [$orderId],
        ]);

        if ($response->successful()) {
            return ['success' => true, 'invoice_url' => $response->json('invoice_url') ?? null];
        }
        return ['success' => false, 'message' => $response->json('message') ?? 'Invoice generation failed.'];
    }

    public function refreshToken(): void
    {
        Cache::forget('shiprocket_token');
    }
}
