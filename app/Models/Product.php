<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'price',
        'mrp',
        'discount_percent',
        'badge',
        'badge_color',
        'meta',
        'short_description',
        'description',
        'category',
        'category_id',
        'type_id',
        'rating',
        'reviews_count',
        'variants',
        'image',
        'images',
        'pack_sizes',
        'delivery_slots',
        'stock_status',
        'stock_quantity',
        'shelf_life',
        'storage_temp',
        'best_for',
        'specifications',
        'nutrition_info',
        'storage_instructions',
        'features',
        'order',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'rating' => 'decimal:1',
        'variants' => 'array',
        'images' => 'array',
        'pack_sizes' => 'array',
        'delivery_slots' => 'array',
        'specifications' => 'array',
        'nutrition_info' => 'array',
        'storage_instructions' => 'array',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true)->orderBy('order');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'available');
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->mrp && $this->discount_percent > 0) {
            return $this->mrp - ($this->mrp * $this->discount_percent / 100);
        }
        return $this->price;
    }

    public function getMainImageAttribute()
    {
        if ($this->images && count($this->images) > 0) {
            return $this->images[0];
        }
        return $this->image;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });
    }

    protected static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = 1;
        $originalSlug = $slug;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
