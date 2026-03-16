@extends('layouts.app')

@section('title', 'Delivery Dashboard')
@section('page-title', 'Delivery Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">
                    Welcome, {{ auth()->user()->name }}! 🚚
                </h1>
                <p class="text-sm mt-1" style="color: var(--muted);">{{ now()->format('l, F j, Y') }}</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                    <i class="fa-solid fa-truck mr-1"></i> Delivery Person
                </span>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium" style="color: var(--muted);">Today's Date</p>
                <p class="text-2xl font-bold" style="color: var(--green);">{{ now()->format('d M') }}</p>
            </div>
        </div>
    </div>

    <!-- Today's Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $totalToday     = $locationStats->sum('total');
            $deliveredToday = $locationStats->sum('delivered');
            $pendingToday   = $locationStats->sum('pending');
            $quantityToday  = $locationStats->sum('quantity');
        @endphp
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-xs font-medium mb-1" style="color: var(--muted);">Total Today</p>
            <p class="text-3xl font-bold" style="color: var(--text);">{{ $totalToday }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-xs font-medium mb-1" style="color: var(--muted);">Delivered</p>
            <p class="text-3xl font-bold" style="color: var(--green);">{{ $deliveredToday }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-xs font-medium mb-1" style="color: var(--muted);">Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $pendingToday }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-xs font-medium mb-1" style="color: var(--muted);">Total Qty</p>
            <p class="text-3xl font-bold" style="color: var(--green);">{{ $quantityToday }} L</p>
        </div>
    </div>

    <!-- Assigned Locations with delivery counts -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-bold" style="color: var(--text);">
                    <i class="fa-solid fa-map-marker-alt mr-2" style="color: var(--green);"></i>My Assigned Locations
                </h2>
                <p class="text-sm mt-1" style="color: var(--muted);">Tap a location to view today's delivery list</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-bold" style="background-color: rgba(47,74,30,0.1); color: var(--green);">
                {{ $user->locations->count() }} {{ Str::plural('Location', $user->locations->count()) }}
            </span>
        </div>

        @if($locationStats->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($locationStats as $stat)
            @php $loc = $stat['location']; @endphp
            <a href="{{ route('delivery.location', $loc) }}"
               class="block p-4 rounded-xl border-2 hover:shadow-lg transition-all group"
               style="border-color: {{ $stat['pending'] > 0 ? '#f59e0b' : ($stat['delivered'] > 0 ? 'var(--green)' : 'var(--border)') }};">

                <!-- Location Header -->
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-11 h-11 rounded-lg flex items-center justify-center flex-shrink-0"
                         style="background-color: rgba(47,74,30,0.1);">
                        <i class="fa-solid fa-map-marker-alt" style="color: var(--green);"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-base truncate" style="color: var(--text);">{{ $loc->name }}</h3>
                        @if($loc->area)
                        <p class="text-xs truncate" style="color: var(--muted);">{{ $loc->area }}{{ $loc->city ? ', '.$loc->city : '' }}</p>
                        @endif
                        @if($loc->delivery_timing)
                        <span class="inline-block text-xs px-2 py-0.5 rounded mt-1" style="background-color: rgba(59,130,246,0.1); color: #3b82f6;">
                            <i class="fa-solid fa-clock mr-1"></i>{{ $loc->delivery_timing }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Today's mini stats -->
                <div class="grid grid-cols-3 gap-2 mb-3">
                    <div class="text-center p-2 rounded-lg" style="background-color: rgba(47,74,30,0.05);">
                        <p class="text-lg font-bold" style="color: var(--text);">{{ $stat['total'] }}</p>
                        <p class="text-[10px]" style="color: var(--muted);">Total</p>
                    </div>
                    <div class="text-center p-2 rounded-lg" style="background-color: #dcfce7;">
                        <p class="text-lg font-bold" style="color: #15803d;">{{ $stat['delivered'] }}</p>
                        <p class="text-[10px]" style="color: #15803d;">Done</p>
                    </div>
                    <div class="text-center p-2 rounded-lg" style="background-color: #fef9c3;">
                        <p class="text-lg font-bold text-yellow-700">{{ $stat['pending'] }}</p>
                        <p class="text-[10px] text-yellow-600">Pending</p>
                    </div>
                </div>

                <!-- Progress bar -->
                @if($stat['total'] > 0)
                @php $pct = round(($stat['delivered'] / $stat['total']) * 100); @endphp
                <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                    <div class="h-2 rounded-full transition-all" style="width: {{ $pct }}%; background-color: var(--green);"></div>
                </div>
                <p class="text-xs text-right" style="color: var(--muted);">{{ $pct }}% complete &bull; {{ $stat['quantity'] }} L</p>
                @else
                <p class="text-xs text-center py-1" style="color: var(--muted);">No deliveries scheduled today</p>
                @endif

                <!-- CTA -->
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-xs font-semibold" style="color: var(--green);">View Delivery List</span>
                    <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform" style="color: var(--green);"></i>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 rounded-lg" style="background-color: rgba(0,0,0,0.02);">
            <i class="fa-solid fa-map-marker-alt text-5xl mb-4" style="color: var(--muted);"></i>
            <p class="text-lg font-medium mb-1" style="color: var(--text);">No locations assigned yet</p>
            <p class="text-sm" style="color: var(--muted);">Contact your administrator to get locations assigned</p>
        </div>
        @endif
    </div>

</div>
@endsection
