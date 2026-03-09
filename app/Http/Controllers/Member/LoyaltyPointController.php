<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPoint;
use Illuminate\Http\Request;

class LoyaltyPointController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $loyaltyPoints = LoyaltyPoint::where('user_id', $user->id)
            ->latest()
            ->paginate(15);
        
        $stats = [
            'total_earned' => LoyaltyPoint::where('user_id', $user->id)
                ->where('type', 'earned')
                ->sum('points'),
            'total_redeemed' => LoyaltyPoint::where('user_id', $user->id)
                ->where('type', 'redeemed')
                ->sum('points'),
            'available_points' => LoyaltyPoint::where('user_id', $user->id)
                ->where('type', 'earned')
                ->sum('points') - LoyaltyPoint::where('user_id', $user->id)
                ->where('type', 'redeemed')
                ->sum('points'),
        ];

        return view('member.loyalty-points.index', compact('loyaltyPoints', 'stats'));
    }
}
