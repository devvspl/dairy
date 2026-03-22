<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OtpService
{
    protected $userId;
    protected $password;
    protected $senderId;
    protected $templateId;
    protected $entityId;

    public function __construct()
    {
        $this->userId     = config('services.otp.user_id');
        $this->password   = config('services.otp.password');
        $this->senderId   = config('services.otp.sender_id');
        $this->templateId = config('services.otp.template_id');
        $this->entityId   = config('services.otp.entity_id');
    }

    /**
     * Check if OTP service is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->userId) && !empty($this->password);
    }

    /**
     * Send OTP to mobile number
     */
    public function sendOtp(string $mobile, string $otp): array
    {
        if (!$this->isConfigured()) {
            Log::info('OTP (Dev Mode)', ['mobile' => $mobile, 'otp' => $otp]);
            return ['success' => true, 'message' => 'OTP sent (Development Mode)', 'otp' => $otp];
        }

        $message = "Your OTP is {$otp}. Valid for 10 minutes. Do not share with anyone.";

        try {
            $response = Http::get('http://nimbusit.biz/api/SmsApi/SendSingleApi', [
                'UserID'     => $this->userId,
                'Password'   => $this->password,
                'SenderID'   => $this->senderId,
                'Phno'       => $mobile,
                'Msg'        => $message,
                'EntityID'   => $this->entityId,
                'TemplateID' => $this->templateId,
            ]);

            $body = $response->json();

            Log::info('NimbusIT SMS Response', ['mobile' => $mobile, 'response' => $body]);

            $success = isset($body['Status']) && $body['Status'] === 'OK';

            return [
                'success' => $success,
                'message' => $success
                    ? 'OTP sent successfully'
                    : ($body['Response']['Message'] ?? 'Failed to send OTP'),
            ];
        } catch (\Exception $e) {
            Log::error('NimbusIT SMS Error', ['mobile' => $mobile, 'error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Failed to send OTP. Please try again.'];
        }
    }
}
