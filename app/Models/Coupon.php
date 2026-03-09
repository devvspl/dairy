<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_purchase_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_per_user',
        'times_used',
        'valid_from',
        'valid_until',
        'is_active',
        'applicable_to',
        'apply_to_specific_items',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
        'apply_to_specific_items' => 'boolean',
        'value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function membershipPlans()
    {
        return $this->belongsToMany(MembershipPlan::class, 'coupon_membership_plan');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_product');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now());
    }

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->valid_from > now() || $this->valid_until < now()) {
            return false;
        }

        if ($this->usage_limit && $this->times_used >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function canBeUsedBy($userId)
    {
        if (!$this->isValid()) {
            return false;
        }

        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        
        return $userUsageCount < $this->usage_per_user;
    }

    public function calculateDiscount($amount)
    {
        if ($amount < $this->min_purchase_amount) {
            return 0;
        }

        $discount = $this->type === 'percentage' 
            ? ($amount * $this->value / 100) 
            : $this->value;

        if ($this->max_discount_amount) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return round($discount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) {
            return '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>';
        }

        if ($this->valid_from > now()) {
            return '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Upcoming</span>';
        }

        if ($this->valid_until < now()) {
            return '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Expired</span>';
        }

        if ($this->usage_limit && $this->times_used >= $this->usage_limit) {
            return '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Limit Reached</span>';
        }

        return '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>';
    }
}
