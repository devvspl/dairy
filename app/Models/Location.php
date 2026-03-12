<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Location extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'area',
        'sector',
        'city',
        'banner_image',
        'title',
        'description',
        'building_name',
        'building_type',
        'delivery_timing',
        'delivery_point',
        'handling_info',
        'address',
        'map_embed_url',
        'hero_badges',
        'route_steps',
        'highlights',
        'mini_items',
        'guidelines',
        'coverage_areas',
        'faqs',
        'contact_phone',
        'contact_whatsapp',
        'meta_title',
        'meta_description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'hero_badges' => 'array',
        'route_steps' => 'array',
        'highlights' => 'array',
        'mini_items' => 'array',
        'guidelines' => 'array',
        'coverage_areas' => 'array',
        'faqs' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active locations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by custom order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get all delivery persons assigned to this location
     */
    public function deliveryPersons()
    {
        return $this->belongsToMany(User::class, 'location_user')
                    ->where('user_type', 'Delivery Person')
                    ->withTimestamps();
    }

    /**
     * Get all users assigned to this location (any type)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'location_user')
                    ->withTimestamps();
    }

    /**
     * Get location assignment logs
     */
    public function assignmentLogs()
    {
        return $this->hasMany(LocationUserLog::class);
    }

    /**
     * Get full address string
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->building_name,
            $this->area,
            $this->sector,
            $this->city
        ]);
        
        return implode(', ', $parts);
    }

    /**
     * Check if location has delivery persons assigned
     */
    public function hasDeliveryPersons()
    {
        return $this->deliveryPersons()->exists();
    }

    /**
     * Get count of assigned delivery persons
     */
    public function getDeliveryPersonsCountAttribute()
    {
        return $this->deliveryPersons()->count();
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($location) {
            if (empty($location->slug)) {
                $location->slug = Str::slug($location->name);
            }
            
            // Set default order if not provided
            if (is_null($location->order)) {
                $location->order = static::max('order') + 1;
            }
        });

        static::updating(function ($location) {
            if ($location->isDirty('name') && empty($location->slug)) {
                $location->slug = Str::slug($location->name);
            }
        });
    }
}
