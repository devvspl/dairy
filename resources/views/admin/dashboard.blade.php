@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-sm lg:text-base mt-1" style="color: var(--muted);">
                    {{ now()->format('l, F j, Y') }} at {{ now()->format('g:i A') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Visitor Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <!-- Total Visits -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Total Visits</p>
                <p class="text-2xl lg:text-3xl font-bold" style="color: var(--text);">{{ number_format($totalVisits) }}</p>
                <p class="text-xs mt-2" style="color: var(--muted);">
                    <span style="color: #16a34a;">{{ number_format($todayVisits) }}</span> today
                </p>
            </div>
        </div>

        <!-- Unique Visitors -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Unique Visitors</p>
                <p class="text-2xl lg:text-3xl font-bold" style="color: var(--text);">{{ number_format($uniqueVisitors) }}</p>
                <p class="text-xs mt-2" style="color: var(--muted);">
                    <span style="color: #16a34a;">{{ number_format($todayUniqueVisitors) }}</span> today
                </p>
            </div>
        </div>

        <!-- Contact Inquiries -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                @if($newInquiries > 0)
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background-color: rgba(239, 68, 68, 0.1); color: #dc2626;">{{ $newInquiries }} new</span>
                @endif
            </div>
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Inquiries</p>
                <p class="text-2xl lg:text-3xl font-bold" style="color: var(--text);">{{ number_format($totalInquiries) }}</p>
                <p class="text-xs mt-2" style="color: var(--muted);">
                    <a href="{{ route('admin.contact-inquiries.index') }}" style="color: var(--green);">View all →</a>
                </p>
            </div>
        </div>

        <!-- Content Stats -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Content</p>
                <p class="text-2xl lg:text-3xl font-bold" style="color: var(--text);">{{ number_format($totalProducts + $totalBlogs) }}</p>
                <p class="text-xs mt-2" style="color: var(--muted);">
                    {{ $totalProducts }} products, {{ $totalBlogs }} blogs
                </p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
        <!-- Last 7 Days Visits Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <h2 class="text-lg font-semibold mb-2 sm:mb-0" style="color: var(--text);">Last 7 Days Visits</h2>
                <div class="flex items-center space-x-4 text-xs">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: var(--green);"></div>
                        <span style="color: var(--muted);">Total Visits</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: rgba(47, 74, 30, 0.4);"></div>
                        <span style="color: var(--muted);">Unique Visitors</span>
                    </div>
                </div>
            </div>
            
            @php
                $maxVisits = $last7Days->max('visits') ?: 1;
                $dates = [];
                for ($i = 6; $i >= 0; $i--) {
                    $dates[] = now()->subDays($i)->format('Y-m-d');
                }
            @endphp
            
            <div class="h-48 lg:h-64 flex items-end justify-between space-x-2">
                @foreach($dates as $date)
                    @php
                        $dayData = $last7Days->firstWhere('date', $date);
                        $visits = $dayData ? $dayData->visits : 0;
                        $unique = $dayData ? $dayData->unique_visitors : 0;
                        $visitHeight = $maxVisits > 0 ? ($visits / $maxVisits * 100) : 0;
                        $uniqueHeight = $maxVisits > 0 ? ($unique / $maxVisits * 100) : 0;
                    @endphp
                    <div class="flex-1 flex flex-col items-center space-y-1">
                        <div class="w-full rounded-t-lg transition-all hover:opacity-80 relative group" 
                             style="background-color: var(--green); height: {{ $visitHeight }}%; min-height: 4px;">
                            <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                {{ $visits }} visits
                            </div>
                        </div>
                        <div class="w-full rounded-t-lg transition-all hover:opacity-80 relative group" 
                             style="background-color: rgba(47, 74, 30, 0.4); height: {{ $uniqueHeight }}%; min-height: 4px;">
                            <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                {{ $unique }} unique
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-4 text-xs" style="color: var(--muted);">
                @foreach($dates as $date)
                    <span>{{ \Carbon\Carbon::parse($date)->format('D') }}</span>
                @endforeach
            </div>
        </div>

        <!-- Device Statistics -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <h2 class="text-lg font-semibold mb-4" style="color: var(--text);">Device Statistics</h2>
            <div class="space-y-4">
                @php
                    $totalDevices = $deviceStats->sum('count');
                    $deviceColors = [
                        'Desktop' => '#2f4a1e',
                        'Mobile' => '#22c55e',
                        'Tablet' => '#3b82f6',
                        'Bot' => '#ef4444',
                    ];
                    $deviceIcons = [
                        'Desktop' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                        'Mobile' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                        'Tablet' => 'M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                        'Bot' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z',
                    ];
                @endphp
                
                @forelse($deviceStats as $device)
                    @php
                        $percentage = $totalDevices > 0 ? round(($device->count / $totalDevices) * 100, 1) : 0;
                        $color = $deviceColors[$device->device_type] ?? '#6b7280';
                        $icon = $deviceIcons[$device->device_type] ?? $deviceIcons['Desktop'];
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" style="color: {{ $color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                                </svg>
                                <span class="text-sm font-medium" style="color: var(--text);">{{ $device->device_type }}</span>
                            </div>
                            <span class="text-sm font-bold" style="color: var(--text);">{{ $percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all" style="width: {{ $percentage }}%; background-color: {{ $color }};"></div>
                        </div>
                        <p class="text-xs mt-1" style="color: var(--muted);">{{ number_format($device->count) }} visits</p>
                    </div>
                @empty
                    <p class="text-sm text-center py-4" style="color: var(--muted);">No device data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Most Visited Pages & Browser Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
        <!-- Most Visited Pages -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <h2 class="text-lg font-semibold mb-4" style="color: var(--text);">Most Visited Pages</h2>
            <div class="space-y-3">
                @forelse($mostVisitedPages as $index => $page)
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                                <span class="text-sm font-bold" style="color: var(--green);">#{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate" style="color: var(--text);" title="{{ $page->url }}">
                                    {{ Str::limit(parse_url($page->url, PHP_URL_PATH) ?: '/', 40) }}
                                </p>
                                <p class="text-xs" style="color: var(--muted);">{{ number_format($page->visits) }} visits</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-center py-4" style="color: var(--muted);">No page visit data available</p>
                @endforelse
            </div>
        </div>

        <!-- Browser Statistics -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <h2 class="text-lg font-semibold mb-4" style="color: var(--text);">Browser Statistics</h2>
            <div class="space-y-3">
                @php
                    $totalBrowsers = $browserStats->sum('count');
                    $browserColors = ['#2f4a1e', '#22c55e', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316', '#6366f1'];
                @endphp
                
                @forelse($browserStats as $index => $browser)
                    @php
                        $percentage = $totalBrowsers > 0 ? round(($browser->count / $totalBrowsers) * 100, 1) : 0;
                        $color = $browserColors[$index % count($browserColors)];
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-3 flex-1">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $color }}20;">
                                <svg class="w-4 h-4" style="color: {{ $color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium" style="color: var(--text);">{{ $browser->browser ?: 'Unknown' }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full transition-all" style="width: {{ $percentage }}%; background-color: {{ $color }};"></div>
                                    </div>
                                    <span class="text-xs font-medium" style="color: var(--muted);">{{ $percentage }}%</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-sm font-bold ml-3" style="color: var(--text);">{{ number_format($browser->count) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-center py-4" style="color: var(--muted);">No browser data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
