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
 * Reconciliation Formula
 * ──────────────────────
 *   expected_balance = Σ real_credits − Σ real_debits
 *
 *   real_credits = wallet credits where description NOT LIKE 'Reconciliation:%'
 *                  (i.e. genuine top-up / reversal credits only)
 *   real_debits  = wallet debits where delivery_log_id IS NOT NULL
 *                  (i.e. genuine delivery debits only)
 *
 *   actual_balance   = user_subscriptions.wallet_balance
 *   difference       = actual_balance − expected_balance
 *
 *   Status:
 *     |difference| < 0.01  → Balanced
 *     otherwise            → Mismatch
 *
 * Why exclude reconciliation adjustment rows?
 * ─────────────────────────────────────────────
 *   Each reconciliation fix inserts an adjustment transaction into
 *   milk_wallet_transactions so there is an audit trail.  If we include
 *   those adjustment rows in subsequent calculations the numbers
 *   compound — every new fix sees the previous fix's correction and
 *   creates yet another adjustment.  By always deriving expected_balance
 *   from "real" (non-adjustment) transactions we stay idempotent.
 */
class WalletReconciliationService
{
    /** Prefix used on all reconciliation adjustment transaction descriptions */
    const RECON_PREFIX = 'Reconciliation:';

    // ── Calculation ──────────────────────────────────────────────────────────

    public function calculate(UserSubscription $subscription): array
    {
        // Real credits = every credit that is NOT a reconciliation adjustment
        $realCredits = (float) $subscription->walletTransactions()
            ->where('type', 'credit')
            ->where('description', 'not like', self::RECON_PREFIX . '%')
            ->sum('amount');

        // Real debits = only delivery-linked debits (not adjustment debits)
        $realDebits = (float) $subscription->walletTransactions()
            ->where('type', 'debit')
            ->whereNotNull('delivery_log_id')
            ->where('is_reversal', false)
            ->sum('amount');

        $expectedBalance = round($realCredits - $realDebits, 2);
        $actualBalance   = round((float) $subscription->wallet_balance, 2);
        $difference      = round($actualBalance - $expectedBalance, 2);
        $isBalanced      = abs($difference) < 0.01;

        // Bank vs real credits
        $bankTotal   = $this->getBankTotal($subscription);
        $bankDiff    = round($bankTotal - $realCredits, 2);
        $bankMatched = abs($bankDiff) < 0.01;

        // Outstanding adjustment transactions (so UI can warn about them)
        $adjustmentCredits = (float) $subscription->walletTransactions()
            ->where('type', 'credit')
            ->where('description', 'like', self::RECON_PREFIX . '%')
            ->sum('amount');

        $adjustmentDebits = (float) $subscription->walletTransactions()
            ->where('type', 'debit')
            ->whereNull('delivery_log_id')
            ->where('description', 'like', self::RECON_PREFIX . '%')
            ->sum('amount');

        $lastLog = WalletReconciliationLog::where('user_subscription_id', $subscription->id)
            ->where('status', 'success')
            ->latest()
            ->first();

        return [
            // Core values (real transactions only)
            'real_credits'          => $realCredits,
            'real_debits'           => $realDebits,
            'expected_balance'      => $expectedBalance,
            'actual_balance'        => $actualBalance,
            'difference'            => $difference,
            'is_balanced'           => $isBalanced,

            // Legacy keys expected by the view
            'total_credits'         => $realCredits,
            'total_debits'          => $realDebits,

            // Bank vs ledger
            'bank_total'            => $bankTotal,
            'bank_diff'             => $bankDiff,
            'bank_matched'          => $bankMatched,

            // Adjustment rows already in ledger (for info display)
            'adjustment_credits'    => $adjustmentCredits,
            'adjustment_debits'     => $adjustmentDebits,

            // wallet_total drift
            'wallet_total'          => (float) $subscription->wallet_total,
            'expected_wallet_total' => $realCredits,
            'wallet_total_diff'     => round((float) $subscription->wallet_total - $realCredits, 2),

            // Last reconciliation
            'last_reconciled_at'    => $lastLog?->created_at?->toIso8601String(),
            'last_reconciled_by'    => $lastLog?->performedBy?->name,
            'last_fix_type'         => $lastLog?->fix_type,
        ];
    }

    // ── Fix Actions ───────────────────────────────────────────────────────────

    /**
     * Rebuild wallet_balance from real ledger transactions.
     * Formula: wallet_balance = real_credits − real_debits
     *
     * Before setting the new balance this removes all prior reconciliation
     * adjustment transactions so we start from a clean slate.
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

            // Remove stale adjustment transactions so ledger is clean
            $this->removeAdjustmentTransactions($subscription);

            $subscription->refresh();
            $subscription->update(['wallet_balance' => $newBalance]);

            WalletReconciliationLog::record(
                $subscription->id, 'rebuild_from_ledger',
                $beforeBalance, $newBalance,
                $calc['expected_balance'], $beforeBalance,
                ['real_credits' => $calc['real_credits'], 'real_debits' => $calc['real_debits']],
                "Rebuilt wallet_balance from real ledger (credits − delivery debits)"
            );

            SubscriptionChangeLog::record(
                $subscription->id, auth()->id(),
                'reconciliation_rebuild_from_ledger',
                ['wallet_balance' => $beforeBalance],
                ['wallet_balance' => $newBalance],
                "Balance rebuilt from ledger. Correction: ₹" . number_format(abs($calc['difference']), 2)
            );

            return [
                'success'        => true,
                'skipped'        => false,
                'before_balance' => $beforeBalance,
                'after_balance'  => $newBalance,
                'difference'     => round($newBalance - $beforeBalance, 2),
                'message'        => "Balance rebuilt. ₹" . number_format($beforeBalance, 2)
                                  . " → ₹" . number_format($newBalance, 2),
            ];
        });
    }

    /**
     * Fix balance using delivered transactions.
     * Formula: expected = bank_payments − delivery_debits
     *
     * This is the "ground truth" fix — it anchors the balance to actual
     * physical deliveries and verified bank payments only.
     */
    public function fixFromDeliveries(UserSubscription $subscription): array
    {
        return DB::transaction(function () use ($subscription) {
            $bankTotal      = $this->getBankTotal($subscription);
            $deliveryDebits = (float) $subscription->walletTransactions()
                ->where('type', 'debit')
                ->whereNotNull('delivery_log_id')
                ->where('is_reversal', false)
                ->sum('amount');

            $expectedBalance = round($bankTotal - $deliveryDebits, 2);
            $beforeBalance   = round((float) $subscription->wallet_balance, 2);
            $adjustment      = round($expectedBalance - $beforeBalance, 2);

            if (abs($adjustment) < 0.01) {
                return ['success' => true, 'skipped' => true,
                        'message' => 'Balance already correct based on delivered transactions.'];
            }

            // Remove prior adjustment entries first
            $this->removeAdjustmentTransactions($subscription);
            $subscription->refresh();

            // Single adjustment transaction for audit trail
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
                'message'        => "Balance fixed. ₹" . number_format($beforeBalance, 2)
                                  . " → ₹" . number_format($expectedBalance, 2),
            ];
        });
    }

    /**
     * Recalculate credits: add/remove a single adjustment so
     * real_credits == bank_total.
     *
     * Use case: bank recorded ₹500 but wallet only shows ₹420 credits
     * (missing ₹80 credit entry).
     */
    public function recalculateCredits(UserSubscription $subscription): array
    {
        $calc = $this->calculate($subscription);

        if ($calc['bank_matched']) {
            return ['success' => true, 'skipped' => true,
                    'message' => 'Bank payments already match wallet credits.'];
        }

        return DB::transaction(function () use ($subscription, $calc) {
            $bankDiff      = $calc['bank_diff'];         // bank_total − real_credits
            $beforeBalance = $calc['actual_balance'];
            $newBalance    = round($beforeBalance + $bankDiff, 2);

            // Remove any prior credit adjustment so we don't compound
            $this->removeAdjustmentTransactions($subscription, 'credit');
            $subscription->refresh();
            $beforeBalance = round((float) $subscription->wallet_balance, 2);
            $newBalance    = round($beforeBalance + $bankDiff, 2);

            MilkWalletTransaction::create([
                'user_id'              => $subscription->user_id,
                'user_subscription_id' => $subscription->id,
                'type'                 => $bankDiff > 0 ? 'credit' : 'debit',
                'amount'               => abs($bankDiff),
                'balance_after'        => $newBalance,
                'description'          => $bankDiff > 0
                    ? self::RECON_PREFIX . ' Missing bank payment credited (admin)'
                    : self::RECON_PREFIX . ' Excess wallet credit removed (admin)',
                'transaction_date'     => now()->toDateString(),
                'is_reversal'          => false,
            ]);

            $subscription->update([
                'wallet_balance' => $newBalance,
                'wallet_total'   => $bankDiff > 0
                    ? round((float) $subscription->wallet_total + $bankDiff, 2)
                    : $subscription->wallet_total,
            ]);

            WalletReconciliationLog::record(
                $subscription->id, 'recalculate_credits',
                $beforeBalance, $newBalance, $newBalance, $beforeBalance,
                ['bank_total' => $calc['bank_total'], 'real_credits' => $calc['real_credits'], 'bank_diff' => $bankDiff],
                "Credits adjusted to match bank payments"
            );

            SubscriptionChangeLog::record(
                $subscription->id, auth()->id(),
                'reconciliation_recalculate_credits',
                ['wallet_balance' => $beforeBalance, 'real_credits' => $calc['real_credits']],
                ['wallet_balance' => $newBalance, 'bank_total' => $calc['bank_total']],
                "Credit adjustment of ₹" . number_format(abs($bankDiff), 2)
            );

            return [
                'success'        => true,
                'skipped'        => false,
                'before_balance' => $beforeBalance,
                'after_balance'  => $newBalance,
                'difference'     => $bankDiff,
                'message'        => "Credits adjusted by ₹" . number_format(abs($bankDiff), 2)
                                  . ". Balance ₹" . number_format($beforeBalance, 2)
                                  . " → ₹" . number_format($newBalance, 2),
            ];
        });
    }

    /**
     * Recalculate debits: compare delivery-based expected debits vs actual
     * delivery-linked debits and insert a single correction entry.
     *
     * expected_debits = Σ (qty × price_per_litre) for all delivered logs
     * actual_debits   = Σ debit transactions WHERE delivery_log_id IS NOT NULL
     *
     * This is idempotent: adjustment rows (no delivery_log_id) are ignored.
     */
    public function recalculateDebits(UserSubscription $subscription): array
    {
        return DB::transaction(function () use ($subscription) {
            // Ground truth: what deliveries say we should have debited
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

            // What has actually been debited via delivery transactions
            $actualDeliveryDebits = (float) $subscription->walletTransactions()
                ->where('type', 'debit')
                ->whereNotNull('delivery_log_id')
                ->where('is_reversal', false)
                ->sum('amount');
            $actualDeliveryDebits = round($actualDeliveryDebits, 2);

            $delta = round($expectedDebits - $actualDeliveryDebits, 2);

            if (abs($delta) < 0.01) {
                return ['success' => true, 'skipped' => true,
                        'message' => "Delivery debits already correct at ₹" . number_format($expectedDebits, 2) . "."];
            }

            // Remove prior debit adjustments to stay idempotent
            $this->removeAdjustmentTransactions($subscription, 'debit');
            $subscription->refresh();

            $beforeBalance = round((float) $subscription->wallet_balance, 2);
            $newBalance    = round($beforeBalance - $delta, 2);

            MilkWalletTransaction::create([
                'user_id'              => $subscription->user_id,
                'user_subscription_id' => $subscription->id,
                'type'                 => $delta > 0 ? 'debit' : 'credit',
                'amount'               => abs($delta),
                'balance_after'        => $newBalance,
                'description'          => $delta > 0
                    ? self::RECON_PREFIX . ' Missing delivery debit applied (admin)'
                    : self::RECON_PREFIX . ' Excess delivery debit reversed (admin)',
                'transaction_date'     => now()->toDateString(),
                'is_reversal'          => $delta < 0,
            ]);

            $subscription->update(['wallet_balance' => $newBalance]);

            WalletReconciliationLog::record(
                $subscription->id, 'recalculate_debits',
                $beforeBalance, $newBalance,
                $beforeBalance - $delta, $beforeBalance,
                [
                    'expected_debits'      => $expectedDebits,
                    'actual_delivery_debits'=> $actualDeliveryDebits,
                    'delta'                => $delta,
                    'delivered_count'      => $deliveries->count(),
                ],
                "Debit reconciliation: expected ₹{$expectedDebits}, actual delivery debits ₹{$actualDeliveryDebits}"
            );

            SubscriptionChangeLog::record(
                $subscription->id, auth()->id(),
                'reconciliation_recalculate_debits',
                ['wallet_balance' => $beforeBalance, 'actual_delivery_debits' => $actualDeliveryDebits],
                ['wallet_balance' => $newBalance, 'expected_debits' => $expectedDebits],
                "Debit correction of ₹" . number_format(abs($delta), 2)
                . " (expected ₹{$expectedDebits}, had ₹{$actualDeliveryDebits})"
            );

            return [
                'success'        => true,
                'skipped'        => false,
                'before_balance' => $beforeBalance,
                'after_balance'  => $newBalance,
                'difference'     => -$delta,
                'message'        => "Debits corrected by ₹" . number_format(abs($delta), 2)
                                  . ". Balance ₹" . number_format($beforeBalance, 2)
                                  . " → ₹" . number_format($newBalance, 2),
            ];
        });
    }

    /**
     * Mark as reconciled (no balance change).
     * Only allowed when books are balanced.
     */
    public function markReconciled(UserSubscription $subscription): array
    {
        $calc = $this->calculate($subscription);

        if (!$calc['is_balanced']) {
            return [
                'success' => false,
                'message' => 'Cannot mark as reconciled — books are not balanced. '
                           . 'Difference: ₹' . number_format(abs($calc['difference']), 2),
            ];
        }

        $balance = $calc['actual_balance'];

        WalletReconciliationLog::record(
            $subscription->id, 'mark_reconciled',
            $balance, $balance, $balance, $balance,
            ['real_credits' => $calc['real_credits'], 'real_debits' => $calc['real_debits']],
            "Manually marked as reconciled by admin"
        );

        SubscriptionChangeLog::record(
            $subscription->id, auth()->id(),
            'reconciliation_marked',
            [], ['balance' => $balance],
            "Books confirmed balanced and marked as reconciled"
        );

        return [
            'success' => true,
            'skipped' => false,
            'message' => 'Marked as reconciled. Balance confirmed at ₹' . number_format($balance, 2),
        ];
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Remove reconciliation adjustment transactions.
     * Pass $type = 'credit'|'debit' to remove only one side, or null for both.
     *
     * These are identified by description starting with 'Reconciliation:' AND
     * having no delivery_log_id (so real delivery debits are never touched).
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
     * Total successful bank payments linked to this subscription.
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
     * Reconciliation audit log history.
     */
    public function getHistory(UserSubscription $subscription, int $limit = 20): array
    {
        return WalletReconciliationLog::where('user_subscription_id', $subscription->id)
            ->with('performedBy:id,name')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn($log) => [
                'id'                => $log->id,
                'fix_type'          => $log->fix_type,
                'fix_label'         => $this->getFixLabel($log->fix_type),
                'before_balance'    => (float) $log->before_balance,
                'after_balance'     => (float) $log->after_balance,
                'difference'        => (float) $log->difference,
                'expected_balance'  => (float) $log->expected_balance,
                'actual_balance'    => (float) $log->actual_balance,
                'status'            => $log->status,
                'notes'             => $log->notes,
                'performed_by'      => $log->performedBy?->name ?? 'System',
                'performed_at'      => $log->created_at->format('M j, Y g:i A'),
                'performed_at_human'=> $log->created_at->diffForHumans(),
                'meta'              => $log->meta,
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
