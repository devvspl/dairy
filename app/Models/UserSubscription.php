<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'membership_plan_id',
        'start_date',
        'end_date',
        'status',
        'payment_method',
        'payment_status',
        'delivery_address',
        'amount_paid',
        'transaction_id',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount_paid' => 'decimal:2',
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
}
