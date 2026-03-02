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
        'delivery_date' => 'date',
        'quantity_delivered' => 'decimal:2',
        'marked_at' => 'datetime',
    ];

    /**
     * Get the subscription this delivery belongs to
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    /**
     * Get the admin who marked this delivery
     */
    public function markedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    /**
     * Scope to get pending deliveries
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get delivered items
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope to get deliveries for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('delivery_date', $date);
    }

    /**
     * Scope to get deliveries for today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('delivery_date', now());
    }

    /**
     * Check if delivery is completed
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if delivery is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
