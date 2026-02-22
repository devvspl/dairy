<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    protected $fillable = [
        'kicker',
        'title',
        'description',
        'image',
        'button_text',
        'button_link',
        'mini_items',
        'badge_rating',
        'badge_text',
        'is_active',
        'order',
    ];

    protected $casts = [
        'mini_items' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
