<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        Log::info('Password reset requested for: ' . $request->email);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        Log::info('Password reset status: ' . $status);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Password reset link sent to your email!')
            : back()->withErrors(['email' => __($status)]);
    }
}
