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
     * Number of days = floor(wallet_balance / daily_cost).
     * Extends from the last existing entry date (or start_date if none).
     * Safe to call multiple times — uses firstOrCreate.
     */
    public static function autoGenerate(UserSubscription $subscription): int
    {
        $qty           = (float) ($subscription->quantity_per_day ?? 1);
        $pricePerLitre = (float) ($subscription->price_per_litre ?? 0);
        $balance       = (float) ($subscription->wallet_balance ?? 0);

        if ($qty <= 0 || $pricePerLitre <= 0 || $balance <= 0) return 0;

        $dailyCost = round($qty * $pricePerLitre, 2);
        $days      = (int) floor($balance / $dailyCost);

        if ($days <= 0) return 0;

        // Find the last existing delivery log to extend from
        $lastDate = static::where('user_subscription_id', $subscription->id)
            ->orderByDesc('delivery_date')
            ->value('delivery_date');

        if ($lastDate) {
            $start = \Carbon\Carbon::parse($lastDate)->addDay();
        } else {
            $start = $subscription->start_date->isFuture()
                ? $subscription->start_date->copy()
                : now()->addDay()->startOfDay();
        }

        $end = $start->copy()->addDays($days - 1);

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
