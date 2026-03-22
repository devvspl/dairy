@extends('layouts.app')

@section('title', 'Member Dashboard')
@section('page-title', 'Member Dashboard')

@section('content')
@php
    $activeSubscription = auth()->user()->activeSubscription()->first();
    $activePlan = $activeSubscription ? $activeSubscription->membershipPlan : null;
    $currentDay = now()->format('D');
    $hasScheduled = $activePlan && $activePlan->isScheduled();
@endphp

<div class="space-y-4 lg:space-y-6">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4 flex items-start gap-3" style="border-color: var(--green);">
        <i class="fa-solid fa-check-circle text-xl" style="color: var(--green);"></i>
        <div class="flex-1">
            <p class="font-semibold" style="color: var(--green);">Success!</p>
            <p class="text-sm" style="color: var(--text);">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times"></i></button>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border-l-4 rounded-lg p-4 flex items-start gap-3" style="border-color: #dc2626;">
        <i class="fa-solid fa-exclamation-circle text-xl" style="color: #dc2626;"></i>
        <div class="flex-1">
            <p class="font-semibold" style="color: #dc2626;">Error!</p>
            <p class="text-sm" style="color: var(--text);">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times"></i></button>
    </div>
    @endif

    {{-- Hero Welcome --}}
    <div class="rounded-2xl p-5 lg:p-7 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
         style="background: linear-gradient(135deg, var(--green) 0%, #3d6b2e 100%);">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full flex items-center justify-center text-xl font-bold flex-shrink-0"
                 style="background: rgba(255,255,255,0.2); color: #fff;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-xl lg:text-2xl font-bold text-white">Welcome, {{ auth()->user()->name }} 🥛</h1>
                <p class="text-sm mt-0.5" style="color: rgba(255,255,255,0.75);">{{ now()->format('l, F j, Y') }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('member.product-orders.index') }}"
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all hover:scale-105"
               style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);">
                <i class="fa-solid fa-box"></i><span class="hidden sm:inline">My Orders</span>
            </a>
            <a href="{{ route('member.support-tickets.index') }}"
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all hover:scale-105"
               style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);">
                <i class="fa-solid fa-headset"></i><span class="hidden sm:inline">Support</span>
            </a>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color: var(--border);">
        <div class="flex border-b" style="border-color: var(--border);">
            <button onclick="switchTab('packs')" id="tab-packs"
                    class="tab-btn flex-1 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-cart-shopping"></i><span>Milk Packs</span>
            </button>
            <button onclick="switchTab('wallet')" id="tab-wallet"
                    class="tab-btn flex-1 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-wallet"></i><span>Wallet</span>
                @if($walletSubscription && $walletSubscription->wallet_balance > 0)
                <span class="w-2 h-2 rounded-full" style="background: var(--green);"></span>
                @endif
            </button>
            <button onclick="switchTab('plan')" id="tab-plan"
                    class="tab-btn flex-1 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-calendar-check"></i><span>Active Plan</span>
                @if($hasScheduled)<span class="w-2 h-2 rounded-full" style="background: var(--green);"></span>@endif
            </button>
            <button onclick="switchTab('history')" id="tab-history"
                    class="tab-btn flex-1 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-receipt"></i><span>History</span>
            </button>
        </div>

        {{-- ===== TAB: MILK PACKS ===== --}}
        <div id="panel-packs" class="tab-panel p-4 lg:p-6">
            <div class="mb-4">
                <h2 class="text-base font-bold" style="color: var(--text);">On-Demand Milk Packs</h2>
                <p class="text-xs mt-0.5" style="color: var(--muted);">Buy milk for any number of days — no fixed schedule, no commitment</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($onDemandPlans as $plan)
                @php
                    $sub = $onDemandSubscriptions->get($plan->id);
                    $isActive  = $sub && $sub->status === 'active' && $sub->end_date >= now();
                    $isPending = $sub && $sub->status === 'pending';
                    $isExpired = $sub && !$isActive && !$isPending && in_array($sub->status, ['expired','cancelled']);
                @endphp
                <div class="border rounded-xl p-4 relative flex flex-col transition-all hover:shadow-lg"
                     style="border-color: {{ $isActive ? 'var(--green)' : ($plan->is_featured ? '#f1cc24' : 'var(--border)') }};
                            background: {{ $isActive ? 'linear-gradient(135deg,#f0fdf4,#fff)' : '#fff' }};">
                    @if($plan->badge)
                    <div class="absolute -top-3 left-4">
                        <span class="px-2 py-0.5 text-xs rounded-full font-bold shadow"
                              style="background: {{ $isActive ? 'var(--green)' : '#f1cc24' }}; color: {{ $isActive ? '#fff' : '#1f2a1a' }};">
                            {{ $isActive ? 'Active' : $plan->badge }}
                        </span>
                    </div>
                    @endif
                    <div class="text-center mt-2 mb-3">
                        @if($plan->icon)<i class="fas {{ $plan->icon }} text-2xl mb-1" style="color: {{ $isActive ? 'var(--green)' : 'var(--muted)' }};"></i>@endif
                        <h3 class="font-bold text-sm" style="color: var(--text);">{{ $plan->name }}</h3>
                        <p class="text-xs mt-1" style="color: var(--muted);">{{ Str::limit($plan->description, 55) }}</p>
                    </div>
                    <div class="text-center mb-3">
                        <span class="text-2xl font-bold" style="color: var(--green);">₹{{ number_format($plan->price, 0) }}</span>
                        <span class="text-xs" style="color: var(--muted);"> / {{ $plan->duration_label }}</span>
                    </div>
                    @if($plan->features && count($plan->features) > 0)
                    <ul class="space-y-1 mb-3 flex-1">
                        @foreach(array_slice($plan->features, 0, 3) as $feature)
                        <li class="flex items-start text-xs gap-1.5">
                            <i class="fa-solid fa-check mt-0.5 flex-shrink-0" style="color: var(--green);"></i>
                            <span style="color: var(--text);">{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    @if($isActive)
                    <div class="rounded-lg p-2 mb-3 text-center text-xs font-semibold" style="background: rgba(47,74,30,0.08); color: var(--green);">
                        <i class="fa-solid fa-circle-check mr-1"></i>Active · expires {{ $sub->end_date->format('d M Y') }}
                        <div class="text-xs font-normal mt-0.5" style="color: var(--muted);">{{ $sub->daysRemaining() }} days remaining</div>
                    </div>
                    @elseif($isPending)
                    <div class="rounded-lg p-2 mb-3 text-center text-xs font-semibold bg-yellow-50 text-yellow-700">
                        <i class="fa-solid fa-clock mr-1"></i>Payment Pending
                    </div>
                    @elseif($isExpired)
                    <div class="rounded-lg p-2 mb-3 text-center text-xs font-semibold bg-gray-100 text-gray-500">
                        <i class="fa-solid fa-rotate-right mr-1"></i>Expired · Renew?
                    </div>
                    @endif
                    <button onclick="buyPlan({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ $plan->price }}, '{{ $plan->duration_label }}')"
                            class="w-full py-2 rounded-lg font-bold text-xs transition-all hover:shadow-md mt-auto"
                            style="background: {{ $isActive ? '#e5e7eb' : 'var(--green)' }}; color: {{ $isActive ? '#6b7280' : '#fff' }};">
                        @if($isActive)<i class="fa-solid fa-plus mr-1"></i>Buy Again
                        @elseif($isExpired)<i class="fa-solid fa-rotate-right mr-1"></i>Renew
                        @else<i class="fa-solid fa-shopping-cart mr-1"></i>Buy Now
                        @endif
                    </button>
                </div>
                @empty
                <div class="col-span-4 text-center py-12">
                    <i class="fa-solid fa-box-open text-4xl mb-3" style="color: var(--muted);"></i>
                    <p style="color: var(--muted);">No on-demand plans available.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ===== TAB: WALLET ===== --}}
        <div id="panel-wallet" class="tab-panel p-4 lg:p-6 hidden">
        @if($walletSubscription)
        @php
            $ws = $walletSubscription;
            $wPlan = $ws->membershipPlan;
            $walletPct = $ws->walletRemainingPercent();
            $today = now();
            $startOfMonth = $today->copy()->startOfMonth();
            $endOfMonth   = $today->copy()->endOfMonth();
            $daysInCal = [];
            $cur = $startOfMonth->copy()->startOfWeek();
            while ($cur <= $endOfMonth->copy()->endOfWeek()) { $daysInCal[] = $cur->copy(); $cur->addDay(); }
        @endphp

        {{-- Wallet Balance Card --}}
        <div class="rounded-2xl p-5 mb-5 border-2" style="border-color: var(--green); background: linear-gradient(135deg,#f0fdf4,#fff);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fa-solid fa-wallet text-lg" style="color: var(--green);"></i>
                        <h2 class="font-bold text-base" style="color: var(--text);">Milk Wallet</h2>
                        <span class="px-2 py-0.5 text-xs rounded-full font-bold" style="background: rgba(47,74,30,0.1); color: var(--green);">{{ $wPlan->name }}</span>
                    </div>
                    <p class="text-xs" style="color: var(--muted);">
                        {{ $ws->milk_type ? ucfirst(str_replace('_',' ',$ws->milk_type)) : 'Milk' }} ·
                        {{ $ws->quantity_per_day ? $ws->quantity_per_day.'L/day' : '' }} ·
                        {{ $ws->delivery_slot ? ucfirst($ws->delivery_slot) : '' }}
                    </p>
                    <p class="text-xs mt-1" style="color: var(--muted);">
                        Valid: {{ $ws->start_date->format('d M') }} – {{ $ws->end_date->format('d M Y') }} · {{ $ws->daysRemaining() }} days left
                    </p>
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-3xl font-bold" style="color: var(--green);">₹{{ number_format($ws->wallet_balance, 2) }}</p>
                    <p class="text-xs" style="color: var(--muted);">of ₹{{ number_format($ws->wallet_total, 2) }} remaining</p>
                    @if($ws->price_per_litre)
                    <p class="text-xs mt-0.5 font-semibold" style="color: var(--green);">₹{{ number_format($ws->price_per_litre, 2) }}/litre</p>
                    @endif
                </div>
            </div>
            {{-- Progress bar --}}
            <div class="mb-3">
                <div class="flex justify-between text-xs mb-1" style="color: var(--muted);">
                    <span>Used: ₹{{ number_format($ws->walletUsedAmount(), 2) }}</span>
                    <span>{{ $walletPct }}% remaining</span>
                </div>
                <div class="w-full rounded-full h-3" style="background: #e5e7eb;">
                    <div class="h-3 rounded-full transition-all" style="width: {{ $walletPct }}%; background: linear-gradient(90deg, var(--green), #5a9e3a);"></div>
                </div>
            </div>
            {{-- Stats row --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white rounded-xl p-3 border text-center" style="border-color: var(--border);">
                    <p class="text-lg font-bold" style="color: var(--green);">{{ number_format($ws->walletTransactions->where('type','debit')->sum('litres'), 1) }}L</p>
                    <p class="text-[10px] mt-0.5" style="color: var(--muted);">Milk Used</p>
                </div>
                <div class="bg-white rounded-xl p-3 border text-center" style="border-color: var(--border);">
                    <p class="text-lg font-bold" style="color: var(--green);">{{ $ws->walletTransactions->where('type','debit')->count() }}</p>
                    <p class="text-[10px] mt-0.5" style="color: var(--muted);">Deliveries</p>
                </div>
                <div class="bg-white rounded-xl p-3 border text-center" style="border-color: var(--border);">
                    <p class="text-lg font-bold" style="color: var(--green);">{{ $ws->walletTransactions->where('type','credit')->count() }}</p>
                    <p class="text-[10px] mt-0.5" style="color: var(--muted);">Top-ups</p>
                </div>
            </div>
        </div>

        {{-- Wallet Calendar --}}
        <div class="mb-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-sm" style="color: var(--text);"><i class="fa-solid fa-calendar-days mr-2" style="color: var(--green);"></i>Wallet Calendar</h3>
                <span class="text-sm font-semibold" style="color: var(--green);">{{ now()->format('F Y') }}</span>
            </div>
            <div class="grid grid-cols-7 gap-1 mb-2">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
                <div class="text-center py-1.5 text-[10px] font-bold rounded" style="background: rgba(47,74,30,0.05); color: var(--green);">{{ $d }}</div>
                @endforeach
            </div>
            <div class="grid grid-cols-7 gap-1">
                @foreach($daysInCal as $calDate)
                @php
                    $isCurrentMonth = $calDate->month === $today->month;
                    $isToday = $calDate->isToday();
                    $dateKey = $calDate->format('Y-m-d');
                    $txn = $walletCalendarData->get($dateKey);
                    $delivery = $deliveryCalendarData->get($dateKey) ?? null;
                    $isFuture = $calDate->isFuture() && !$isToday;
                @endphp
                <div class="min-h-[72px] p-1.5 rounded-lg border-2 text-center transition-all {{ $isToday ? 'scale-105 shadow-md' : '' }}"
                     style="@if($isToday) background: linear-gradient(135deg,var(--green),#3d6b2e); border-color: var(--green);
                            @elseif($txn && $txn->type === 'debit') background:#fef3c7; border-color:#d97706;
                            @elseif($txn && $txn->type === 'credit') background:#dcfce7; border-color:#16a34a;
                            @elseif($delivery && $delivery->status === 'pending' && $isCurrentMonth) background:#eff6ff; border-color:#93c5fd;
                            @elseif($delivery && $delivery->status === 'skipped' && $isCurrentMonth) background:#f3f4f6; border-color:#d1d5db;
                            @else background:{{ $isCurrentMonth ? '#fff' : '#fafafa' }}; border-color:#e5e7eb; @endif">
                    <span class="text-xs font-bold block" style="color: {{ $isToday ? '#fff' : ($isCurrentMonth ? '#1f2937' : '#9ca3af') }};">{{ $calDate->day }}</span>
                    @if($isCurrentMonth && $txn)
                        @if($txn->type === 'debit')
                            <i class="fa-solid fa-droplet text-[9px]" style="color:#d97706;"></i>
                            <p class="text-[9px] font-bold leading-tight" style="color:#92400e;">{{ number_format($txn->litres,1) }}L</p>
                            <p class="text-[8px] leading-tight" style="color:#b45309;">−₹{{ number_format($txn->amount,0) }}</p>
                        @else
                            <i class="fa-solid fa-plus text-[9px]" style="color:#16a34a;"></i>
                            <p class="text-[8px] font-bold leading-tight" style="color:#15803d;">+₹{{ number_format($txn->amount,0) }}</p>
                        @endif
                    @elseif($isCurrentMonth && $delivery)
                        @if($delivery->status === 'pending')
                            <i class="fa-solid fa-clock text-[9px]" style="color:#3b82f6;"></i>
                            <p class="text-[8px] leading-tight font-semibold" style="color:#1d4ed8;">{{ number_format($delivery->quantity_delivered,1) }}L</p>
                            <p class="text-[8px] leading-tight" style="color:#3b82f6;">Pending</p>
                        @elseif($delivery->status === 'skipped')
                            <i class="fa-solid fa-ban text-[9px]" style="color:#9ca3af;"></i>
                            <p class="text-[8px] leading-tight" style="color:#6b7280;">Skipped</p>
                        @elseif($delivery->status === 'failed')
                            <i class="fa-solid fa-circle-xmark text-[9px]" style="color:#ef4444;"></i>
                            <p class="text-[8px] leading-tight" style="color:#dc2626;">Failed</p>
                        @endif
                    @elseif($isCurrentMonth && $isToday)
                        <i class="fa-solid fa-star text-[9px] text-white"></i>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="mt-3 flex flex-wrap gap-3">
                <div class="flex items-center gap-1.5 text-xs"><div class="w-4 h-4 rounded border-2 border-yellow-500" style="background:#fef3c7;"></div><span style="color:var(--muted);">Delivered (debit)</span></div>
                <div class="flex items-center gap-1.5 text-xs"><div class="w-4 h-4 rounded border-2 border-green-600" style="background:#dcfce7;"></div><span style="color:var(--muted);">Top-up (credit)</span></div>
                <div class="flex items-center gap-1.5 text-xs"><div class="w-4 h-4 rounded border-2 border-blue-400" style="background:#eff6ff;"></div><span style="color:var(--muted);">Pending delivery</span></div>
                <div class="flex items-center gap-1.5 text-xs"><div class="w-4 h-4 rounded border-2 border-gray-300" style="background:#f3f4f6;"></div><span style="color:var(--muted);">Skipped</span></div>
                <div class="flex items-center gap-1.5 text-xs"><div class="w-4 h-4 rounded" style="background:var(--green);"></div><span style="color:var(--muted);">Today</span></div>
            </div>
        </div>

        {{-- Transaction History --}}
        <div>
            <h3 class="font-bold text-sm mb-3" style="color: var(--text);"><i class="fa-solid fa-list-ul mr-2" style="color: var(--green);"></i>Transaction History</h3>
            @php $allTxns = $ws->walletTransactions->sortByDesc('transaction_date'); @endphp
            @if($allTxns->count() > 0)
            <div class="space-y-2">
                @foreach($allTxns->take(20) as $txn)
                <div class="flex items-center justify-between px-4 py-3 rounded-xl border" style="border-color: var(--border); background: {{ $txn->isCredit() ? '#f0fdf4' : '#fffbeb' }};">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                             style="background: {{ $txn->isCredit() ? 'rgba(22,163,74,0.12)' : 'rgba(217,119,6,0.12)' }};">
                            <i class="fa-solid {{ $txn->isCredit() ? 'fa-arrow-down' : 'fa-droplet' }} text-xs"
                               style="color: {{ $txn->isCredit() ? '#16a34a' : '#d97706' }};"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold" style="color: var(--text);">{{ $txn->description }}</p>
                            <p class="text-[10px]" style="color: var(--muted);">{{ $txn->transaction_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold" style="color: {{ $txn->isCredit() ? '#16a34a' : '#d97706' }};">
                            {{ $txn->isCredit() ? '+' : '−' }}₹{{ number_format($txn->amount, 2) }}
                        </p>
                        <p class="text-[10px]" style="color: var(--muted);">Bal: ₹{{ number_format($txn->balance_after, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fa-solid fa-receipt text-3xl mb-2" style="color: var(--muted);"></i>
                <p class="text-sm" style="color: var(--muted);">No transactions yet.</p>
            </div>
            @endif
        </div>

        @else
        <div class="text-center py-16">
            <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center" style="background: rgba(47,74,30,0.08);">
                <i class="fa-solid fa-wallet text-3xl" style="color: var(--muted);"></i>
            </div>
            <h3 class="text-lg font-bold mb-2" style="color: var(--text);">No Active Wallet</h3>
            <p class="text-sm mb-5" style="color: var(--muted);">Buy an on-demand milk pack to activate your milk wallet.</p>
            <button onclick="switchTab('packs')" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm transition-all hover:shadow-lg" style="background: var(--green); color: #fff;">
                <i class="fa-solid fa-cart-shopping"></i>Browse Milk Packs
            </button>
        </div>
        @endif
        </div>

        {{-- ===== TAB: ACTIVE PLAN ===== --}}
        <div id="panel-plan" class="tab-panel p-4 lg:p-6 hidden">
            @if($hasScheduled && $activeSubscription)
            <div class="rounded-2xl p-5 mb-5 border-2" style="border-color: var(--green); background: linear-gradient(135deg,#f0fdf4,#fff);">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h2 class="text-lg font-bold" style="color: var(--text);">{{ $activePlan->name }}</h2>
                            @if($activePlan->badge)<span class="px-2 py-0.5 text-xs rounded-full font-bold" style="background: #f1cc24; color: #1f2a1a;">{{ $activePlan->badge }}</span>@endif
                            <span class="px-2 py-0.5 text-xs rounded-full font-bold" style="background: rgba(47,74,30,0.1); color: var(--green);">{{ ucfirst($activeSubscription->status) }}</span>
                        </div>
                        <p class="text-sm" style="color: var(--muted);">{{ $activePlan->description }}</p>
                        <p class="text-xs mt-1" style="color: var(--muted);">Valid until <strong>{{ $activeSubscription->end_date->format('M d, Y') }}</strong> · {{ $activeSubscription->daysRemaining() }} days remaining</p>
                        @if($activeSubscription->location)
                        <p class="text-xs mt-1 flex items-center gap-1" style="color: var(--green);">
                            <i class="fa-solid fa-map-marker-alt"></i>
                            <strong>{{ $activeSubscription->location->name }}</strong>
                            @if($activeSubscription->location->area || $activeSubscription->location->city)
                            <span style="color: var(--muted);">({{ collect([$activeSubscription->location->area, $activeSubscription->location->city])->filter()->implode(', ') }})</span>
                            @endif
                        </p>
                        @endif
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-3xl font-bold" style="color: var(--green);">₹{{ number_format($activePlan->price, 0) }}</p>
                        <p class="text-xs" style="color: var(--muted);">per {{ $activePlan->duration_label }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="bg-white rounded-xl p-3 border text-center" style="border-color: var(--border);">
                        <p class="text-2xl font-bold" style="color: var(--green);">{{ $activeSubscription->deliveredCount() }}</p>
                        <p class="text-xs mt-0.5" style="color: var(--muted);">Deliveries Done</p>
                    </div>
                    <div class="bg-white rounded-xl p-3 border text-center" style="border-color: var(--border);">
                        <p class="text-2xl font-bold" style="color: var(--green);">{{ $activeSubscription->totalQuantityDelivered() }}L</p>
                        <p class="text-xs mt-0.5" style="color: var(--muted);">Total Milk</p>
                    </div>
                    <div class="bg-white rounded-xl p-3 border text-center" style="border-color: var(--border);">
                        <p class="text-2xl font-bold" style="color: var(--green);">{{ $activePlan->getDayQuantity($currentDay) }}L</p>
                        <p class="text-xs mt-0.5" style="color: var(--muted);">Today's Qty</p>
                    </div>
                    <div class="bg-white rounded-xl p-3 border text-center" style="border-color: var(--border);">
                        <p class="text-2xl font-bold text-yellow-600">{{ $activeSubscription->pendingCount() }}</p>
                        <p class="text-xs mt-0.5" style="color: var(--muted);">Pending</p>
                    </div>
                </div>
            </div>

            @if($activePlan->day_wise_schedule)
            @php
                $today = now();
                $startOfMonth = $today->copy()->startOfMonth();
                $endOfMonth   = $today->copy()->endOfMonth();
                $daysInCalendar = [];
                $cur = $startOfMonth->copy()->startOfWeek();
                while ($cur <= $endOfMonth->copy()->endOfWeek()) { $daysInCalendar[] = $cur->copy(); $cur->addDay(); }
                $monthDeliveries = $activeSubscription->deliveryLogs()
                    ->whereBetween('delivery_date', [$startOfMonth, $endOfMonth])
                    ->get()->keyBy(fn($i) => $i->delivery_date->format('Y-m-d'));
            @endphp
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold" style="color: var(--text);"><i class="fa-solid fa-calendar-days mr-2" style="color: var(--green);"></i>Delivery Calendar</h3>
                    <span class="text-sm font-semibold" style="color: var(--green);">{{ now()->format('F Y') }}</span>
                </div>
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
                    <div class="text-center py-2 text-xs font-bold rounded" style="background: rgba(47,74,30,0.05); color: var(--green);">{{ $d }}</div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 gap-1 md:gap-2">
                    @foreach($daysInCalendar as $date)
                    @php
                        $isCurrentMonth = $date->month === $today->month;
                        $isToday = $date->isToday();
                        $dayKey  = $date->format('D');
                        $hasDelivery = $activePlan->hasDeliveryOnDay($dayKey);
                        $dateKey = $date->format('Y-m-d');
                        $log = $monthDeliveries->get($dateKey);
                        $isPast = $date->isPast() && !$isToday;
                    @endphp
                    <div class="relative min-h-[70px] md:min-h-[90px] p-1.5 md:p-2 rounded-lg border-2 transition-all text-center {{ $isToday ? 'scale-105 shadow-md' : '' }}"
                         style="@if($isToday) background: linear-gradient(135deg,var(--green),#3d6b2e); border-color: var(--green);
                                @elseif($log && $log->status === 'delivered') background:#dcfce7; border-color:#16a34a;
                                @elseif($log && $log->status === 'pending') background:#fef3c7; border-color:#d97706;
                                @elseif($log) background:#f3f4f6; border-color:#d1d5db;
                                @elseif($hasDelivery && $isCurrentMonth && !$isPast) background:#f0fdf4; border-color:rgba(47,74,30,0.4); border-style:dashed;
                                @else background:{{ $isCurrentMonth ? '#fff' : '#fafafa' }}; border-color:#e5e7eb; @endif">
                        <span class="text-xs md:text-sm font-bold block" style="color: {{ $isToday ? '#fff' : ($isCurrentMonth ? '#1f2937' : '#9ca3af') }};">{{ $date->day }}</span>
                        @if($isCurrentMonth)
                            @if($log)
                                @if($log->status === 'delivered')<i class="fa-solid fa-check text-xs" style="color:#15803d;"></i><p class="text-[9px] font-bold" style="color:#15803d;">{{ $log->quantity_delivered }}L</p>
                                @elseif($log->status === 'pending')<i class="fa-solid fa-clock text-xs" style="color:#78350f;"></i><p class="text-[9px] font-bold" style="color:#78350f;">{{ $log->quantity_delivered }}L</p>
                                @elseif($log->status === 'skipped')<i class="fa-solid fa-forward text-xs text-gray-500"></i>
                                @else<i class="fa-solid fa-times text-xs text-red-600"></i>
                                @endif
                            @elseif($hasDelivery && !$isPast)
                            <i class="fa-solid fa-droplet text-xs" style="color:var(--green);"></i>
                            <p class="text-[9px] font-bold" style="color:var(--green);">{{ $activePlan->getDayQuantity($dayKey) }}L</p>
                            @endif
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t flex flex-wrap gap-3" style="border-color: var(--border);">
                    <div class="flex items-center gap-1.5 text-xs"><div class="w-4 h-4 rounded border-2 border-green-600" style="background:#dcfce7;"></div><span style="color:var(--muted);">Delivered</span></div>
                    <div class="flex items-center gap-1.5 text-xs"><div class="w-4 h-4 rounded border-2 border-yellow-500" style="background:#fef3c7;"></div><span style="color:var(--muted);">Pending</span></div>
                    <div class="flex items-center gap-1.5 text-xs"><div class="w-4 h-4 rounded border-2 border-dashed" style="background:#f0fdf4; border-color:rgba(47,74,30,0.4);"></div><span style="color:var(--muted);">Scheduled</span></div>
                    <div class="flex items-center gap-1.5 text-xs"><div class="w-4 h-4 rounded" style="background:var(--green);"></div><span style="color:var(--muted);">Today</span></div>
                </div>
            </div>
            @endif

            @else
            <div class="text-center py-16">
                <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center" style="background: rgba(47,74,30,0.08);">
                    <i class="fa-solid fa-calendar-xmark text-3xl" style="color: var(--muted);"></i>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--text);">No Active Scheduled Plan</h3>
                <p class="text-sm mb-5" style="color: var(--muted);">You don't have a scheduled milk plan yet.</p>
                <a href="{{ route('membership') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm transition-all hover:shadow-lg" style="background: var(--green); color: #fff;">
                    <i class="fa-solid fa-arrow-right"></i>View Membership Plans
                </a>
            </div>
            @endif
        </div>

        {{-- ===== TAB: HISTORY ===== --}}
        <div id="panel-history" class="tab-panel p-4 lg:p-6 hidden">
            <h2 class="text-base font-bold mb-4" style="color: var(--text);">Subscription History</h2>
            @if($subscriptionHistory->count() > 0)
            {{-- Mobile cards --}}
            <div class="space-y-3 md:hidden">
                @foreach($subscriptionHistory as $sub)
                <div class="border rounded-xl p-4" style="border-color: var(--border);">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-semibold text-sm" style="color: var(--text);">{{ $sub->membershipPlan->name ?? '—' }}</p>
                            <p class="text-xs mt-0.5" style="color: var(--muted);">{{ $sub->location->name ?? '—' }}</p>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full font-semibold
                            {{ $sub->status === 'active'    ? 'bg-green-100 text-green-800'  : '' }}
                            {{ $sub->status === 'expired'   ? 'bg-gray-100 text-gray-600'    : '' }}
                            {{ $sub->status === 'cancelled' ? 'bg-red-100 text-red-700'      : '' }}
                            {{ $sub->status === 'pending'   ? 'bg-yellow-100 text-yellow-800': '' }}">
                            {{ ucfirst($sub->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs" style="color: var(--muted);">
                        <span>{{ $sub->start_date->format('d M Y') }} – {{ $sub->end_date->format('d M Y') }}</span>
                        <span class="font-bold" style="color: var(--green);">₹{{ number_format($sub->amount_paid, 0) }}</span>
                    </div>
                    <div class="mt-1.5">
                        <span class="px-2 py-0.5 text-xs rounded-full font-semibold {{ $sub->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($sub->payment_status ?? 'pending') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            {{-- Desktop table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b" style="border-color: var(--border);">
                            <th class="pb-3 text-left font-semibold" style="color: var(--muted);">Plan</th>
                            <th class="pb-3 text-left font-semibold" style="color: var(--muted);">Location</th>
                            <th class="pb-3 text-left font-semibold" style="color: var(--muted);">Period</th>
                            <th class="pb-3 text-left font-semibold" style="color: var(--muted);">Amount</th>
                            <th class="pb-3 text-left font-semibold" style="color: var(--muted);">Payment</th>
                            <th class="pb-3 text-left font-semibold" style="color: var(--muted);">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--border);">
                        @foreach($subscriptionHistory as $sub)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 font-medium" style="color: var(--text);">{{ $sub->membershipPlan->name ?? '—' }}</td>
                            <td class="py-3" style="color: var(--muted);">{{ $sub->location->name ?? '—' }}</td>
                            <td class="py-3 text-xs" style="color: var(--muted);">{{ $sub->start_date->format('d M Y') }} – {{ $sub->end_date->format('d M Y') }}</td>
                            <td class="py-3 font-semibold" style="color: var(--green);">₹{{ number_format($sub->amount_paid, 0) }}</td>
                            <td class="py-3"><span class="px-2 py-0.5 text-xs rounded-full font-semibold {{ $sub->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($sub->payment_status ?? 'pending') }}</span></td>
                            <td class="py-3"><span class="px-2 py-0.5 text-xs rounded-full font-semibold
                                {{ $sub->status === 'active'    ? 'bg-green-100 text-green-800'  : '' }}
                                {{ $sub->status === 'expired'   ? 'bg-gray-100 text-gray-600'    : '' }}
                                {{ $sub->status === 'cancelled' ? 'bg-red-100 text-red-700'      : '' }}
                                {{ $sub->status === 'pending'   ? 'bg-yellow-100 text-yellow-800': '' }}">{{ ucfirst($sub->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fa-solid fa-receipt text-4xl mb-3" style="color: var(--muted);"></i>
                <p style="color: var(--muted);">No subscription history yet.</p>
            </div>
            @endif
        </div>

    </div>{{-- end tab container --}}
</div>{{-- end space-y --}}

{{-- Floating Support --}}
<a href="{{ route('member.support-tickets.index') }}"
   class="fixed bottom-6 right-6 w-14 h-14 rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 hover:shadow-xl z-50"
   style="background-color: var(--green);" title="Support & Help">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
</a>

{{-- ===== ORDER MODAL ===== --}}
<div id="buyPlanModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden items-center justify-center p-3 sm:p-4" style="backdrop-filter: blur(6px);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col relative" style="max-height:92vh;" onclick="event.stopPropagation()">

        {{-- Modal Header (fixed, never scrolls) --}}
        <div class="flex-shrink-0 bg-white rounded-t-2xl px-5 pt-5 pb-3 border-b" style="border-color: var(--border);">
            <button onclick="closeBuyModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors">
                <i class="fa-solid fa-times text-sm" style="color: var(--muted);"></i>
            </button>
            <div class="flex items-start pr-8">
                <div class="flex flex-col items-center" style="min-width:64px;">
                    <div id="step-dot-1" class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all mb-1.5" style="background: var(--green); color: #fff;">1</div>
                    <span id="step-label-1" class="text-[10px] font-bold text-center leading-tight" style="color: var(--green);">Milk Order</span>
                </div>
                <div class="flex-1 h-0.5 rounded mt-3.5 mx-1" style="background: #e5e7eb;"></div>
                <div class="flex flex-col items-center" style="min-width:64px;">
                    <div id="step-dot-2" class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all mb-1.5" style="background: #e5e7eb; color: #9ca3af;">2</div>
                    <span id="step-label-2" class="text-[10px] font-semibold text-center leading-tight" style="color: var(--muted);">Delivery</span>
                </div>
                <div class="flex-1 h-0.5 rounded mt-3.5 mx-1" style="background: #e5e7eb;"></div>
                <div class="flex flex-col items-center" style="min-width:64px;">
                    <div id="step-dot-3" class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all mb-1.5" style="background: #e5e7eb; color: #9ca3af;">3</div>
                    <span id="step-label-3" class="text-[10px] font-semibold text-center leading-tight" style="color: var(--muted);">Confirm & Pay</span>
                </div>
            </div>
        </div>

        {{-- Scrollable body --}}
        <div class="overflow-y-auto flex-1" id="modalScrollBody">
        <form id="buyPlanForm" method="POST" action="{{ route('payment.initiate') }}">
            @csrf
            <input type="hidden" name="plan_id" id="selectedPlanId">
            <input type="hidden" name="payment_method" value="phonepe">

            {{-- STEP 1 --}}
            <div id="modal-step-1" class="p-5 space-y-4">
                <div class="flex items-center justify-between rounded-xl px-4 py-3" style="background: rgba(47,74,30,0.06); border: 1px solid rgba(47,74,30,0.15);">
                    <div>
                        <p class="text-xs font-medium" style="color: var(--muted);">Selected Pack</p>
                        <p class="font-bold text-sm" style="color: var(--text);" id="modalPlanName">—</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold" style="color: var(--green);">₹<span id="modalPlanPrice">0</span></p>
                        <p class="text-xs" style="color: var(--muted);" id="modalPlanDuration"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-2" style="color: var(--text);"><i class="fa-solid fa-cow mr-1" style="color: var(--green);"></i>Milk Type</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([['value'=>'cow','label'=>'Cow Milk','icon'=>'fa-cow','desc'=>'A2 · Light & digestible'],['value'=>'buffalo','label'=>'Buffalo Milk','icon'=>'fa-hippo','desc'=>'Rich & creamy'],['value'=>'toned','label'=>'Toned Milk','icon'=>'fa-droplet','desc'=>'Low fat · 3% fat'],['value'=>'full_fat','label'=>'Full Fat','icon'=>'fa-bottle-water','desc'=>'Whole milk · 6% fat']] as $mt)
                        <label class="milk-type-card flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all hover:border-green-400" style="border-color: var(--border);">
                            <input type="radio" name="milk_type" value="{{ $mt['value'] }}" class="hidden milk-type-radio" {{ $loop->first ? 'checked' : '' }}>
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(47,74,30,0.08);">
                                <i class="fas {{ $mt['icon'] }} text-sm" style="color: var(--green);"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold leading-tight" style="color: var(--text);">{{ $mt['label'] }}</p>
                                <p class="text-[10px] leading-tight" style="color: var(--muted);">{{ $mt['desc'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-2" style="color: var(--text);"><i class="fa-solid fa-scale-balanced mr-1" style="color: var(--green);"></i>Quantity per Day</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="changeQty(-0.5)" class="w-10 h-10 rounded-xl border-2 flex items-center justify-center font-bold text-lg transition-all hover:border-green-500" style="border-color: var(--border); color: var(--text);">−</button>
                        <div class="flex-1 text-center">
                            <span id="qtyDisplay" class="text-2xl font-bold" style="color: var(--green);">0.5</span>
                            <span class="text-sm font-semibold ml-1" style="color: var(--muted);">Litre</span>
                        </div>
                        <button type="button" onclick="changeQty(0.5)" class="w-10 h-10 rounded-xl border-2 flex items-center justify-center font-bold text-lg transition-all hover:border-green-500" style="border-color: var(--border); color: var(--text);">+</button>
                        <input type="hidden" name="quantity_per_day" id="qtyInput" value="0.5">
                    </div>
                    <div class="flex justify-between mt-2 gap-1">
                        @foreach([0.5, 1, 1.5, 2, 3] as $q)
                        <button type="button" onclick="setQty({{ $q }})" class="qty-preset flex-1 py-1.5 rounded-lg text-xs font-semibold border transition-all" style="border-color: var(--border); color: var(--muted);">{{ $q }}L</button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);"><i class="fa-solid fa-calendar-day mr-1" style="color: var(--green);"></i>Delivery Start Date</label>
                    <input type="date" name="start_date" id="startDate" required
                           min="{{ now()->format('Y-m-d') }}" max="{{ now()->addDays(30)->format('Y-m-d') }}"
                           value="{{ now()->addDay()->format('Y-m-d') }}"
                           class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" style="border-color: var(--border);">
                    <p class="text-[10px] mt-1" style="color: var(--muted);">Deliveries start from this date. You can order on any day within your pack window.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-2" style="color: var(--text);"><i class="fa-solid fa-clock mr-1" style="color: var(--green);"></i>Preferred Delivery Slot</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([['value'=>'morning','label'=>'Morning','time'=>'5–8 AM','icon'=>'fa-sun'],['value'=>'afternoon','label'=>'Afternoon','time'=>'12–3 PM','icon'=>'fa-cloud-sun'],['value'=>'evening','label'=>'Evening','time'=>'5–8 PM','icon'=>'fa-moon']] as $slot)
                        <label class="slot-card flex flex-col items-center gap-1 p-3 rounded-xl border-2 cursor-pointer transition-all hover:border-green-400 text-center" style="border-color: var(--border);">
                            <input type="radio" name="delivery_slot" value="{{ $slot['value'] }}" class="hidden slot-radio" {{ $loop->first ? 'checked' : '' }}>
                            <i class="fas {{ $slot['icon'] }} text-base" style="color: var(--muted);"></i>
                            <p class="text-xs font-bold leading-tight" style="color: var(--text);">{{ $slot['label'] }}</p>
                            <p class="text-[10px]" style="color: var(--muted);">{{ $slot['time'] }}</p>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button type="button" onclick="goStep(2)" class="w-full py-3 rounded-xl font-bold text-sm transition-all hover:shadow-lg" style="background: var(--green); color: #fff;">
                    Next: Delivery Details <i class="fa-solid fa-arrow-right ml-1"></i>
                </button>
            </div>

            {{-- STEP 2 --}}
            <div id="modal-step-2" class="p-5 space-y-4 hidden">
                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);"><i class="fa-solid fa-map-marker-alt mr-1" style="color: var(--green);"></i>Delivery Location / Society</label>
                    <div class="relative mb-2">
                        <input type="text" id="locationSearch" placeholder="Search your society or area..."
                               class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" style="border-color: var(--border);">
                        <i class="fa-solid fa-search absolute right-3 top-3 text-xs" style="color: var(--muted);"></i>
                    </div>
                    <select name="location_id" id="locationSelect" required
                            class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" style="border-color: var(--border);">
                        <option value="">— Select your society / area —</option>
                        @php $locations = \App\Models\Location::active()->ordered()->get(); @endphp
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}"
                                data-name="{{ strtolower($location->name) }}"
                                data-area="{{ strtolower($location->area ?? '') }}"
                                data-city="{{ strtolower($location->city ?? '') }}"
                                data-sector="{{ strtolower($location->sector ?? '') }}"
                                data-timing="{{ $location->delivery_timing ?? '' }}">
                            {{ $location->name }}@if($location->area || $location->city) — {{ collect([$location->area, $location->city])->filter()->implode(', ') }}@endif
                        </option>
                        @endforeach
                    </select>
                    <div id="locationTimingHint" class="hidden mt-2 px-3 py-2 rounded-lg text-xs" style="background: rgba(47,74,30,0.06); color: var(--green);">
                        <i class="fa-solid fa-clock mr-1"></i><span id="locationTimingText"></span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);"><i class="fa-solid fa-door-open mr-1" style="color: var(--green);"></i>Flat / House / Door No.</label>
                    <input type="text" id="flatNo" placeholder="e.g. A-204, Tower B, 3rd Floor"
                           class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" style="border-color: var(--border);">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);"><i class="fa-solid fa-location-dot mr-1" style="color: var(--green);"></i>Full Delivery Address</label>
                    <textarea name="delivery_address" id="deliveryAddress" rows="3" required
                              class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none" style="border-color: var(--border);"
                              placeholder="Building name, Street, Landmark, City"></textarea>
                </div>
                <div class="flex items-start gap-2 px-3 py-2.5 rounded-xl text-xs" style="background: #fffbeb; border: 1px solid #fde68a;">
                    <i class="fa-solid fa-triangle-exclamation mt-0.5 flex-shrink-0" style="color: #d97706;"></i>
                    <span style="color: #92400e;">Make sure your address is complete and correct. Our delivery person will use this to find you every day.</span>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="goStep(1)" class="flex-1 py-3 rounded-xl font-semibold border-2 text-sm transition-all hover:bg-gray-50" style="border-color: var(--border); color: var(--text);"><i class="fa-solid fa-arrow-left mr-1"></i> Back</button>
                    <button type="button" onclick="goStep(3)" class="flex-1 py-3 rounded-xl font-bold text-sm transition-all hover:shadow-lg" style="background: var(--green); color: #fff;">Review Order <i class="fa-solid fa-arrow-right ml-1"></i></button>
                </div>
            </div>

            {{-- STEP 3 --}}
            <div id="modal-step-3" class="hidden">
                <div class="p-5 space-y-4">
                <h4 class="font-bold text-sm" style="color: var(--text);">Order Summary</h4>

                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);"><i class="fa-solid fa-tag mr-1" style="color: var(--green);"></i>Have a coupon?</label>
                    <div class="flex gap-2">
                        <input type="text" id="couponInput" placeholder="Enter coupon code"
                               class="flex-1 px-3 py-2.5 text-sm border-2 rounded-xl uppercase tracking-widest focus:ring-2 focus:ring-green-500 focus:border-transparent" style="border-color: var(--border);">
                        <button type="button" onclick="applyCoupon()" class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all hover:shadow-md" style="background: var(--green); color: #fff;">Apply</button>
                    </div>
                    <div id="couponMsg" class="hidden mt-2 px-3 py-2 rounded-lg text-xs font-semibold"></div>
                    <input type="hidden" name="coupon_code" id="appliedCouponCode">
                </div>

                <div class="rounded-xl border-2 overflow-hidden" style="border-color: var(--green);">
                    <div class="px-4 py-3" style="background: rgba(47,74,30,0.06);">
                        <p class="font-bold text-sm" style="color: var(--text);" id="summaryPlanName">—</p>
                        <p class="text-xs mt-0.5" style="color: var(--muted);" id="summaryPlanDuration">—</p>
                    </div>
                    <div class="divide-y" style="border-color: var(--border);">
                        <div class="flex justify-between px-4 py-2.5 text-sm"><span style="color: var(--muted);">Milk Type</span><span class="font-semibold" style="color: var(--text);" id="summaryMilkType">—</span></div>
                        <div class="flex justify-between px-4 py-2.5 text-sm"><span style="color: var(--muted);">Quantity / Day</span><span class="font-semibold" style="color: var(--text);" id="summaryQty">—</span></div>
                        <div class="flex justify-between px-4 py-2.5 text-sm"><span style="color: var(--muted);">Start Date</span><span class="font-semibold" style="color: var(--text);" id="summaryStartDate">—</span></div>
                        <div class="flex justify-between px-4 py-2.5 text-sm"><span style="color: var(--muted);">Delivery Slot</span><span class="font-semibold" style="color: var(--text);" id="summarySlot">—</span></div>
                        <div class="flex justify-between px-4 py-2.5 text-sm"><span style="color: var(--muted);">Location</span><span class="font-semibold text-right max-w-[55%]" style="color: var(--text);" id="summaryLocation">—</span></div>
                        <div class="flex justify-between px-4 py-2.5 text-sm"><span style="color: var(--muted);">Address</span><span class="font-semibold text-right max-w-[55%] text-xs" style="color: var(--text);" id="summaryAddress">—</span></div>
                        <div id="summaryDiscountRow" style="display:none;">
                            <div class="flex justify-between px-4 py-2.5 text-sm">
                                <span style="color: #16a34a;"><i class="fa-solid fa-tag mr-1"></i>Coupon (<span id="summaryDiscountCode"></span>)</span>
                                <span class="font-semibold" style="color: #16a34a;">−₹<span id="summaryDiscountAmt">0</span></span>
                            </div>
                        </div>
                        <div class="flex justify-between px-4 py-3 font-bold text-base" style="background: rgba(47,74,30,0.04);">
                            <span style="color: var(--text);">Total Amount</span>
                            <span style="color: var(--green);">₹<span id="summaryTotal">0</span></span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="rounded-xl p-2" style="background: rgba(47,74,30,0.05);"><i class="fa-solid fa-shield-halved text-base mb-1" style="color: var(--green);"></i><p class="text-[10px] font-semibold" style="color: var(--text);">Secure Pay</p></div>
                    <div class="rounded-xl p-2" style="background: rgba(47,74,30,0.05);"><i class="fa-solid fa-truck-fast text-base mb-1" style="color: var(--green);"></i><p class="text-[10px] font-semibold" style="color: var(--text);">Daily Delivery</p></div>
                    <div class="rounded-xl p-2" style="background: rgba(47,74,30,0.05);"><i class="fa-solid fa-rotate-left text-base mb-1" style="color: var(--green);"></i><p class="text-[10px] font-semibold" style="color: var(--text);">Easy Cancel</p></div>
                </div>
                </div>
            </div>
        </form>
        </div>{{-- end #modalScrollBody --}}

        {{-- Step 3 Pay Footer — always visible at bottom of modal --}}
        <div id="step3Footer" class="hidden flex-shrink-0 border-t px-5 py-4 bg-white rounded-b-2xl flex gap-3" style="border-color: var(--border);">
            <button type="button" onclick="goStep(2)" class="flex-1 py-3 rounded-xl font-semibold border-2 text-sm transition-all hover:bg-gray-50" style="border-color: var(--border); color: var(--text);"><i class="fa-solid fa-arrow-left mr-1"></i> Back</button>
            <button type="submit" form="buyPlanForm" class="flex-1 py-3 rounded-xl font-bold text-sm transition-all hover:shadow-lg flex items-center justify-center gap-2" style="background: var(--green); color: #fff;">
                <i class="fa-solid fa-lock"></i> Pay ₹<span id="payBtnTotal">0</span>
            </button>
        </div>
    </div>
</div>

<style>
.tab-btn { color: var(--muted); border-bottom: 3px solid transparent; }
.tab-btn.active { color: var(--green); border-bottom-color: var(--green); background: rgba(47,74,30,0.04); }
.milk-type-card:has(.milk-type-radio:checked),
.slot-card:has(.slot-radio:checked) { border-color: var(--green) !important; background: rgba(47,74,30,0.05); }
.qty-preset.active { background: var(--green); color: #fff; border-color: var(--green); }
</style>

<script>
// ── Tabs ──────────────────────────────────────────────────────
function switchTab(name) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.getElementById('tab-' + name).classList.add('active');
    document.getElementById('panel-' + name).classList.remove('hidden');
    localStorage.setItem('dashTab', name);
}
(function() { switchTab(localStorage.getItem('dashTab') || 'packs'); })();

// ── Modal state ───────────────────────────────────────────────
let currentPlanPrice = 0, currentPlanDuration = '', currentPlanId = null;
let appliedDiscount = 0, appliedCouponCode = '';

function buyPlan(planId, planName, planPrice, planDuration) {
    currentPlanPrice = planPrice; currentPlanDuration = planDuration || '';
    currentPlanId = planId; appliedDiscount = 0; appliedCouponCode = '';
    document.getElementById('selectedPlanId').value = planId;
    document.getElementById('modalPlanName').textContent  = planName;
    document.getElementById('modalPlanPrice').textContent = Number(planPrice).toLocaleString('en-IN');
    document.getElementById('modalPlanDuration').textContent = planDuration || '';
    document.getElementById('payBtnTotal').textContent  = Number(planPrice).toLocaleString('en-IN');
    document.getElementById('summaryTotal').textContent = Number(planPrice).toLocaleString('en-IN');
    goStep(1);
    const modal = document.getElementById('buyPlanModal');
    modal.classList.remove('hidden'); modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeBuyModal() {
    const modal = document.getElementById('buyPlanModal');
    modal.classList.add('hidden'); modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
    document.getElementById('buyPlanForm').reset();
    document.getElementById('locationSearch').value = '';
    document.getElementById('locationTimingHint').classList.add('hidden');
    appliedDiscount = 0; appliedCouponCode = '';
    document.getElementById('couponInput').value = '';
    document.getElementById('appliedCouponCode').value = '';
    document.getElementById('couponMsg').classList.add('hidden');
    document.getElementById('summaryDiscountRow').style.display = 'none';
    document.getElementById('step3Footer').classList.add('hidden');
    document.getElementById('step3Footer').style.display = '';
    setQty(0.5);
}

document.getElementById('buyPlanModal')?.addEventListener('click', function(e) { if (e.target === this) closeBuyModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeBuyModal(); });

// ── Steps ─────────────────────────────────────────────────────
function goStep(n) {
    if (n === 2 && !validateStep1()) return;
    if (n === 3 && !validateStep2()) return;
    if (n === 3) buildSummary();
    [1,2,3].forEach(i => {
        const panel = document.getElementById('modal-step-' + i);
        if (i === n) {
            panel.classList.remove('hidden');
        } else {
            panel.classList.add('hidden');
        }
        const dot = document.getElementById('step-dot-' + i);
        if (i < n) { dot.style.background = 'var(--green)'; dot.style.color = '#fff'; dot.innerHTML = '<i class="fa-solid fa-check text-xs"></i>'; }
        else if (i === n) { dot.style.background = 'var(--green)'; dot.style.color = '#fff'; dot.textContent = i; }
        else { dot.style.background = '#e5e7eb'; dot.style.color = '#9ca3af'; dot.textContent = i; }
        const lbl = document.getElementById('step-label-' + i);
        lbl.style.color = i <= n ? 'var(--green)' : 'var(--muted)';
        lbl.style.fontWeight = i === n ? '700' : '400';
    });
    // Show/hide the sticky Pay footer
    const footer = document.getElementById('step3Footer');
    if (n === 3) { footer.classList.remove('hidden'); footer.style.display = 'flex'; }
    else { footer.classList.add('hidden'); footer.style.display = ''; }
    // Scroll modal body to top on step change
    const scrollEl = document.getElementById('modalScrollBody');
    if (scrollEl) scrollEl.scrollTop = 0;
}

function validateStep1() {
    if (!document.getElementById('startDate').value) { alert('Please select a delivery start date.'); return false; }
    return true;
}
function validateStep2() {
    const loc  = document.getElementById('locationSelect').value;
    const addr = document.getElementById('deliveryAddress').value.trim();
    if (!loc)  { alert('Please select your delivery location.'); return false; }
    if (!addr) { alert('Please enter your delivery address.'); return false; }
    const flat = document.getElementById('flatNo').value.trim();
    if (flat && !document.getElementById('deliveryAddress').value.includes(flat)) {
        document.getElementById('deliveryAddress').value = flat + ', ' + addr;
    }
    return true;
}

function buildSummary() {
    const milkRadio = document.querySelector('.milk-type-radio:checked');
    const slotRadio = document.querySelector('.slot-radio:checked');
    const locSelect = document.getElementById('locationSelect');
    const locText   = locSelect.options[locSelect.selectedIndex]?.text?.trim() || '—';
    const slotLabels = { morning: 'Morning (5–8 AM)', afternoon: 'Afternoon (12–3 PM)', evening: 'Evening (5–8 PM)' };
    const milkLabels = { cow: 'Cow Milk (A2)', buffalo: 'Buffalo Milk', toned: 'Toned Milk', full_fat: 'Full Fat Milk' };
    const finalAmount = currentPlanPrice - appliedDiscount;

    document.getElementById('summaryPlanName').textContent     = document.getElementById('modalPlanName').textContent;
    document.getElementById('summaryPlanDuration').textContent = currentPlanDuration;
    document.getElementById('summaryMilkType').textContent     = milkLabels[milkRadio?.value] || '—';
    document.getElementById('summaryQty').textContent          = document.getElementById('qtyDisplay').textContent + ' Litre';
    document.getElementById('summaryStartDate').textContent    = formatDate(document.getElementById('startDate').value);
    document.getElementById('summarySlot').textContent         = slotLabels[slotRadio?.value] || '—';
    document.getElementById('summaryLocation').textContent     = locText;
    document.getElementById('summaryAddress').textContent      = document.getElementById('deliveryAddress').value.trim();
    document.getElementById('summaryTotal').textContent        = finalAmount.toLocaleString('en-IN');
    document.getElementById('payBtnTotal').textContent         = finalAmount.toLocaleString('en-IN');
    if (appliedDiscount > 0) {
        document.getElementById('summaryDiscountRow').style.display = 'block';
        document.getElementById('summaryDiscountCode').textContent = appliedCouponCode;
        document.getElementById('summaryDiscountAmt').textContent  = appliedDiscount.toLocaleString('en-IN');
    } else {
        document.getElementById('summaryDiscountRow').style.display = 'none';
    }
}

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });
}

// ── Quantity ──────────────────────────────────────────────────
let qty = 0.5;
function changeQty(delta) { setQty(Math.round((qty + delta) * 10) / 10); }
function setQty(val) {
    qty = Math.max(0.5, Math.min(10, val));
    document.getElementById('qtyDisplay').textContent = qty % 1 === 0 ? qty.toFixed(0) : qty.toFixed(1);
    document.getElementById('qtyInput').value = qty;
    document.querySelectorAll('.qty-preset').forEach(b => b.classList.toggle('active', parseFloat(b.textContent) === qty));
}
setQty(0.5);

// ── Coupon ────────────────────────────────────────────────────
function applyCoupon() {
    const code = document.getElementById('couponInput').value.trim().toUpperCase();
    if (!code) { showCouponMsg('Enter a coupon code first.', false); return; }
    fetch('{{ route("payment.apply-coupon") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ code, plan_id: currentPlanId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            appliedDiscount = data.discount; appliedCouponCode = data.coupon_code;
            document.getElementById('appliedCouponCode').value = data.coupon_code;
            const final = currentPlanPrice - appliedDiscount;
            document.getElementById('summaryTotal').textContent    = final.toLocaleString('en-IN');
            document.getElementById('payBtnTotal').textContent     = final.toLocaleString('en-IN');
            document.getElementById('summaryDiscountRow').style.display = 'block';
            document.getElementById('summaryDiscountCode').textContent = data.coupon_code;
            document.getElementById('summaryDiscountAmt').textContent  = appliedDiscount.toLocaleString('en-IN');
            showCouponMsg('✓ ' + data.message, true);
        } else {
            appliedDiscount = 0; appliedCouponCode = '';
            document.getElementById('appliedCouponCode').value = '';
            document.getElementById('summaryDiscountRow').style.display = 'none';
            showCouponMsg(data.message, false);
        }
    })
    .catch(() => showCouponMsg('Could not apply coupon. Try again.', false));
}
function showCouponMsg(text, success) {
    const el = document.getElementById('couponMsg');
    el.textContent = text;
    el.className = 'mt-2 px-3 py-2 rounded-lg text-xs font-semibold ' + (success ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600');
    el.classList.remove('hidden');
}

// ── Location search ───────────────────────────────────────────
document.getElementById('locationSearch')?.addEventListener('input', function() {
    const term = this.value.toLowerCase();
    const select = document.getElementById('locationSelect');
    select.querySelectorAll('option').forEach(opt => {
        if (!opt.value) return;
        const text = [opt.dataset.name, opt.dataset.area, opt.dataset.city, opt.dataset.sector].join(' ');
        opt.style.display = text.includes(term) ? '' : 'none';
    });
    if (!term) select.value = '';
    setTimeout(() => {
        const visible = Array.from(select.querySelectorAll('option')).filter(o => o.value && o.style.display !== 'none');
        if (visible.length === 1) { select.value = visible[0].value; select.dispatchEvent(new Event('change')); }
    }, 100);
});

document.getElementById('locationSelect')?.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const timing = opt?.dataset?.timing;
    const hint = document.getElementById('locationTimingHint');
    if (timing && this.value) {
        document.getElementById('locationTimingText').textContent = 'Delivery timing for this area: ' + timing;
        hint.classList.remove('hidden');
    } else {
        hint.classList.add('hidden');
    }
});
</script>
@endsection
