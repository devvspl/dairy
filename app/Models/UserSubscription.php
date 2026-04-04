<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id', 'membership_plan_id', 'location_id',
        'start_date', 'end_date', 'status',
        'payment_method', 'payment_status', 'delivery_address',
        'amount_paid', 'transaction_id', 'notes',
        'wallet_total', 'wallet_balance', 'price_per_litre',
        'milk_type', 'quantity_per_day', 'delivery_slot',
        'delivery_status', 'delivery_instructions',
    ];

    protected $casts = [
        'start_date'       => 'date',
        'end_date'         => 'date',
        'amount_paid'      => 'decimal:2',
        'wallet_total'     => 'decimal:2',
        'wallet_balance'   => 'decimal:2',
        'price_per_litre'  => 'decimal:2',
        'quantity_per_day' => 'decimal:2',
        'delivery_status'  => 'string',
    ];

    /**
     * Get the user that owns the subscription
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the membership plan for this subscription
     */
    public function membershipPlan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class);
    }

    /**
     * Get the location for this subscription
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Scope to get active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    /**
     * Scope to get pending subscriptions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->start_date <= now() 
            && $this->end_date >= now();
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->end_date < now();
    }

    /**
     * Get days remaining in subscription
     */
    public function daysRemaining(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Get all delivery logs for this subscription
     */
    public function deliveryLogs()
    {
        return $this->hasMany(DeliveryLog::class);
    }

    /**
     * Wallet transactions for this subscription
     */
    public function walletTransactions()
    {
        return $this->hasMany(MilkWalletTransaction::class);
    }

    /**
     * Get delivered count
     */
    public function deliveredCount(): int
    {
        return $this->deliveryLogs()->delivered()->count();
    }

    /**
     * Get pending deliveries count
     */
    public function pendingCount(): int
    {
        return $this->deliveryLogs()->pending()->count();
    }

    /**
     * Get total quantity delivered
     */
    public function totalQuantityDelivered(): float
    {
        return $this->deliveryLogs()->delivered()->sum('quantity_delivered');
    }

    // ── Wallet helpers (on-demand plans) ─────────────────────

    public function isOnDemand(): bool
    {
        // Wallet-only subscriptions (no plan) are always on-demand
        if (!$this->membership_plan_id) return true;
        return $this->membershipPlan && $this->membershipPlan->isOnDemand();
    }

    public function walletBalanceFormatted(): string
    {
        return '₹' . number_format((float) $this->wallet_balance, 2);
    }

    public function walletUsedAmount(): float
    {
        return max(0, (float) $this->wallet_total - (float) $this->wallet_balance);
    }

    public function walletUsedPercent(): float
    {
        if (!$this->wallet_total || $this->wallet_total == 0) return 0;
        return round(($this->walletUsedAmount() / $this->wallet_total) * 100, 1);
    }

    public function walletRemainingPercent(): float
    {
        return max(0, 100 - $this->walletUsedPercent());
    }

    /**
     * Debit wallet for a delivery.
     * Pass $deliveryLogId to link the transaction to the specific delivery.
     * Idempotent: if a debit already exists for this delivery_log_id, skips.
     * Returns false if insufficient balance.
     */
    public function debitWallet(float $litres, string $date, ?int $markedBy = null, ?int $deliveryLogId = null): bool
    {
        // Idempotency: skip if a debit already exists for this delivery log
        if ($deliveryLogId) {
            $exists = MilkWalletTransaction::where('user_subscription_id', $this->id)
                ->where('delivery_log_id', $deliveryLogId)
                ->where('type', 'debit')
                ->where('is_reversal', false)
                ->exists();
            if ($exists) return true; // already debited, nothing to do
        }

        $pricePerLitre = (float) $this->price_per_litre;
        $amount        = round($litres * $pricePerLitre, 2);
        $newBalance    = round((float) $this->wallet_balance - $amount, 2);

        if ($newBalance < 0) return false;

        $this->update(['wallet_balance' => $newBalance]);

        MilkWalletTransaction::create([
            'user_id'              => $this->user_id,
            'user_subscription_id' => $this->id,
            'delivery_log_id'      => $deliveryLogId,
            'type'                 => 'debit',
            'amount'               => $amount,
            'litres'               => $litres,
            'balance_after'        => $newBalance,
            'description'          => number_format($litres, 2) . 'L milk delivered',
            'transaction_date'     => $date,
            'is_reversal'          => false,
        ]);

        return true;
    }

    /**
     * Credit wallet.
     * For reversals (delivery un-marked): pass is_reversal=true so wallet_total is NOT inflated.
     * For top-ups: is_reversal=false (default), wallet_total IS incremented.
     */
    public function creditWallet(float $amount, string $description = 'Pack purchased', bool $isReversal = false, ?int $deliveryLogId = null): void
    {
        $newBalance = round((float) $this->wallet_balance + $amount, 2);

        $update = ['wallet_balance' => $newBalance];
        // Only inflate wallet_total for real top-ups, not reversals
        if (!$isReversal) {
            $update['wallet_total'] = round((float) $this->wallet_total + $amount, 2);
        }
        $this->update($update);

        MilkWalletTransaction::create([
            'user_id'              => $this->user_id,
            'user_subscription_id' => $this->id,
            'delivery_log_id'      => $deliveryLogId,
            'type'                 => 'credit',
            'amount'               => $amount,
            'balance_after'        => $newBalance,
            'description'          => $description,
            'transaction_date'     => now()->toDateString(),
            'is_reversal'          => $isReversal,
        ]);
    }
}
