<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'plan_id',
        'subject',
        'message',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(\App\Models\MembershipPlan::class, 'plan_id');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'new' => '<span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: #dbeafe; color: #1e40af;">New</span>',
            'read' => '<span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: #fef3c7; color: #92400e;">Read</span>',
            'replied' => '<span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: #d1fae5; color: #065f46;">Replied</span>',
            'closed' => '<span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: #f3f4f6; color: #4b5563;">Closed</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: #f3f4f6; color: #4b5563;">Unknown</span>',
        };
    }
}

