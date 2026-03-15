<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PhonePeService
{
    private const UAT_BASE  = 'https://api-preprod.phonepe.com/apis/pg-sandbox';
    private const PROD_AUTH = 'https://api.phonepe.com/apis/identity-manager';
    private const PROD_PG   = 'https://api.phonepe.com/apis/pg';

    private string $clientId;
    private string $clientSecret;
    private int    $clientVersion;
    private string $authBase;
    private string $pgBase;

    public function __construct()
    {
        $this->clientId      = config('services.phonepe.client_id');
        $this->clientSecret  = config('services.phonepe.client_secret');
        $this->clientVersion = (int) config('services.phonepe.client_version', 1);
        $isUat               = (bool) config('services.phonepe.uat', false);

        if (empty($this->clientId) || empty($this->clientSecret)) {
            throw new Exception('PhonePe credentials are not configured.');
        }

        $this->authBase = $isUat ? self::UAT_BASE : self::PROD_AUTH;
        $this->pgBase   = $isUat ? self::UAT_BASE : self::PROD_PG;
    }

    // ── OAuth Token (cached 55 min, force-refresh on demand) ─────

    private function getAccessToken(bool $forceRefresh = false): string
    {
        $cacheKey = 'phonepe_token_' . md5($this->clientId);

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, now()->addMinutes(55), function () {
            $response = Http::timeout(15)
                ->asForm()
                ->post($this->authBase . '/v1/oauth/token', [
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
                throw new Exception('PhonePe authentication failed. Check client_id and client_secret.');
            }

            $token = $response->json('access_token');

            if (empty($token)) {
                throw new Exception('PhonePe returned empty access token.');
            }

            return $token;
        });
    }

    // Auto-retry once with a fresh token on 401
    private function withAutoRetry(callable $call): array
    {
        $result = $call($this->getAccessToken());

        if (($result['_status_code'] ?? null) === 401) {
            $result = $call($this->getAccessToken(forceRefresh: true));
        }

        unset($result['_status_code']);
        return $result;
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

            return $this->withAutoRetry(function (string $token) use ($orderId, $payload) {
                $response = Http::timeout(30)
                    ->withToken($token)
                    ->acceptJson()
                    ->post($this->pgBase . '/checkout/v2/pay', $payload);

                $result = $response->json();

                if (!$response->successful() || empty($result['redirectUrl'])) {
                    Log::error('PhonePe: Payment initiation failed', [
                        'order_id'    => $orderId,
                        'status_code' => $response->status(),
                        'response'    => $result,
                    ]);

                    return [
                        '_status_code' => $response->status(),
                        'success'      => false,
                        'message'      => $result['message'] ?? 'Payment initiation failed.',
                        'code'         => $result['code'] ?? 'HTTP_' . $response->status(),
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
            });

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
            return $this->withAutoRetry(function (string $token) use ($merchantOrderId) {
                $response = Http::timeout(30)
                    ->withToken($token)
                    ->acceptJson()
                    ->get($this->pgBase . "/checkout/v2/order/{$merchantOrderId}/status");

                $result = $response->json();

                if (!$response->successful()) {
                    Log::error('PhonePe: Order status check failed', [
                        'order_id'    => $merchantOrderId,
                        'status_code' => $response->status(),
                        'response'    => $result,
                    ]);

                    return [
                        '_status_code' => $response->status(),
                        'success'      => false,
                        'state'        => 'FAILED',
                        'message'      => $result['message'] ?? 'Status check failed.',
                        'code'         => $result['code'] ?? 'HTTP_' . $response->status(),
                        'data'         => null,
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
            });

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
            return $this->withAutoRetry(function (string $token) use ($merchantOrderId, $refundId, $amount) {
                $response = Http::timeout(30)
                    ->withToken($token)
                    ->acceptJson()
                    ->post($this->pgBase . '/payments/v2/refund', [
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
                    '_status_code' => $response->status(),
                    'success'      => $response->successful(),
                    'message'      => $result['message'] ?? null,
                    'data'         => $result,
                ];
            });

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
            return $this->withAutoRetry(function (string $token) use ($merchantRefundId) {
                $response = Http::timeout(30)
                    ->withToken($token)
                    ->acceptJson()
                    ->get($this->pgBase . "/payments/v2/refund/{$merchantRefundId}/status");

                $result = $response->json();

                return [
                    '_status_code' => $response->status(),
                    'success'      => $response->successful(),
                    'state'        => $result['state'] ?? null,
                    'message'      => $result['message'] ?? null,
                    'data'         => $result,
                ];
            });

        } catch (Exception $e) {
            Log::error('PhonePe: refundStatus exception', ['error' => $e->getMessage()]);

            return ['success' => false, 'message' => 'Refund status check failed.'];
        }
    }

    // ── Webhook verification (Authorization: O-Bearer <token>) ───

    public function verifyWebhookToken(string $authorizationHeader): bool
    {
        try {
            $received = trim(preg_replace('/^O-Bearer\s+/i', '', trim($authorizationHeader)));

            if (empty($received)) {
                Log::warning('PhonePe: Empty webhook authorization header');
                return false;
            }

            return hash_equals($this->getAccessToken(), $received);

        } catch (Exception $e) {
            Log::error('PhonePe: verifyWebhookToken exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
