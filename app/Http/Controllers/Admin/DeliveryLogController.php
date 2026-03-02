<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryLog;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class DeliveryLogController extends Controller
{
    /**
     * Display delivery logs for a subscription
     */
    public function index(Request $request, UserSubscription $subscription)
    {
        $query = $subscription->deliveryLogs()
            ->with(['markedBy'])
            ->orderBy('delivery_date', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('delivery_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('delivery_date', '<=', $request->to_date);
        }

        $deliveries = $query->paginate(20);

        return view('admin.deliveries.index', compact('subscription', 'deliveries'));
    }

    /**
     * Update delivery status
     */
    public function updateStatus(Request $request, DeliveryLog $delivery)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,delivered,skipped,failed',
            'delivery_time' => 'nullable|date_format:H:i',
            'quantity_delivered' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $delivery->update([
            'status' => $validated['status'],
            'delivery_time' => $validated['delivery_time'] ?? $delivery->delivery_time,
            'quantity_delivered' => $validated['quantity_delivered'] ?? $delivery->quantity_delivered,
            'notes' => $validated['notes'] ?? $delivery->notes,
            'marked_by' => auth()->id(),
            'marked_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Delivery status updated successfully!');
    }

    /**
     * Generate delivery schedule for a subscription
     */
    public function generateSchedule(UserSubscription $subscription)
    {
        $plan = $subscription->membershipPlan;
        
        if (!$plan->day_wise_schedule) {
            return redirect()->back()->with('error', 'No delivery schedule found for this plan.');
        }

        $startDate = $subscription->start_date->copy();
        $endDate = $subscription->end_date->copy();
        $generated = 0;

        // Loop through each day from start to end
        while ($startDate <= $endDate) {
            $dayKey = $startDate->format('D'); // Mon, Tue, Wed, etc.
            
            // Check if delivery is scheduled for this day
            if ($plan->hasDeliveryOnDay($dayKey)) {
                $quantity = $plan->getDayQuantity($dayKey);
                
                // Create delivery log if it doesn't exist
                DeliveryLog::firstOrCreate(
                    [
                        'user_subscription_id' => $subscription->id,
                        'delivery_date' => $startDate->format('Y-m-d'),
                    ],
                    [
                        'quantity_delivered' => $quantity,
                        'status' => $startDate->isPast() ? 'pending' : 'pending',
                    ]
                );
                
                $generated++;
            }
            
            $startDate->addDay();
        }

        return redirect()->back()->with('success', "Generated {$generated} delivery entries for this subscription.");
    }

    /**
     * Bulk update deliveries for today
     */
    public function bulkUpdateToday(Request $request)
    {
        $validated = $request->validate([
            'delivery_ids' => 'required|array',
            'delivery_ids.*' => 'exists:delivery_logs,id',
            'status' => 'required|in:delivered,skipped,failed',
        ]);

        DeliveryLog::whereIn('id', $validated['delivery_ids'])
            ->update([
                'status' => $validated['status'],
                'marked_by' => auth()->id(),
                'marked_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Deliveries updated successfully!');
    }

    /**
     * Show today's deliveries dashboard
     */
    public function todayDeliveries(Request $request)
    {
        $query = DeliveryLog::with(['subscription.user', 'subscription.membershipPlan'])
            ->today()
            ->orderBy('status', 'asc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deliveries = $query->paginate(50);

        $stats = [
            'total' => DeliveryLog::today()->count(),
            'delivered' => DeliveryLog::today()->delivered()->count(),
            'pending' => DeliveryLog::today()->pending()->count(),
            'total_quantity' => DeliveryLog::today()->sum('quantity_delivered'),
        ];

        return view('admin.deliveries.today', compact('deliveries', 'stats'));
    }

    /**
     * Forward delivery to next day
     */
    public function forwardToNextDay(DeliveryLog $delivery)
    {
        // Get the subscription and plan
        $subscription = $delivery->subscription;
        $plan = $subscription->membershipPlan;
        
        // Find the next scheduled delivery day
        $nextDate = $delivery->delivery_date->copy()->addDay();
        $maxAttempts = 7; // Check up to 7 days ahead
        $attempts = 0;
        
        while ($attempts < $maxAttempts) {
            $dayKey = $nextDate->format('D');
            
            // Check if this day has a scheduled delivery
            if ($plan->hasDeliveryOnDay($dayKey)) {
                // Check if delivery already exists for this date
                $existingDelivery = DeliveryLog::where('user_subscription_id', $subscription->id)
                    ->whereDate('delivery_date', $nextDate)
                    ->first();
                
                if ($existingDelivery) {
                    // Add the forwarded quantity to existing delivery
                    $existingDelivery->update([
                        'quantity_delivered' => $existingDelivery->quantity_delivered + $delivery->quantity_delivered,
                        'notes' => ($existingDelivery->notes ? $existingDelivery->notes . ' | ' : '') . 
                                   'Forwarded from ' . $delivery->delivery_date->format('M d, Y'),
                    ]);
                } else {
                    // Create new delivery for next day
                    DeliveryLog::create([
                        'user_subscription_id' => $subscription->id,
                        'delivery_date' => $nextDate,
                        'quantity_delivered' => $delivery->quantity_delivered,
                        'status' => 'pending',
                        'notes' => 'Forwarded from ' . $delivery->delivery_date->format('M d, Y'),
                    ]);
                }
                
                // Mark current delivery as skipped
                $delivery->update([
                    'status' => 'skipped',
                    'notes' => ($delivery->notes ? $delivery->notes . ' | ' : '') . 
                               'Forwarded to ' . $nextDate->format('M d, Y'),
                    'marked_by' => auth()->id(),
                    'marked_at' => now(),
                ]);
                
                return redirect()->back()->with('success', 
                    'Delivery forwarded to ' . $nextDate->format('M d, Y') . ' successfully!');
            }
            
            $nextDate->addDay();
            $attempts++;
        }
        
        return redirect()->back()->with('error', 
            'No scheduled delivery day found in the next 7 days.');
    }
}
