<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use App\Models\ReferralUsage;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get or create referral code
        $referralCode = ReferralCode::firstOrCreate(
            ['user_id' => $user->id],
            ['is_active' => true]
        );
        
        $referralUsages = ReferralUsage::where('referral_code_id', $referralCode->id)
            ->with('referredUser')
            ->latest()
            ->paginate(15);
        
        $stats = [
            'total_referrals' => $referralCode->total_referrals,
            'total_earnings' => $referralCode->total_earnings,
            'pending_referrals' => ReferralUsage::where('referral_code_id', $referralCode->id)
                ->where('status', 'pending')
                ->count(),
            'completed_referrals' => ReferralUsage::where('referral_code_id', $referralCode->id)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('member.referrals.index', compact('referralCode', 'referralUsages', 'stats'));
    }
}
