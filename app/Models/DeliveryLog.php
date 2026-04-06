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
        'milk_items',
        'status',
        'delivery_time',
        'notes',
        'marked_by',
        'marked_at',
    ];

    protected $casts = [
        'delivery_date'     => 'date',
        'quantity_delivered'=> 'decimal:2',
        'milk_items'        => 'array',
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
     * Supports both single-milk (legacy) and multi-milk (milk_items) modes.
     */
    public static function autoGenerate(UserSubscription $subscription): int
    {
        $balance = (float) ($subscription->wallet_balance ?? 0);
        if ($balance <= 0) return 0;

        // Try to get delivery settings for multi-milk support
        $settings  = $subscription->deliverySettings;
        $milkItems = $settings ? $settings->getMilkItemsResolved() : [];

        // Calculate daily cost
        if (!empty($milkItems)) {
            $dailyCost  = $settings->dailyCost();
            $totalQty   = $settings->totalQtyPerDay();
        } else {
            // Legacy single-milk fallback
            $qty           = (float) ($subscription->quantity_per_day ?? 1);
            $pricePerLitre = (float) ($subscription->price_per_litre ?? 0);
            if ($qty <= 0 || $pricePerLitre <= 0) return 0;
            $dailyCost  = round($qty * $pricePerLitre, 2);
            $totalQty   = $qty;
            $milkItems  = [];
        }

        if ($dailyCost <= 0) return 0;

        $totalDaysCovered = (int) floor($balance / $dailyCost);
        if ($totalDaysCovered <= 0) return 0;

        $alreadyPending = static::where('user_subscription_id', $subscription->id)
            ->where('status', 'pending')
            ->whereDate('delivery_date', '>=', now()->toDateString())
            ->count();

        $netNew = $totalDaysCovered - $alreadyPending;
        if ($netNew <= 0) return 0;

        $lastDate = static::where('user_subscription_id', $subscription->id)
            ->orderByDesc('delivery_date')
            ->value('delivery_date');

        $start = $lastDate
            ? \Carbon\Carbon::parse($lastDate)->addDay()
            : $subscription->start_date->copy()->startOfDay();

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
                    'quantity_delivered' => $totalQty,
                    'milk_items'         => !empty($milkItems) ? $milkItems : null,
                    'status'             => 'pending',
                ]
            );
            if ($log->wasRecentlyCreated) $generated++;
            $cur->addDay();
        }
        return $generated;
    }
}
