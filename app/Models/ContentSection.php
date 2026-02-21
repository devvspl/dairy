<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentSection extends Model
{
    protected $fillable = [
        'section_key',
        'kicker',
        'title',
        'description',
        'points',
        'buttons',
        'image',
        'video_id',
        'gallery_images',
        'meta',
        'is_active',
    ];

    protected $casts = [
        'points' => 'array',
        'buttons' => 'array',
        'gallery_images' => 'array',
        'meta' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByKey($query, $key)
    {
        return $query->where('section_key', $key);
    }
}
