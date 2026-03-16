@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-4 lg:space-y-6">

    <!-- Welcome -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">Welcome back, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-sm mt-1" style="color: var(--muted);">{{ now()->format('l, F j, Y') }} at {{ now()->format('g:i A') }}</p>
    </div>

    <!-- Row 1: Visitor + Content stats (6 cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">

        <!-- Total Visits -->
        <div class="bg-white rounded-xl shadow-sm p-4 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(47,74,30,0.1);">
                <svg class="w-5 h-5" style="color:var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <p class="text-xs font-medium mb-1" style="color:var(--muted);">Total Visits</p>
            <p class="text-xl font-bold" style="color:var(--text);">{{ number_format($totalVisits) }}</p>
            <p class="text-xs mt-1" style="color:var(--muted);"><span style="color:#16a34a;">{{ number_format($todayVisits) }}</span> today</p>
        </div>

        <!-- Unique Visitors -->
        <div class="bg-white rounded-xl shadow-sm p-4 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(47,74,30,0.1);">
                <svg class="w-5 h-5" style="color:var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <p class="text-xs font-medium mb-1" style="color:var(--muted);">Unique Visitors</p>
            <p class="text-xl font-bold" style="color:var(--text);">{{ number_format($uniqueVisitors) }}</p>
            <p class="text-xs mt-1" style="color:var(--muted);"><span style="color:#16a34a;">{{ number_format($todayUniqueVisitors) }}</span> today</p>
        </div>

        <!-- Users -->
        <div class="bg-white rounded-xl shadow-sm p-4 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(47,74,30,0.1);">
                <svg class="w-5 h-5" style="color:var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-xs font-medium mb-1" style="color:var(--muted);">Total Users</p>
            <p class="text-xl font-bold" style="color:var(--text);">{{ number_format($totalUsers) }}</p>
            <p class="text-xs mt-1" style="color:var(--muted);">registered</p>
        </div>

        <!-- Inquiries -->
        <div class="bg-white rounded-xl shadow-sm p-4 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(47,74,30,0.1);">
                <svg class="w-5 h-5" style="color:var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-xs font-medium mb-1" style="color:var(--muted);">Inquiries</p>
            <p class="text-xl font-bold" style="color:var(--text);">{{ number_format($totalInquiries) }}</p>
            <p class="text-xs mt-1">
                @if($newInquiries > 0)
                <span style="color:#dc2626;">{{ $newInquiries }} new</span> ·
                @endif
                <a href="{{ route('admin.contact-inquiries.index') }}" style="color:var(--green);">View →</a>
            </p>
        </div>

        <!-- Products -->
        <div class="bg-white rounded-xl shadow-sm p-4 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(47,74,30,0.1);">
                <svg class="w-5 h-5" style="color:var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <p class="text-xs font-medium mb-1" style="color:var(--muted);">Products</p>
            <p class="text-xl font-bold" style="color:var(--text);">{{ number_format($totalProducts) }}</p>
            <p class="text-xs mt-1" style="color:var(--muted);">{{ $totalBlogs }} blogs</p>
        </div>

        <!-- Today Unique -->
        <div class="bg-white rounded-xl shadow-sm p-4 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(59,130,246,0.1);">
                <svg class="w-5 h-5" style="color:#3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-xs font-medium mb-1" style="color:var(--muted);">Today Unique</p>
            <p class="text-xl font-bold" style="color:var(--text);">{{ number_format($todayUniqueVisitors) }}</p>
            <p class="text-xs mt-1" style="color:var(--muted);">visitors today</p>
        </div>
    </div>

    <!-- Row 2: Membership & Product Order stats (4 cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">

        <!-- Memberships -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background:rgba(34,197,94,0.1);">
                    <svg class="w-6 h-6" style="color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background:rgba(34,197,94,0.1);color:#16a34a;">{{ $activeSubscriptions }} active</span>
            </div>
            <p class="text-sm font-medium mb-1" style="color:var(--muted);">Memberships</p>
            <p class="text-2xl lg:text-3xl font-bold" style="color:var(--text);">{{ number_format($totalSubscriptions) }}</p>
            <p class="text-xs mt-2" style="color:var(--muted);">{{ $expiredSubscriptions }} expired · <a href="{{ route('admin.subscriptions.index') }}" style="color:var(--green);">View all →</a></p>
        </div>

        <!-- Membership Revenue -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background:rgba(47,74,30,0.1);">
                    <svg class="w-6 h-6" style="color:var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium mb-1" style="color:var(--muted);">Membership Revenue</p>
            <p class="text-2xl lg:text-3xl font-bold" style="color:var(--text);">₹{{ number_format($membershipRevenue, 0) }}</p>
            <p class="text-xs mt-2" style="color:var(--muted);">from paid subscriptions</p>
        </div>

        <!-- Product Orders -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background:rgba(59,130,246,0.1);">
                    <svg class="w-6 h-6" style="color:#3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                @if($pendingProductOrders > 0)
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background:rgba(245,158,11,0.1);color:#d97706;">{{ $pendingProductOrders }} pending</span>
                @endif
            </div>
            <p class="text-sm font-medium mb-1" style="color:var(--muted);">Product Orders</p>
            <p class="text-2xl lg:text-3xl font-bold" style="color:var(--text);">{{ number_format($totalProductOrders) }}</p>
            <p class="text-xs mt-2" style="color:var(--muted);">{{ $successProductOrders }} completed · <a href="{{ route('admin.product-orders.index') }}" style="color:var(--green);">View all →</a></p>
        </div>

        <!-- Product Revenue -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background:rgba(139,92,246,0.1);">
                    <svg class="w-6 h-6" style="color:#8b5cf6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium mb-1" style="color:var(--muted);">Product Revenue</p>
            <p class="text-2xl lg:text-3xl font-bold" style="color:var(--text);">₹{{ number_format($productOrderRevenue, 0) }}</p>
            <p class="text-xs mt-2" style="color:var(--muted);">from completed orders</p>
        </div>
    </div>

    <!-- Row 3: Recent Memberships & Recent Product Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">

        <!-- Recent Subscriptions -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold" style="color:var(--text);">Recent Memberships</h2>
                <a href="{{ route('admin.subscriptions.index') }}" class="text-xs font-semibold" style="color:var(--green);">View all →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentSubscriptions as $sub)
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm text-white" style="background:var(--green);">
                            {{ strtoupper(substr($sub->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold truncate" style="color:var(--text);">{{ $sub->user->name ?? '-' }}</p>
                            <p class="text-xs truncate" style="color:var(--muted);">{{ $sub->membershipPlan->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-3">
                        <p class="text-sm font-bold" style="color:var(--green);">₹{{ number_format($sub->amount_paid, 0) }}</p>
                        @php
                            $sc = ['active'=>'#16a34a','expired'=>'#dc2626','cancelled'=>'#6b7280','pending'=>'#d97706'];
                            $sb = ['active'=>'rgba(34,197,94,0.1)','expired'=>'rgba(239,68,68,0.1)','cancelled'=>'rgba(107,114,128,0.1)','pending'=>'rgba(245,158,11,0.1)'];
                        @endphp
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                              style="color:{{ $sc[$sub->status] ?? '#6b7280' }};background:{{ $sb[$sub->status] ?? 'rgba(107,114,128,0.1)' }};">
                            {{ ucfirst($sub->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-center py-4" style="color:var(--muted);">No subscriptions yet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Product Orders -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold" style="color:var(--text);">Recent Product Orders</h2>
                <a href="{{ route('admin.product-orders.index') }}" class="text-xs font-semibold" style="color:var(--green);">View all →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentProductOrders as $order)
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm text-white" style="background:#3b82f6;">
                            {{ strtoupper(substr($order->customer_name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold truncate" style="color:var(--text);">{{ $order->customer_name }}</p>
                            <p class="text-xs truncate" style="color:var(--muted);">{{ $order->order_id }} · {{ collect($order->items)->sum('quantity') }} item(s)</p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-3">
                        <p class="text-sm font-bold" style="color:#3b82f6;">₹{{ number_format($order->amount, 0) }}</p>
                        @php
                            $oc = ['success'=>'#16a34a','pending'=>'#d97706','failed'=>'#dc2626'];
                            $ob = ['success'=>'rgba(34,197,94,0.1)','pending'=>'rgba(245,158,11,0.1)','failed'=>'rgba(239,68,68,0.1)'];
                        @endphp
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                              style="color:{{ $oc[$order->status] ?? '#6b7280' }};background:{{ $ob[$order->status] ?? 'rgba(107,114,128,0.1)' }};">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-center py-4" style="color:var(--muted);">No product orders yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Last Row: 7-day Line Chart -->
    @php
        $chartDates = [];
        for ($i = 6; $i >= 0; $i--) { $chartDates[] = now()->subDays($i)->format('Y-m-d'); }

        $visitPoints  = [];
        $uniquePoints = [];
        foreach ($chartDates as $d) {
            $row = $last7Days->firstWhere('date', $d);
            $visitPoints[]  = $row ? (int)$row->visits : 0;
            $uniquePoints[] = $row ? (int)$row->unique_visitors : 0;
        }

        $maxVal = max(array_merge($visitPoints, $uniquePoints, [1]));

        // SVG viewport: 700 wide × 200 tall, with 40px padding on each side
        $svgW = 700; $svgH = 200; $padX = 40; $padY = 20;
        $plotW = $svgW - $padX * 2;
        $plotH = $svgH - $padY * 2;
        $n = count($chartDates); // 7

        $toX = fn($i) => $padX + ($i / ($n - 1)) * $plotW;
        $toY = fn($v) => $padY + $plotH - ($v / $maxVal) * $plotH;

        // Build polyline points strings
        $visitPts  = implode(' ', array_map(fn($i) => round($toX($i), 1) . ',' . round($toY($visitPoints[$i]), 1),  range(0, $n-1)));
        $uniquePts = implode(' ', array_map(fn($i) => round($toX($i), 1) . ',' . round($toY($uniquePoints[$i]), 1), range(0, $n-1)));

        // Area fill paths (close down to baseline)
        $visitArea  = 'M ' . implode(' L ', array_map(fn($i) => round($toX($i), 1) . ',' . round($toY($visitPoints[$i]), 1),  range(0, $n-1)))
                    . ' L ' . round($toX($n-1), 1) . ',' . ($padY + $plotH)
                    . ' L ' . $padX . ',' . ($padY + $plotH) . ' Z';
        $uniqueArea = 'M ' . implode(' L ', array_map(fn($i) => round($toX($i), 1) . ',' . round($toY($uniquePoints[$i]), 1), range(0, $n-1)))
                    . ' L ' . round($toX($n-1), 1) . ',' . ($padY + $plotH)
                    . ' L ' . $padX . ',' . ($padY + $plotH) . ' Z';
    @endphp

    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
            <h2 class="text-lg font-semibold mb-2 sm:mb-0" style="color:var(--text);">Last 7 Days Visits</h2>
            <div class="flex items-center gap-4 text-xs">
                <div class="flex items-center gap-1.5">
                    <div class="w-8 h-0.5 rounded" style="background:#2f4a1e;"></div>
                    <span style="color:var(--muted);">Total Visits</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-8 h-0.5 rounded" style="background:#3b82f6;"></div>
                    <span style="color:var(--muted);">Unique Visitors</span>
                </div>
            </div>
        </div>

        <div style="width:100%;overflow-x:auto;">
            <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}" preserveAspectRatio="none"
                 style="width:100%;height:220px;display:block;" id="visitLineChart">
                <defs>
                    <linearGradient id="visitGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#2f4a1e" stop-opacity="0.18"/>
                        <stop offset="100%" stop-color="#2f4a1e" stop-opacity="0"/>
                    </linearGradient>
                    <linearGradient id="uniqueGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.12"/>
                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                    </linearGradient>
                </defs>

                {{-- Horizontal grid lines --}}
                @for ($g = 0; $g <= 4; $g++)
                    @php $gy = $padY + ($g / 4) * $plotH; @endphp
                    <line x1="{{ $padX }}" y1="{{ round($gy, 1) }}" x2="{{ $svgW - $padX }}" y2="{{ round($gy, 1) }}"
                          stroke="#e7e7e7" stroke-width="1"/>
                    <text x="{{ $padX - 6 }}" y="{{ round($gy + 4, 1) }}" text-anchor="end"
                          font-size="11" fill="#9ca3af">{{ number_format($maxVal - ($g / 4) * $maxVal) }}</text>
                @endfor

                {{-- Area fills --}}
                <path d="{{ $visitArea }}"  fill="url(#visitGrad)"/>
                <path d="{{ $uniqueArea }}" fill="url(#uniqueGrad)"/>

                {{-- Lines --}}
                <polyline points="{{ $visitPts }}"  fill="none" stroke="#2f4a1e" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>
                <polyline points="{{ $uniquePts }}" fill="none" stroke="#3b82f6" stroke-width="2"   stroke-linejoin="round" stroke-linecap="round"/>

                {{-- Data points + tooltips --}}
                @foreach($chartDates as $i => $d)
                    @php
                        $cx = round($toX($i), 1);
                        $vy = round($toY($visitPoints[$i]), 1);
                        $uy = round($toY($uniquePoints[$i]), 1);
                        $label = \Carbon\Carbon::parse($d)->format('D');
                    @endphp

                    {{-- Visit dot --}}
                    <circle cx="{{ $cx }}" cy="{{ $vy }}" r="4" fill="#fff" stroke="#2f4a1e" stroke-width="2">
                        <title>{{ $label }}: {{ $visitPoints[$i] }} visits</title>
                    </circle>

                    {{-- Unique dot --}}
                    <circle cx="{{ $cx }}" cy="{{ $uy }}" r="3.5" fill="#fff" stroke="#3b82f6" stroke-width="2">
                        <title>{{ $label }}: {{ $uniquePoints[$i] }} unique</title>
                    </circle>

                    {{-- X-axis label --}}
                    <text x="{{ $cx }}" y="{{ $svgH - 4 }}" text-anchor="middle" font-size="11" fill="#9ca3af">{{ $label }}</text>
                @endforeach
            </svg>
        </div>
    </div>

</div>
@endsection
