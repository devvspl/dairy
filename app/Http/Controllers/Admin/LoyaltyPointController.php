<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPoint;
use App\Models\User;
use Illuminate\Http\Request;

class LoyaltyPointController extends Controller
{
    public function index(Request $request)
    {
        $query = LoyaltyPoint::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $loyaltyPoints = $query->latest()->paginate(15);
        
        // Stats
        $stats = [
            'total_earned' => LoyaltyPoint::where('type', 'earned')->sum('points'),
            'total_redeemed' => LoyaltyPoint::where('type', 'redeemed')->sum('points'),
            'active_points' => LoyaltyPoint::where('type', 'earned')->sum('points') - LoyaltyPoint::where('type', 'redeemed')->sum('points'),
            'total_users' => LoyaltyPoint::distinct('user_id')->count(),
        ];

        return view('admin.loyalty-points.index', compact('loyaltyPoints', 'stats'));
    }

    public function create()
    {
        $users = User::where('user_type', 'Member')->orderBy('name')->get();
        return view('admin.loyalty-points.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'points' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:earned,redeemed,expired,adjusted'],
            'reason' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        LoyaltyPoint::create($validated);

        return redirect()->route('admin.loyalty-points.index')->with('success', 'Loyalty points added successfully!');
    }

    public function edit(LoyaltyPoint $loyaltyPoint)
    {
        $users = User::where('user_type', 'Member')->orderBy('name')->get();
        return view('admin.loyalty-points.edit', compact('loyaltyPoint', 'users'));
    }

    public function update(Request $request, LoyaltyPoint $loyaltyPoint)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'points' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:earned,redeemed,expired,adjusted'],
            'reason' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $loyaltyPoint->update($validated);

        return redirect()->route('admin.loyalty-points.index')->with('success', 'Loyalty points updated successfully!');
    }

    public function destroy(LoyaltyPoint $loyaltyPoint)
    {
        $loyaltyPoint->delete();

        return redirect()->route('admin.loyalty-points.index')->with('success', 'Loyalty points deleted successfully!');
    }
}
