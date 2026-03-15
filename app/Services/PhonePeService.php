<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PhonePeService
{
    private const AUTH_URL = 'https://api.phonepe.com/apis/identity-manager';
    private const PG_URL   = 'https://api.phonepe.com/apis/pg';

    private string $clientId;
    private string $clientSecret;
    private int    $clientVersion;

    public function __construct()
    {
        $this->clientId      = config('services.phonepe.client_id');
        $this->clientSecret  = config('services.phonepe.client_secret');
        $this->clientVersion = (int) config('services.phonepe.client_version', 1);

        if (empty($this->clientId) || empty($this->clientSecret)) {
            throw new Exception('PhonePe credentials are not configured.');
        }
    }

    // ── OAuth Token (cached for 55 min) ──────────────────────────

    private function getAccessToken(): string
    {
        return Cache::remember('phonepe_token_' . $this->clientId, now()->addMinutes(55), function () {
            $response = Http::timeout(15)
                ->asForm()
                ->post(self::AUTH_URL . '/v1/oauth/token', [
                    'client_id'      => $this->clientId,
                    'client_secret'  => $this->clientSecret,
                    'client_version' => $this->clientVersion,
                    'grant_type'     => 'client_credentials',
                ]);

            if (!$response->successful()) {
                Log::error('PhonePe: OAuth token request failed', [
                    'status'   => $response->status(),
                    'response' => $response->json(),
                ]);
                throw new Exception('PhonePe authentication failed.');
            }

            $token = $response->json('access_token');

            if (empty($token)) {
                throw new Exception('PhonePe returned empty access token.');
            }

            return $token;
        });
    }

    // ── Initiate Payment → POST /checkout/v2/pay ─────────────────

    public function initiatePayment(
        string $orderId,
        float  $amount,
        int    $userId,
        string $userName,
        string $userPhone
    ): array {
        try {
            $payload = [
                'merchantOrderId' => $orderId,
                'amount'          => (int) round($amount * 100),
                'expireAfter'     => 1200,
                'metaInfo'        => [
                    'udf1' => (string) $userId,
                    'udf2' => $userName,
                    'udf3' => $userPhone,
                ],
                'paymentFlow' => [
                    'type'         => 'PG_CHECKOUT',
                    'message'      => 'Membership Payment',
                    'merchantUrls' => [
                        'redirectUrl' => route('payment.callback'),
                    ],
                ],
            ];

            $response = Http::timeout(30)
                ->withToken($this->getAccessToken())
                ->acceptJson()
                ->post(self::PG_URL . '/checkout/v2/pay', $payload);

            $result = $response->json();

            if (!$response->successful() || empty($result['redirectUrl'])) {
                Log::error('PhonePe: Payment initiation failed', [
                    'order_id'    => $orderId,
                    'status_code' => $response->status(),
                    'response'    => $result,
                ]);

                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Payment initiation failed.',
                    'code'    => $result['code'] ?? 'HTTP_' . $response->status(),
                ];
            }

            Log::info('PhonePe: Payment initiated', [
                'order_id'     => $orderId,
                'redirect_url' => $result['redirectUrl'],
            ]);

            return [
                'success'      => true,
                'redirect_url' => $result['redirectUrl'],
                'order_id'     => $result['orderId'] ?? $orderId,
                'state'        => $result['state'] ?? null,
                'data'         => $result,
            ];

        } catch (Exception $e) {
            Log::error('PhonePe: initiatePayment exception', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Payment gateway error. Please try again.',
                'code'    => 'EXCEPTION',
            ];
        }
    }

    // ── Order Status → GET /checkout/v2/order/{id}/status ────────

    public function verifyPayment(string $merchantOrderId): array
    {
        try {
            $response = Http::timeout(30)
                ->withToken($this->getAccessToken())
                ->acceptJson()
                ->get(self::PG_URL . "/checkout/v2/order/{$merchantOrderId}/status");

            $result = $response->json();

            if (!$response->successful()) {
                Log::error('PhonePe: Order status check failed', [
                    'order_id'    => $merchantOrderId,
                    'status_code' => $response->status(),
                    'response'    => $result,
                ]);

                return [
                    'success' => false,
                    'state'   => 'FAILED',
                    'message' => $result['message'] ?? 'Status check failed.',
                    'code'    => $result['code'] ?? 'HTTP_' . $response->status(),
                    'data'    => null,
                ];
            }

            $state = $result['state'] ?? 'FAILED';

            Log::info('PhonePe: Order status', [
                'order_id' => $merchantOrderId,
                'state'    => $state,
            ]);

            return [
                'success' => $state === 'COMPLETED',
                'state'   => $state,
                'code'    => $result['code'] ?? null,
                'message' => $result['message'] ?? null,
                'data'    => $result,
            ];

        } catch (Exception $e) {
            Log::error('PhonePe: verifyPayment exception', [
                'order_id' => $merchantOrderId,
                'error'    => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'state'   => 'FAILED',
                'message' => 'Payment verification error.',
                'code'    => 'EXCEPTION',
                'data'    => null,
            ];
        }
    }

    // ── Refund → POST /payments/v2/refund ────────────────────────

    public function refund(string $merchantOrderId, string $refundId, float $amount): array
    {
        try {
            $response = Http::timeout(30)
                ->withToken($this->getAccessToken())
                ->acceptJson()
                ->post(self::PG_URL . '/payments/v2/refund', [
                    'merchantRefundId' => $refundId,
                    'originalOrderId'  => $merchantOrderId,
                    'amount'           => (int) round($amount * 100),
                ]);

            $result = $response->json();

            Log::info('PhonePe: Refund response', [
                'order_id'  => $merchantOrderId,
                'refund_id' => $refundId,
                'state'     => $result['state'] ?? null,
            ]);

            return [
                'success' => $response->successful(),
                'message' => $result['message'] ?? null,
                'data'    => $result,
            ];

        } catch (Exception $e) {
            Log::error('PhonePe: refund exception', [
                'order_id' => $merchantOrderId,
                'error'    => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'Refund request failed.'];
        }
    }

    // ── Refund Status → GET /payments/v2/refund/{id}/status ──────

    public function refundStatus(string $merchantRefundId): array
    {
        try {
            $response = Http::timeout(30)
                ->withToken($this->getAccessToken())
                ->acceptJson()
                ->get(self::PG_URL . "/payments/v2/refund/{$merchantRefundId}/status");

            $result = $response->json();

            return [
                'success' => $response->successful(),
                'state'   => $result['state'] ?? null,
                'message' => $result['message'] ?? null,
                'data'    => $result,
            ];

        } catch (Exception $e) {
            Log::error('PhonePe: refundStatus exception', ['error' => $e->getMessage()]);

            return ['success' => false, 'message' => 'Refund status check failed.'];
        }
    }

    // ── Webhook signature verification ───────────────────────────
    // PhonePe sends: Authorization: O-Bearer <token>

    public function verifyWebhookToken(string $authorizationHeader): bool
    {
        try {
            $received = preg_replace('/^O-Bearer\s+/i', '', trim($authorizationHeader));

            if (empty($received)) {
                Log::warning('PhonePe: Empty webhook authorization header');
                return false;
            }

            $expected = $this->getAccessToken();

            return hash_equals($expected, $received);

        } catch (Exception $e) {
            Log::error('PhonePe: verifyWebhookToken exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
