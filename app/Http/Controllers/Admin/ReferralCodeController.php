<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralCodeController extends Controller
{
    public function index(Request $request)
    {
        $query = ReferralCode::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $referralCodes = $query->latest()->paginate(15);
        
        // Stats
        $stats = [
            'total_codes' => ReferralCode::count(),
            'active_codes' => ReferralCode::where('is_active', true)->count(),
            'total_referrals' => ReferralCode::sum('total_referrals'),
            'total_earnings' => ReferralCode::sum('total_earnings'),
        ];

        return view('admin.referral-codes.index', compact('referralCodes', 'stats'));
    }

    public function create()
    {
        $users = User::where('user_type', 'Member')->orderBy('name')->get();
        return view('admin.referral-codes.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'code' => ['nullable', 'string', 'max:20', 'unique:referral_codes,code'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        ReferralCode::create($validated);

        return redirect()->route('admin.referral-codes.index')->with('success', 'Referral code created successfully!');
    }

    public function edit(ReferralCode $referralCode)
    {
        $users = User::where('user_type', 'Member')->orderBy('name')->get();
        return view('admin.referral-codes.edit', compact('referralCode', 'users'));
    }

    public function update(Request $request, ReferralCode $referralCode)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'code' => ['required', 'string', 'max:20', 'unique:referral_codes,code,' . $referralCode->id],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $referralCode->update($validated);

        return redirect()->route('admin.referral-codes.index')->with('success', 'Referral code updated successfully!');
    }

    public function destroy(ReferralCode $referralCode)
    {
        $referralCode->delete();

        return redirect()->route('admin.referral-codes.index')->with('success', 'Referral code deleted successfully!');
    }
}
