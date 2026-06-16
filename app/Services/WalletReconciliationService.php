<?php

namespace App\Services;

use App\Models\MilkWalletTransaction;
use App\Models\Order;
use App\Models\SubscriptionChangeLog;
use App\Models\UserSubscription;
use App\Models\WalletReconciliationLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * WalletReconciliationService
 *
 * Reconciliation Formula
 * ──────────────────────
 *   expected_balance = Σ wallet credits − Σ wallet debits
 *   actual_balance   = user_subscriptions.wallet_balance
 *   difference       = actual_balance − expected_balance
 *
 *   Status:
 *     difference == 0  → Balanced
 *     difference != 0  → Mismatch
 *
 * Bank vs Ledger Formula
 * ──────────────────────
 *   bank_total       = Σ successful wallet_topup orders for this subscription
 *   ledger_credits   = Σ MilkWalletTransaction (type=credit) for this subscription
 *   bank_diff        = bank_total − ledger_credits
 *   bank_diff > 0  → bank payments not yet credited to wallet
 *   bank_diff < 0  → wallet has more credits than bank payments (excess credits)
 */
class WalletReconciliationService
{
    // ── Calculation ──────────────────────────────────────────────────────────

    /**
     * Full reconciliation snapshot for a subscription.
     */
    public function calculate(UserSubscription $subscription): array
    {
        $totalCredits    = (float) $subscription->walletTransactions()->where('type', 'credit')->sum('amount');
        $totalDebits     = (float) $subscription->walletTransactions()->where('type', 'debit')->sum('amount');
        $expectedBalance = round($totalCredits - $totalDebits, 2);
        $actualBalance   = round((float) $subscription->wallet_balance, 2);
        $difference      = round($actualBalance - $expectedBalance, 2);

        $bankTotal = $this->getBankTotal($subscription);
        $bankDiff  = round($bankTotal - $totalCredits, 2);

        $isBalanced   = abs($difference) < 0.01;
        $bankMatched  = abs($bankDiff) < 0.01;

        $lastLog = WalletReconciliationLog::where('user_subscription_id', $subscription->id)
            ->where('status', 'success')
            ->latest()
            ->first();

        return [
            // Core ledger values
            'total_credits'        => $totalCredits,
            'total_debits'         => $totalDebits,
            'expected_balance'     => $expectedBalance,
            'actual_balance'       => $actualBalance,
            'difference'           => $difference,
            'is_balanced'          => $isBalanced,

            // Bank vs ledger
            'bank_total'           => $bankTotal,
            'bank_diff'            => $bankDiff,
            'bank_matched'         => $bankMatched,

            // wallet_total drift check
            'wallet_total'         => (float) $subscription->wallet_total,
            'expected_wallet_total'=> $totalCredits,                       // real top-up credits = should equal wallet_total
            'wallet_total_diff'    => round((float)$subscription->wallet_total - $totalCredits, 2),

            // Last reconciliation
            'last_reconciled_at'   => $lastLog?->created_at?->toIso8601String(),
            'last_reconciled_by'   => $lastLog?->performedBy?->name,
            'last_fix_type'        => $lastLog?->fix_type,
        ];
    }

    // ── Fix Actions ───────────────────────────────────────────────────────────

    /**
     * Rebuild wallet_balance from the wallet transaction ledger.
     * Formula: wallet_balance = Σ credits − Σ debits
     * Safe to run anytime; no-ops when already balanced.
     */
    public function rebuildFromLedger(UserSubscription $subscription): array
    {
        $calc = $this->calculate($subscription);

        if ($calc['is_balanced']) {
            return ['success' => true, 'skipped' => true, 'message' => 'Books already balanced. No changes made.'];
        }

        return DB::transaction(function () use ($subscription, $calc) {
            $beforeBalance = $calc['actual_balance'];
            $newBalance    = $calc['expected_balance'];

            $subscription->update(['wallet_balance' => $newBalance]);

            WalletReconciliationLog::record(
                $subscription->id,
                'rebuild_from_ledger',
                $beforeBalance,
                $newBalance,
                $calc['expected_balance'],
                $beforeBalance,
                ['total_credits' => $calc['total_credits'], 'total_debits' => $calc['total_debits']],
                "Rebuilt wallet_balance from ledger (credits − debits)"
            );

            SubscriptionChangeLog::record(
                $subscription->id,
                auth()->id(),
                'reconciliation_rebuild_from_ledger',
                ['wallet_balance' => $beforeBalance],
                ['wallet_balance' => $newBalance],
                "Wallet balance rebuilt from transaction ledger. Difference corrected: ₹" . number_format(abs($calc['difference']), 2)
            );

            return [
                'success'       => true,
                'skipped'       => false,
                'before_balance'=> $beforeBalance,
                'after_balance' => $newBalance,
                'difference'    => round($newBalance - $beforeBalance, 2),
                'message'       => "Balance rebuilt from ledger. Changed from ₹" . number_format($beforeBalance, 2) . " → ₹" . number_format($newBalance, 2),
            ];
        });
    }

    /**
     * Fix wallet_balance by recalculating from delivered transactions only.
     * Formula: balance = bank_payments − delivered_debits
     * This ignores any manual/adjustment transactions and resets to a clean state.
     */
    public function fixFromDeliveries(UserSubscription $subscription): array
    {
        return DB::transaction(function () use ($subscription) {
            $bankTotal      = $this->getBankTotal($subscription);
            $deliveryDebits = (float) $subscription->walletTransactions()
                ->where('type', 'debit')
                ->where('is_reversal', false)
                ->whereNotNull('delivery_log_id')
                ->sum('amount');

            $expectedBalance = round($bankTotal - $deliveryDebits, 2);
            $beforeBalance   = round((float) $subscription->wallet_balance, 2);
            $difference      = round($expectedBalance - $beforeBalance, 2);

            if (abs($difference) < 0.01) {
                return ['success' => true, 'skipped' => true, 'message' => 'Balance is already correct based on delivered transactions.'];
            }

            $subscription->update(['wallet_balance' => $expectedBalance]);

            // Insert an adjustment transaction for the audit trail
            MilkWalletTransaction::create([
                'user_id'              => $subscription->user_id,
                'user_subscription_id' => $subscription->id,
                'type'                 => $difference > 0 ? 'credit' : 'debit',
                'amount'               => abs($difference),
                'balance_after'        => $expectedBalance,
                'description'          => "Reconciliation: Fix balance using delivered transactions (admin)",
                'transaction_date'     => now()->toDateString(),
                'is_reversal'          => false,
            ]);

            WalletReconciliationLog::record(
                $subscription->id,
                'fix_from_deliveries',
                $beforeBalance,
                $expectedBalance,
                $expectedBalance,
                $beforeBalance,
                [
                    'bank_total'      => $bankTotal,
                    'delivery_debits' => $deliveryDebits,
                ],
                "Balance fixed using bank payments − delivered debits formula"
            );

            SubscriptionChangeLog::record(
                $subscription->id,
                auth()->id(),
                'reconciliation_fix_from_deliveries',
                ['wallet_balance' => $beforeBalance],
                ['wallet_balance' => $expectedBalance],
                "Balance corrected using delivered transactions. Bank: ₹{$bankTotal}, Delivery debits: ₹{$deliveryDebits}"
            );

            return [
                'success'       => true,
                'skipped'       => false,
                'before_balance'=> $beforeBalance,
                'after_balance' => $expectedBalance,
                'difference'    => $difference,
                'message'       => "Balance fixed. Changed from ₹" . number_format($beforeBalance, 2) . " → ₹" . number_format($expectedBalance, 2),
            ];
        });
    }

    /**
     * Recalculate wallet credits from bank payments.
     * Inserts a single adjustment credit/debit to make ledger credits == bank total.
     * Only runs when bank_diff != 0.
     */
    public function recalculateCredits(UserSubscription $subscription): array
    {
        $calc = $this->calculate($subscription);

        if ($calc['bank_matched']) {
            return ['success' => true, 'skipped' => true, 'message' => 'Bank payments already match wallet credits.'];
        }

        return DB::transaction(function () use ($subscription, $calc) {
            $bankDiff      = $calc['bank_diff'];
            $beforeBalance = $calc['actual_balance'];
            $adjustment    = round($bankDiff, 2);
            $newBalance    = round($beforeBalance + $adjustment, 2);

            MilkWalletTransaction::create([
                'user_id'              => $subscription->user_id,
                'user_subscription_id' => $subscription->id,
                'type'                 => $adjustment > 0 ? 'credit' : 'debit',
                'amount'               => abs($adjustment),
                'balance_after'        => $newBalance,
                'description'          => $adjustment > 0
                    ? "Reconciliation: Missing bank payment credited (admin)"
                    : "Reconciliation: Excess wallet credit removed (admin)",
                'transaction_date'     => now()->toDateString(),
                'is_reversal'          => false,
            ]);

            $subscription->update([
                'wallet_balance' => $newBalance,
                'wallet_total'   => $adjustment > 0
                    ? round((float)$subscription->wallet_total + $adjustment, 2)
                    : $subscription->wallet_total,
            ]);

            WalletReconciliationLog::record(
                $subscription->id,
                'recalculate_credits',
                $beforeBalance,
                $newBalance,
                round($newBalance, 2),
                $beforeBalance,
                [
                    'bank_total'     => $calc['bank_total'],
                    'ledger_credits' => $calc['total_credits'],
                    'bank_diff'      => $bankDiff,
                ],
                "Wallet credits adjusted to match bank payments"
            );

            SubscriptionChangeLog::record(
                $subscription->id,
                auth()->id(),
                'reconciliation_recalculate_credits',
                ['wallet_balance' => $beforeBalance, 'total_credits' => $calc['total_credits']],
                ['wallet_balance' => $newBalance, 'bank_total' => $calc['bank_total']],
                "Bank/credit reconciliation adjustment of ₹" . number_format(abs($adjustment), 2)
            );

            return [
                'success'       => true,
                'skipped'       => false,
                'before_balance'=> $beforeBalance,
                'after_balance' => $newBalance,
                'difference'    => $adjustment,
                'message'       => "Credits adjusted by ₹" . number_format(abs($adjustment), 2)
                    . " to match bank payments. Balance: ₹" . number_format($beforeBalance, 2)
                    . " → ₹" . number_format($newBalance, 2),
            ];
        });
    }

    /**
     * Recalculate wallet debits by verifying each delivery.
     * Inserts a correction entry for the delta between actual debits and expected debits.
     */
    public function recalculateDebits(UserSubscription $subscription): array
    {
        return DB::transaction(function () use ($subscription) {
            // Expected debits = sum of (qty × price) for all DELIVERED logs
            $expectedDebits = 0.0;
            $deliveries = $subscription->deliveryLogs()
                ->where('status', 'delivered')
                ->get();

            foreach ($deliveries as $log) {
                $expectedDebits += round((float)$log->quantity_delivered * (float)$subscription->price_per_litre, 2);
            }
            $expectedDebits = round($expectedDebits, 2);

            $actualDebits = (float) $subscription->walletTransactions()
                ->where('type', 'debit')
                ->sum('amount');
            $actualDebits = round($actualDebits, 2);

            $delta = round($expectedDebits - $actualDebits, 2);

            if (abs($delta) < 0.01) {
                return ['success' => true, 'skipped' => true, 'message' => 'Wallet debits already match delivered transactions.'];
            }

            $beforeBalance = round((float) $subscription->wallet_balance, 2);
            $newBalance    = round($beforeBalance - $delta, 2); // positive delta = more debits needed

            MilkWalletTransaction::create([
                'user_id'              => $subscription->user_id,
                'user_subscription_id' => $subscription->id,
                'type'                 => $delta > 0 ? 'debit' : 'credit',
                'amount'               => abs($delta),
                'balance_after'        => $newBalance,
                'description'          => $delta > 0
                    ? "Reconciliation: Missing delivery debit applied (admin)"
                    : "Reconciliation: Excess delivery debit reversed (admin)",
                'transaction_date'     => now()->toDateString(),
                'is_reversal'          => $delta < 0,
            ]);

            $subscription->update(['wallet_balance' => $newBalance]);

            WalletReconciliationLog::record(
                $subscription->id,
                'recalculate_debits',
                $beforeBalance,
                $newBalance,
                round($beforeBalance - $delta, 2),
                $beforeBalance,
                [
                    'expected_debits' => $expectedDebits,
                    'actual_debits'   => $actualDebits,
                    'delta'           => $delta,
                    'delivered_count' => $deliveries->count(),
                ],
                "Debit reconciliation: expected ₹{$expectedDebits}, actual ₹{$actualDebits}"
            );

            SubscriptionChangeLog::record(
                $subscription->id,
                auth()->id(),
                'reconciliation_recalculate_debits',
                ['wallet_balance' => $beforeBalance, 'actual_debits' => $actualDebits],
                ['wallet_balance' => $newBalance, 'expected_debits' => $expectedDebits],
                "Debit correction of ₹" . number_format(abs($delta), 2)
            );

            return [
                'success'       => true,
                'skipped'       => false,
                'before_balance'=> $beforeBalance,
                'after_balance' => $newBalance,
                'difference'    => -$delta,
                'message'       => "Debits corrected by ₹" . number_format(abs($delta), 2)
                    . ". Balance: ₹" . number_format($beforeBalance, 2)
                    . " → ₹" . number_format($newBalance, 2),
            ];
        });
    }

    /**
     * Mark as reconciled without changing balance.
     * Only allowed when books are already balanced.
     */
    public function markReconciled(UserSubscription $subscription): array
    {
        $calc = $this->calculate($subscription);

        if (!$calc['is_balanced']) {
            return [
                'success' => false,
                'message' => 'Cannot mark as reconciled — books are not balanced. Difference: ₹' . number_format(abs($calc['difference']), 2),
            ];
        }

        $balance = $calc['actual_balance'];

        WalletReconciliationLog::record(
            $subscription->id,
            'mark_reconciled',
            $balance,
            $balance,
            $balance,
            $balance,
            ['total_credits' => $calc['total_credits'], 'total_debits' => $calc['total_debits']],
            "Manually marked as reconciled by admin"
        );

        SubscriptionChangeLog::record(
            $subscription->id,
            auth()->id(),
            'reconciliation_marked',
            [],
            ['balance' => $balance],
            "Books confirmed balanced and marked as reconciled"
        );

        return [
            'success' => true,
            'skipped' => false,
            'message' => 'Marked as reconciled. Books are balanced at ₹' . number_format($balance, 2),
        ];
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Get total successful bank payments for a subscription.
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
     * Get reconciliation log history for display.
     */
    public function getHistory(UserSubscription $subscription, int $limit = 20): array
    {
        return WalletReconciliationLog::where('user_subscription_id', $subscription->id)
            ->with('performedBy:id,name')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn($log) => [
                'id'               => $log->id,
                'fix_type'         => $log->fix_type,
                'fix_label'        => $this->getFixLabel($log->fix_type),
                'before_balance'   => (float) $log->before_balance,
                'after_balance'    => (float) $log->after_balance,
                'difference'       => (float) $log->difference,
                'expected_balance' => (float) $log->expected_balance,
                'actual_balance'   => (float) $log->actual_balance,
                'status'           => $log->status,
                'notes'            => $log->notes,
                'performed_by'     => $log->performedBy?->name ?? 'System',
                'performed_at'     => $log->created_at->format('M j, Y g:i A'),
                'performed_at_human' => $log->created_at->diffForHumans(),
                'meta'             => $log->meta,
            ])
            ->toArray();
    }

    private function getFixLabel(string $fixType): string
    {
        return match($fixType) {
            'rebuild_from_ledger'      => 'Rebuild Balance from Ledger',
            'fix_from_deliveries'      => 'Fix Balance from Deliveries',
            'recalculate_credits'      => 'Recalculate Credits',
            'recalculate_debits'       => 'Recalculate Debits',
            'mark_reconciled'          => 'Marked as Reconciled',
            // legacy fix types
            'sync_balance'             => 'Sync Balance (Legacy)',
            'sync_bank_to_wallet'      => 'Sync Bank to Wallet (Legacy)',
            'prevent_negative'         => 'Prevent Negative Balance (Legacy)',
            'remove_excess_credits'    => 'Remove Excess Credits (Legacy)',
            default                    => ucwords(str_replace('_', ' ', $fixType)),
        };
    }
}
