<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class UserSubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions
     */
    public function index(Request $request)
    {
        $query = UserSubscription::with(['user', 'membershipPlan'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by user name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $subscriptions = $query->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Display the specified subscription
     */
    public function show(UserSubscription $subscription)
    {
        $subscription->load(['user', 'membershipPlan']);
        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Update subscription status
     */
    public function updateStatus(Request $request, UserSubscription $subscription)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,active,paused,cancelled,expired',
        ]);

        $subscription->update(['status' => $validated['status']]);

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Subscription status updated successfully!');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, UserSubscription $subscription)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'transaction_id' => 'nullable|string|max:255',
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Payment status updated successfully!');
    }

    /**
     * Add notes to subscription
     */
    public function addNote(Request $request, UserSubscription $subscription)
    {
        $validated = $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $existingNotes = $subscription->notes ? $subscription->notes . "\n\n" : '';
        $newNote = "[" . now()->format('Y-m-d H:i:s') . "] " . $validated['notes'];
        
        $subscription->update([
            'notes' => $existingNotes . $newNote
        ]);

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Note added successfully!');
    }
}
