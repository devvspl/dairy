<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usp extends Model
{
    protected $fillable = [
        'title',
        'description',
        'svg_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
