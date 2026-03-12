@extends('layouts.app')

@section('title', 'Delivery Dashboard')
@section('page-title', 'Delivery Dashboard')

@section('content')
    <div class="space-y-4 lg:space-y-6">
        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 border-l-4 rounded-lg p-4 flex items-start gap-3" style="border-color: var(--green);">
                <i class="fa-solid fa-check-circle text-xl" style="color: var(--green);"></i>
                <div class="flex-1">
                    <p class="font-semibold" style="color: var(--green);">Success!</p>
                    <p class="text-sm" style="color: var(--text);">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                <div>
                    <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">
                        Welcome back, {{ auth()->user()->name }}! 🚚
                    </h1>
                    <p class="text-sm lg:text-base mt-1" style="color: var(--muted);">
                        {{ now()->format('l, F j, Y') }}
                    </p>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                            </path>
                        </svg>
                        Delivery Person
                    </span>
                </div>
            </div>
        </div>

        <!-- Assigned Locations -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold flex items-center" style="color: var(--text);">
                        <svg class="w-6 h-6 mr-2" style="color: var(--green);" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        My Assigned Locations
                    </h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Locations you're responsible for delivering to</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-bold"
                    style="background-color: rgba(47, 74, 30, 0.1); color: var(--green);">
                    {{ auth()->user()->locations->count() }}
                    {{ Str::plural('Location', auth()->user()->locations->count()) }}
                </span>
            </div>

            @if (auth()->user()->locations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach (auth()->user()->locations as $location)
                        <div class="p-4 rounded-lg border hover:shadow-md transition-all"
                            style="border-color: var(--border);">
                            <div class="flex items-start space-x-3">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0"
                                    style="background-color: rgba(47, 74, 30, 0.1);">
                                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-base mb-1 truncate" style="color: var(--text);">
                                        {{ $location->name }}</h3>
                                    <p class="text-sm truncate" style="color: var(--muted);">
                                        <i class="fa-solid fa-map-marker-alt mr-1"></i>{{ $location->area }}
                                    </p>
                                    <p class="text-sm truncate" style="color: var(--muted);">
                                        <i class="fa-solid fa-city mr-1"></i>{{ $location->city }}
                                    </p>
                                    @if ($location->delivery_timing)
                                        <p class="text-xs mt-2 px-2 py-1 rounded inline-block"
                                            style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                            <i class="fa-solid fa-clock mr-1"></i>{{ $location->delivery_timing }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 rounded-lg" style="background-color: rgba(0,0,0,0.02);">
                    <svg class="w-16 h-16 mx-auto mb-4" style="color: var(--muted);" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="text-lg font-medium mb-1" style="color: var(--text);">No locations assigned yet</p>
                    <p class="text-sm" style="color: var(--muted);">Contact your administrator to get locations assigned to
                        you</p>
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-6 border" style="border-color: var(--border);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Total Locations</p>
                        <p class="text-3xl font-bold" style="color: var(--green);">
                            {{ auth()->user()->locations->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                        style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border" style="border-color: var(--border);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Active Since</p>
                        <p class="text-2xl font-bold" style="color: var(--green);">
                            {{ auth()->user()->created_at->format('M Y') }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                        style="background-color: rgba(139, 92, 246, 0.1);">
                        <svg class="w-6 h-6" style="color: #8b5cf6;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border" style="border-color: var(--border);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Status</p>
                        <p class="text-2xl font-bold text-green-600">Active</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
