<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SubscriptionsExport;
use App\Http\Controllers\Controller;
use App\Models\ExportLog;
use App\Models\MilkWalletTransaction;
use App\Models\Order;
use App\Models\SubscriptionChangeLog;
use App\Models\UserSubscription;
use App\Models\WalletReconciliationLog;
use App\Services\WalletReconciliationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UserSubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions
     */
    public function index(Request $request)
    {
        $query = UserSubscription::with(['user', 'membershipPlan', 'location', 'deliverySettings'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('delivery_frequency')) {
            $freq = $request->delivery_frequency;
            $query->whereHas('deliverySettings', function($q) use ($freq) {
                $q->where('delivery_frequency', $freq);
            });
        }

        // Group by user — fetch all (no pagination) then group
        $subscriptions = $query->get()->groupBy('user_id');
        $locations = \App\Models\Location::orderBy('name')->get();

        return view('admin.subscriptions.index', compact('subscriptions', 'locations'));
    }

    /**
     * Export subscriptions to Excel and store the file
     */
    public function export(Request $request)
    {
        $filters  = $request->only(['status', 'payment_status', 'location_id', 'search']);
        $exporter = new SubscriptionsExport($filters);

        $filename = 'subscriptions-' . now()->format('d-M-Y-His') . '.xlsx';
        $path     = 'exports/subscriptions/' . $filename;

        Excel::store($exporter, $path, 'public_folder');

        ExportLog::create([
            'type'          => 'subscription',
            'filename'      => $filename,
            'path'          => $path,
            'filter_status' => implode('|', array_filter($filters)) ?: null,
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
     * List stored subscription export files (AJAX)
     */
    public function exportList()
    {
        $exports = ExportLog::where('type', 'subscription')
            ->with('generatedBy:id,name')
            ->latest()
            ->take(30)
            ->get()
            ->map(fn($e) => [
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
     * Delete a stored subscription export file
     */
    public function exportDelete(ExportLog $export)
    {
        $full = public_path($export->path);
        if (file_exists($full)) unlink($full);
        $export->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Display a listing of subscriptions
     */
    public function show(UserSubscription $subscription)
    {
        $subscription->load(['user', 'membershipPlan', 'location']);

        $today = now();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth   = $today->copy()->endOfMonth();

        $monthDeliveries = $subscription->deliveryLogs()
            ->whereBetween('delivery_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy(fn($item) => $item->delivery_date->format('Y-m-d'));

        return view('admin.subscriptions.show', compact('subscription', 'monthDeliveries'));
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
     * Update delivery address on behalf of customer (admin only)
     */
    public function updateAddress(Request $request, UserSubscription $subscription)
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string|max:500',
        ]);

        $subscription->update(['delivery_address' => $validated['delivery_address']]);

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Delivery address updated successfully!');
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

    /**
     * Get payment history for a subscription (AJAX)
     */
    public function paymentHistory(UserSubscription $subscription): JsonResponse
    {
        $svc = app(WalletReconciliationService::class);

        // Bank Payments (from orders table)
        $bankPayments = Order::where('user_id', $subscription->user_id)
            ->where(function ($q) use ($subscription) {
                $q->where('user_subscription_id', $subscription->id)
                  ->orWhere(function ($q2) use ($subscription) {
                      $q2->where('order_type', 'wallet_topup')
                         ->whereNull('user_subscription_id')
                         ->whereJsonContains('wallet_meta->location_id', (string) $subscription->location_id);
                  });
            })
            ->where('order_type', 'wallet_topup')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->map(fn($order) => [
                'order_id'       => $order->order_id,
                'transaction_id' => $order->transaction_id,
                'amount'         => (float) $order->amount,
                'status'         => $order->status,
                'payment_method' => $order->payment_method ?? 'phonepe',
                'date'           => $order->created_at->format('M d, Y'),
                'time'           => $order->created_at->format('h:i A'),
            ]);

        // Wallet Transactions
        $walletTransactions = $subscription->walletTransactions()
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->map(fn($txn) => [
                'id'            => $txn->id,
                'type'          => $txn->type,
                'amount'        => (float) $txn->amount,
                'litres'        => $txn->litres ? (float) $txn->litres : null,
                'balance_after' => (float) $txn->balance_after,
                'description'   => $txn->description,
                'is_reversal'   => (bool) $txn->is_reversal,
                'date'          => $txn->transaction_date->format('M d, Y'),
                'time'          => $txn->created_at->format('h:i A'),
            ]);

        // Full reconciliation snapshot from service
        $reconciliation = $svc->calculate($subscription);

        // Reconciliation history
        $history = $svc->getHistory($subscription, 10);

        return response()->json([
            'success'              => true,
            'bank_payments'        => $bankPayments,
            'wallet_transactions'  => $walletTransactions,
            'reconciliation'       => $reconciliation,
            'reconciliation_history' => $history,
        ]);
    }

    /**
     * Fix reconciliation issues (AJAX)
     * All logic delegated to WalletReconciliationService.
     */
    public function fixReconciliation(Request $request, UserSubscription $subscription): JsonResponse
    {
        $request->validate([
            'fix_type' => 'required|string|in:rebuild_from_ledger,fix_from_deliveries,recalculate_credits,recalculate_debits,mark_reconciled',
        ]);

        $fixType = $request->input('fix_type');
        $svc     = app(WalletReconciliationService::class);

        // Safety: never run destructive fixes on already-balanced books
        // (except mark_reconciled which is explicitly for balanced books)
        if ($fixType !== 'mark_reconciled') {
            $calc = $svc->calculate($subscription);
            if ($calc['is_balanced'] && in_array($fixType, ['rebuild_from_ledger'])) {
                return response()->json([
                    'success' => true,
                    'skipped' => true,
                    'message' => 'Books are already balanced. No fix needed.',
                ]);
            }
        }

        try {
            $result = match ($fixType) {
                'rebuild_from_ledger'  => $svc->rebuildFromLedger($subscription),
                'fix_from_deliveries'  => $svc->fixFromDeliveries($subscription),
                'recalculate_credits'  => $svc->recalculateCredits($subscription),
                'recalculate_debits'   => $svc->recalculateDebits($subscription),
                'mark_reconciled'      => $svc->markReconciled($subscription),
            };

            // Refresh balance for response
            $subscription->refresh();
            $result['current_balance']  = (float) $subscription->wallet_balance;
            $result['reconciliation']   = $svc->calculate($subscription);

            return response()->json($result);

        } catch (\Throwable $e) {
            Log::error('Reconciliation Fix Error', [
                'fix_type'        => $fixType,
                'subscription_id' => $subscription->id,
                'error'           => $e->getMessage(),
                'trace'           => $e->getTraceAsString(),
            ]);

            WalletReconciliationLog::record(
                $subscription->id,
                $fixType,
                (float) $subscription->wallet_balance,
                (float) $subscription->wallet_balance,
                0,
                (float) $subscription->wallet_balance,
                ['error' => $e->getMessage()],
                'Fix failed: ' . $e->getMessage(),
                'failed'
            );

            return response()->json([
                'success' => false,
                'message' => 'Reconciliation fix failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Detect duplicate wallet credits (same order credited more than once)
     */
    public function duplicateCredits(): JsonResponse
    {
        // Find credit transactions that reference the same Order ID more than once
        $credits = MilkWalletTransaction::where('type', 'credit')
            ->where('is_reversal', false)
            ->where('description', 'like', '%Order:%')
            ->where('description', 'not like', '%[DUPLICATE - REVERSED]%')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by order ID extracted from description
        $grouped = $credits->groupBy(function ($txn) {
            if (preg_match('/Order:\s*(ORD\w+)/', $txn->description, $m)) {
                return $m[1];
            }
            return 'unknown_' . $txn->id;
        });

        // Filter to only groups with duplicates (more than 1 credit for same order)
        $duplicates = $grouped->filter(fn($group) => $group->count() > 1);

        $results = [];
        foreach ($duplicates as $orderId => $txns) {
            $first = $txns->first();
            $sub = UserSubscription::with('user')->find($first->user_subscription_id);
            $extraCount = $txns->count() - 1;
            $extraAmount = round($first->amount * $extraCount, 2);

            $results[] = [
                'order_id'          => $orderId,
                'subscription_id'   => $first->user_subscription_id,
                'user_name'         => $sub?->user?->name ?? 'Unknown',
                'user_phone'        => $sub?->user?->phone ?? '',
                'amount_per_credit' => (float) $first->amount,
                'total_credits'     => $txns->count(),
                'extra_credits'     => $extraCount,
                'extra_amount'      => $extraAmount,
                'first_credited_at' => $txns->last()->created_at->format('d M Y, h:i A'),
                'last_credited_at'  => $txns->first()->created_at->format('d M Y, h:i A'),
                'transaction_ids'   => $txns->pluck('id')->toArray(),
            ];
        }

        return response()->json([
            'success'    => true,
            'duplicates' => $results,
            'count'      => count($results),
        ]);
    }

    /**
     * Fix a duplicate credit by reversing the extra amount
     */
    public function fixDuplicateCredit(Request $request): JsonResponse
    {
        $request->validate([
            'order_id'        => 'required|string',
            'subscription_id' => 'required|integer|exists:user_subscriptions,id',
        ]);

        $orderId        = $request->order_id;
        $subscriptionId = $request->subscription_id;

        $subscription = UserSubscription::findOrFail($subscriptionId);

        // Find all credit transactions for this order (excluding already reversed)
        $credits = MilkWalletTransaction::where('type', 'credit')
            ->where('is_reversal', false)
            ->where('user_subscription_id', $subscriptionId)
            ->where('description', 'like', "%Order: {$orderId}%")
            ->where('description', 'not like', '%[DUPLICATE - REVERSED]%')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($credits->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'No duplicate found for this order.',
            ]);
        }

        // Keep the first credit, reverse all extras
        $extras = $credits->slice(1);
        $totalReversed = 0;

        DB::beginTransaction();
        try {
            foreach ($extras as $extraTxn) {
                $amount = (float) $extraTxn->amount;
                $totalReversed += $amount;

                // Debit the wallet balance (and reduce wallet_total since it was wrongly inflated)
                $newBalance = round((float) $subscription->wallet_balance - $amount, 2);
                $newTotal   = round((float) $subscription->wallet_total - $amount, 2);

                $subscription->update([
                    'wallet_balance' => max(0, $newBalance),
                    'wallet_total'   => max(0, $newTotal),
                ]);
                $subscription->refresh();

                // Create a reversal transaction
                MilkWalletTransaction::create([
                    'user_id'              => $subscription->user_id,
                    'user_subscription_id' => $subscription->id,
                    'type'                 => 'debit',
                    'amount'               => $amount,
                    'balance_after'        => (float) $subscription->wallet_balance,
                    'description'          => "Duplicate credit reversed | Order: {$orderId} | Txn #{$extraTxn->id}",
                    'transaction_date'     => now()->toDateString(),
                    'is_reversal'          => true,
                ]);

                // Mark the original extra as reversed
                $extraTxn->update([
                    'description' => $extraTxn->description . ' [DUPLICATE - REVERSED]',
                ]);
            }

            SubscriptionChangeLog::record(
                $subscription->id,
                auth()->id(),
                'duplicate_credit_fix',
                ['order_id' => $orderId, 'extra_credits' => $extras->count()],
                ['reversed_amount' => $totalReversed, 'new_balance' => (float) $subscription->wallet_balance],
                "Reversed {$extras->count()} duplicate credit(s) totalling ₹{$totalReversed} for Order: {$orderId}"
            );

            DB::commit();

            return response()->json([
                'success'          => true,
                'message'          => "Reversed {$extras->count()} duplicate credit(s) totalling ₹" . number_format($totalReversed, 2) . ".",
                'reversed_amount'  => $totalReversed,
                'new_balance'      => (float) $subscription->wallet_balance,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Fix Duplicate Credit Error', ['error' => $e->getMessage(), 'order_id' => $orderId]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fix duplicate: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all pending payment orders (for admin alert panel)
     */
    public function pendingPayments(): JsonResponse
    {
        $pendingOrders = Order::where('status', 'pending')
            ->where('order_type', 'wallet_topup')
            ->where('created_at', '>=', now()->subDays(30)) // Only last 30 days
            ->with(['user:id,name,phone,email'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'order_id'        => $order->order_id,
                    'user_name'       => $order->user?->name ?? 'Unknown',
                    'user_phone'      => $order->user?->phone ?? '',
                    'amount'          => (float) $order->amount,
                    'subscription_id' => $order->user_subscription_id,
                    'created_at'      => $order->created_at->format('d M Y, h:i A'),
                    'age'             => $order->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success'  => true,
            'payments' => $pendingOrders,
            'count'    => $pendingOrders->count(),
        ]);
    }

    /**
     * Get all payment transactions (for admin panel)
     */
    public function allPayments(Request $request): JsonResponse
    {
        $query = Order::where('order_type', 'wallet_topup')
            ->with(['user:id,name,phone,email'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->take(200)->get();

        $successAmount = $payments->where('status', 'success')->sum('amount');
        $pendingAmount = $payments->where('status', 'pending')->sum('amount');
        $totalAmount   = $payments->sum('amount');

        $mapped = $payments->map(function ($order) {
            return [
                'order_id'        => $order->order_id,
                'transaction_id'  => $order->transaction_id,
                'user_name'       => $order->user?->name ?? 'Unknown',
                'user_phone'      => $order->user?->phone ?? '',
                'amount'          => (float) $order->amount,
                'status'          => $order->status,
                'subscription_id' => $order->user_subscription_id,
                'date'            => $order->created_at->format('d M Y'),
                'time'            => $order->created_at->format('h:i A'),
            ];
        });

        return response()->json([
            'success'        => true,
            'payments'       => $mapped,
            'total_amount'   => (float) $totalAmount,
            'success_amount' => (float) $successAmount,
            'pending_amount' => (float) $pendingAmount,
        ]);
    }

    /**
     * Verify a pending payment order with PhonePe and process if paid
     */
    public function verifyPendingPayment(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $orderId = $request->order_id;
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.']);
        }

        if ($order->status === 'success') {
            return response()->json(['success' => false, 'message' => 'Order is already marked as success.']);
        }

        // Admin can manually mark as failed — but ONLY after verifying with PhonePe that it's not paid
        if ($request->boolean('mark_failed')) {
            // First verify with PhonePe to make sure money was NOT transacted
            try {
                $phonePe = app(\App\Services\PhonePeService::class);
                $verification = $phonePe->verifyPayment($order->order_id);

                $state = $verification['state'] ?? 'UNKNOWN';

                // If PhonePe says COMPLETED, do NOT allow marking as failed — it was actually paid!
                if ($verification['success'] && $state === 'COMPLETED') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot mark as failed — PhonePe confirms this payment was COMPLETED (₹' . number_format($order->amount, 2) . '). Use "Verify" to credit it instead.',
                    ]);
                }

                // If PhonePe says PENDING, warn admin — payment might still complete
                if (in_array($state, ['PENDING', 'INITIATED'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot mark as failed — PhonePe shows status as "' . $state . '". Payment may still be processing. Wait and try again later.',
                    ]);
                }
            } catch (\Exception $e) {
                // If PhonePe service is not configured, allow marking failed with a warning logged
                Log::warning('Mark Failed: PhonePe verification unavailable, proceeding with mark failed', [
                    'order_id' => $orderId,
                    'error'    => $e->getMessage(),
                ]);
            }

            // PhonePe confirmed NOT paid (FAILED/EXPIRED/etc.) — safe to mark as failed
            $order->update([
                'status' => 'failed',
                'payment_response' => array_merge(
                    $order->payment_response ?? [],
                    ['admin_action' => [
                        'action'    => 'marked_failed',
                        'by'        => auth()->user()->name,
                        'by_id'     => auth()->id(),
                        'at'        => now()->toDateTimeString(),
                        'phonepe_state' => $state ?? 'NOT_CHECKED',
                        'reason'    => 'Admin marked as failed after PhonePe confirmed not paid',
                    ]]
                ),
            ]);

            // Log to subscription change log if subscription exists
            if ($order->user_subscription_id) {
                SubscriptionChangeLog::record(
                    $order->user_subscription_id,
                    auth()->id(),
                    'payment_marked_failed',
                    ['order_id' => $orderId, 'amount' => (float) $order->amount, 'old_status' => 'pending'],
                    ['order_id' => $orderId, 'amount' => (float) $order->amount, 'new_status' => 'failed'],
                    "Admin marked pending payment ₹" . number_format($order->amount, 2) . " as failed — PhonePe status: " . ($state ?? 'N/A') . " (Order: {$orderId})"
                );
            }

            Log::info('Admin: Marked payment as failed', [
                'order_id'     => $orderId,
                'amount'       => $order->amount,
                'phonepe_state'=> $state ?? 'NOT_CHECKED',
                'admin_id'     => auth()->id(),
                'admin'        => auth()->user()->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order marked as failed (PhonePe confirmed: ' . ($state ?? 'not transacted') . ').',
                'status'  => 'failed',
            ]);
        }

        try {
            $phonePe = app(\App\Services\PhonePeService::class);
            $verification = $phonePe->verifyPayment($order->order_id);

            if ($verification['success'] && ($verification['state'] ?? '') === 'COMPLETED') {
                DB::beginTransaction();
                try {
                    $order->update([
                        'status'           => 'success',
                        'transaction_id'   => $verification['data']['orderId'] ?? $order->transaction_id,
                        'paid_at'          => now(),
                        'payment_response' => array_merge(
                            $order->payment_response ?? [],
                            ['admin_verification' => $verification]
                        ),
                    ]);

                    if ($order->isWalletTopup()) {
                        // Process wallet top-up
                        $subscription = $order->user_subscription_id
                            ? UserSubscription::find($order->user_subscription_id)
                            : null;

                        if ($subscription) {
                            $subscription->creditWallet(
                                (float) $order->amount,
                                'Wallet top-up | Order: ' . $order->order_id . ' (admin verified)'
                            );

                            if (in_array($subscription->delivery_status, ['stopped', 'paused'])) {
                                $subscription->update(['delivery_status' => 'active']);
                            }
                            \App\Models\DeliveryLog::autoGenerate($subscription);

                            // Log the admin verification action
                            SubscriptionChangeLog::record(
                                $subscription->id,
                                auth()->id(),
                                'payment_verified_success',
                                ['order_id' => $orderId, 'amount' => (float) $order->amount, 'old_status' => 'pending'],
                                ['order_id' => $orderId, 'amount' => (float) $order->amount, 'new_status' => 'success', 'new_balance' => (float) $subscription->wallet_balance],
                                "Admin verified pending payment ₹" . number_format($order->amount, 2) . " as COMPLETED via PhonePe API (Order: {$orderId})"
                            );
                        }
                    }

                    Log::info('Admin: Verified payment as success', [
                        'order_id' => $orderId,
                        'amount'   => $order->amount,
                        'admin_id' => auth()->id(),
                        'admin'    => auth()->user()->name,
                    ]);

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Payment verified as COMPLETED. ₹' . number_format($order->amount, 2) . ' credited to wallet.',
                        'status'  => 'success',
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

            // Payment not completed
            $state = $verification['state'] ?? 'UNKNOWN';
            return response()->json([
                'success' => true,
                'message' => "Payment status from PhonePe: {$state}. Not yet paid.",
                'status'  => strtolower($state),
            ]);

        } catch (\Exception $e) {
            Log::error('Verify Pending Payment Error', ['error' => $e->getMessage(), 'order_id' => $orderId]);
            return response()->json([
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage(),
            ]);
        }
    }
}
