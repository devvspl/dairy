<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MembershipPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'duration',
        'badge',
        'icon',
        'description',
        'day_wise_schedule',
        'features',
        'order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'day_wise_schedule' => 'array',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($plan) {
            if (empty($plan->slug)) {
                $plan->slug = Str::slug($plan->name);
            }
        });

        static::updating(function ($plan) {
            if ($plan->isDirty('name') && empty($plan->slug)) {
                $plan->slug = Str::slug($plan->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get delivery quantity for a specific day
     * 
     * @param string $day (Mon, Tue, Wed, Thu, Fri, Sat, Sun)
     * @return float|int
     */
    public function getDayQuantity($day)
    {
        if (!$this->day_wise_schedule || !isset($this->day_wise_schedule[$day])) {
            return 0;
        }
        
        return $this->day_wise_schedule[$day]['qty'] ?? 0;
    }

    /**
     * Check if delivery is available on a specific day
     * 
     * @param string $day (Mon, Tue, Wed, Thu, Fri, Sat, Sun)
     * @return bool
     */
    public function hasDeliveryOnDay($day)
    {
        if (!$this->day_wise_schedule || !isset($this->day_wise_schedule[$day])) {
            return false;
        }
        
        return $this->day_wise_schedule[$day]['delivery'] ?? false;
    }

    /**
     * Get total weekly quantity
     * 
     * @return float|int
     */
    public function getTotalWeeklyQuantity()
    {
        if (!$this->day_wise_schedule) {
            return 0;
        }

        $total = 0;
        foreach ($this->day_wise_schedule as $day => $schedule) {
            if (isset($schedule['delivery']) && $schedule['delivery']) {
                $total += $schedule['qty'] ?? 0;
            }
        }

        return $total;
    }

    /**
     * Get delivery days count
     * 
     * @return int
     */
    public function getDeliveryDaysCount()
    {
        if (!$this->day_wise_schedule) {
            return 0;
        }

        $count = 0;
        foreach ($this->day_wise_schedule as $day => $schedule) {
            if (isset($schedule['delivery']) && $schedule['delivery']) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get all subscriptions for this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Get active subscriptions for this plan
     */
    public function activeSubscriptions()
    {
        return $this->hasMany(UserSubscription::class)->active();
    }
}
