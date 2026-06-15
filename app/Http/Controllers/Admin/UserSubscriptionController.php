<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SubscriptionsExport;
use App\Http\Controllers\Controller;
use App\Models\ExportLog;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserSubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions
     */
    public function index(Request $request)
    {
        $query = UserSubscription::with(['user', 'membershipPlan', 'location'])
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
    public function paymentHistory(UserSubscription $subscription)
    {
        // Bank Payments (from orders table)
        $bankPayments = \App\Models\Order::where('user_id', $subscription->user_id)
            ->where(function($q) use ($subscription) {
                $q->where('user_subscription_id', $subscription->id)
                  ->orWhere(function($q2) use ($subscription) {
                      // Include wallet init orders that created this subscription
                      $q2->where('order_type', 'wallet_topup')
                         ->whereNull('user_subscription_id')
                         ->whereJsonContains('wallet_meta->location_id', (string)$subscription->location_id);
                  });
            })
            ->where('order_type', 'wallet_topup')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->map(function($order) {
                return [
                    'order_id'       => $order->order_id,
                    'transaction_id' => $order->transaction_id,
                    'amount'         => (float) $order->amount,
                    'status'         => $order->status,
                    'payment_method' => $order->payment_method ?? 'phonepe',
                    'date'           => $order->created_at->format('M d, Y'),
                    'time'           => $order->created_at->format('h:i A'),
                ];
            });

        // Wallet Transactions
        $walletTransactions = $subscription->walletTransactions()
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->map(function($txn) {
                return [
                    'id'            => $txn->id,
                    'type'          => $txn->type,
                    'amount'        => (float) $txn->amount,
                    'litres'        => $txn->litres ? (float) $txn->litres : null,
                    'balance_after' => (float) $txn->balance_after,
                    'description'   => $txn->description,
                    'date'          => $txn->transaction_date->format('M d, Y'),
                    'time'          => $txn->created_at->format('h:i A'),
                ];
            });

        // Reconciliation Data
        $totalBankPayments = \App\Models\Order::where('user_id', $subscription->user_id)
            ->where(function($q) use ($subscription) {
                $q->where('user_subscription_id', $subscription->id)
                  ->orWhere(function($q2) use ($subscription) {
                      $q2->where('order_type', 'wallet_topup')
                         ->whereNull('user_subscription_id')
                         ->whereJsonContains('wallet_meta->location_id', (string)$subscription->location_id);
                  });
            })
            ->where('order_type', 'wallet_topup')
            ->where('status', 'success')
            ->sum('amount');

        $totalCredits = $subscription->walletTransactions()
            ->where('type', 'credit')
            ->sum('amount');

        $totalDebits = $subscription->walletTransactions()
            ->where('type', 'debit')
            ->sum('amount');

        return response()->json([
            'success'           => true,
            'bank_payments'     => $bankPayments,
            'wallet_transactions' => $walletTransactions,
            'reconciliation'    => [
                'total_bank_payments' => (float) $totalBankPayments,
                'total_credits'       => (float) $totalCredits,
                'total_debits'        => (float) $totalDebits,
                'current_balance'     => (float) $subscription->wallet_balance,
            ],
        ]);
    }

    /**
     * Fix reconciliation issues (AJAX)
     */
    public function fixReconciliation(Request $request, UserSubscription $subscription)
    {
        $fixType = $request->input('fix_type');
        
        DB::beginTransaction();
        try {
            $result = ['success' => false, 'message' => 'Unknown fix type'];
            
            switch ($fixType) {
                case 'sync_balance':
                    // Recalculate balance from transactions
                    $totalCredits = $subscription->walletTransactions()->where('type', 'credit')->sum('amount');
                    $totalDebits = $subscription->walletTransactions()->where('type', 'debit')->sum('amount');
                    $calculatedBalance = $totalCredits - $totalDebits;
                    
                    $oldBalance = $subscription->wallet_balance;
                    $subscription->update(['wallet_balance' => $calculatedBalance]);
                    
                    \App\Models\SubscriptionChangeLog::record(
                        $subscription->id,
                        auth()->id(),
                        'balance_reconciliation',
                        ['old_balance' => $oldBalance],
                        ['new_balance' => $calculatedBalance],
                        "Balance synced from transactions (admin fix)"
                    );
                    
                    $result = [
                        'success' => true,
                        'message' => "Balance synchronized successfully. Updated from ₹{$oldBalance} to ₹{$calculatedBalance}",
                        'old_balance' => (float) $oldBalance,
                        'new_balance' => (float) $calculatedBalance,
                    ];
                    break;
                    
                case 'sync_bank_to_wallet':
                    // Find successful bank payments not yet credited to wallet
                    $bankTotal = \App\Models\Order::where('user_id', $subscription->user_id)
                        ->where(function($q) use ($subscription) {
                            $q->where('user_subscription_id', $subscription->id)
                              ->orWhere(function($q2) use ($subscription) {
                                  $q2->where('order_type', 'wallet_topup')
                                     ->whereNull('user_subscription_id')
                                     ->whereJsonContains('wallet_meta->location_id', (string)$subscription->location_id);
                              });
                        })
                        ->where('order_type', 'wallet_topup')
                        ->where('status', 'success')
                        ->sum('amount');
                    
                    $walletCredits = $subscription->walletTransactions()->where('type', 'credit')->sum('amount');
                    $difference = $bankTotal - $walletCredits;
                    
                    if (abs($difference) > 0.01) {
                        // Add adjustment transaction
                        \App\Models\MilkWalletTransaction::create([
                            'user_id' => $subscription->user_id,
                            'user_subscription_id' => $subscription->id,
                            'type' => $difference > 0 ? 'credit' : 'debit',
                            'amount' => abs($difference),
                            'balance_after' => $subscription->wallet_balance + $difference,
                            'description' => "Reconciliation adjustment: Bank payments sync (admin fix)",
                            'transaction_date' => now()->toDateString(),
                        ]);
                        
                        $subscription->update([
                            'wallet_balance' => $subscription->wallet_balance + $difference,
                            'wallet_total' => $subscription->wallet_total + ($difference > 0 ? $difference : 0),
                        ]);
                        
                        $result = [
                            'success' => true,
                            'message' => "Adjustment of ₹" . number_format(abs($difference), 2) . " applied to match bank payments",
                            'adjustment' => (float) $difference,
                        ];
                    } else {
                        $result = [
                            'success' => true,
                            'message' => "No adjustment needed. Bank payments and wallet credits are already synchronized.",
                        ];
                    }
                    break;
                    
                case 'prevent_negative':
                    // Check if balance is negative and fix
                    if ($subscription->wallet_balance < 0) {
                        $negativeAmount = abs($subscription->wallet_balance);
                        
                        // Add credit to bring balance to zero
                        \App\Models\MilkWalletTransaction::create([
                            'user_id' => $subscription->user_id,
                            'user_subscription_id' => $subscription->id,
                            'type' => 'credit',
                            'amount' => $negativeAmount,
                            'balance_after' => 0,
                            'description' => "Negative balance correction (admin fix)",
                            'transaction_date' => now()->toDateString(),
                        ]);
                        
                        $subscription->update([
                            'wallet_balance' => 0,
                            'wallet_total' => $subscription->wallet_total + $negativeAmount,
                        ]);
                        
                        $result = [
                            'success' => true,
                            'message' => "Negative balance of ₹{$negativeAmount} corrected. Balance is now ₹0.",
                            'correction' => (float) $negativeAmount,
                        ];
                    } else {
                        $result = [
                            'success' => true,
                            'message' => "Balance is positive. No correction needed.",
                        ];
                    }
                    break;
                    
                case 'remove_excess_credits':
                    // Remove wallet credits that exceed bank payments
                    $bankTotal = \App\Models\Order::where('user_id', $subscription->user_id)
                        ->where(function($q) use ($subscription) {
                            $q->where('user_subscription_id', $subscription->id)
                              ->orWhere(function($q2) use ($subscription) {
                                  $q2->where('order_type', 'wallet_topup')
                                     ->whereNull('user_subscription_id')
                                     ->whereJsonContains('wallet_meta->location_id', (string)$subscription->location_id);
                              });
                        })
                        ->where('order_type', 'wallet_topup')
                        ->where('status', 'success')
                        ->sum('amount');
                    
                    $walletCredits = $subscription->walletTransactions()->where('type', 'credit')->sum('amount');
                    $excessAmount = $walletCredits - $bankTotal;
                    
                    if ($excessAmount > 0.01) {
                        // Add debit transaction to remove excess credits
                        \App\Models\MilkWalletTransaction::create([
                            'user_id' => $subscription->user_id,
                            'user_subscription_id' => $subscription->id,
                            'type' => 'debit',
                            'amount' => $excessAmount,
                            'balance_after' => $subscription->wallet_balance - $excessAmount,
                            'description' => "Reconciliation adjustment: Remove excess credits not backed by bank payments (admin fix)",
                            'transaction_date' => now()->toDateString(),
                        ]);
                        
                        $oldBalance = $subscription->wallet_balance;
                        $newBalance = $subscription->wallet_balance - $excessAmount;
                        
                        $subscription->update([
                            'wallet_balance' => $newBalance,
                        ]);
                        
                        \App\Models\SubscriptionChangeLog::record(
                            $subscription->id,
                            auth()->id(),
                            'excess_credits_removal',
                            [
                                'old_balance' => $oldBalance,
                                'excess_amount' => $excessAmount,
                                'bank_total' => $bankTotal,
                                'wallet_credits' => $walletCredits
                            ],
                            ['new_balance' => $newBalance],
                            "Removed excess credits (₹{$excessAmount}) not backed by bank payments (admin fix)"
                        );
                        
                        $result = [
                            'success' => true,
                            'message' => "Removed excess credits of ₹" . number_format($excessAmount, 2) . " to match bank payments",
                            'adjustment' => -(float) $excessAmount,
                        ];
                    } else {
                        $result = [
                            'success' => true,
                            'message' => "No excess credits found. Wallet credits match or are less than bank payments.",
                        ];
                    }
                    break;
            }
            
            DB::commit();
            return response()->json($result);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reconciliation Fix Error', ['error' => $e->getMessage(), 'subscription_id' => $subscription->id]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fixing reconciliation: ' . $e->getMessage(),
            ], 500);
        }
    }
}
