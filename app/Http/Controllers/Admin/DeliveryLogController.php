<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TodayDeliveriesExport;
use App\Http\Controllers\Controller;
use App\Models\DeliveryLog;
use App\Models\ExportLog;
use App\Models\MilkWalletTransaction;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
            'status'             => 'required|in:pending,delivered,skipped,failed',
            'delivery_time'      => 'nullable|date_format:H:i',
            'quantity_delivered' => 'nullable|numeric|min:0|max:100',
            'notes'              => 'nullable|string|max:500',
        ]);

        $oldStatus  = $delivery->status;
        $newStatus  = $validated['status'];
        $qty        = $validated['quantity_delivered'] ?? $delivery->quantity_delivered;
        $subscription = $delivery->subscription;

        $delivery->update([
            'status'             => $newStatus,
            'delivery_time'      => $validated['delivery_time'] ?? $delivery->delivery_time,
            'quantity_delivered' => $qty,
            'notes'              => $validated['notes'] ?? $delivery->notes,
            'marked_by'          => auth()->id(),
            'marked_at'          => now(),
        ]);

        // Auto-debit wallet for on-demand plans when marked delivered (only once)
        if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            $plan = $subscription->membershipPlan;
            if ($plan && $plan->isOnDemand() && $subscription->wallet_balance > 0) {
                $subscription->debitWallet(
                    (float) $qty,
                    $delivery->delivery_date->toDateString(),
                    auth()->id()
                );
            }
        }

        // If reverting from delivered → pending/skipped, credit back
        if ($oldStatus === 'delivered' && in_array($newStatus, ['pending', 'skipped', 'failed'])) {
            $plan = $subscription->membershipPlan;
            if ($plan && $plan->isOnDemand()) {
                $creditAmt = round((float) $qty * (float) $subscription->price_per_litre, 2);
                if ($creditAmt > 0) {
                    $subscription->creditWallet(
                        $creditAmt,
                        'Delivery reversed on ' . $delivery->delivery_date->format('d M Y') . ' (admin)'
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'Delivery status updated successfully!');
    }

    /**
     * Generate delivery schedule for a subscription
     */
    public function generateSchedule(UserSubscription $subscription)
    {
        $plan = $subscription->membershipPlan;

        // ── On-Demand: generate one entry per day for the full subscription period ──
        if ($plan->isOnDemand()) {
            $qty = (float) ($subscription->quantity_per_day ?? 1);
            if ($qty <= 0) {
                return redirect()->back()->with('error', 'Subscription has no quantity_per_day set.');
            }

            $startDate = $subscription->start_date->copy();
            $endDate   = $subscription->end_date->copy();
            $generated = 0;

            while ($startDate->lte($endDate)) {
                DeliveryLog::firstOrCreate(
                    [
                        'user_subscription_id' => $subscription->id,
                        'delivery_date'        => $startDate->format('Y-m-d'),
                    ],
                    [
                        'quantity_delivered' => $qty,
                        'status'             => 'pending',
                    ]
                );
                $generated++;
                $startDate->addDay();
            }

            return redirect()->back()->with('success', "Generated {$generated} on-demand delivery entries.");
        }

        // ── Scheduled: use day_wise_schedule ────────────────────────────────
        if (!$plan->day_wise_schedule) {
            return redirect()->back()->with('error', 'No delivery schedule found for this plan.');
        }

        $startDate = $subscription->start_date->copy();
        $endDate   = $subscription->end_date->copy();
        $generated = 0;

        while ($startDate->lte($endDate)) {
            $dayKey = $startDate->format('D'); // Mon, Tue, etc.

            if ($plan->hasDeliveryOnDay($dayKey)) {
                DeliveryLog::firstOrCreate(
                    [
                        'user_subscription_id' => $subscription->id,
                        'delivery_date'        => $startDate->format('Y-m-d'),
                    ],
                    [
                        'quantity_delivered' => $plan->getDayQuantity($dayKey),
                        'status'             => 'pending',
                    ]
                );
                $generated++;
            }

            $startDate->addDay();
        }

        return redirect()->back()->with('success', "Generated {$generated} delivery entries for this subscription.");
    }

    /**
     * Reset (delete all) delivery entries for a subscription
     */
    public function resetSchedule(UserSubscription $subscription)
    {
        $count = $subscription->deliveryLogs()->count();
        $subscription->deliveryLogs()->delete();

        return redirect()->back()->with('success', "Deleted {$count} delivery entries. You can now regenerate the schedule.");
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

    /**
     * Export today's deliveries to Excel and store the file
     */
    public function exportToday(Request $request)
    {
        $status   = (string) ($request->get('status') ?? '');
        $exporter = new TodayDeliveriesExport($status);

        $filename = 'deliveries-' . now()->format('d-M-Y-His') . '.xlsx';
        $path     = 'exports/deliveries/' . $filename;

        Excel::store($exporter, $path, 'public_folder');

        ExportLog::create([
            'type'          => 'delivery',
            'filename'      => $filename,
            'path'          => $path,
            'filter_status' => $status ?: null,
            'row_count'     => $exporter->rowCount,
            'generated_by'  => auth()->id(),
        ]);

        return response()->json([
            'success'      => true,
            'message'      => 'Export generated successfully.',
            'download_url' => asset($path),
            'filename'     => $filename,
        ]);
    }

    /**
     * List stored export files (AJAX)
     */
    public function exportList()
    {
        $exports = ExportLog::where('type', 'delivery')
            ->with('generatedBy:id,name')
            ->latest()
            ->take(30)
            ->get()
            ->map(fn ($e) => [
                'id'            => $e->id,
                'filename'      => $e->filename,
                'filter_status' => $e->filter_status ?? 'All',
                'row_count'     => $e->row_count,
                'file_size'     => $e->file_size,
                'generated_by'  => $e->generatedBy->name ?? '-',
                'created_at'    => $e->created_at->format('d M Y, h:i A'),
                'download_url'  => $e->download_url,
                'exists'        => file_exists(public_path($e->path)),
            ]);

        return response()->json(['success' => true, 'exports' => $exports]);
    }

    /**
     * Delete a stored export file
     */
    public function exportDelete(ExportLog $export)
    {
        $full = public_path($export->path);
        if (file_exists($full)) unlink($full);
        $export->delete();
        return response()->json(['success' => true]);
    }
}
