@extends('layouts.app')

@section('title', 'View Membership Plan')
@section('page-title', 'Membership Plan Details')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color: var(--text);">{{ $plan->name }}</h2>
            @if($plan->is_featured)
            <span class="inline-block px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 mt-2">Featured Plan</span>
            @endif
        </div>
        
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <a href="{{ route('admin.membership-plans.edit', $plan) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.membership-plans.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Plan Name</p>
                    <p class="text-base font-semibold" style="color: var(--text);">{{ $plan->name }}</p>
                </div>
            </div>
        </div>

        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Price</p>
                    <p class="text-base font-semibold" style="color: var(--text);">₹{{ number_format($plan->price, 2) }} / {{ $plan->duration }}</p>
                </div>
            </div>
        </div>

        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Order</p>
                    <p class="text-base font-semibold" style="color: var(--text);">{{ $plan->order }}</p>
                </div>
            </div>
        </div>

        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Status</p>
                    <span class="px-2 py-1 text-xs rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        @if($plan->slug)
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-2" style="color: var(--muted);">Slug</p>
            <p class="text-sm font-mono" style="color: var(--text);">{{ $plan->slug }}</p>
        </div>
        @endif

        @if($plan->badge)
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-2" style="color: var(--muted);">Badge</p>
            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $plan->badge }}</span>
        </div>
        @endif

        @if($plan->icon)
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-2" style="color: var(--muted);">Icon</p>
            <div class="flex items-center space-x-2">
                <i class="fas {{ $plan->icon }} text-2xl" style="color: var(--green);"></i>
                <p class="text-sm font-mono" style="color: var(--text);">{{ $plan->icon }}</p>
            </div>
        </div>
        @endif

        @if($plan->description)
        <div class="md:col-span-2 p-4 rounded-lg border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-2" style="color: var(--muted);">Description</p>
            <p class="text-base" style="color: var(--text);">{{ $plan->description }}</p>
        </div>
        @endif

        @if($plan->features && count($plan->features) > 0)
        <div class="md:col-span-2 p-4 rounded-lg border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-3" style="color: var(--muted);">Features</p>
            <ul class="space-y-2">
                @foreach($plan->features as $feature)
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span style="color: var(--text);">{{ $feature }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($plan->day_wise_schedule && count($plan->day_wise_schedule) > 0)
        <div class="md:col-span-2 p-4 rounded-lg border" style="border-color: var(--border); background-color: #f9fdf7;">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm font-bold" style="color: var(--text);">Day-Wise Delivery Schedule</p>
                </div>
                <div class="flex items-center gap-4 text-xs" style="color: var(--muted);">
                    <span><strong>{{ $plan->getDeliveryDaysCount() }}</strong> delivery days</span>
                    <span><strong>{{ $plan->getTotalWeeklyQuantity() }}</strong> L/week</span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                @php
                    $days = [
                        'Mon' => 'Monday',
                        'Tue' => 'Tuesday',
                        'Wed' => 'Wednesday',
                        'Thu' => 'Thursday',
                        'Fri' => 'Friday',
                        'Sat' => 'Saturday',
                        'Sun' => 'Sunday'
                    ];
                @endphp

                @foreach($days as $key => $label)
                    @php
                        $daySchedule = $plan->day_wise_schedule[$key] ?? null;
                        $hasDelivery = $daySchedule && isset($daySchedule['delivery']) && $daySchedule['delivery'];
                        $quantity = $daySchedule['qty'] ?? 0;
                    @endphp
                    
                    <div class="p-3 rounded-lg border {{ $hasDelivery ? 'bg-white' : 'bg-gray-50' }}" style="border-color: {{ $hasDelivery ? 'var(--green)' : 'var(--border)' }};">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold" style="color: var(--text);">{{ $key }}</span>
                            @if($hasDelivery)
                                <svg class="w-4 h-4" style="color: var(--green);" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="text-center">
                            @if($hasDelivery)
                                <p class="text-lg font-bold" style="color: var(--green);">{{ $quantity }}</p>
                                <p class="text-xs" style="color: var(--muted);">liters</p>
                            @else
                                <p class="text-sm text-gray-400">No delivery</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Weekly Summary Card -->
            <div class="mt-4 p-4 rounded-lg" style="background-color: rgba(47, 74, 30, 0.1); border: 1px solid rgba(47, 74, 30, 0.2);">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <p class="text-xs font-medium mb-1" style="color: var(--muted);">Total Delivery Days</p>
                        <p class="text-2xl font-bold" style="color: var(--green);">{{ $plan->getDeliveryDaysCount() }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium mb-1" style="color: var(--muted);">Weekly Quantity</p>
                        <p class="text-2xl font-bold" style="color: var(--green);">{{ $plan->getTotalWeeklyQuantity() }} L</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium mb-1" style="color: var(--muted);">Avg Per Day</p>
                        <p class="text-2xl font-bold" style="color: var(--green);">
                            {{ $plan->getDeliveryDaysCount() > 0 ? number_format($plan->getTotalWeeklyQuantity() / $plan->getDeliveryDaysCount(), 1) : 0 }} L
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium mb-1" style="color: var(--muted);">Monthly Est.</p>
                        <p class="text-2xl font-bold" style="color: var(--green);">{{ number_format($plan->getTotalWeeklyQuantity() * 4, 0) }} L</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="mt-6 pt-6 border-t grid grid-cols-1 md:grid-cols-2 gap-4" style="border-color: var(--border);">
        <div class="p-4 rounded-lg" style="background-color: rgba(47, 74, 30, 0.05);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Created At</p>
            <p class="text-sm" style="color: var(--text);">{{ $plan->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <div class="p-4 rounded-lg" style="background-color: rgba(47, 74, 30, 0.05);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Last Updated</p>
            <p class="text-sm" style="color: var(--text);">{{ $plan->updated_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>

    <div class="mt-6 pt-6 border-t" style="border-color: var(--border);">
        <form method="POST" action="{{ route('admin.membership-plans.destroy', $plan) }}" onsubmit="return confirm('Are you sure you want to delete this membership plan?');">
            @csrf @method('DELETE')
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors">Delete Plan</button>
        </form>
    </div>
</div>
@endsection
