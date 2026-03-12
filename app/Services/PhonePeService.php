<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhonePeService
{
    private $merchantId;
    private $saltKey;
    private $saltIndex;
    private $apiUrl;
    private $redirectUrl;
    private $callbackUrl;

    public function __construct()
    {
        $this->merchantId = config('services.phonepe.merchant_id');
        $this->saltKey = config('services.phonepe.salt_key');
        $this->saltIndex = config('services.phonepe.salt_index', 1);
        $this->apiUrl = config('services.phonepe.api_url');
        $this->redirectUrl = route('payment.callback');
        $this->callbackUrl = route('payment.callback');
    }

    /**
     * Initiate payment with PhonePe
     */
    public function initiatePayment($orderId, $amount, $userId, $userName, $userPhone)
    {
        $payload = [
            'merchantId' => $this->merchantId,
            'merchantTransactionId' => $orderId,
            'merchantUserId' => 'USER_' . $userId,
            'amount' => $amount * 100, // Convert to paise
            'redirectUrl' => $this->redirectUrl,
            'redirectMode' => 'POST',
            'callbackUrl' => $this->callbackUrl,
            'mobileNumber' => $userPhone,
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

        $jsonPayload = json_encode($payload);
        $base64Payload = base64_encode($jsonPayload);

        // Generate X-VERIFY header
        $xVerify = hash('sha256', $base64Payload . '/pg/v1/pay' . $this->saltKey) . '###' . $this->saltIndex;

        // Debug logging
        Log::info('PhonePe Payment Request Debug', [
            'merchant_id' => $this->merchantId,
            'salt_key_length' => strlen($this->saltKey),
            'api_url' => $this->apiUrl,
            'payload' => $payload,
            'x_verify' => $xVerify
        ]);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-VERIFY' => $xVerify
            ])->post($this->apiUrl . '/pg/v1/pay', [
                'request' => $base64Payload
            ]);

            $result = $response->json();

            Log::info('PhonePe Payment Initiation', [
                'order_id' => $orderId,
                'response' => $result
            ]);

            if ($result['success'] ?? false) {
                return [
                    'success' => true,
                    'data' => $result['data'],
                    'redirect_url' => $result['data']['instrumentResponse']['redirectInfo']['url'] ?? null
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Payment initiation failed',
                'code' => $result['code'] ?? null
            ];

        } catch (\Exception $e) {
            Log::error('PhonePe Payment Initiation Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Payment gateway error: ' . $e->getMessage()
            ];
        }
    }


    /**
     * Verify payment status
     */
    public function verifyPayment($merchantTransactionId)
    {
        $endpoint = "/pg/v1/status/{$this->merchantId}/{$merchantTransactionId}";
        $xVerify = hash('sha256', $endpoint . $this->saltKey) . '###' . $this->saltIndex;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-VERIFY' => $xVerify
            ])->get($this->apiUrl . $endpoint);

            $result = $response->json();

            Log::info('PhonePe Payment Verification', [
                'transaction_id' => $merchantTransactionId,
                'response' => $result
            ]);

            return [
                'success' => $result['success'] ?? false,
                'code' => $result['code'] ?? null,
                'message' => $result['message'] ?? null,
                'data' => $result['data'] ?? null
            ];

        } catch (\Exception $e) {
            Log::error('PhonePe Payment Verification Error', [
                'transaction_id' => $merchantTransactionId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Verification error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify callback signature
     */
    public function verifyCallbackSignature($base64Response, $xVerifyHeader)
    {
        $expectedSignature = hash('sha256', $base64Response . $this->saltKey) . '###' . $this->saltIndex;
        return hash_equals($expectedSignature, $xVerifyHeader);
    }
}
