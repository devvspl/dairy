<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryLog extends Model
{
    protected $fillable = [
        'user_subscription_id',
        'delivery_date',
        'quantity_delivered',
        'status',
        'delivery_time',
        'notes',
        'marked_by',
        'marked_at',
    ];

    protected $casts = [
        'delivery_date'     => 'date',
        'quantity_delivered'=> 'decimal:2',
        'marked_at'         => 'datetime',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    public function markedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function scopePending($query)   { return $query->where('status', 'pending'); }
    public function scopeDelivered($query) { return $query->where('status', 'delivered'); }
    public function scopeForDate($query, $date) { return $query->whereDate('delivery_date', $date); }
    public function scopeToday($query)     { return $query->whereDate('delivery_date', now()); }

    public function isDelivered(): bool { return $this->status === 'delivered'; }
    public function isPending(): bool   { return $this->status === 'pending'; }

    /**
     * Auto-generate delivery logs for a wallet subscription.
     *
     * Logic:
     * - daily_cost = price_per_litre × quantity_per_day
     * - total_days_covered = floor(wallet_balance / daily_cost)
     * - already_pending = count of future pending entries (already committed, not yet delivered)
     * - net_new_days = total_days_covered - already_pending
     * - Generates net_new_days entries starting after the last existing entry
     *
     * Safe to call multiple times. Uses firstOrCreate to avoid duplicates.
     */
    public static function autoGenerate(UserSubscription $subscription): int
    {
        $qty           = (float) ($subscription->quantity_per_day ?? 1);
        $pricePerLitre = (float) ($subscription->price_per_litre ?? 0);
        $balance       = (float) ($subscription->wallet_balance ?? 0);

        if ($qty <= 0 || $pricePerLitre <= 0 || $balance <= 0) return 0;

        $dailyCost = round($qty * $pricePerLitre, 2);

        // Total days the current balance can cover
        $totalDaysCovered = (int) floor($balance / $dailyCost);
        if ($totalDaysCovered <= 0) return 0;

        // Count future pending entries (already scheduled, not yet delivered)
        $alreadyPending = static::where('user_subscription_id', $subscription->id)
            ->where('status', 'pending')
            ->whereDate('delivery_date', '>=', now()->toDateString())
            ->count();

        // Net new days to generate
        $netNew = $totalDaysCovered - $alreadyPending;
        if ($netNew <= 0) return 0;

        // Start from the day after the last existing entry (any status)
        $lastDate = static::where('user_subscription_id', $subscription->id)
            ->orderByDesc('delivery_date')
            ->value('delivery_date');

        if ($lastDate) {
            // Extending existing schedule — start after last entry
            $start = \Carbon\Carbon::parse($lastDate)->addDay();
        } else {
            // First time — always use the user's chosen start_date exactly
            $start = $subscription->start_date->copy()->startOfDay();
        }

        $end = $start->copy()->addDays($netNew - 1);

        $generated = 0;
        $cur = $start->copy();
        while ($cur->lte($end)) {
            $log = static::firstOrCreate(
                [
                    'user_subscription_id' => $subscription->id,
                    'delivery_date'        => $cur->format('Y-m-d'),
                ],
                [
                    'quantity_delivered' => $qty,
                    'status'             => 'pending',
                ]
            );
            if ($log->wasRecentlyCreated) $generated++;
            $cur->addDay();
        }
        return $generated;
    }
}
