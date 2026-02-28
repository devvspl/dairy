<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $fillable = [
        'page_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots',
    ];

    /**
     * Get SEO meta by page URL
     */
    public static function getByPageUrl($pageUrl)
    {
        return static::where('page_url', $pageUrl)->first();
    }

    /**
     * Get or create SEO meta for a page
     */
    public static function getOrCreateForPage($pageUrl, $defaultTitle = null, $defaultDescription = null)
    {
        return static::firstOrCreate(
            ['page_url' => $pageUrl],
            [
                'meta_title' => $defaultTitle ?? 'Page Title',
                'meta_description' => $defaultDescription ?? 'Page description',
                'robots' => 'index,follow',
            ]
        );
    }
}
