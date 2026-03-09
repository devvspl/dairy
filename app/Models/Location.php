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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($location) {
            if (empty($location->slug)) {
                $location->slug = Str::slug($location->name);
            }
        });

        static::updating(function ($location) {
            if ($location->isDirty('name') && empty($location->slug)) {
                $location->slug = Str::slug($location->name);
            }
        });
    }
}
