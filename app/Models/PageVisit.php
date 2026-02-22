<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PageVisit extends Model
{
    protected $fillable = [
        'url',
        'ip_address',
        'user_agent',
        'referer',
        'device_type',
        'browser',
        'platform',
        'user_id',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get total visits
    public static function getTotalVisits()
    {
        return self::count();
    }

    // Get unique visitors (by IP)
    public static function getUniqueVisitors()
    {
        return self::distinct('ip_address')->count('ip_address');
    }

    // Get today's visits
    public static function getTodayVisits()
    {
        return self::whereDate('visited_at', today())->count();
    }

    // Get today's unique visitors
    public static function getTodayUniqueVisitors()
    {
        return self::whereDate('visited_at', today())
            ->distinct('ip_address')
            ->count('ip_address');
    }

    // Get visits by date range
    public static function getVisitsByDateRange($startDate, $endDate)
    {
        return self::whereBetween('visited_at', [$startDate, $endDate])
            ->selectRaw('DATE(visited_at) as date, COUNT(*) as visits, COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
    }

    // Get most visited pages
    public static function getMostVisitedPages($limit = 10)
    {
        return self::select('url', DB::raw('COUNT(*) as visits'))
            ->groupBy('url')
            ->orderBy('visits', 'desc')
            ->limit($limit)
            ->get();
    }

    // Get device statistics
    public static function getDeviceStats()
    {
        return self::select('device_type', DB::raw('COUNT(*) as count'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->get();
    }

    // Get browser statistics
    public static function getBrowserStats()
    {
        return self::select('browser', DB::raw('COUNT(*) as count'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }
}
