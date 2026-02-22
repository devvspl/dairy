<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPage extends Model
{
    protected $fillable = [
        'section_key',
        'hero_title',
        'hero_description',
        'hero_image',
        'hero_phone',
        'hero_email',
        'phone_title',
        'phone_description',
        'phone_number',
        'email_title',
        'email_description',
        'email_address',
        'address_title',
        'address_description',
        'address_full',
        'map_title',
        'map_embed_url',
        'map_link',
        'faqs',
        'is_active',
    ];

    protected $casts = [
        'faqs' => 'array',
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
