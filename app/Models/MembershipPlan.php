<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MembershipPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'plan_type', 'max_orders_per_month', 'product_discount_percent',
        'price', 'duration', 'badge', 'icon', 'description',
        'day_wise_schedule', 'features', 'order', 'is_featured', 'is_active',
    ];

    const TYPE_SCHEDULED = 'scheduled';
    const TYPE_ON_DEMAND = 'on_demand';

    const DURATIONS = [
        '7_days'   => ['label' => '1 Week',   'days' => 7],
        '15_days'  => ['label' => '15 Days',  'days' => 15],
        '1_month'  => ['label' => '1 Month',  'days' => 30],
        '3_months' => ['label' => '3 Months', 'days' => 90],
        '6_months' => ['label' => '6 Months', 'days' => 180],
        '1_year'   => ['label' => '1 Year',   'days' => 365],
    ];

    public function getDurationLabelAttribute(): string
    {
        return self::DURATIONS[$this->duration]['label'] ?? ucfirst(str_replace('_', ' ', $this->duration));
    }

    public function getDurationDaysAttribute(): int
    {
        return self::DURATIONS[$this->duration]['days'] ?? 30;
    }

    protected $casts = [
        'features'                 => 'array',
        'day_wise_schedule'        => 'array',
        'price'                    => 'decimal:2',
        'product_discount_percent' => 'decimal:2',
        'is_featured'              => 'boolean',
        'is_active'                => 'boolean',
    ];

    public function isOnDemand(): bool { return $this->plan_type === self::TYPE_ON_DEMAND; }
    public function isScheduled(): bool { return $this->plan_type === self::TYPE_SCHEDULED; }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($plan) {
            if (empty($plan->slug)) $plan->slug = Str::slug($plan->name);
        });
        static::updating(function ($plan) {
            if ($plan->isDirty('name') && empty($plan->slug)) $plan->slug = Str::slug($plan->name);
        });
    }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }

    public function getDayQuantity($day)
    {
        if (!$this->day_wise_schedule || !isset($this->day_wise_schedule[$day])) return 0;
        return $this->day_wise_schedule[$day]['qty'] ?? 0;
    }

    public function hasDeliveryOnDay($day)
    {
        if (!$this->day_wise_schedule || !isset($this->day_wise_schedule[$day])) return false;
        return $this->day_wise_schedule[$day]['delivery'] ?? false;
    }

    public function getTotalWeeklyQuantity()
    {
        if (!$this->day_wise_schedule) return 0;
        $total = 0;
        foreach ($this->day_wise_schedule as $schedule) {
            if (!empty($schedule['delivery'])) $total += $schedule['qty'] ?? 0;
        }
        return $total;
    }

    public function getDeliveryDaysCount()
    {
        if (!$this->day_wise_schedule) return 0;
        return collect($this->day_wise_schedule)->filter(fn($s) => !empty($s['delivery']))->count();
    }

    public function subscriptions() { return $this->hasMany(UserSubscription::class); }
    public function activeSubscriptions() { return $this->hasMany(UserSubscription::class)->active(); }
}
