<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilkPrice extends Model
{
    protected $fillable = ['milk_type', 'label', 'price_per_litre', 'is_active', 'order'];

    protected $casts = [
        'price_per_litre' => 'decimal:2',
        'is_active'       => 'boolean',
    ];

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeOrdered($query) { return $query->orderBy('order'); }

    public static function forType(string $type): ?self
    {
        return static::where('milk_type', $type)->where('is_active', true)->first();
    }
}
