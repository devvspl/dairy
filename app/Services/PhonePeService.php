<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class PhonePeService
{
    private const SANDBOX_PAY_URL    = 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay';
    private const SANDBOX_STATUS_URL = 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status';
    private const PROD_PAY_URL       = 'https://api.phonepe.com/apis/pg/v1/pay';
    private const PROD_STATUS_URL    = 'https://api.phonepe.com/apis/pg/v1/status';

    private string $merchantId;
    private string $saltKey;
    private int    $saltIndex;
    private string $payUrl;
    private string $statusUrl;

    public function __construct()
    {
        $this->merchantId = config('services.phonepe.merchant_id');
        $this->saltKey    = config('services.phonepe.salt_key');
        $this->saltIndex  = (int) config('services.phonepe.salt_index', 1);
        $isSandbox        = config('services.phonepe.sandbox', true);

        $this->payUrl    = $isSandbox ? self::SANDBOX_PAY_URL    : self::PROD_PAY_URL;
        $this->statusUrl = $isSandbox ? self::SANDBOX_STATUS_URL : self::PROD_STATUS_URL;

        if (empty($this->merchantId) || empty($this->saltKey)) {
            throw new Exception('PhonePe merchant_id or salt_key is not configured.');
        }
    }

    // ── cURL helper ───────────────────────────────────────────────

    private function curlRequest(string $method, string $url, array $headers, ?string $body = null): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $method,
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
            'raw'         => $response,
        ];
    }

    // ── X-VERIFY signature ────────────────────────────────────────

    private function generateXVerify(string $base64Payload, string $endpoint): string
    {
        return hash('sha256', $base64Payload . $endpoint . $this->saltKey) . '###' . $this->saltIndex;
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
                'amount'                => (int) round($amount * 100),
                'redirectUrl'           => route('payment.callback'),
                'redirectMode'          => 'POST',
                'callbackUrl'           => route('payment.callback'),
                'mobileNumber'          => $userPhone,
                'paymentInstrument'     => ['type' => 'PAY_PAGE'],
            ];

            $base64Payload = base64_encode(json_encode($payload));
            $xVerify       = $this->generateXVerify($base64Payload, '/pg/v1/pay');
            $json_request  = json_encode(['request' => $base64Payload]);

            Log::info('PhonePe: Initiating payment', [
                'order_id' => $orderId,
                'amount'   => $amount,
                'url'      => $this->payUrl,
                'x_verify' => $xVerify,
            ]);

            $response = $this->curlRequest('POST', $this->payUrl, [
                'Content-Type: application/json',
                'X-VERIFY: ' . $xVerify,
                'accept: application/json',
            ], $json_request);

            $statusCode = $response['status_code'];
            $result     = $response['body'];

            Log::info('PhonePe: Payment initiation response', [
                'order_id'    => $orderId,
                'status_code' => $statusCode,
                'response'    => $result,
            ]);

            if ($statusCode !== 200 || !($result['success'] ?? false)) {
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
            $url      = $this->statusUrl . "/{$this->merchantId}/{$merchantTransactionId}";

            $response = $this->curlRequest('GET', $url, [
                'Content-Type: application/json',
                'X-VERIFY: ' . $xVerify,
                'X-MERCHANT-ID: ' . $this->merchantId,
                'accept: application/json',
            ]);

            $statusCode = $response['status_code'];
            $result     = $response['body'];

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
