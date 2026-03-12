@extends('layouts.app')

@section('title', 'Member Dashboard')
@section('page-title', 'Member Dashboard')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Success Message -->
    @if(session('success'))
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

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-50 border-l-4 rounded-lg p-4 flex items-start gap-3" style="border-color: #dc2626;">
        <i class="fa-solid fa-exclamation-circle text-xl" style="color: #dc2626;"></i>
        <div class="flex-1">
            <p class="font-semibold" style="color: #dc2626;">Error!</p>
            <p class="text-sm" style="color: var(--text);">{{ session('error') }}</p>
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
                    Welcome back, {{ auth()->user()->name }}! 🥛
                </h1>
                <p class="text-sm lg:text-base mt-1" style="color: var(--muted);">
                    {{ now()->format('l, F j, Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Referrals Card -->
        <a href="{{ route('member.referrals.index') }}" class="bg-white rounded-xl shadow-sm p-6 border hover:shadow-lg transition-all" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <svg class="w-5 h-5" style="color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold mb-1" style="color: var(--text);">My Referrals</h3>
            <p class="text-sm" style="color: var(--muted);">Share your code and earn rewards</p>
        </a>

        <!-- Loyalty Points Card -->
        <a href="{{ route('member.loyalty-points.index') }}" class="bg-white rounded-xl shadow-sm p-6 border hover:shadow-lg transition-all" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(139, 92, 246, 0.1);">
                    <svg class="w-6 h-6" style="color: #8b5cf6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <svg class="w-5 h-5" style="color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold mb-1" style="color: var(--text);">Loyalty Points</h3>
            <p class="text-sm" style="color: var(--muted);">View and redeem your points</p>
        </a>

        <!-- Payment History Card -->
        <a href="{{ route('payment.history') }}" class="bg-white rounded-xl shadow-sm p-6 border hover:shadow-lg transition-all" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(234, 179, 8, 0.1);">
                    <svg class="w-6 h-6" style="color: #eab308;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <svg class="w-5 h-5" style="color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold mb-1" style="color: var(--text);">Payment History</h3>
            <p class="text-sm" style="color: var(--muted);">View all your transactions</p>
        </a>

        <!-- Support Card -->
        <a href="{{ route('member.support-tickets.index') }}" class="bg-white rounded-xl shadow-sm p-6 border hover:shadow-lg transition-all" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(59, 130, 246, 0.1);">
                    <svg class="w-6 h-6" style="color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <svg class="w-5 h-5" style="color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold mb-1" style="color: var(--text);">Support</h3>
            <p class="text-sm" style="color: var(--muted);">Get help with your deliveries</p>
        </a>
    </div>

    @php
        // Get user's active subscription
        $activeSubscription = auth()->user()->activeSubscription()->first();
        $activePlan = $activeSubscription ? $activeSubscription->membershipPlan : null;
        $todayDelivery = true; // Check if today has delivery
        $currentDay = now()->format('D'); // Mon, Tue, Wed, etc.
    @endphp

    <!-- Active Membership Plan -->
    @if($activePlan && $activeSubscription)
    <div class="bg-gradient-to-br from-green-50 to-white rounded-xl shadow-sm p-4 lg:p-6 border-2" style="border-color: var(--green);">
        <div class="flex items-start justify-between mb-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <h2 class="text-lg font-bold" style="color: var(--text);">Your Active Plan</h2>
                    @if($activePlan->badge)
                    <span class="px-2 py-1 text-xs rounded-full font-bold" style="background-color: #f1cc24; color: #1f2a1a;">
                        {{ $activePlan->badge }}
                    </span>
                    @endif
                    <span class="px-2 py-1 text-xs rounded-full font-bold" style="background-color: rgba(47, 74, 30, 0.1); color: var(--green);">
                        {{ ucfirst($activeSubscription->status) }}
                    </span>
                </div>
                <p class="text-sm" style="color: var(--muted);">{{ $activePlan->description }}</p>
                <p class="text-xs mt-1" style="color: var(--muted);">
                    Valid until: {{ $activeSubscription->end_date->format('M d, Y') }} 
                    ({{ $activeSubscription->daysRemaining() }} days remaining)
                </p>
                @if($activeSubscription->location)
                <p class="text-xs mt-1 flex items-center" style="color: var(--green);">
                    <i class="fa-solid fa-map-marker-alt mr-1"></i>
                    <span class="font-semibold">{{ $activeSubscription->location->name }}</span>
                    @if($activeSubscription->location->area || $activeSubscription->location->city)
                        <span class="ml-1" style="color: var(--muted);">
                            ({{ collect([$activeSubscription->location->area, $activeSubscription->location->city])->filter()->implode(', ') }})
                        </span>
                    @endif
                </p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold" style="color: var(--green);">₹{{ number_format($activePlan->price, 0) }}</p>
                <p class="text-xs" style="color: var(--muted);">per {{ $activePlan->duration }}</p>
            </div>
        </div>

        <!-- Plan Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
            <div class="bg-white rounded-lg p-3 border" style="border-color: var(--border);">
                <p class="text-xs font-medium mb-1" style="color: var(--muted);">Deliveries Done</p>
                <p class="text-xl font-bold" style="color: var(--green);">{{ $activeSubscription->deliveredCount() }}</p>
            </div>
            <div class="bg-white rounded-lg p-3 border" style="border-color: var(--border);">
                <p class="text-xs font-medium mb-1" style="color: var(--muted);">Total Milk</p>
                <p class="text-xl font-bold" style="color: var(--green);">{{ $activeSubscription->totalQuantityDelivered() }} L</p>
            </div>
            <div class="bg-white rounded-lg p-3 border" style="border-color: var(--border);">
                <p class="text-xs font-medium mb-1" style="color: var(--muted);">Today's Qty</p>
                <p class="text-xl font-bold" style="color: var(--green);">{{ $activePlan->getDayQuantity($currentDay) }} L</p>
            </div>
            <div class="bg-white rounded-lg p-3 border" style="border-color: var(--border);">
                <p class="text-xs font-medium mb-1" style="color: var(--muted);">Pending</p>
                <p class="text-xl font-bold text-yellow-600">{{ $activeSubscription->pendingCount() }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Available Plans to Buy -->
    @if(!$activeSubscription)
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold" style="color: var(--text);">🛒 Available Membership Plans</h2>
                <p class="text-sm" style="color: var(--muted);">Choose a plan that fits your daily milk needs</p>
            </div>
            <a href="{{ route('membership') }}" class="text-sm font-semibold hover:underline" style="color: var(--green);">
                View All Plans <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>

        @php
            $availablePlans = \App\Models\MembershipPlan::active()->orderBy('order')->take(3)->get();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($availablePlans as $plan)
            <div class="border rounded-xl p-4 hover:shadow-lg transition-all relative" style="border-color: {{ $plan->is_featured ? 'var(--green)' : 'var(--border)' }}; background: {{ $plan->is_featured ? 'linear-gradient(135deg, #f9fdf7 0%, #ffffff 100%)' : '#fff' }};">
                
                @if($plan->badge)
                <div class="absolute -top-3 left-4">
                    <span class="px-3 py-1 text-xs rounded-full font-bold shadow-md" style="background-color: #f1cc24; color: #1f2a1a;">
                        {{ $plan->badge }}
                    </span>
                </div>
                @endif

                <div class="text-center mb-4 mt-2">
                    @if($plan->icon)
                    <i class="fas {{ $plan->icon }} text-3xl mb-2" style="color: var(--green);"></i>
                    @endif
                    <h3 class="font-bold text-lg mb-1" style="color: var(--text);">{{ $plan->name }}</h3>
                    <p class="text-xs mb-3" style="color: var(--muted);">{{ Str::limit($plan->description, 60) }}</p>
                    
                    <div class="mb-3">
                        <span class="text-3xl font-bold" style="color: var(--green);">₹{{ number_format($plan->price, 0) }}</span>
                        <span class="text-sm" style="color: var(--muted);">/{{ $plan->duration }}</span>
                    </div>
                </div>

                <!-- Plan Highlights -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center justify-between text-xs p-2 rounded" style="background-color: rgba(47, 74, 30, 0.05);">
                        <span style="color: var(--muted);">Delivery Days</span>
                        <span class="font-bold" style="color: var(--green);">{{ $plan->getDeliveryDaysCount() }}/week</span>
                    </div>
                    <div class="flex items-center justify-between text-xs p-2 rounded" style="background-color: rgba(47, 74, 30, 0.05);">
                        <span style="color: var(--muted);">Weekly Milk</span>
                        <span class="font-bold" style="color: var(--green);">{{ $plan->getTotalWeeklyQuantity() }} L</span>
                    </div>
                </div>

                @if($plan->features && count($plan->features) > 0)
                <ul class="space-y-1 mb-4">
                    @foreach(array_slice($plan->features, 0, 3) as $feature)
                    <li class="flex items-start text-xs">
                        <i class="fa-solid fa-check text-xs mt-0.5 mr-2" style="color: var(--green);"></i>
                        <span style="color: var(--text);">{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
                @endif

                <!-- Buy Button -->
                <button onclick="buyPlan({{ $plan->id }}, '{{ $plan->name }}', {{ $plan->price }})" 
                        class="w-full py-2.5 rounded-lg font-bold text-sm transition-all hover:shadow-md hover:-translate-y-0.5" 
                        style="background-color: var(--green); color: #fff;">
                    <i class="fa-solid fa-shopping-cart mr-2"></i>Buy Now
                </button>
            </div>
            @empty
            <div class="col-span-3 text-center py-8">
                <p style="color: var(--muted);">No plans available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
    @endif

    <!-- This Week's Delivery Schedule -->
    @if($activePlan && $activePlan->day_wise_schedule)
    <!-- Monthly Delivery Calendar -->
    <div class="bg-white rounded-xl shadow-sm p-6 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold" style="color: var(--text);">
                    <i class="fa-solid fa-calendar-days mr-2" style="color: var(--green);"></i>Delivery Calendar
                </h2>
                <p class="text-sm mt-1" style="color: var(--muted);">Track your daily milk deliveries</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold" style="color: var(--green);">{{ now()->format('F') }}</p>
                <p class="text-sm" style="color: var(--muted);">{{ now()->format('Y') }}</p>
            </div>
        </div>

        @php
            $today = now();
            $startOfMonth = $today->copy()->startOfMonth();
            $endOfMonth = $today->copy()->endOfMonth();
            $startDay = $startOfMonth->copy()->startOfWeek();
            $endDay = $endOfMonth->copy()->endOfWeek();
            
            $daysInCalendar = [];
            $currentDate = $startDay->copy();
            
            while ($currentDate <= $endDay) {
                $daysInCalendar[] = $currentDate->copy();
                $currentDate->addDay();
            }
            
            // Get delivery logs for this month
            $monthDeliveries = $activeSubscription->deliveryLogs()
                ->whereBetween('delivery_date', [$startOfMonth, $endOfMonth])
                ->get()
                ->keyBy(function($item) {
                    return $item->delivery_date->format('Y-m-d');
                });
        @endphp

        <!-- Calendar Header -->
        <div class="grid grid-cols-7 gap-3 mb-3">
            @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
            <div class="text-center py-3 rounded-lg font-bold text-sm" style="background-color: rgba(47, 74, 30, 0.05); color: var(--green);">
                <span class="hidden md:inline">{{ $day }}</span>
                <span class="md:hidden">{{ substr($day, 0, 3) }}</span>
            </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 gap-2 md:gap-3">
            @foreach($daysInCalendar as $date)
                @php
                    $isCurrentMonth = $date->month === $today->month;
                    $isToday = $date->isToday();
                    $dayKey = $date->format('D');
                    $hasScheduledDelivery = $activePlan->hasDeliveryOnDay($dayKey);
                    $dateKey = $date->format('Y-m-d');
                    $deliveryLog = $monthDeliveries->get($dateKey);
                    $isPast = $date->isPast() && !$isToday;
                @endphp
                
                <div class="relative min-h-[90px] md:min-h-[110px] p-2 md:p-3 rounded-lg md:rounded-xl border-2 transition-all hover:shadow-lg {{ $isToday ? 'scale-105 shadow-lg' : '' }}" 
                     style="
                        @if($isToday)
                            background: linear-gradient(135deg, var(--green) 0%, #3d6b2e 100%);
                            border-color: var(--green);
                            color: #fff;
                        @elseif($deliveryLog)
                            @if($deliveryLog->status === 'delivered')
                                background: #dcfce7;
                                border-color: #16a34a;
                            @elseif($deliveryLog->status === 'pending')
                                background: #fef3c7;
                                border-color: #d97706;
                            @else
                                background: #f3f4f6;
                                border-color: #d1d5db;
                            @endif
                        @elseif($hasScheduledDelivery && $isCurrentMonth && !$isPast)
                            background: #f0fdf4;
                            border-color: rgba(47, 74, 30, 0.5);
                            border-style: dashed;
                        @else
                            background: {{ $isCurrentMonth ? '#fff' : '#fafafa' }};
                            border-color: #e5e7eb;
                        @endif
                     ">
                    
                    <!-- Date Number -->
                    <div class="flex items-start justify-between mb-1">
                        <span class="text-base md:text-lg font-bold" style="color: {{ $isToday ? '#fff' : ($isCurrentMonth ? '#1f2937' : '#9ca3af') }};">
                            {{ $date->day }}
                        </span>
                        
                        @if($isToday)
                        <div class="w-5 h-5 md:w-6 md:h-6 rounded-full flex items-center justify-center" style="background-color: #f1cc24;">
                            <i class="fa-solid fa-star text-xs" style="color: #1f2a1a;"></i>
                        </div>
                        @endif
                    </div>
                    
                    @if($isCurrentMonth)
                        <!-- Delivery Status -->
                        @if($deliveryLog)
                            <div class="flex flex-col items-center justify-center py-1">
                                @if($deliveryLog->status === 'delivered')
                                    <div class="w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center mb-1" style="background-color: #16a34a;">
                                        <i class="fa-solid fa-check text-xs md:text-sm text-white"></i>
                                    </div>
                                    <p class="text-sm md:text-base font-bold leading-tight" style="color: #15803d;">{{ $deliveryLog->quantity_delivered }}L</p>
                                    <p class="text-[9px] md:text-[10px] font-bold leading-tight" style="color: #15803d;">Delivered</p>
                                @elseif($deliveryLog->status === 'pending')
                                    <div class="w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center mb-1" style="background-color: #d97706;">
                                        <i class="fa-solid fa-clock text-xs md:text-sm text-white"></i>
                                    </div>
                                    <p class="text-sm md:text-base font-bold leading-tight" style="color: #78350f;">{{ $deliveryLog->quantity_delivered }}L</p>
                                    <p class="text-[9px] md:text-[10px] font-bold leading-tight" style="color: #78350f;">Pending</p>
                                @elseif($deliveryLog->status === 'skipped')
                                    <div class="w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center mb-1" style="background-color: #6b7280;">
                                        <i class="fa-solid fa-forward text-xs text-white"></i>
                                    </div>
                                    <p class="text-[9px] md:text-[10px] font-bold leading-tight" style="color: #374151;">Skipped</p>
                                @else
                                    <div class="w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center mb-1" style="background-color: #dc2626;">
                                        <i class="fa-solid fa-times text-xs text-white"></i>
                                    </div>
                                    <p class="text-[9px] md:text-[10px] font-bold leading-tight" style="color: #991b1b;">Failed</p>
                                @endif
                            </div>
                        @elseif($hasScheduledDelivery && !$isPast)
                            <!-- Scheduled Delivery -->
                            <div class="flex flex-col items-center justify-center py-1">
                                <div class="w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center mb-1" style="background-color: rgba(47, 74, 30, 0.25);">
                                    <i class="fa-solid fa-droplet text-xs md:text-sm" style="color: var(--green);"></i>
                                </div>
                                <p class="text-sm md:text-base font-bold leading-tight" style="color: #15803d;">{{ $activePlan->getDayQuantity($dayKey) }}L</p>
                                <p class="text-[9px] md:text-[10px] font-bold leading-tight" style="color: #15803d;">Scheduled</p>
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Calendar Legend -->
        <div class="mt-6 md:mt-8 pt-4 md:pt-6 border-t" style="border-color: var(--border);">
            <p class="text-sm font-semibold mb-3 md:mb-4" style="color: var(--text);">Legend:</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #dcfce7; border: 2px solid #16a34a;">
                        <i class="fa-solid fa-check text-sm md:text-base" style="color: #15803d;"></i>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm font-bold leading-tight" style="color: var(--text);">Delivered</p>
                        <p class="text-[10px] md:text-xs leading-tight" style="color: var(--muted);">Completed</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #fef3c7; border: 2px solid #d97706;">
                        <i class="fa-solid fa-clock text-sm md:text-base" style="color: #78350f;"></i>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm font-bold leading-tight" style="color: var(--text);">Pending</p>
                        <p class="text-[10px] md:text-xs leading-tight" style="color: var(--muted);">Awaiting</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #f0fdf4; border: 2px dashed rgba(47, 74, 30, 0.5);">
                        <i class="fa-solid fa-droplet text-sm md:text-base" style="color: var(--green);"></i>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm font-bold leading-tight" style="color: var(--text);">Scheduled</p>
                        <p class="text-[10px] md:text-xs leading-tight" style="color: var(--muted);">Upcoming</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, var(--green) 0%, #3d6b2e 100%);">
                        <i class="fa-solid fa-star text-sm md:text-base text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm font-bold leading-tight" style="color: var(--text);">Today</p>
                        <p class="text-[10px] md:text-xs leading-tight" style="color: var(--muted);">Current day</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Floating Support Button -->
<a href="{{ route('member.support-tickets.index') }}" 
   class="fixed bottom-6 right-6 w-14 h-14 rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 hover:shadow-xl z-50"
   style="background-color: var(--green);"
   title="Support & Help">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
</a>

<!-- Buy Plan Modal -->
<div id="buyPlanModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative" onclick="event.stopPropagation()">
        <button onclick="closeBuyModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors z-10">
            <i class="fa-solid fa-times" style="color: var(--muted);"></i>
        </button>

        <div class="p-6">
            <!-- Header Section - Compact -->
            <div class="text-center mb-4">
                <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <i class="fa-solid fa-shopping-cart text-xl" style="color: var(--green);"></i>
                </div>
                <h3 class="text-lg font-bold mb-1" style="color: var(--text);">Subscribe to Plan</h3>
                <p class="text-xs" style="color: var(--muted);">You're about to subscribe to:</p>
            </div>

            <!-- Plan Details - Compact -->
            <div class="bg-gray-50 rounded-lg p-3 mb-4 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-base" style="color: var(--text);" id="modalPlanName">-</h4>
                    <div class="flex items-center gap-2 mt-1">
                        <i class="fa-solid fa-check-circle text-xs" style="color: var(--green);"></i>
                        <span class="text-xs" style="color: var(--muted);">Fresh milk • Flexible schedule • Secure payment</span>
                    </div>
                </div>
                <p class="text-xl font-bold" style="color: var(--green);">₹<span id="modalPlanPrice">0</span></p>
            </div>

            <form id="buyPlanForm" method="POST" action="{{ route('payment.initiate') }}">
                @csrf
                <input type="hidden" name="plan_id" id="selectedPlanId">
                <input type="hidden" name="payment_method" value="phonepe">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Location Selection -->
                    <div>
                        <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);">
                            <i class="fa-solid fa-map-marker-alt mr-1" style="color: var(--green);"></i>Delivery Location
                        </label>
                        <div class="relative mb-2">
                            <input type="text" 
                                   id="locationSearch" 
                                   placeholder="Search location..."
                                   class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   style="border-color: var(--border);">
                            <i class="fa-solid fa-search absolute right-3 top-2.5 text-xs" style="color: var(--muted);"></i>
                        </div>
                        <select name="location_id" 
                                id="locationSelect" 
                                required 
                                class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                style="border-color: var(--border);">
                            <option value="">Select location</option>
                            @php
                                $locations = \App\Models\Location::active()->ordered()->get();
                            @endphp
                            @foreach($locations as $location)
                            <option value="{{ $location->id }}" 
                                    data-name="{{ strtolower($location->name) }}"
                                    data-area="{{ strtolower($location->area ?? '') }}"
                                    data-city="{{ strtolower($location->city ?? '') }}"
                                    data-sector="{{ strtolower($location->sector ?? '') }}">
                                {{ $location->name }}
                                @if($location->area || $location->city)
                                    - {{ collect([$location->area, $location->city])->filter()->implode(', ') }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Delivery Address -->
                    <div>
                        <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);">
                            <i class="fa-solid fa-location-dot mr-1" style="color: var(--green);"></i>Delivery Address
                        </label>
                        <textarea name="delivery_address" rows="4" required 
                                  class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none" 
                                  style="border-color: var(--border);" 
                                  placeholder="House/Flat No, Building, Street, Landmark"></textarea>
                    </div>
                </div>

                <!-- Helper Text - Compact -->
                <div class="bg-blue-50 rounded-lg p-2 mb-4">
                    <p class="text-xs flex items-start" style="color: #1e40af;">
                        <i class="fa-solid fa-info-circle mr-1.5 mt-0.5 flex-shrink-0"></i>
                        <span>Select your delivery location and provide complete address for smooth milk delivery</span>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="button" onclick="closeBuyModal()" 
                            class="flex-1 py-2.5 rounded-lg font-semibold border transition-colors hover:bg-gray-50 text-sm" 
                            style="border-color: var(--border); color: var(--text);">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 py-2.5 rounded-lg font-semibold transition-all hover:shadow-lg text-sm" 
                            style="background-color: var(--green); color: #fff;">
                        <i class="fa-solid fa-lock mr-2"></i>Proceed to Pay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function buyPlan(planId, planName, planPrice) {
    document.getElementById('selectedPlanId').value = planId;
    document.getElementById('modalPlanName').textContent = planName;
    document.getElementById('modalPlanPrice').textContent = planPrice.toLocaleString();
    
    const modal = document.getElementById('buyPlanModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeBuyModal() {
    const modal = document.getElementById('buyPlanModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.getElementById('buyPlanForm').reset();
    document.getElementById('locationSearch').value = '';
}

// Close modal on backdrop click
document.getElementById('buyPlanModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeBuyModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBuyModal();
    }
});

// Location search functionality
document.getElementById('locationSearch')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const select = document.getElementById('locationSelect');
    const options = select.querySelectorAll('option');
    
    options.forEach(option => {
        if (option.value === '') {
            option.style.display = 'block';
            return;
        }
        
        const name = option.getAttribute('data-name') || '';
        const area = option.getAttribute('data-area') || '';
        const city = option.getAttribute('data-city') || '';
        const sector = option.getAttribute('data-sector') || '';
        
        const searchableText = `${name} ${area} ${city} ${sector}`;
        
        if (searchableText.includes(searchTerm)) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });
    
    // Reset select if search is cleared
    if (searchTerm === '') {
        select.value = '';
    }
});

// Auto-select if only one option is visible after search
document.getElementById('locationSearch')?.addEventListener('input', function(e) {
    setTimeout(() => {
        const select = document.getElementById('locationSelect');
        const visibleOptions = Array.from(select.querySelectorAll('option')).filter(opt => 
            opt.value !== '' && opt.style.display !== 'none'
        );
        
        if (visibleOptions.length === 1) {
            select.value = visibleOptions[0].value;
        }
    }, 100);
});
</script>
@endsection
