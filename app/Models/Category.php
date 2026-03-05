<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'icon_type',
        'svg_path',
        'price_text',
        'bg_color',
        'link',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->title);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('title')) {
                $category->slug = static::generateUniqueSlug($category->title, $category->id);
            }
        });
    }

    protected static function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->when($ignoreId, function ($query, $ignoreId) {
            return $query->where('id', '!=', $ignoreId);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
