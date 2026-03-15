<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class PhonePeService
{
    // OAuth token endpoints
    private const SANDBOX_AUTH_URL = 'https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token';
    private const PROD_AUTH_URL    = 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token';

    // Payment endpoints
    private const SANDBOX_PAY_URL    = 'https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/pay';
    private const PROD_PAY_URL       = 'https://api.phonepe.com/apis/pg/checkout/v2/pay';

    // Order status endpoints
    private const SANDBOX_STATUS_URL = 'https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/order';
    private const PROD_STATUS_URL    = 'https://api.phonepe.com/apis/pg/checkout/v2/order';

    private string $clientId;
    private string $clientSecret;
    private int    $clientVersion;
    private bool   $isSandbox;
    private string $authUrl;
    private string $payUrl;
    private string $statusUrl;

    public function __construct()
    {
        $this->clientId      = config('services.phonepe.client_id');
        $this->clientSecret  = config('services.phonepe.client_secret');
        $this->clientVersion = (int) config('services.phonepe.client_version', 1);
        $this->isSandbox     = (bool) config('services.phonepe.sandbox', true);

        if (empty($this->clientId) || empty($this->clientSecret)) {
            throw new Exception('PhonePe client_id or client_secret is not configured.');
        }

        $this->authUrl   = $this->isSandbox ? self::SANDBOX_AUTH_URL : self::PROD_AUTH_URL;
        $this->payUrl    = $this->isSandbox ? self::SANDBOX_PAY_URL  : self::PROD_PAY_URL;
        $this->statusUrl = $this->isSandbox ? self::SANDBOX_STATUS_URL : self::PROD_STATUS_URL;
    }

    // ── cURL helper ───────────────────────────────────────────────

    private function curlPost(string $url, array $headers, string $body): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => $headers,
        ]);

        $response   = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err        = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception('cURL error: ' . $err);
        }

        return [
            'status_code' => $statusCode,
            'body'        => json_decode($response, true) ?? [],
        ];
    }

    private function curlGet(string $url, array $headers): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => $headers,
        ]);

        $response   = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err        = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception('cURL error: ' . $err);
        }

        return [
            'status_code' => $statusCode,
            'body'        => json_decode($response, true) ?? [],
        ];
    }

    // ── OAuth Token (cached until expires_at) ─────────────────────

    private function getAccessToken(bool $forceRefresh = false): string
    {
        $cacheKey = 'phonepe_token_' . md5($this->clientId);

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, now()->addMinutes(55), function () {
            $body = http_build_query([
                'client_id'      => $this->clientId,
                'client_secret'  => $this->clientSecret,
                'client_version' => $this->clientVersion,
                'grant_type'     => 'client_credentials',
            ]);

            $response = $this->curlPost($this->authUrl, [
                'Content-Type: application/x-www-form-urlencoded',
            ], $body);

            if ($response['status_code'] !== 200 || empty($response['body']['access_token'])) {
                Log::error('PhonePe: OAuth token request failed', [
                    'status'   => $response['status_code'],
                    'response' => $response['body'],
                    'sandbox'  => $this->isSandbox,
                ]);
                throw new Exception('PhonePe authentication failed. Check client_id and client_secret.');
            }

            Log::info('PhonePe: OAuth token obtained', [
                'expires_at' => $response['body']['expires_at'] ?? null,
            ]);

            return $response['body']['access_token'];
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
            $token = $this->getAccessToken();

            // Embed merchantOrderId in redirectUrl so PhonePe preserves it on redirect
            $redirectUrl = route('payment.callback') . '?merchantOrderId=' . urlencode($orderId);

            $payload = [
                'merchantOrderId' => $orderId,
                'amount'          => (int) round($amount * 100), // paise
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
                        'redirectUrl' => $redirectUrl,
                    ],
                ],
            ];

            Log::info('PhonePe: Initiating payment', [
                'order_id' => $orderId,
                'amount'   => $amount,
                'url'      => $this->payUrl,
            ]);

            $response = $this->curlPost($this->payUrl, [
                'Content-Type: application/json',
                'Authorization: O-Bearer ' . $token,
                'accept: application/json',
            ], json_encode($payload));

            $statusCode = $response['status_code'];
            $result     = $response['body'];

            Log::info('PhonePe: Payment initiation response', [
                'order_id'    => $orderId,
                'status_code' => $statusCode,
                'response'    => $result,
            ]);

            // Retry once with fresh token on 401
            if ($statusCode === 401) {
                $token    = $this->getAccessToken(forceRefresh: true);
                $response = $this->curlPost($this->payUrl, [
                    'Content-Type: application/json',
                    'Authorization: O-Bearer ' . $token,
                    'accept: application/json',
                ], json_encode($payload));

                $statusCode = $response['status_code'];
                $result     = $response['body'];
            }

            if ($statusCode !== 200 || empty($result['redirectUrl'])) {
                Log::error('PhonePe: Payment initiation failed', [
                    'order_id'    => $orderId,
                    'status_code' => $statusCode,
                    'code'        => $result['code'] ?? null,
                    'message'     => $result['message'] ?? null,
                ]);

                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Payment initiation failed.',
                    'code'    => $result['code'] ?? 'HTTP_' . $statusCode,
                ];
            }

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
            $token = $this->getAccessToken();
            $url   = $this->statusUrl . "/{$merchantOrderId}/status";

            $response = $this->curlGet($url, [
                'Content-Type: application/json',
                'Authorization: O-Bearer ' . $token,
                'accept: application/json',
            ]);

            $statusCode = $response['status_code'];
            $result     = $response['body'];

            Log::info('PhonePe: Order status response', [
                'order_id'    => $merchantOrderId,
                'status_code' => $statusCode,
                'state'       => $result['state'] ?? null,
            ]);

            $state = $result['state'] ?? 'FAILED';

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

    // ── Webhook token verification ────────────────────────────────

    public function verifyCallbackSignature(string $authorizationHeader): bool
    {
        try {
            $received = trim(str_ireplace('O-Bearer', '', $authorizationHeader));
            if (empty($received)) {
                return false;
            }
            return hash_equals($this->getAccessToken(), $received);
        } catch (Exception $e) {
            Log::error('PhonePe: verifyCallbackSignature exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
