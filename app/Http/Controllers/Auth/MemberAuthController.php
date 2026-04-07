<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MemberAuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show member login form
     */
    public function showLoginForm()
    {
        $otpEnabled = $this->otpService->isConfigured();
        // Store intended redirect in session if passed as query param
        if (request()->has('redirect')) {
            session(['url.intended_member' => request()->query('redirect')]);
        }
        return view('auth.member.login', compact('otpEnabled'));
    }

    /**
     * Show member registration form
     */
    public function showRegisterForm()
    {
        $otpEnabled = $this->otpService->isConfigured();
        return view('auth.member.register', compact('otpEnabled'));
    }

    /**
     * Send OTP to mobile number (Login)
     */
    public function sendLoginOtp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'phone' => 'required|digits:10|exists:users,phone'
        ], [
            'phone.exists' => 'Mobile number not registered. Please register first.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        // Generate OTP
        $otp = $user->generateOtp();

        // In dev mode, still show OTP flow (display OTP in response)
        if (!$this->otpService->isConfigured()) {
            return response()->json([
                'success' => true,
                'message' => 'OTP generated (Development Mode)',
                'otp' => $otp,
            ]);
        }

        // Send OTP
        $result = $this->otpService->sendOtp($request->phone, $otp);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your mobile number',
                'otp' => config('app.debug') ? $otp : null, // Only in debug mode
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to send OTP'
        ], 500);
    }


    /**
     * Verify OTP and login
     */
    public function verifyLoginOtp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'phone' => 'required|digits:10',
            'otp'   => 'required|digits:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($user->verifyOtp($request->otp)) {
            Auth::login($user, true);

            $redirect = session()->pull('url.intended_member', route('member.dashboard'));

            return response()->json([
                'success'  => true,
                'message'  => 'Login successful',
                'redirect' => $redirect,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired OTP'
        ], 400);
    }

    /**
     * Send OTP for registration
     */
    public function sendRegisterOtp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'phone' => 'required|digits:10|unique:users,phone'
        ], [
            'phone.unique' => 'Mobile number already registered. Please login.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Store phone in session for registration
        session(['register_phone' => $request->phone]);

        // If OTP is not configured, generate OTP and show it (dev mode)
        if (!$this->otpService->isConfigured()) {
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            session(['register_otp' => $otp, 'register_otp_expires' => now()->addMinutes(10)]);

            return response()->json([
                'success' => true,
                'message' => 'OTP generated (Development Mode)',
                'otp' => $otp,
            ]);
        }

        // Generate temporary OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        session(['register_otp' => $otp]);
        session(['register_otp_expires' => now()->addMinutes(10)]);

        // Send OTP
        $result = $this->otpService->sendOtp($request->phone, $otp);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your mobile number',
                'otp' => config('app.debug') ? $otp : null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to send OTP'
        ], 500);
    }

    /**
     * Verify OTP and complete registration
     */
    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'phone' => 'required|digits:10|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'otp'   => 'nullable|digits:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $sessionPhone = session('register_phone');

        if (!$sessionPhone) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please start registration again.'
            ], 400);
        }

        if ($request->phone !== $sessionPhone) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number mismatch'
            ], 400);
        }

        // If OTP is configured, verify it
        if ($this->otpService->isConfigured()) {
            $sessionOtp = session('register_otp');
            $otpExpires = session('register_otp_expires');

            if (!$sessionOtp || !$otpExpires) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP session expired. Please request a new OTP.'
                ], 400);
            }

            if ($request->otp !== $sessionOtp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP'
                ], 400);
            }

            if (now()->isAfter($otpExpires)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP expired. Please request a new OTP.'
                ], 400);
            }
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'user_type' => 'Member',
            'mobile_verified_at' => now(),
            'otp_verified_at' => now(),
        ]);

        // Clear session
        session()->forget(['register_phone', 'register_otp', 'register_otp_expires']);

        // Login user
        Auth::login($user, true);

        $redirect = session()->pull('url.intended_member', route('member.dashboard'));

        return response()->json([
            'success'  => true,
            'message'  => 'Registration successful',
            'redirect' => $redirect,
        ]);
    }

    /**
     * Logout member
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('member.login')->with('success', 'Logged out successfully');
    }
}
