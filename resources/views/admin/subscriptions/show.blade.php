@extends('layouts.app')

@section('title', 'Subscription Details')
@section('page-title', 'Subscription #' . $subscription->id)

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center text-sm font-semibold hover:underline" style="color: var(--green);">
        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Subscriptions
    </a>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4" style="border-color: var(--green);">
        <p class="font-semibold" style="color: var(--green);">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User & Plan Info -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Subscription Details</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">User</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $subscription->user->name }}</p>
                        <p class="text-sm" style="color: var(--muted);">{{ $subscription->user->email }}</p>
                        <p class="text-sm" style="color: var(--muted);">{{ $subscription->user->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Membership Plan</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $subscription->membershipPlan->name ?? 'Milk Wallet' }}</p>
                        <p class="text-sm" style="color: var(--muted);">{{ $subscription->membershipPlan->duration ?? '—' }}</p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t" style="border-color: var(--border);">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Start Date</p>
                            <p class="font-semibold" style="color: var(--text);">{{ $subscription->start_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium mb-1" style="color: var(--muted);">End Date</p>
                            <p class="font-semibold" style="color: var(--text);">{{ $subscription->end_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Days Remaining</p>
                            <p class="font-semibold" style="color: var(--green);">{{ $subscription->daysRemaining() }} days</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Created</p>
                            <p class="font-semibold" style="color: var(--text);">{{ $subscription->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t" style="border-color: var(--border);">
                    <a href="{{ route('admin.subscriptions.deliveries.index', $subscription) }}" class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-sm" style="background-color: var(--green); color: #fff;">
                        <i class="fa-solid fa-truck mr-2"></i>View Delivery Logs
                    </a>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Delivery Address</h3>
                <p class="text-sm" style="color: var(--text);">{{ $subscription->delivery_address }}</p>
            </div>

            <!-- Location Details -->
            @if($subscription->location)
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">
                    <i class="fa-solid fa-map-marker-alt mr-2" style="color: var(--green);"></i>Delivery Location
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium mb-1" style="color: var(--muted);">Location Name</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $subscription->location->name }}</p>
                    </div>
                    @if($subscription->location->area)
                    <div>
                        <p class="text-xs font-medium mb-1" style="color: var(--muted);">Area</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $subscription->location->area }}</p>
                    </div>
                    @endif
                    @if($subscription->location->city)
                    <div>
                        <p class="text-xs font-medium mb-1" style="color: var(--muted);">City</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $subscription->location->city }}</p>
                    </div>
                    @endif
                    @if($subscription->location->pincode)
                    <div>
                        <p class="text-xs font-medium mb-1" style="color: var(--muted);">Pincode</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $subscription->location->pincode }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Day-wise Schedule -->
            @if(false)
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Weekly Delivery Schedule</h3>
                
                <div class="grid grid-cols-7 gap-2">
                    @php
                        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    @endphp
                    @foreach($days as $day)
                        @php
                            $hasDelivery = $subscription->membershipPlan->hasDeliveryOnDay($day);
                            $quantity = $subscription->membershipPlan->getDayQuantity($day);
                        @endphp
                        <div class="text-center p-3 rounded-lg border" style="border-color: {{ $hasDelivery ? 'var(--green)' : 'var(--border)' }}; background-color: {{ $hasDelivery ? 'rgba(47, 74, 30, 0.05)' : '#f9f9f9' }};">
                            <p class="text-xs font-bold mb-1" style="color: var(--text);">{{ $day }}</p>
                            @if($hasDelivery)
                                <p class="text-lg font-bold" style="color: var(--green);">{{ $quantity }}L</p>
                            @else
                                <p class="text-xs" style="color: var(--muted);">No delivery</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t grid grid-cols-3 gap-4" style="border-color: var(--border);">
                    <div class="text-center">
                        <p class="text-sm" style="color: var(--muted);">Delivery Days</p>
                        <p class="text-xl font-bold" style="color: var(--green);">{{ $subscription->membershipPlan->getDeliveryDaysCount() }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm" style="color: var(--muted);">Weekly Total</p>
                        <p class="text-xl font-bold" style="color: var(--green);">{{ $subscription->membershipPlan->getTotalWeeklyQuantity() }} L</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm" style="color: var(--muted);">Avg per Day</p>
                        <p class="text-xl font-bold" style="color: var(--green);">
                            {{ number_format($subscription->membershipPlan->getTotalWeeklyQuantity() / max($subscription->membershipPlan->getDeliveryDaysCount(), 1), 1) }} L
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Monthly Delivery Calendar -->
            @if($subscription->membershipPlan?->day_wise_schedule)
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold" style="color: var(--text);">
                            <i class="fa-solid fa-calendar-days mr-2" style="color: var(--green);"></i>Delivery Calendar
                        </h3>
                        <p class="text-sm mt-1" style="color: var(--muted);">Monthly delivery tracking</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold" style="color: var(--green);">{{ now()->format('F Y') }}</p>
                    </div>
                </div>

                @php
                    $today         = now();
                    $startOfMonth  = $today->copy()->startOfMonth();
                    $endOfMonth    = $today->copy()->endOfMonth();
                    $startDay      = $startOfMonth->copy()->startOfWeek();
                    $endDay        = $endOfMonth->copy()->endOfWeek();
                    $daysInCalendar = [];
                    $cur = $startDay->copy();
                    while ($cur <= $endDay) { $daysInCalendar[] = $cur->copy(); $cur->addDay(); }
                @endphp

                <!-- Day headers -->
                <div class="grid grid-cols-7 gap-2 mb-2">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
                    <div class="text-center py-2 rounded text-xs font-bold" style="background-color: rgba(47,74,30,0.07); color: var(--green);">{{ $d }}</div>
                    @endforeach
                </div>

                <!-- Calendar grid -->
                <div class="grid grid-cols-7 gap-2">
                    @foreach($daysInCalendar as $date)
                        @php
                            $isCurrentMonth = $date->month === $today->month;
                            $isToday        = $date->isToday();
                            $dayKey         = $date->format('D');
                            $hasScheduled   = $subscription->membershipPlan->hasDeliveryOnDay($dayKey);
                            $dateKey        = $date->format('Y-m-d');
                            $log            = $monthDeliveries->get($dateKey);
                            $isPast         = $date->isPast() && !$isToday;
                        @endphp
                        <div class="relative min-h-[80px] p-2 rounded-lg border-2 transition-all"
                             style="
                                @if($isToday)
                                    background: linear-gradient(135deg, var(--green) 0%, #3d6b2e 100%); border-color: var(--green);
                                @elseif($log)
                                    @if($log->status === 'delivered') background:#dcfce7; border-color:#16a34a;
                                    @elseif($log->status === 'pending') background:#fef3c7; border-color:#d97706;
                                    @elseif($log->status === 'skipped') background:#f3f4f6; border-color:#9ca3af;
                                    @else background:#fee2e2; border-color:#dc2626;
                                    @endif
                                @elseif($hasScheduled && $isCurrentMonth && !$isPast)
                                    background:#f0fdf4; border-color:rgba(47,74,30,0.4); border-style:dashed;
                                @else
                                    background:{{ $isCurrentMonth ? '#fff' : '#fafafa' }}; border-color:#e5e7eb;
                                @endif
                             ">
                            <span class="text-sm font-bold" style="color:{{ $isToday ? '#fff' : ($isCurrentMonth ? '#1f2937' : '#9ca3af') }};">
                                {{ $date->day }}
                            </span>
                            @if($isCurrentMonth)
                                @if($log)
                                    <div class="flex flex-col items-center mt-1">
                                        @if($log->status === 'delivered')
                                            <i class="fa-solid fa-check text-xs" style="color:#15803d;"></i>
                                            <p class="text-xs font-bold" style="color:#15803d;">{{ $log->quantity_delivered }}L</p>
                                        @elseif($log->status === 'pending')
                                            <i class="fa-solid fa-clock text-xs" style="color:#92400e;"></i>
                                            <p class="text-xs font-bold" style="color:#92400e;">{{ $log->quantity_delivered }}L</p>
                                        @elseif($log->status === 'skipped')
                                            <i class="fa-solid fa-forward text-xs" style="color:#6b7280;"></i>
                                            <p class="text-[10px]" style="color:#6b7280;">Skip</p>
                                        @else
                                            <i class="fa-solid fa-times text-xs" style="color:#dc2626;"></i>
                                            <p class="text-[10px]" style="color:#dc2626;">Failed</p>
                                        @endif
                                    </div>
                                @elseif($hasScheduled && !$isPast)
                                    <div class="flex flex-col items-center mt-1">
                                        <i class="fa-solid fa-droplet text-xs" style="color:var(--green);"></i>
                                        <p class="text-xs font-bold" style="color:var(--green);">{{ $subscription->membershipPlan->getDayQuantity($dayKey) }}L</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Legend -->
                <div class="mt-4 pt-4 border-t flex flex-wrap gap-4" style="border-color: var(--border);">
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded border-2 flex items-center justify-center" style="background:#dcfce7; border-color:#16a34a;"><i class="fa-solid fa-check text-[9px]" style="color:#15803d;"></i></div>
                        <span class="text-xs" style="color:var(--muted);">Delivered</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded border-2 flex items-center justify-center" style="background:#fef3c7; border-color:#d97706;"><i class="fa-solid fa-clock text-[9px]" style="color:#92400e;"></i></div>
                        <span class="text-xs" style="color:var(--muted);">Pending</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded border-2 flex items-center justify-center" style="background:#f3f4f6; border-color:#9ca3af;"><i class="fa-solid fa-forward text-[9px]" style="color:#6b7280;"></i></div>
                        <span class="text-xs" style="color:var(--muted);">Skipped</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded border-2 flex items-center justify-center" style="background:#fee2e2; border-color:#dc2626;"><i class="fa-solid fa-times text-[9px]" style="color:#dc2626;"></i></div>
                        <span class="text-xs" style="color:var(--muted);">Failed</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded border-2" style="background:#f0fdf4; border-color:rgba(47,74,30,0.4); border-style:dashed;"></div>
                        <span class="text-xs" style="color:var(--muted);">Scheduled</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Notes</h3>
                
                @if($subscription->notes)
                <div class="bg-gray-50 rounded-lg p-4 mb-4 whitespace-pre-wrap text-sm" style="color: var(--text);">{{ $subscription->notes }}</div>
                @else
                <p class="text-sm mb-4" style="color: var(--muted);">No notes added yet.</p>
                @endif

                <form method="POST" action="{{ route('admin.subscriptions.add-note', $subscription) }}">
                    @csrf
                    <textarea name="notes" rows="3" required class="w-full px-3 py-2 border rounded-lg mb-2" style="border-color: var(--border);" placeholder="Add a note..."></textarea>
                    <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-sm" style="background-color: var(--green); color: #fff;">
                        <i class="fa-solid fa-plus mr-2"></i>Add Note
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Management -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Status</h3>
                
                <form method="POST" action="{{ route('admin.subscriptions.update-status', $subscription) }}">
                    @csrf
                    <select name="status" class="w-full px-3 py-2 border rounded-lg mb-3" style="border-color: var(--border);">
                        <option value="pending" {{ $subscription->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ $subscription->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="paused" {{ $subscription->status === 'paused' ? 'selected' : '' }}>Paused</option>
                        <option value="cancelled" {{ $subscription->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="expired" {{ $subscription->status === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg font-semibold" style="background-color: var(--green); color: #fff;">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Payment Management -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Payment Details</h3>
                
                <div class="space-y-3 mb-4">
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">Payment Method</p>
                        <p class="font-semibold" style="color: var(--text);">{{ ucfirst(str_replace('_', ' ', $subscription->payment_method)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">Amount</p>
                        <p class="text-xl font-bold" style="color: var(--green);">₹{{ number_format($subscription->amount_paid ?? $subscription->membershipPlan?->price ?? 0, 2) }}</p>
                    </div>
                    @if($subscription->transaction_id)
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">Transaction ID</p>
                        <p class="text-sm font-mono" style="color: var(--text);">{{ $subscription->transaction_id }}</p>
                    </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.subscriptions.update-payment', $subscription) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--text);">Payment Status</label>
                        <select name="payment_status" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <option value="pending" {{ $subscription->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $subscription->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $subscription->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $subscription->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--text);">Transaction ID</label>
                        <input type="text" name="transaction_id" value="{{ $subscription->transaction_id }}" 
                               class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--text);">Amount Paid</label>
                        <input type="number" step="0.01" name="amount_paid" value="{{ $subscription->amount_paid ?? $subscription->membershipPlan?->price ?? 0 }}" 
                               class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg font-semibold" style="background-color: var(--green); color: #fff;">
                        Update Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Change Log --}}
@php $changeLogs = \App\Models\SubscriptionChangeLog::where('user_subscription_id', $subscription->id)->with('changedBy')->latest()->get(); @endphp
@if($changeLogs->count() > 0)
<div class="mt-6 bg-white rounded-lg shadow-sm border p-5" style="border-color: var(--border);">
    <h3 class="font-bold text-base mb-4" style="color: var(--text);"><i class="fa-solid fa-clock-rotate-left mr-2" style="color:var(--green);"></i>Change History</h3>
    <div class="space-y-2">
        @foreach($changeLogs as $log)
        @php
            $typeColors = [
                'settings_update' => ['bg-blue-50','text-blue-700','fa-sliders'],
                'extra_milk'      => ['bg-green-50','text-green-700','fa-plus'],
                'pause'           => ['bg-yellow-50','text-yellow-700','fa-pause'],
                'resume'          => ['bg-green-50','text-green-700','fa-play'],
                'stop'            => ['bg-red-50','text-red-700','fa-stop'],
                'restart'         => ['bg-green-50','text-green-700','fa-rotate-right'],
                'topup'           => ['bg-purple-50','text-purple-700','fa-arrow-up'],
            ];
            [$bg, $tc, $icon] = $typeColors[$log->change_type] ?? ['bg-gray-50','text-gray-600','fa-circle'];
        @endphp
        <div class="flex items-start gap-3 px-3 py-2.5 rounded-xl {{ $bg }}">
            <i class="fa-solid {{ $icon }} mt-0.5 text-xs {{ $tc }}"></i>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs font-semibold {{ $tc }}">{{ ucfirst(str_replace('_',' ',$log->change_type)) }}</span>
                    <span class="text-[10px]" style="color:var(--muted);">{{ $log->created_at->format('d M Y, h:i A') }}</span>
                </div>
                @if($log->notes)<p class="text-xs mt-0.5" style="color:var(--muted);">{{ $log->notes }}</p>@endif
                @if($log->old_values || $log->new_values)
                <div class="flex flex-wrap gap-x-4 gap-y-0.5 mt-1 text-[10px]" style="color:var(--muted);">
                    @foreach($log->new_values ?? [] as $k => $v)
                    <span><span class="font-semibold">{{ ucfirst(str_replace('_',' ',$k)) }}:</span>
                        @if(isset($log->old_values[$k]) && $log->old_values[$k] != $v)
                            <span style="text-decoration:line-through;opacity:0.6;">{{ $log->old_values[$k] }}</span> →
                        @endif
                        {{ $v }}</span>
                    @endforeach
                </div>
                @endif
                <p class="text-[10px] mt-0.5" style="color:var(--muted);">by {{ $log->changedBy?->name ?? '—' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
