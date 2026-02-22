<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageVisit;
use App\Models\ContactInquiry;
use App\Models\Product;
use App\Models\Blog;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function admin()
    {
        // Visitor Statistics
        $totalVisits = PageVisit::getTotalVisits();
        $uniqueVisitors = PageVisit::getUniqueVisitors();
        $todayVisits = PageVisit::getTodayVisits();
        $todayUniqueVisitors = PageVisit::getTodayUniqueVisitors();

        // Get last 7 days visits
        $last7Days = PageVisit::getVisitsByDateRange(
            now()->subDays(6)->startOfDay(),
            now()->endOfDay()
        );

        // Most visited pages
        $mostVisitedPages = PageVisit::getMostVisitedPages(5);

        // Device statistics
        $deviceStats = PageVisit::getDeviceStats();

        // Browser statistics
        $browserStats = PageVisit::getBrowserStats();

        // Other statistics
        $totalInquiries = ContactInquiry::count();
        $newInquiries = ContactInquiry::where('status', 'new')->count();
        $totalProducts = Product::count();
        $totalBlogs = Blog::count();
        $totalUsers = User::count();

        return view('admin.dashboard', compact(
            'totalVisits',
            'uniqueVisitors',
            'todayVisits',
            'todayUniqueVisitors',
            'last7Days',
            'mostVisitedPages',
            'deviceStats',
            'browserStats',
            'totalInquiries',
            'newInquiries',
            'totalProducts',
            'totalBlogs',
            'totalUsers'
        ));
    }
}

