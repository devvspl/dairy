<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $fillable = [
        'section_key',
        'hero_title',
        'hero_description',
        'hero_image',
        'hero_badges',
        'hero_button_1_text',
        'hero_button_1_link',
        'hero_button_2_text',
        'hero_button_2_link',
        'overview_title',
        'overview_description',
        'overview_image',
        'overview_badge_rating',
        'overview_badge_text',
        'overview_checks',
        'overview_button_text',
        'overview_button_link',
        'usps',
        'counters',
        'why_items',
        'why_promise_title',
        'why_promise_description',
        'why_promise_button_text',
        'why_promise_button_link',
        'team_members',
        'faqs',
        'contact_form_title',
        'contact_form_description',
        'is_active',
    ];

    protected $casts = [
        'hero_badges' => 'array',
        'overview_checks' => 'array',
        'usps' => 'array',
        'counters' => 'array',
        'why_items' => 'array',
        'team_members' => 'array',
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
