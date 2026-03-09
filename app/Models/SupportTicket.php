<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id',
        'ticket_number',
        'category',
        'subject',
        'message',
        'status',
        'priority',
        'admin_reply',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'TKT-' . strtoupper(uniqid());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'open' => 'primary',
            'in_progress' => 'warning',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'secondary'
        };
    }

    public function getPriorityBadgeColorAttribute()
    {
        return match($this->priority) {
            'low' => 'secondary',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'secondary'
        };
    }

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'delivery_missed' => '🚚 Missed Delivery',
            'delivery_late' => '⏰ Late Delivery',
            'quality_issue' => '🥛 Quality Issue',
            'quantity_wrong' => '📏 Wrong Quantity',
            'subscription_change' => '📝 Change Subscription',
            'subscription_pause' => '⏸️ Pause/Resume',
            'subscription_cancel' => '❌ Cancel Subscription',
            'payment_issue' => '💳 Payment Issue',
            'address_change' => '📍 Address Change',
            'other' => '❓ Other',
            default => 'General'
        };
    }
}
