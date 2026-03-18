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

    protected function token(): ?string
    {
        return Cache::remember('shiprocket_token', 3600 * 8, function () {
            $s = $this->settings();
            if (!$s->email || !$s->password) return null;

            $response = Http::post("{$this->baseUrl}/auth/login", [
                'email'    => $s->email,
                'password' => $s->password,
            ]);

            if ($response->successful()) return $response->json('token');

            Log::error('Shiprocket auth failed', ['response' => $response->body()]);
            return null;
        });
    }

    public function isEnabled(): bool
    {
        return (bool) $this->settings()->enabled;
    }

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

        $response = Http::withToken($token)->post("{$this->baseUrl}/orders/create/adhoc", $payload);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success'     => true,
                'order_id'    => $data['order_id'] ?? null,
                'shipment_id' => $data['shipment_id'] ?? null,
                'status'      => $data['status'] ?? null,
                'awb_code'    => $data['awb_code'] ?? null,
                'courier'     => $data['courier_name'] ?? null,
            ];
        }

        Log::error('Shiprocket create order failed', ['order' => $order->order_id, 'response' => $response->body()]);
        return ['success' => false, 'message' => $response->json('message') ?? 'Failed to create Shiprocket order.'];
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

    public function refreshToken(): void
    {
        Cache::forget('shiprocket_token');
    }
}
