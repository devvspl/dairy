<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PhonePeService
{
    private const SANDBOX_URL    = 'https://api-preprod.phonepe.com/apis/pg-sandbox';
    private const PRODUCTION_URL = 'https://api.phonepe.com/apis/pg';

    private string $merchantId;
    private string $saltKey;
    private int    $saltIndex;
    private string $baseUrl;

    public function __construct()
    {
        $this->merchantId = config('services.phonepe.merchant_id');
        $this->saltKey    = config('services.phonepe.salt_key');
        $this->saltIndex  = (int) config('services.phonepe.salt_index', 1);
        $this->baseUrl    = config('services.phonepe.sandbox', true)
                            ? self::SANDBOX_URL
                            : self::PRODUCTION_URL;

        if (empty($this->merchantId) || empty($this->saltKey)) {
            throw new Exception('PhonePe merchant_id or salt_key is not configured.');
        }
    }

    // ── X-VERIFY signature ────────────────────────────────────────
    // SHA256(base64Payload + "/pg/v1/pay" + saltKey) + "###" + saltIndex

    private function generateXVerify(string $base64Payload, string $endpoint): string
    {
        $hash = hash('sha256', $base64Payload . $endpoint . $this->saltKey);
        return $hash . '###' . $this->saltIndex;
    }

    // ── Initiate Payment → POST /pg/v1/pay ───────────────────────

    public function initiatePayment(
        string $orderId,
        float  $amount,
        int    $userId,
        string $userName,
        string $userPhone
    ): array {
        try {
            $payload = [
                'merchantId'            => $this->merchantId,
                'merchantTransactionId' => $orderId,
                'merchantUserId'        => 'USER_' . $userId,
                'amount'                => (int) round($amount * 100), // paise
                'redirectUrl'           => route('payment.callback'),
                'redirectMode'          => 'POST',
                'callbackUrl'           => route('payment.callback'),
                'mobileNumber'          => $userPhone,
                'paymentInstrument'     => ['type' => 'PAY_PAGE'],
            ];

            $jsonPayload    = json_encode($payload);
            $base64Payload  = base64_encode($jsonPayload);
            $endpoint       = '/pg/v1/pay';
            $xVerify        = $this->generateXVerify($base64Payload, $endpoint);

            Log::info('PhonePe: Initiating payment', [
                'order_id'   => $orderId,
                'amount'     => $amount,
                'endpoint'   => $this->baseUrl . $endpoint,
                'x_verify'   => $xVerify,
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-VERIFY'     => $xVerify,
                    'accept'       => 'application/json',
                ])
                ->post($this->baseUrl . $endpoint, ['request' => $base64Payload]);

            $statusCode = $response->status();
            $result     = $response->json();

            Log::info('PhonePe: Payment initiation response', [
                'order_id'    => $orderId,
                'status_code' => $statusCode,
                'response'    => $result,
            ]);

            if (!$response->successful() || !($result['success'] ?? false)) {
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

            $redirectUrl = $result['data']['instrumentResponse']['redirectInfo']['url'] ?? null;

            if (empty($redirectUrl)) {
                Log::error('PhonePe: Redirect URL missing', [
                    'order_id' => $orderId,
                    'response' => $result,
                ]);

                return [
                    'success' => false,
                    'message' => 'Payment redirect URL not found.',
                    'code'    => 'REDIRECT_URL_MISSING',
                ];
            }

            return [
                'success'      => true,
                'redirect_url' => $redirectUrl,
                'data'         => $result['data'],
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

    // ── Verify Payment → GET /pg/v1/status/{merchantId}/{txnId} ──

    public function verifyPayment(string $merchantTransactionId): array
    {
        try {
            $endpoint = "/pg/v1/status/{$this->merchantId}/{$merchantTransactionId}";
            $xVerify  = hash('sha256', $endpoint . $this->saltKey) . '###' . $this->saltIndex;

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-VERIFY'     => $xVerify,
                    'X-MERCHANT-ID'=> $this->merchantId,
                    'accept'       => 'application/json',
                ])
                ->get($this->baseUrl . $endpoint);

            $statusCode = $response->status();
            $result     = $response->json();

            Log::info('PhonePe: Payment verification response', [
                'transaction_id' => $merchantTransactionId,
                'status_code'    => $statusCode,
                'code'           => $result['code'] ?? null,
            ]);

            $state = $result['data']['state'] ?? null;

            return [
                'success' => ($result['success'] ?? false) && $state === 'COMPLETED',
                'state'   => $state,
                'code'    => $result['code'] ?? null,
                'message' => $result['message'] ?? null,
                'data'    => $result['data'] ?? null,
            ];

        } catch (Exception $e) {
            Log::error('PhonePe: verifyPayment exception', [
                'transaction_id' => $merchantTransactionId,
                'error'          => $e->getMessage(),
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

    // ── Verify callback X-VERIFY signature ───────────────────────

    public function verifyCallbackSignature(string $base64Response, string $xVerifyHeader): bool
    {
        try {
            $expected = hash('sha256', $base64Response . $this->saltKey) . '###' . $this->saltIndex;
            return hash_equals($expected, $xVerifyHeader);
        } catch (Exception $e) {
            Log::error('PhonePe: verifyCallbackSignature exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
