<?php

namespace App\Services;

use App\Models\MilkWalletTransaction;
use App\Models\Order;
use App\Models\SubscriptionChangeLog;
use App\Models\UserSubscription;
use App\Models\WalletReconciliationLog;
use Illuminate\Support\Facades\DB;

/**
 * WalletReconciliationService
 *
 * Single source of truth formula
 * ───────────────────────────────
 *   expected_balance = bank_total − delivery_debits
 *
 *   bank_total      = Σ successful wallet_topup orders (what the customer actually paid)
 *   delivery_debits = Σ debit transactions WHERE delivery_log_id IS NOT NULL (milk delivered)
 *   actual_balance  = user_subscriptions.wallet_balance field
 *   difference      = actual_balance − expected_balance
 *
 *   |difference| < 0.01  → Balanced
 *   otherwise            → Mismatch — admin fix needed
 *
 * Why bank_total as the source?
 * ──────────────────────────────
 *   The wallet ledger (milk_wallet_transactions) may accumulate stale
 *   reconciliation adjustment rows from past fix attempts. Using bank_total
 *   (Orders table) and delivery_debits (delivery_log_id-linked rows) ensures
 *   calculations are always anchored to real, verified data and cannot
 *   compound across multiple fix runs.
 */
class WalletReconciliationService
{
    const RECON_PREFIX = 'Reconciliation:';

    // ── Core Calculation ─────────────────────────────────────────────────────

    public function calculate(UserSubscription $subscription): array
    {
        $bankTotal = $this->getBankTotal($subscription);

        // Delivery debits = ALL debit transactions linked to a delivery log
        // (includes original debit + any qty-increase debits for same delivery)
        $deliveryDebits = round(
            (float) $subscription->walletTransactions()
                ->where('type', 'debit')
                ->whereNotNull('delivery_log_id')
                ->where('is_reversal', false)
                ->sum('amount'),
            2
        );

        // Expected debits from delivery logs (ground truth for debit validation)
        $deliveries = $subscription->deliveryLogs()->where('status', 'delivered')->get();
        $expectedDebitsFromLogs = 0.0;
        foreach ($deliveries as $log) {
            $expectedDebitsFromLogs += round(
                (float) $log->quantity_delivered * (float) $subscription->price_per_litre, 2
            );
        }
        $expectedDebitsFromLogs = round($expectedDebitsFromLogs, 2);
        $debitMismatch = abs($deliveryDebits - $expectedDebitsFromLogs) > 0.01;

        $expectedBalance = round($bankTotal - $deliveryDebits, 2);
        $actualBalance   = round((float) $subscription->wallet_balance, 2);
        $difference      = round($actualBalance - $expectedBalance, 2);

        // Has stale adjustment entries in the ledger?
        // Full ledger totals for display only
        $totalCredits = (float) $subscription->walletTransactions()->where('type', 'credit')->sum('amount');
        $totalDebits  = (float) $subscription->walletTransactions()->where('type', 'debit')->sum('amount');

        $adjustmentCredits = (float) $subscription->walletTransactions()
            ->where('type', 'credit')
            ->where('description', 'like', self::RECON_PREFIX . '%')
            ->sum('amount');

        $adjustmentDebits = (float) $subscription->walletTransactions()
            ->where('type', 'debit')
            ->whereNull('delivery_log_id')
            ->where('description', 'like', self::RECON_PREFIX . '%')
            ->sum('amount');

        $hasStaleAdjustments = ($adjustmentCredits + $adjustmentDebits) > 0.01;

        // Books are only truly balanced when:
        // 1. actual_balance == expected_balance, AND
        // 2. No stale adjustment entries exist in the ledger
        $isBalanced = abs($difference) < 0.01 && !$hasStaleAdjustments;

        $lastLog = WalletReconciliationLog::where('user_subscription_id', $subscription->id)
            ->where('status', 'success')
            ->latest()
            ->first();

        return [
            // Core (source of truth)
            'bank_total'                => $bankTotal,
            'delivery_debits'           => $deliveryDebits,
            'expected_debits_from_logs' => $expectedDebitsFromLogs,
            'debit_mismatch'            => $debitMismatch,
            'delivered_count'           => $deliveries->count(),
            'expected_balance'          => $expectedBalance,
            'actual_balance'            => $actualBalance,
            'difference'                => $difference,
            'is_balanced'               => $isBalanced,

            // Aliases used by the view JS
            'real_credits'          => $bankTotal,
            'real_debits'           => $deliveryDebits,
            'total_credits'         => $totalCredits,
            'total_debits'          => $totalDebits,

            // Bank diff always 0 (bank IS the source; kept for view compat)
            'bank_diff'             => 0.0,
            'bank_matched'          => true,

            // Stale adjustment rows in ledger
            'adjustment_credits'    => $adjustmentCredits,
            'adjustment_debits'     => $adjustmentDebits,
            'has_stale_adjustments' => $hasStaleAdjustments,

            // wallet_total field
            'wallet_total'          => (float) $subscription->wallet_total,
            'expected_wallet_total' => $bankTotal,
            'wallet_total_diff'     => round((float) $subscription->wallet_total - $bankTotal, 2),

            // Last reconciliation audit
            'last_reconciled_at'    => $lastLog?->created_at?->toIso8601String(),
            'last_reconciled_by'    => $lastLog?->performedBy?->name,
            'last_fix_type'         => $lastLog?->fix_type,
        ];
    }

    // ── Fix Actions ──────────────────────────────────────────────────────────

    /**
     * Rebuild wallet_balance = bank_total − delivery_debits.
     * Removes all stale reconciliation adjustment rows first.
     */
    public function rebuildFromLedger(UserSubscription $subscription): array
    {
        $calc = $this->calculate($subscription);

        if ($calc['is_balanced']) {
            return ['success' => true, 'skipped' => true,
                    'message' => 'Books already balanced. No changes made.'];
        }

        return DB::transaction(function () use ($subscription, $calc) {
            $beforeBalance = $calc['actual_balance'];
            $newBalance    = $calc['expected_balance'];

            $this->removeAdjustmentTransactions($subscription);
            $subscription->refresh();

            $subscription->update(['wallet_balance' => $newBalance]);

            WalletReconciliationLog::record(
                $subscription->id, 'rebuild_from_ledger',
                $beforeBalance, $newBalance, $newBalance, $beforeBalance,
                ['bank_total' => $calc['bank_total'], 'delivery_debits' => $calc['delivery_debits']],
                "Balance rebuilt: bank ₹{$calc['bank_total']} − deliveries ₹{$calc['delivery_debits']} = ₹{$newBalance}"
            );

            SubscriptionChangeLog::record(
                $subscription->id, auth()->id(),
                'reconciliation_rebuild_from_ledger',
                ['wallet_balance' => $beforeBalance],
                ['wallet_balance' => $newBalance],
                "Balance corrected from ₹{$beforeBalance} to ₹{$newBalance}"
            );

            return [
                'success'        => true,
                'skipped'        => false,
                'before_balance' => $beforeBalance,
                'after_balance'  => $newBalance,
                'difference'     => round($newBalance - $beforeBalance, 2),
                'message'        => "Balance set to ₹" . number_format($newBalance, 2)
                                  . " (bank ₹" . number_format($calc['bank_total'], 2)
                                  . " − delivered ₹" . number_format($calc['delivery_debits'], 2) . ")",
            ];
        });
    }

    /**
     * Fix balance from deliveries — same formula as rebuildFromLedger
     * but inserts an adjustment transaction for the audit trail.
     */
    public function fixFromDeliveries(UserSubscription $subscription): array
    {
        return DB::transaction(function () use ($subscription) {
            $bankTotal      = $this->getBankTotal($subscription);
            $deliveryDebits = round(
                (float) $subscription->walletTransactions()
                    ->where('type', 'debit')
                    ->whereNotNull('delivery_log_id')
                    ->where('is_reversal', false)
                    ->sum('amount'),
                2
            );

            $expectedBalance = round($bankTotal - $deliveryDebits, 2);
            $beforeBalance   = round((float) $subscription->wallet_balance, 2);
            $adjustment      = round($expectedBalance - $beforeBalance, 2);

            if (abs($adjustment) < 0.01) {
                return ['success' => true, 'skipped' => true,
                        'message' => 'Balance already correct.'];
            }

            $this->removeAdjustmentTransactions($subscription);
            $subscription->refresh();
            $beforeBalance = round((float) $subscription->wallet_balance, 2);

            MilkWalletTransaction::create([
                'user_id'              => $subscription->user_id,
                'user_subscription_id' => $subscription->id,
                'type'                 => $adjustment > 0 ? 'credit' : 'debit',
                'amount'               => abs($adjustment),
                'balance_after'        => $expectedBalance,
                'description'          => self::RECON_PREFIX . ' Fix balance from deliveries (admin)',
                'transaction_date'     => now()->toDateString(),
                'is_reversal'          => false,
            ]);

            $subscription->update(['wallet_balance' => $expectedBalance]);

            WalletReconciliationLog::record(
                $subscription->id, 'fix_from_deliveries',
                $beforeBalance, $expectedBalance, $expectedBalance, $beforeBalance,
                ['bank_total' => $bankTotal, 'delivery_debits' => $deliveryDebits],
                "Balance fixed: bank ₹{$bankTotal} − delivery debits ₹{$deliveryDebits} = ₹{$expectedBalance}"
            );

            SubscriptionChangeLog::record(
                $subscription->id, auth()->id(),
                'reconciliation_fix_from_deliveries',
                ['wallet_balance' => $beforeBalance],
                ['wallet_balance' => $expectedBalance],
                "Balance corrected. Bank: ₹{$bankTotal}, Delivery debits: ₹{$deliveryDebits}"
            );

            return [
                'success'        => true,
                'skipped'        => false,
                'before_balance' => $beforeBalance,
                'after_balance'  => $expectedBalance,
                'difference'     => $adjustment,
                'message'        => "Balance fixed to ₹" . number_format($expectedBalance, 2)
                                  . " (₹" . number_format($beforeBalance, 2) . " → ₹" . number_format($expectedBalance, 2) . ")",
            ];
        });
    }

    /**
     * Recalculate credits — since bank_total IS the source of truth,
     * this is equivalent to rebuildFromLedger.
     */
    public function recalculateCredits(UserSubscription $subscription): array
    {
        return $this->rebuildFromLedger($subscription);
    }

    /**
     * Recalculate debits:
     * 1. Compute expected_debits = Σ (qty × price) for all DELIVERED logs.
     * 2. Set wallet_balance = bank_total − expected_debits.
     * 3. Insert a single adjustment transaction for the difference.
     */
    public function recalculateDebits(UserSubscription $subscription): array
    {
        return DB::transaction(function () use ($subscription) {
            $deliveries = $subscription->deliveryLogs()
                ->where('status', 'delivered')
                ->get();

            $expectedDebits = 0.0;
            foreach ($deliveries as $log) {
                $expectedDebits += round(
                    (float) $log->quantity_delivered * (float) $subscription->price_per_litre,
                    2
                );
            }
            $expectedDebits = round($expectedDebits, 2);

            $actualDeliveryDebits = round(
                (float) $subscription->walletTransactions()
                    ->where('type', 'debit')
                    ->whereNotNull('delivery_log_id')
                    ->where('is_reversal', false)
                    ->sum('amount'),
                2
            );

            $bankTotal     = $this->getBankTotal($subscription);
            $newBalance    = round($bankTotal - $expectedDebits, 2);
            $beforeBalance = round((float) $subscription->wallet_balance, 2);
            $balanceDelta  = round($newBalance - $beforeBalance, 2);

            if (abs($balanceDelta) < 0.01 && abs($expectedDebits - $actualDeliveryDebits) < 0.01) {
                return ['success' => true, 'skipped' => true,
                        'message' => "Everything correct. Expected debits ₹" . number_format($expectedDebits, 2)];
            }

            // Clean stale adjustments first
            $this->removeAdjustmentTransactions($subscription);
            $subscription->refresh();
            $beforeBalance = round((float) $subscription->wallet_balance, 2);
            $newBalance    = round($bankTotal - $expectedDebits, 2);
            $balanceDelta  = round($newBalance - $beforeBalance, 2);

            if (abs($balanceDelta) > 0.01) {
                MilkWalletTransaction::create([
                    'user_id'              => $subscription->user_id,
                    'user_subscription_id' => $subscription->id,
                    'type'                 => $balanceDelta > 0 ? 'credit' : 'debit',
                    'amount'               => abs($balanceDelta),
                    'balance_after'        => $newBalance,
                    'description'          => self::RECON_PREFIX . ' Debit recalculation adjustment (admin)',
                    'transaction_date'     => now()->toDateString(),
                    'is_reversal'          => false,
                ]);
            }

            $subscription->update(['wallet_balance' => $newBalance]);

            WalletReconciliationLog::record(
                $subscription->id, 'recalculate_debits',
                $beforeBalance, $newBalance, $newBalance, $beforeBalance,
                [
                    'bank_total'             => $bankTotal,
                    'expected_debits'        => $expectedDebits,
                    'actual_delivery_debits' => $actualDeliveryDebits,
                    'delivered_count'        => $deliveries->count(),
                ],
                "Debits recalculated. {$deliveries->count()} deliveries × ₹{$subscription->price_per_litre}/L = ₹{$expectedDebits}"
            );

            SubscriptionChangeLog::record(
                $subscription->id, auth()->id(),
                'reconciliation_recalculate_debits',
                ['wallet_balance' => $beforeBalance, 'actual_delivery_debits' => $actualDeliveryDebits],
                ['wallet_balance' => $newBalance, 'expected_debits' => $expectedDebits],
                "Balance set to ₹{$newBalance} (bank ₹{$bankTotal} − expected debits ₹{$expectedDebits})"
            );

            return [
                'success'        => true,
                'skipped'        => false,
                'before_balance' => $beforeBalance,
                'after_balance'  => $newBalance,
                'difference'     => $balanceDelta,
                'message'        => "Balance corrected to ₹" . number_format($newBalance, 2)
                                  . " (bank ₹" . number_format($bankTotal, 2)
                                  . " − ₹" . number_format($expectedDebits, 2) . " deliveries)",
            ];
        });
    }

    /**
     * Mark as reconciled — only when books are already balanced.
     */
    public function markReconciled(UserSubscription $subscription): array
    {
        $calc = $this->calculate($subscription);

        if (!$calc['is_balanced']) {
            return [
                'success' => false,
                'message' => 'Cannot mark as reconciled — books are not balanced. Difference: ₹'
                           . number_format(abs($calc['difference']), 2),
            ];
        }

        $balance = $calc['actual_balance'];

        WalletReconciliationLog::record(
            $subscription->id, 'mark_reconciled',
            $balance, $balance, $balance, $balance,
            ['bank_total' => $calc['bank_total'], 'delivery_debits' => $calc['delivery_debits']],
            "Manually marked as reconciled by admin"
        );

        SubscriptionChangeLog::record(
            $subscription->id, auth()->id(),
            'reconciliation_marked',
            [], ['balance' => $balance],
            "Books confirmed balanced at ₹{$balance}"
        );

        return [
            'success' => true,
            'skipped' => false,
            'message' => 'Marked as reconciled. Balance confirmed at ₹' . number_format($balance, 2),
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Delete reconciliation adjustment transactions (those with RECON_PREFIX
     * in description and no delivery_log_id).
     */
    private function removeAdjustmentTransactions(UserSubscription $subscription, ?string $type = null): void
    {
        $query = $subscription->walletTransactions()
            ->whereNull('delivery_log_id')
            ->where('description', 'like', self::RECON_PREFIX . '%');

        if ($type !== null) {
            $query->where('type', $type);
        }

        $query->delete();
    }

    /**
     * Total successful bank payments for a subscription.
     */
    public function getBankTotal(UserSubscription $subscription): float
    {
        return (float) Order::where('user_id', $subscription->user_id)
            ->where(function ($q) use ($subscription) {
                $q->where('user_subscription_id', $subscription->id)
                  ->orWhere(function ($q2) use ($subscription) {
                      $q2->where('order_type', 'wallet_topup')
                         ->whereNull('user_subscription_id')
                         ->whereJsonContains('wallet_meta->location_id', (string) $subscription->location_id);
                  });
            })
            ->where('order_type', 'wallet_topup')
            ->where('status', 'success')
            ->sum('amount');
    }

    /**
     * Reconciliation log history for display.
     */
    public function getHistory(UserSubscription $subscription, int $limit = 20): array
    {
        return WalletReconciliationLog::where('user_subscription_id', $subscription->id)
            ->with('performedBy:id,name')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn($log) => [
                'id'                 => $log->id,
                'fix_type'           => $log->fix_type,
                'fix_label'          => $this->getFixLabel($log->fix_type),
                'before_balance'     => (float) $log->before_balance,
                'after_balance'      => (float) $log->after_balance,
                'difference'         => (float) $log->difference,
                'expected_balance'   => (float) $log->expected_balance,
                'actual_balance'     => (float) $log->actual_balance,
                'status'             => $log->status,
                'notes'              => $log->notes,
                'performed_by'       => $log->performedBy?->name ?? 'System',
                'performed_at'       => $log->created_at->format('M j, Y g:i A'),
                'performed_at_human' => $log->created_at->diffForHumans(),
                'meta'               => $log->meta,
            ])
            ->toArray();
    }

    private function getFixLabel(string $fixType): string
    {
        return match ($fixType) {
            'rebuild_from_ledger'   => 'Rebuild Balance from Ledger',
            'fix_from_deliveries'   => 'Fix Balance from Deliveries',
            'recalculate_credits'   => 'Recalculate Credits',
            'recalculate_debits'    => 'Recalculate Debits',
            'mark_reconciled'       => 'Marked as Reconciled',
            'sync_balance'          => 'Sync Balance (Legacy)',
            'sync_bank_to_wallet'   => 'Sync Bank to Wallet (Legacy)',
            'prevent_negative'      => 'Prevent Negative Balance (Legacy)',
            'remove_excess_credits' => 'Remove Excess Credits (Legacy)',
            default                 => ucwords(str_replace('_', ' ', $fixType)),
        };
    }
}
