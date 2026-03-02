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
            <a href="{{ route('home') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition-colors hover:opacity-90"
               style="background-color: var(--green); color: #fff;">
                <i class="fa-solid fa-globe mr-2"></i>
                Visit Website
            </a>
        </div>
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

<!-- Buy Plan Modal -->
<div id="buyPlanModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative" onclick="event.stopPropagation()">
        <button onclick="closeBuyModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors">
            <i class="fa-solid fa-times" style="color: var(--muted);"></i>
        </button>

        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                <i class="fa-solid fa-shopping-cart text-2xl" style="color: var(--green);"></i>
            </div>
            <h3 class="text-xl font-bold mb-2" style="color: var(--text);">Subscribe to Plan</h3>
            <p class="text-sm" style="color: var(--muted);">You're about to subscribe to:</p>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 mb-6">
            <h4 class="font-bold text-lg mb-1" style="color: var(--text);" id="modalPlanName">-</h4>
            <p class="text-2xl font-bold" style="color: var(--green);">₹<span id="modalPlanPrice">0</span></p>
        </div>

        <div class="space-y-3 mb-6">
            <div class="flex items-center gap-3 text-sm">
                <i class="fa-solid fa-check-circle" style="color: var(--green);"></i>
                <span style="color: var(--text);">Fresh milk delivered daily</span>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <i class="fa-solid fa-check-circle" style="color: var(--green);"></i>
                <span style="color: var(--text);">Flexible delivery schedule</span>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <i class="fa-solid fa-check-circle" style="color: var(--green);"></i>
                <span style="color: var(--text);">Cancel anytime</span>
            </div>
        </div>

        <form id="buyPlanForm" method="POST" action="{{ route('membership.subscribe') }}">
            @csrf
            <input type="hidden" name="plan_id" id="selectedPlanId">
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Payment Method</label>
                <select name="payment_method" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    <option value="">Select payment method</option>
                    <option value="online">Online Payment (UPI/Card)</option>
                    <option value="cod">Cash on Delivery</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Delivery Address</label>
                <textarea name="delivery_address" rows="2" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Enter your delivery address"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeBuyModal()" class="flex-1 py-3 rounded-lg font-bold border transition-colors hover:bg-gray-50" style="border-color: var(--border); color: var(--text);">
                    Cancel
                </button>
                <button type="submit" class="flex-1 py-3 rounded-lg font-bold transition-all hover:shadow-lg" style="background-color: var(--green); color: #fff;">
                    <i class="fa-solid fa-check mr-2"></i>Confirm
                </button>
            </div>
        </form>
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
</script>
@endsection
