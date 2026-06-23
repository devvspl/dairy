<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionDeliverySettings extends Model
{
    protected $fillable = [
        'user_subscription_id',
        'milk_type',        // legacy single-milk (kept for backward compat)
        'quantity_per_day', // legacy single-milk (kept for backward compat)
        'delivery_slot',    // legacy single-milk (kept for backward compat)
        'milk_items',       // JSON: [{"milk_type":"cow","qty":1,"slot":"morning","ppl":70.00}, ...]
        'location_id',
        'delivery_address',
        'delivery_instructions',
        'delivery_frequency', // daily, alternate, weekly
        'preferred_day',      // 0=Sun, 1=Mon, ... 6=Sat (used when frequency=weekly)
    ];

    protected $casts = [
        'quantity_per_day' => 'decimal:2',
        'milk_items'       => 'array',
        'preferred_day'    => 'integer',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get milk items array, falling back to legacy single-milk fields.
     */
    public function getMilkItemsResolved(): array
    {
        if (!empty($this->milk_items)) {
            return $this->milk_items;
        }
        // Legacy fallback
        if ($this->milk_type) {
            return [[
                'milk_type' => $this->milk_type,
                'qty'       => (float) ($this->quantity_per_day ?? 1),
                'slot'      => $this->delivery_slot ?? 'morning',
                'ppl'       => 0, // will be looked up
            ]];
        }
        return [];
    }

    /**
     * Calculate total daily cost across all milk items.
     */
    public function dailyCost(): float
    {
        $items = $this->getMilkItemsResolved();
        $total = 0;
        foreach ($items as $item) {
            $ppl = (float) ($item['ppl'] ?? 0);
            if ($ppl <= 0) {
                $mp  = MilkPrice::forType($item['milk_type'] ?? '');
                $ppl = $mp ? (float) $mp->price_per_litre : 0;
            }
            $total += $ppl * (float) ($item['qty'] ?? 1);
        }
        return round($total, 2);
    }

    /**
     * Total litres per day across all milk items.
     */
    public function totalQtyPerDay(): float
    {
        $items = $this->getMilkItemsResolved();
        return array_sum(array_column($items, 'qty'));
    }

    /**
     * Check if a delivery should happen on the given date based on frequency.
     *
     * @param \Carbon\Carbon $date      The date to check
     * @param \Carbon\Carbon $startDate The subscription/schedule start date (used for alternate-day calculation)
     */
    public function shouldDeliverOn(\Carbon\Carbon $date, \Carbon\Carbon $startDate): bool
    {
        $frequency = $this->delivery_frequency ?? 'daily';

        if ($frequency === 'daily') {
            return true;
        }

        if ($frequency === 'alternate') {
            // Deliver on even-numbered days from start (day 0, 2, 4, ...)
            $daysDiff = $startDate->diffInDays($date);
            return $daysDiff % 2 === 0;
        }

        if ($frequency === 'weekly') {
            // Deliver only on the preferred day of the week
            $preferredDay = $this->preferred_day; // 0=Sun ... 6=Sat
            if ($preferredDay === null) {
                return true; // fallback to daily if no preferred day set
            }
            return $date->dayOfWeek === $preferredDay;
        }

        return true;
    }
}
