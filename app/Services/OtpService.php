<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OtpService
{
    protected $provider;
    protected $apiKey;
    protected $senderId;
    protected $templateId;

    public function __construct()
    {
        $this->provider = config('services.otp.provider');
        $this->apiKey = config('services.otp.api_key');
        $this->senderId = config('services.otp.sender_id');
        $this->templateId = config('services.otp.template_id');
    }

    /**
     * Send OTP to mobile number
     */
    public function sendOtp($mobile, $otp)
    {
        // Development mode - just log the OTP
        if (!$this->isConfigured()) {
            Log::info('OTP Sent (Development Mode)', [
                'mobile' => $mobile,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10)
            ]);

            return [
                'success' => true,
                'message' => 'OTP sent successfully (Development Mode)',
                'otp' => $otp // Only in development
            ];
        }

        // Production mode - send via configured provider
        return $this->sendViaProvider($mobile, $otp);
    }

    /**
     * Check if OTP service is configured
     */
    public function isConfigured()
    {
        return !empty($this->provider) && !empty($this->apiKey);
    }

    /**
     * Send OTP via configured provider
     */
    private function sendViaProvider($mobile, $otp)
    {
        try {
            switch ($this->provider) {
                case 'msg91':
                    return $this->sendViaMSG91($mobile, $otp);
                
                case 'fast2sms':
                    return $this->sendViaFast2SMS($mobile, $otp);
                
                case 'twilio':
                    return $this->sendViaTwilio($mobile, $otp);
                
                default:
                    throw new \Exception('Unsupported OTP provider: ' . $this->provider);
            }
        } catch (\Exception $e) {
            Log::error('OTP Send Error', [
                'mobile' => $mobile,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ];
        }
    }

    /**
     * Send OTP via MSG91
     */
    private function sendViaMSG91($mobile, $otp)
    {
        $response = Http::post('https://api.msg91.com/api/v5/otp', [
            'authkey' => $this->apiKey,
            'mobile' => $mobile,
            'otp' => $otp,
            'sender' => $this->senderId,
            'template_id' => $this->templateId,
        ]);

        return [
            'success' => $response->successful(),
            'message' => $response->successful() ? 'OTP sent successfully' : 'Failed to send OTP'
        ];
    }

    /**
     * Send OTP via Fast2SMS
     */
    private function sendViaFast2SMS($mobile, $otp)
    {
        $message = "Your OTP is: {$otp}. Valid for 10 minutes. Do not share with anyone.";
        
        $response = Http::withHeaders([
            'authorization' => $this->apiKey
        ])->post('https://www.fast2sms.com/dev/bulkV2', [
            'sender_id' => $this->senderId,
            'message' => $message,
            'route' => 'v3',
            'numbers' => $mobile,
        ]);

        return [
            'success' => $response->successful(),
            'message' => $response->successful() ? 'OTP sent successfully' : 'Failed to send OTP'
        ];
    }

    /**
     * Send OTP via Twilio
     */
    private function sendViaTwilio($mobile, $otp)
    {
        $message = "Your OTP is: {$otp}. Valid for 10 minutes.";
        
        // Twilio implementation would go here
        // This is a placeholder for future implementation
        
        return [
            'success' => false,
            'message' => 'Twilio integration pending'
        ];
    }
}
