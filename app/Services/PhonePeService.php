<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PhonePeService
{
    private $merchantId;
    private $saltKey;
    private $saltIndex;
    private $apiUrl;
    private $redirectUrl;
    private $callbackUrl;
    private $isDebugMode;

    public function __construct()
    {
        $this->merchantId = config('services.phonepe.merchant_id');
        $this->saltKey = config('services.phonepe.salt_key');
        $this->saltIndex = config('services.phonepe.salt_index', 1);
        $this->apiUrl = config('services.phonepe.api_url');
        $this->redirectUrl = route('payment.callback');
        $this->callbackUrl = route('payment.callback');
        $this->isDebugMode = config('app.debug', false);

        // Validate configuration
        $this->validateConfiguration();
    }

    /**
     * Validate PhonePe configuration
     */
    private function validateConfiguration()
    {
        if (empty($this->merchantId)) {
            Log::error('PhonePe Configuration Error: Merchant ID is not set');
            throw new Exception('PhonePe Merchant ID is not configured');
        }

        if (empty($this->saltKey)) {
            Log::error('PhonePe Configuration Error: Salt Key is not set');
            throw new Exception('PhonePe Salt Key is not configured');
        }

        if (empty($this->apiUrl)) {
            Log::error('PhonePe Configuration Error: API URL is not set');
            throw new Exception('PhonePe API URL is not configured');
        }

        if ($this->isDebugMode) {
            Log::debug('PhonePe Configuration Loaded', [
                'merchant_id' => $this->merchantId,
                'salt_key_length' => strlen($this->saltKey),
                'salt_index' => $this->saltIndex,
                'api_url' => $this->apiUrl,
                'redirect_url' => $this->redirectUrl,
                'callback_url' => $this->callbackUrl
            ]);
        }
    }

    /**
     * Initiate payment with PhonePe
     */
    public function initiatePayment($orderId, $amount, $userId, $userName, $userPhone)
    {
        try {
            // Validate input parameters
            $this->validatePaymentInput($orderId, $amount, $userId, $userName, $userPhone);

            // Prepare payload
            $payload = [
                'merchantId' => $this->merchantId,
                'merchantTransactionId' => $orderId,
                'merchantUserId' => 'USER_' . $userId,
                'amount' => (int)($amount * 100), // Convert to paise
                'redirectUrl' => $this->redirectUrl,
                'redirectMode' => 'POST',
                'callbackUrl' => $this->callbackUrl,
                'mobileNumber' => $userPhone,
                'paymentInstrument' => [
                    'type' => 'PAY_PAGE'
                ]
            ];

            if ($this->isDebugMode) {
                Log::debug('PhonePe Payment Payload', [
                    'order_id' => $orderId,
                    'payload' => $payload
                ]);
            }

            // Encode payload
            $jsonPayload = json_encode($payload);
            $base64Payload = base64_encode($jsonPayload);

            // Generate X-VERIFY header
            $stringToHash = $base64Payload . '/pg/v1/pay' . $this->saltKey;
            $xVerify = hash('sha256', $stringToHash) . '###' . $this->saltIndex;

            if ($this->isDebugMode) {
                Log::debug('PhonePe Request Details', [
                    'endpoint' => $this->apiUrl . '/pg/v1/pay',
                    'base64_payload_length' => strlen($base64Payload),
                    'x_verify' => $xVerify
                ]);
            }

            // Make API request
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-VERIFY' => $xVerify,
                    'accept' => 'application/json'
                ])
                ->post($this->apiUrl . '/pg/v1/pay', [
                    'request' => $base64Payload
                ]);

            // Get response
            $statusCode = $response->status();
            $result = $response->json();

            // Log response
            Log::info('PhonePe Payment Initiation Response', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'response' => $result
            ]);

            // Check for HTTP errors
            if ($statusCode !== 200) {
                Log::error('PhonePe API HTTP Error', [
                    'order_id' => $orderId,
                    'status_code' => $statusCode,
                    'response' => $result
                ]);

                return [
                    'success' => false,
                    'message' => 'Payment gateway returned error: ' . ($result['message'] ?? 'Unknown error'),
                    'code' => $result['code'] ?? 'HTTP_ERROR',
                    'status_code' => $statusCode
                ];
            }

            // Check success flag
            if ($result['success'] ?? false) {
                $redirectUrl = $result['data']['instrumentResponse']['redirectInfo']['url'] ?? null;

                if (empty($redirectUrl)) {
                    Log::error('PhonePe Redirect URL Missing', [
                        'order_id' => $orderId,
                        'response' => $result
                    ]);

                    return [
                        'success' => false,
                        'message' => 'Payment redirect URL not found',
                        'code' => 'REDIRECT_URL_MISSING'
                    ];
                }

                Log::info('PhonePe Payment Initiated Successfully', [
                    'order_id' => $orderId,
                    'redirect_url' => $redirectUrl
                ]);

                return [
                    'success' => true,
                    'data' => $result['data'],
                    'redirect_url' => $redirectUrl
                ];
            }

            // Payment initiation failed
            Log::warning('PhonePe Payment Initiation Failed', [
                'order_id' => $orderId,
                'code' => $result['code'] ?? 'UNKNOWN',
                'message' => $result['message'] ?? 'Unknown error',
                'response' => $result
            ]);

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Payment initiation failed',
                'code' => $result['code'] ?? 'UNKNOWN_ERROR',
                'data' => $result['data'] ?? []
            ];

        } catch (Exception $e) {
            Log::error('PhonePe Payment Initiation Exception', [
                'order_id' => $orderId ?? 'N/A',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Payment gateway error: ' . $e->getMessage(),
                'code' => 'EXCEPTION'
            ];
        }
    }

    /**
     * Validate payment input parameters
     */
    private function validatePaymentInput($orderId, $amount, $userId, $userName, $userPhone)
    {
        if (empty($orderId)) {
            throw new Exception('Order ID is required');
        }

        if (empty($amount) || $amount <= 0) {
            throw new Exception('Invalid amount');
        }

        if (empty($userId)) {
            throw new Exception('User ID is required');
        }

        if (empty($userPhone)) {
            throw new Exception('User phone number is required');
        }

        // Validate phone number format (10 digits)
        if (!preg_match('/^[0-9]{10}$/', $userPhone)) {
            Log::warning('Invalid phone number format', [
                'phone' => $userPhone,
                'order_id' => $orderId
            ]);
        }
    }

    /**
     * Verify payment status
     */
    public function verifyPayment($merchantTransactionId)
    {
        try {
            if (empty($merchantTransactionId)) {
                throw new Exception('Transaction ID is required');
            }

            $endpoint = "/pg/v1/status/{$this->merchantId}/{$merchantTransactionId}";
            $stringToHash = $endpoint . $this->saltKey;
            $xVerify = hash('sha256', $stringToHash) . '###' . $this->saltIndex;

            if ($this->isDebugMode) {
                Log::debug('PhonePe Verification Request', [
                    'endpoint' => $this->apiUrl . $endpoint,
                    'transaction_id' => $merchantTransactionId,
                    'x_verify' => $xVerify
                ]);
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-VERIFY' => $xVerify,
                    'accept' => 'application/json'
                ])
                ->get($this->apiUrl . $endpoint);

            $statusCode = $response->status();
            $result = $response->json();

            Log::info('PhonePe Payment Verification Response', [
                'transaction_id' => $merchantTransactionId,
                'status_code' => $statusCode,
                'response' => $result
            ]);

            if ($statusCode !== 200) {
                Log::error('PhonePe Verification HTTP Error', [
                    'transaction_id' => $merchantTransactionId,
                    'status_code' => $statusCode,
                    'response' => $result
                ]);
            }

            return [
                'success' => $result['success'] ?? false,
                'code' => $result['code'] ?? null,
                'message' => $result['message'] ?? null,
                'data' => $result['data'] ?? null,
                'status_code' => $statusCode
            ];

        } catch (Exception $e) {
            Log::error('PhonePe Payment Verification Exception', [
                'transaction_id' => $merchantTransactionId ?? 'N/A',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Verification error: ' . $e->getMessage(),
                'code' => 'EXCEPTION'
            ];
        }
    }

    /**
     * Verify callback signature
     */
    public function verifyCallbackSignature($base64Response, $xVerifyHeader)
    {
        try {
            if (empty($base64Response) || empty($xVerifyHeader)) {
                Log::warning('PhonePe Callback Signature Verification: Missing parameters');
                return false;
            }

            $stringToHash = $base64Response . $this->saltKey;
            $expectedSignature = hash('sha256', $stringToHash) . '###' . $this->saltIndex;

            $isValid = hash_equals($expectedSignature, $xVerifyHeader);

            if ($this->isDebugMode) {
                Log::debug('PhonePe Callback Signature Verification', [
                    'expected' => $expectedSignature,
                    'received' => $xVerifyHeader,
                    'is_valid' => $isValid
                ]);
            }

            if (!$isValid) {
                Log::warning('PhonePe Callback Signature Mismatch', [
                    'expected' => $expectedSignature,
                    'received' => $xVerifyHeader
                ]);
            }

            return $isValid;

        } catch (Exception $e) {
            Log::error('PhonePe Callback Signature Verification Exception', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get merchant configuration (for debugging)
     */
    public function getMerchantConfig()
    {
        return [
            'merchant_id' => $this->merchantId,
            'salt_key_configured' => !empty($this->saltKey),
            'salt_key_length' => strlen($this->saltKey),
            'salt_index' => $this->saltIndex,
            'api_url' => $this->apiUrl,
            'redirect_url' => $this->redirectUrl,
            'callback_url' => $this->callbackUrl,
            'debug_mode' => $this->isDebugMode
        ];
    }
}
