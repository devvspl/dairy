<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MilkWalletTransaction;
use App\Models\ReferralCode;
use App\Models\ReferralUsage;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // Reward credited to the referrer for each successful signup
    const REFERRAL_REWARD = 100.00;

    public function showRegistrationForm()
    {
        $referralCode = request('ref');
        return view('auth.register', compact('referralCode'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'password'      => ['required', 'confirmed', Password::min(8)],
            'referral_code' => ['nullable', 'string', 'max:20'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'] ?? null,
                'user_type' => 'Member',
                'password'  => Hash::make($validated['password']),
            ]);

            // Process referral code if provided
            if (!empty($validated['referral_code'])) {
                $referralCode = ReferralCode::where('code', strtoupper(trim($validated['referral_code'])))
                    ->where('is_active', true)
                    ->first();

                if ($referralCode && $referralCode->user_id !== $user->id) {
                    // Record the usage
                    ReferralUsage::create([
                        'referral_code_id' => $referralCode->id,
                        'referred_user_id' => $user->id,
                        'referrer_reward'  => self::REFERRAL_REWARD,
                        'referee_reward'   => 0,
                        'status'           => 'completed',
                        'completed_at'     => now(),
                    ]);

                    // Update referral code stats
                    $referralCode->increment('total_referrals');
                    $referralCode->increment('total_earnings', self::REFERRAL_REWARD);

                    // Credit ₹100 to the referrer's active wallet subscription
                    $referrerSubscription = UserSubscription::where('user_id', $referralCode->user_id)
                        ->where('status', 'active')
                        ->whereNotNull('wallet_balance')
                        ->latest()
                        ->first();

                    if ($referrerSubscription) {
                        $referrerSubscription->creditWallet(
                            self::REFERRAL_REWARD,
                            '🎉 Referral bonus — ' . $user->name . ' joined using your code'
                        );
                    }
                }
            }

            Auth::login($user);
        });

        return redirect()->route('dashboard')->with('success', 'Registration successful! Welcome aboard.');
    }
}
