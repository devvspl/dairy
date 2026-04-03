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
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600"><i
                        class="fa-solid fa-times"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border-l-4 rounded-lg p-4 flex items-start gap-3" style="border-color: #dc2626;">
                <i class="fa-solid fa-exclamation-circle text-xl" style="color: #dc2626;"></i>
                <div class="flex-1">
                    <p class="font-semibold" style="color: #dc2626;">Error!</p>
                    <p class="text-sm" style="color: var(--text);">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600"><i
                        class="fa-solid fa-times"></i></button>
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
                {{-- <a href="{{ route('member.support-tickets.index') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all hover:scale-105"
                    style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fa-solid fa-headset"></i><span class="hidden sm:inline">Support</span>
                </a> --}}
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color: var(--border);">
            <div class="flex border-b" style="border-color: var(--border);">
                <button onclick="switchTab('wallet')" id="tab-wallet"
                    class="tab-btn flex-1 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-wallet"></i><span>Wallet</span>
                    @if($walletSubscription && $walletSubscription->wallet_balance > 0)
                        <span class="w-2 h-2 rounded-full" style="background: var(--green);"></span>
                    @endif
                </button>
                <button onclick="switchTab('history')" id="tab-history"
                    class="tab-btn flex-1 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-receipt"></i><span>History</span>
                </button>
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
                        $endOfMonth = $today->copy()->endOfMonth();
                        $daysInCal = [];
                        $cur = $startOfMonth->copy()->startOfWeek();
                        while ($cur <= $endOfMonth->copy()->endOfWeek()) {
                            $daysInCal[] = $cur->copy();
                            $cur->addDay();
                        }
                    @endphp

                    {{-- Wallet Balance Card --}}
                    <div
                        style="background: var(--surface); border: 0.5px solid var(--border); border-radius: 16px; overflow: hidden;">

                        {{-- Header --}}
                        <div class="p-5 pb-0">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class="fa-solid fa-wallet text-sm" style="color: var(--green);"></i>
                                        <span class="text-sm font-medium" style="color: var(--text);">Milk Wallet</span>
                                        @if($wPlan)
                                        <span class="text-[11px] font-medium px-2 py-0.5 rounded-full"
                                            style="background: rgba(47,74,30,0.1); color: var(--green);">{{ $wPlan->name }}</span>
                                        @elseif($ws->milk_type)
                                        <span class="text-[11px] font-medium px-2 py-0.5 rounded-full"
                                            style="background: rgba(47,74,30,0.1); color: var(--green);">{{ ucfirst(str_replace('_',' ',$ws->milk_type)) }}</span>
                                        @endif
                                        {{-- Dynamic status badge --}}
                                        <span class="text-[11px] font-medium px-2 py-0.5 rounded-full"
                                            style="
                      background: {{ $ws->delivery_status === 'paused' ? 'rgba(180,96,0,0.1)' : ($ws->delivery_status === 'stopped' ? 'rgba(180,0,0,0.1)' : 'rgba(47,74,30,0.1)') }};
                      color: {{ $ws->delivery_status === 'paused' ? '#b46000' : ($ws->delivery_status === 'stopped' ? '#b40000' : 'var(--green)') }};">
                                            {{ ucfirst($ws->delivery_status ?? 'Active') }}
                                        </span>
                                    </div>
                                    <p class="text-xs" style="color: var(--muted);">
                                        {{ $ws->milk_type ? ucfirst(str_replace('_', ' ', $ws->milk_type)) : 'Milk' }} ·
                                        {{ $ws->quantity_per_day ? $ws->quantity_per_day . 'L/day' : '' }} ·
                                        {{ $ws->delivery_slot ? ucfirst($ws->delivery_slot) : '' }}
                                    </p>
                                    <p class="text-xs mt-0.5" style="color: var(--muted);">
                                        {{ $ws->start_date->format('d M') }} –
                                        @if(!$ws->membership_plan_id && $ws->price_per_litre && $ws->quantity_per_day)
                                            @php
                                                $dailyCost = (float)$ws->price_per_litre * (float)$ws->quantity_per_day;
                                                $estDays = $dailyCost > 0 ? floor((float)$ws->wallet_balance / $dailyCost) : 0;
                                            @endphp
                                            <span class="font-medium" style="color: var(--text);">≈ {{ $estDays }} days remaining</span>
                                        @else
                                            {{ $ws->end_date->format('d M Y') }} ·
                                            <span class="font-medium" style="color: var(--text);">{{ $ws->daysRemaining() }} days left</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[28px] font-medium leading-none" style="color: var(--green);">
                                        ₹{{ number_format($ws->wallet_balance, 2) }}</p>
                                    <p class="text-[11px] mt-0.5" style="color: var(--muted);">of
                                        ₹{{ number_format($ws->wallet_total, 2) }} remaining</p>
                                    @if($ws->price_per_litre)
                                        <p class="text-[11px] font-medium mt-0.5" style="color: var(--green);">
                                            ₹{{ number_format($ws->price_per_litre, 2) }}/litre</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Progress bar --}}
                            <div class="mt-4 mb-4">
                                <div class="flex justify-between text-[11px] mb-1.5" style="color: var(--muted);">
                                    <span>Used ₹{{ number_format($ws->walletUsedAmount(), 2) }}</span>
                                    <span>{{ $walletPct }}% remaining</span>
                                </div>
                                <div class="w-full h-1.5 rounded-full" style="background: var(--border);">
                                    <div class="h-1.5 rounded-full" style="width: {{ $walletPct }}%; background: var(--green);">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Stats row --}}
                        <div class="grid grid-cols-3 divide-x"
                            style="border-top: 0.5px solid var(--border); border-color: var(--border);">
                            <div class="py-3 text-center">
                                <p class="text-lg font-medium" style="color: var(--green);">
                                    {{ number_format($ws->walletTransactions->where('type', 'debit')->sum('litres'), 1) }}L</p>
                                <p class="text-[10px] mt-0.5" style="color: var(--muted);">milk used</p>
                            </div>
                            <div class="py-3 text-center">
                                <p class="text-lg font-medium" style="color: var(--green);">
                                    {{ $ws->walletTransactions->where('type', 'debit')->count() }}</p>
                                <p class="text-[10px] mt-0.5" style="color: var(--muted);">deliveries</p>
                            </div>
                            <div class="py-3 text-center">
                                <p class="text-lg font-medium" style="color: var(--green);">
                                    {{ $ws->walletTransactions->where('type', 'credit')->count() }}</p>
                                <p class="text-[10px] mt-0.5" style="color: var(--muted);">top-ups</p>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="grid grid-cols-3 divide-x"
                            style="border-top: 0.5px solid var(--border); border-color: var(--border);">

                            {{-- Top up --}}
                            <button onclick="openTopupModal({{ $ws->id }})"
                                class="flex items-center justify-center gap-1.5 py-3 text-xs font-medium transition-colors hover:bg-black/5 active:bg-black/10"
                                style="color: var(--muted); background: transparent; border: none; cursor: pointer;">
                                <i class="fa-solid fa-arrow-up text-xs"></i>
                                Top up
                            </button>

                            {{-- Pause / Resume / Restart toggle --}}
                            @if($ws->delivery_status === 'stopped')
                                <form method="POST" action="{{ route('wallet.restart', $ws->id) }}" class="contents">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="flex items-center justify-center gap-1.5 py-3 text-xs font-medium transition-colors hover:bg-green-50 active:bg-green-100"
                                        style="color: var(--green); background: transparent; border: none; cursor: pointer;">
                                        <i class="fa-solid fa-play text-xs"></i>
                                        Restart
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('wallet.pause', $ws->id) }}" class="contents">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action"
                                        value="{{ $ws->delivery_status === 'paused' ? 'resume' : 'pause' }}">
                                    <button type="submit"
                                        class="flex items-center justify-center gap-1.5 py-3 text-xs font-medium transition-colors hover:bg-yellow-50 active:bg-yellow-100"
                                        style="color: #b46000; background: transparent; border: none; cursor: pointer;">
                                        <i class="fa-solid {{ $ws->delivery_status === 'paused' ? 'fa-play' : 'fa-pause' }} text-xs"></i>
                                        {{ $ws->delivery_status === 'paused' ? 'Resume' : 'Pause' }}
                                    </button>
                                </form>
                            @endif

                            {{-- Stop (hidden when already stopped) --}}
                            @if($ws->delivery_status !== 'stopped')
                                <button onclick="document.getElementById('stop-confirm-{{ $ws->id }}').classList.remove('hidden')"
                                    class="flex items-center justify-center gap-1.5 py-3 text-xs font-medium transition-colors hover:bg-red-50 active:bg-red-100"
                                    style="color: #b40000; background: transparent; border: none; cursor: pointer;">
                                    <i class="fa-solid fa-stop text-xs"></i>
                                    Stop
                                </button>
                            @else
                                {{-- Add money prompt when stopped --}}
                                <button onclick="openTopupModal({{ $ws->id }})"
                                    class="flex items-center justify-center gap-1.5 py-3 text-xs font-medium transition-colors hover:bg-green-50"
                                    style="color: var(--green); background: transparent; border: none; cursor: pointer;">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                    Add Money
                                </button>
                            @endif

                        </div>
                    </div>

                    {{-- Stop confirmation panel --}}
                    <div id="stop-confirm-{{ $ws->id }}" class="hidden mt-2 rounded-2xl border p-4"
                        style="border-color: #fca5a5; background: #fff5f5;">
                        <p class="text-sm font-medium mb-0.5" style="color: var(--text);">Stop all deliveries?</p>
                        <p class="text-xs mb-3" style="color: var(--muted);">Your wallet balance is safe. You can restart
                            anytime.</p>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('wallet.stop', $ws->id) }}" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full py-2 rounded-xl text-xs font-medium"
                                    style="background: #fee2e2; color: #b91c1c; border: none; cursor: pointer;">
                                    Yes, stop deliveries
                                </button>
                            </form>
                            <button onclick="document.getElementById('stop-confirm-{{ $ws->id }}').classList.add('hidden')"
                                class="flex-1 py-2 rounded-xl text-xs font-medium"
                                style="background: transparent; border: 0.5px solid var(--border); color: var(--muted); cursor: pointer;">
                                Cancel
                            </button>
                        </div>
                    </div>

                    {{-- Wallet Calendar --}}
                    <div class="mb-5">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-bold text-sm" style="color: var(--text);"><i class="fa-solid fa-calendar-days mr-2" style="color: var(--green);"></i>Wallet Calendar</h3>
                            <div class="flex items-center gap-2">
                                <button onclick="calPrev()" class="w-7 h-7 rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors" style="color:var(--muted);">
                                    <i class="fa-solid fa-chevron-left text-xs"></i>
                                </button>
                                <span id="cal-month-label" class="text-sm font-semibold" style="color: var(--green);">{{ now()->format('F Y') }}</span>
                                <button onclick="calNext()" id="cal-next-btn" class="w-7 h-7 rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors" style="color:var(--muted);">
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $d)
                                <div class="text-center py-1.5 text-[10px] font-bold rounded" style="background: rgba(47,74,30,0.05); color: var(--green);">{{ $d }}</div>
                            @endforeach
                        </div>
                        <div id="cal-grid" class="grid grid-cols-7 gap-1">
                            {{-- Rendered by JS --}}
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
                        <h3 class="font-bold text-sm mb-3" style="color: var(--text);"><i class="fa-solid fa-list-ul mr-2"
                                style="color: var(--green);"></i>Transaction History</h3>
                        @php $allTxns = $ws->walletTransactions->sortByDesc('transaction_date'); @endphp
                        @if($allTxns->count() > 0)
                            <div class="space-y-2">
                                @foreach($allTxns->take(20) as $txn)
                                    <div class="flex items-center justify-between px-4 py-3 rounded-xl border"
                                        style="border-color: var(--border); background: {{ $txn->isCredit() ? '#f0fdf4' : '#fffbeb' }};">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                                                style="background: {{ $txn->isCredit() ? 'rgba(22,163,74,0.12)' : 'rgba(217,119,6,0.12)' }};">
                                                <i class="fa-solid {{ $txn->isCredit() ? 'fa-arrow-down' : 'fa-droplet' }} text-xs"
                                                    style="color: {{ $txn->isCredit() ? '#16a34a' : '#d97706' }};"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold" style="color: var(--text);">{{ $txn->description }}</p>
                                                <p class="text-[10px]" style="color: var(--muted);">
                                                    {{ $txn->transaction_date->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold" style="color: {{ $txn->isCredit() ? '#16a34a' : '#d97706' }};">
                                                {{ $txn->isCredit() ? '+' : '−' }}₹{{ number_format($txn->amount, 2) }}
                                            </p>
                                            <p class="text-[10px]" style="color: var(--muted);">Bal:
                                                ₹{{ number_format($txn->balance_after, 2) }}</p>
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
                    {{-- ===== FIRST TIME: Add Money to Wallet ===== --}}
                    @php $milkPriceMap = $milkPrices->keyBy('milk_type'); @endphp
                    <div class="max-w-lg mx-auto py-4">

                        {{-- Header --}}
                        <div class="text-center mb-5">
                            <div class="w-14 h-14 rounded-full mx-auto mb-3 flex items-center justify-center" style="background:rgba(47,74,30,0.08);">
                                <i class="fa-solid fa-wallet text-xl" style="color:var(--green);"></i>
                            </div>
                            <h3 class="text-base font-bold" style="color:var(--text);">Set Up Your Milk Wallet</h3>
                            <p class="text-xs mt-1" style="color:var(--muted);">Add money once — we deliver daily and deduct automatically</p>
                        </div>

                        {{-- Step dots --}}
                        <div class="flex items-center justify-center mb-5">
                            @foreach(['Milk & Qty','Address','Add Money'] as $si => $sl)
                            <div class="flex items-center">
                                <div id="wi-dot-{{ $si+1 }}" class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold transition-all"
                                    style="{{ $si===0 ? 'background:var(--green);color:#fff;' : 'background:#e5e7eb;color:#9ca3af;' }}">{{ $si+1 }}</div>
                                <span id="wi-lbl-{{ $si+1 }}" class="text-[10px] font-semibold ml-1 hidden sm:block"
                                    style="{{ $si===0 ? 'color:var(--green);' : 'color:var(--muted);' }}">{{ $sl }}</span>
                                @if($si < 2)<div class="w-6 h-px mx-2" style="background:#e5e7eb;"></div>@endif
                            </div>
                            @endforeach
                        </div>

                        <form method="POST" action="{{ route('wallet.initiate') }}" id="walletInitForm">
                            @csrf

                            {{-- ── STEP 1: Milk + Qty + Slot + Date ── --}}
                            <div id="wi-step-1" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold mb-2" style="color:var(--text);"><i class="fa-solid fa-cow mr-1" style="color:var(--green);"></i>Milk Type</label>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($milkPrices as $mp)
                                    @php $icons=['cow'=>'fa-cow','buffalo'=>'fa-hippo','toned'=>'fa-droplet','full_fat'=>'fa-bottle-water']; @endphp
                                    <label class="wi-milk-card flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all hover:border-green-400" style="border-color:var(--border);">
                                        <input type="radio" name="milk_type" value="{{ $mp->milk_type }}"
                                            class="hidden wi-milk-radio" data-ppl="{{ $mp->price_per_litre }}"
                                            {{ $loop->first ? 'checked' : '' }}>
                                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(47,74,30,0.08);">
                                            <i class="fas {{ $icons[$mp->milk_type] ?? 'fa-droplet' }} text-sm" style="color:var(--green);"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold leading-tight" style="color:var(--text);">{{ $mp->label }}</p>
                                            <p class="text-[10px] font-semibold" style="color:var(--green);">₹{{ number_format($mp->price_per_litre,2) }}/L</p>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Quantity per day — whole numbers only --}}
                            <div>
                                <label class="block text-xs font-semibold mb-2" style="color:var(--text);"><i class="fa-solid fa-scale-balanced mr-1" style="color:var(--green);"></i>Quantity per Day</label>
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach([1,2,3,5,8] as $q)
                                    <button type="button" onclick="wiSetQty({{ $q }})"
                                        class="wi-qty-btn py-3 rounded-xl text-sm font-bold border-2 transition-all"
                                        data-qty="{{ $q }}"
                                        style="border-color:var(--border);color:var(--muted);">{{ $q }}L</button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="quantity_per_day" id="wi-qty-input" value="1">
                            </div>

                            {{-- Delivery slot — Morning & Evening only --}}
                            <div>
                                <label class="block text-xs font-semibold mb-2" style="color:var(--text);"><i class="fa-solid fa-clock mr-1" style="color:var(--green);"></i>Delivery Slot</label>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach([['value'=>'morning','label'=>'Morning','time'=>'5–8 AM','icon'=>'fa-sun'],['value'=>'evening','label'=>'Evening','time'=>'5–8 PM','icon'=>'fa-moon']] as $slot)
                                    <label class="wi-slot-card flex flex-col items-center gap-1.5 p-4 rounded-xl border-2 cursor-pointer transition-all hover:border-green-400 text-center" style="border-color:var(--border);">
                                        <input type="radio" name="delivery_slot" value="{{ $slot['value'] }}" class="hidden wi-slot-radio" {{ $loop->first ? 'checked' : '' }}>
                                        <i class="fas {{ $slot['icon'] }} text-xl" style="color:var(--muted);"></i>
                                        <p class="text-sm font-bold" style="color:var(--text);">{{ $slot['label'] }}</p>
                                        <p class="text-[10px]" style="color:var(--muted);">{{ $slot['time'] }}</p>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Start date --}}
                            <div>
                                <label class="block text-xs font-semibold mb-1.5" style="color:var(--text);"><i class="fa-solid fa-calendar-day mr-1" style="color:var(--green);"></i>Start Date</label>
                                <input type="date" name="start_date" required
                                    min="{{ now()->format('Y-m-d') }}" max="{{ now()->addDays(30)->format('Y-m-d') }}"
                                    value="{{ now()->addDay()->format('Y-m-d') }}"
                                    class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    style="border-color:var(--border);">
                            </div>

                            {{-- Cost preview --}}
                            <div id="wi-preview" class="hidden rounded-xl px-4 py-3 text-xs space-y-1" style="background:rgba(47,74,30,0.05);">
                                <div class="flex justify-between"><span style="color:var(--muted);">Price per litre</span><span id="wi-ppl" class="font-semibold" style="color:var(--text);">—</span></div>
                                <div class="flex justify-between"><span style="color:var(--muted);">Daily cost</span><span id="wi-daily" class="font-semibold" style="color:var(--text);">—</span></div>
                            </div>

                            <button type="button" onclick="wiGoStep(2)"
                                class="w-full py-3 rounded-xl font-bold text-sm text-white hover:shadow-lg"
                                style="background:var(--green);">
                                Next: Delivery Address <i class="fa-solid fa-arrow-right ml-1"></i>
                            </button>
                        </div>{{-- end wi-step-1 --}}

                        {{-- ── STEP 2: Address ── --}}
                        <div id="wi-step-2" class="space-y-4 hidden">

                            @if($savedAddresses->count() > 0)
                            <div>
                                <label class="block text-xs font-semibold mb-2" style="color:var(--text);"><i class="fa-solid fa-bookmark mr-1" style="color:var(--green);"></i>Saved Addresses</label>
                                <div class="space-y-2">
                                    @foreach($savedAddresses as $addr)
                                    <label class="wi-saved-card flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all hover:border-green-400" style="border-color:var(--border);">
                                        <input type="radio" name="_wi_saved" value="{{ $addr->id }}" class="hidden wi-saved-radio"
                                            data-location="{{ $addr->location_id }}"
                                            data-flat="{{ addslashes($addr->flat_no ?? '') }}"
                                            data-address="{{ addslashes($addr->address) }}">
                                        <i class="fa-solid fa-location-dot mt-0.5 flex-shrink-0 text-sm" style="color:var(--green);"></i>
                                        <div class="flex-1 min-w-0">
                                            @if($addr->label)<p class="text-xs font-bold" style="color:var(--text);">{{ $addr->label }}</p>@endif
                                            <p class="text-xs truncate" style="color:var(--muted);">{{ $addr->full_address }}</p>
                                            @if($addr->location)<p class="text-[10px]" style="color:var(--green);">{{ $addr->location->name }}</p>@endif
                                        </div>
                                        @if($addr->is_default)<span class="text-[10px] px-1.5 py-0.5 rounded-full flex-shrink-0" style="background:rgba(47,74,30,0.1);color:var(--green);">Default</span>@endif
                                    </label>
                                    @endforeach
                                </div>
                                <p class="text-[10px] mt-1.5" style="color:var(--muted);">Or enter a new address below.</p>
                            </div>
                            @endif

                            <div>
                                <label class="block text-xs font-semibold mb-1.5" style="color:var(--text);"><i class="fa-solid fa-map-marker-alt mr-1" style="color:var(--green);"></i>Society / Area</label>
                                <div class="relative mb-2">
                                    <input type="text" id="wi-loc-search" placeholder="Search society or area..."
                                        class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        style="border-color:var(--border);">
                                    <i class="fa-solid fa-search absolute right-3 top-3 text-xs" style="color:var(--muted);"></i>
                                </div>
                                <select name="location_id" id="wi-location-select" required
                                    class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    style="border-color:var(--border);">
                                    <option value="">— Select your society / area —</option>
                                    @php $wiLocations = \App\Models\Location::active()->ordered()->get(); @endphp
                                    @foreach($wiLocations as $loc)
                                    <option value="{{ $loc->id }}"
                                        data-name="{{ strtolower($loc->name) }}"
                                        data-area="{{ strtolower($loc->area ?? '') }}"
                                        data-city="{{ strtolower($loc->city ?? '') }}"
                                        data-timing="{{ $loc->delivery_timing ?? '' }}">
                                        {{ $loc->name }}@if($loc->area || $loc->city) — {{ collect([$loc->area,$loc->city])->filter()->implode(', ') }}@endif
                                    </option>
                                    @endforeach
                                </select>
                                <div id="wi-timing-hint" class="hidden mt-2 px-3 py-2 rounded-lg text-xs" style="background:rgba(47,74,30,0.06);color:var(--green);">
                                    <i class="fa-solid fa-clock mr-1"></i><span id="wi-timing-text"></span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold mb-1.5" style="color:var(--text);"><i class="fa-solid fa-door-open mr-1" style="color:var(--green);"></i>Flat / House No.</label>
                                <input type="text" id="wi-flat-no" placeholder="e.g. A-204, Tower B"
                                    class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    style="border-color:var(--border);">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold mb-1.5" style="color:var(--text);"><i class="fa-solid fa-location-dot mr-1" style="color:var(--green);"></i>Full Address</label>
                                <textarea name="delivery_address" id="wi-address" rows="2" required
                                    placeholder="Building, Street, Landmark, City"
                                    class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
                                    style="border-color:var(--border);"></textarea>
                            </div>

                            <div class="flex gap-3">
                                <button type="button" onclick="wiGoStep(1)" class="flex-1 py-3 rounded-xl font-semibold border-2 text-sm hover:bg-gray-50" style="border-color:var(--border);color:var(--text);"><i class="fa-solid fa-arrow-left mr-1"></i>Back</button>
                                <button type="button" onclick="wiGoStep(3)" class="flex-1 py-3 rounded-xl font-bold text-sm hover:shadow-lg" style="background:var(--green);color:#fff;">Next: Add Money <i class="fa-solid fa-arrow-right ml-1"></i></button>
                            </div>
                        </div>{{-- end wi-step-2 --}}

                        {{-- ── STEP 3: Amount ── --}}
                        <div id="wi-step-3" class="space-y-4 hidden">

                            <div class="rounded-xl px-4 py-3 text-xs space-y-1" style="background:rgba(47,74,30,0.05);">
                                <div class="flex justify-between"><span style="color:var(--muted);">Milk</span><span id="wi-sum-milk" class="font-semibold" style="color:var(--text);">—</span></div>
                                <div class="flex justify-between"><span style="color:var(--muted);">Qty / Day</span><span id="wi-sum-qty" class="font-semibold" style="color:var(--text);">—</span></div>
                                <div class="flex justify-between"><span style="color:var(--muted);">Price / Litre</span><span id="wi-sum-ppl" class="font-semibold" style="color:var(--green);">—</span></div>
                                <div class="flex justify-between"><span style="color:var(--muted);">Daily cost</span><span id="wi-sum-daily" class="font-semibold" style="color:var(--text);">—</span></div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold mb-2" style="color:var(--text);"><i class="fa-solid fa-indian-rupee-sign mr-1" style="color:var(--green);"></i>Add Money to Wallet</label>
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    @foreach([500,1000,1500,2000,3000,5000] as $amt)
                                    <button type="button" onclick="wiSetAmount({{ $amt }})"
                                        class="wi-amt-preset py-2.5 rounded-xl text-sm font-semibold border-2 transition-all"
                                        style="border-color:var(--border);color:var(--muted);">
                                        ₹{{ number_format($amt) }}
                                    </button>
                                    @endforeach
                                </div>
                                <input type="number" name="amount" id="wi-amount" placeholder="Or enter custom amount"
                                    min="50" max="500000" step="1" required
                                    class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    style="border-color:var(--border);">
                                <div id="wi-days-preview" class="hidden mt-2 text-xs font-semibold text-center" style="color:var(--green);"></div>
                            </div>

                            <div class="flex gap-3">
                                <button type="button" onclick="wiGoStep(2)" class="flex-1 py-3 rounded-xl font-semibold border-2 text-sm hover:bg-gray-50" style="border-color:var(--border);color:var(--text);"><i class="fa-solid fa-arrow-left mr-1"></i>Back</button>
                                <button type="button" onclick="wiSubmit()"
                                    class="flex-1 py-3 rounded-xl font-bold text-sm text-white hover:shadow-lg flex items-center justify-center gap-2"
                                    style="background:var(--green);">
                                    <i class="fa-solid fa-lock"></i> Pay & Activate
                                </button>
                            </div>
                        </div>{{-- end wi-step-3 --}}

                        </form>
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
                                        <p class="font-semibold text-sm" style="color: var(--text);">
                                            {{ $sub->membershipPlan->name ?? '—' }}</p>
                                        <p class="text-xs mt-0.5" style="color: var(--muted);">{{ $sub->location->name ?? '—' }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 text-xs rounded-full font-semibold
                                        {{ $sub->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $sub->status === 'expired' ? 'bg-gray-100 text-gray-600' : '' }}
                                        {{ $sub->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $sub->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-xs" style="color: var(--muted);">
                                    <span>{{ $sub->start_date->format('d M Y') }} – {{ $sub->end_date->format('d M Y') }}</span>
                                    <span class="font-bold"
                                        style="color: var(--green);">₹{{ number_format($sub->amount_paid, 0) }}</span>
                                </div>
                                <div class="mt-1.5">
                                    <span
                                        class="px-2 py-0.5 text-xs rounded-full font-semibold {{ $sub->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
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
                                        <td class="py-3 font-medium" style="color: var(--text);">
                                            {{ $sub->membershipPlan->name ?? '—' }}</td>
                                        <td class="py-3" style="color: var(--muted);">{{ $sub->location->name ?? '—' }}</td>
                                        <td class="py-3 text-xs" style="color: var(--muted);">
                                            {{ $sub->start_date->format('d M Y') }} – {{ $sub->end_date->format('d M Y') }}</td>
                                        <td class="py-3 font-semibold" style="color: var(--green);">
                                            ₹{{ number_format($sub->amount_paid, 0) }}</td>
                                        <td class="py-3"><span
                                                class="px-2 py-0.5 text-xs rounded-full font-semibold {{ $sub->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($sub->payment_status ?? 'pending') }}</span>
                                        </td>
                                        <td class="py-3"><span
                                                class="px-2 py-0.5 text-xs rounded-full font-semibold
                                            {{ $sub->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $sub->status === 'expired' ? 'bg-gray-100 text-gray-600' : '' }}
                                            {{ $sub->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $sub->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">{{ ucfirst($sub->status) }}</span>
                                        </td>
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
            </path>
        </svg>
    </a>

    {{-- ===== ORDER MODAL ===== --}}
    <div id="buyPlanModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden items-center justify-center p-3 sm:p-4"
        style="backdrop-filter: blur(6px);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col relative" style="max-height:92vh;"
            onclick="event.stopPropagation()">

            {{-- Modal Header (fixed, never scrolls) --}}
            <div class="flex-shrink-0 bg-white rounded-t-2xl px-5 pt-5 pb-3 border-b" style="border-color: var(--border);">
                <button onclick="closeBuyModal()"
                    class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors">
                    <i class="fa-solid fa-times text-sm" style="color: var(--muted);"></i>
                </button>
                <div class="flex items-start pr-8">
                    <div class="flex flex-col items-center" style="min-width:64px;">
                        <div id="step-dot-1"
                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all mb-1.5"
                            style="background: var(--green); color: #fff;">1</div>
                        <span id="step-label-1" class="text-[10px] font-bold text-center leading-tight"
                            style="color: var(--green);">Milk Order</span>
                    </div>
                    <div class="flex-1 h-0.5 rounded mt-3.5 mx-1" style="background: #e5e7eb;"></div>
                    <div class="flex flex-col items-center" style="min-width:64px;">
                        <div id="step-dot-2"
                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all mb-1.5"
                            style="background: #e5e7eb; color: #9ca3af;">2</div>
                        <span id="step-label-2" class="text-[10px] font-semibold text-center leading-tight"
                            style="color: var(--muted);">Delivery</span>
                    </div>
                    <div class="flex-1 h-0.5 rounded mt-3.5 mx-1" style="background: #e5e7eb;"></div>
                    <div class="flex flex-col items-center" style="min-width:64px;">
                        <div id="step-dot-3"
                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all mb-1.5"
                            style="background: #e5e7eb; color: #9ca3af;">3</div>
                        <span id="step-label-3" class="text-[10px] font-semibold text-center leading-tight"
                            style="color: var(--muted);">Confirm & Pay</span>
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
                        <div class="flex items-center justify-between rounded-xl px-4 py-3"
                            style="background: rgba(47,74,30,0.06); border: 1px solid rgba(47,74,30,0.15);">
                            <div>
                                <p class="text-xs font-medium" style="color: var(--muted);">Selected Pack</p>
                                <p class="font-bold text-sm" style="color: var(--text);" id="modalPlanName">—</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold" style="color: var(--green);">₹<span
                                        id="modalPlanPrice">0</span></p>
                                <p class="text-xs" style="color: var(--muted);" id="modalPlanDuration"></p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-2" style="color: var(--text);"><i
                                    class="fa-solid fa-cow mr-1" style="color: var(--green);"></i>Milk Type</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach([['value' => 'cow', 'label' => 'Cow Milk', 'icon' => 'fa-cow', 'desc' => 'A2 · Light & digestible'], ['value' => 'buffalo', 'label' => 'Buffalo Milk', 'icon' => 'fa-hippo', 'desc' => 'Rich & creamy'], ['value' => 'toned', 'label' => 'Toned Milk', 'icon' => 'fa-droplet', 'desc' => 'Low fat · 3% fat'], ['value' => 'full_fat', 'label' => 'Full Fat', 'icon' => 'fa-bottle-water', 'desc' => 'Whole milk · 6% fat']] as $mt)
                                    <label
                                        class="milk-type-card flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all hover:border-green-400"
                                        style="border-color: var(--border);">
                                        <input type="radio" name="milk_type" value="{{ $mt['value'] }}"
                                            class="hidden milk-type-radio" {{ $loop->first ? 'checked' : '' }}>
                                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                                            style="background: rgba(47,74,30,0.08);">
                                            <i class="fas {{ $mt['icon'] }} text-sm" style="color: var(--green);"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold leading-tight" style="color: var(--text);">
                                                {{ $mt['label'] }}</p>
                                            <p class="text-[10px] leading-tight" style="color: var(--muted);">{{ $mt['desc'] }}
                                            </p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-2" style="color: var(--text);"><i
                                    class="fa-solid fa-scale-balanced mr-1" style="color: var(--green);"></i>Quantity per
                                Day</label>
                            <div class="grid grid-cols-5 gap-2">
                                @foreach([1,2,3,5,8] as $q)
                                    <button type="button" onclick="setQty({{ $q }})"
                                        class="qty-preset py-3 rounded-xl text-sm font-bold border-2 transition-all"
                                        style="border-color: var(--border); color: var(--muted);">{{ $q }}L</button>
                                @endforeach
                            </div>
                            <input type="hidden" name="quantity_per_day" id="qtyInput" value="1">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);"><i
                                    class="fa-solid fa-calendar-day mr-1" style="color: var(--green);"></i>Delivery Start
                                Date</label>
                            <input type="date" name="start_date" id="startDate" required min="{{ now()->format('Y-m-d') }}"
                                max="{{ now()->addDays(30)->format('Y-m-d') }}"
                                value="{{ now()->addDay()->format('Y-m-d') }}"
                                class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                style="border-color: var(--border);">
                            <p class="text-[10px] mt-1" style="color: var(--muted);">Deliveries start from this date. You
                                can order on any day within your pack window.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-2" style="color: var(--text);"><i
                                    class="fa-solid fa-clock mr-1" style="color: var(--green);"></i>Preferred Delivery
                                Slot</label>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach([['value' => 'morning', 'label' => 'Morning', 'time' => '5–8 AM', 'icon' => 'fa-sun'], ['value' => 'evening', 'label' => 'Evening', 'time' => '5–8 PM', 'icon' => 'fa-moon']] as $slot)
                                    <label
                                        class="slot-card flex flex-col items-center gap-1.5 p-4 rounded-xl border-2 cursor-pointer transition-all hover:border-green-400 text-center"
                                        style="border-color: var(--border);">
                                        <input type="radio" name="delivery_slot" value="{{ $slot['value'] }}"
                                            class="hidden slot-radio" {{ $loop->first ? 'checked' : '' }}>
                                        <i class="fas {{ $slot['icon'] }} text-xl" style="color: var(--muted);"></i>
                                        <p class="text-sm font-bold leading-tight" style="color: var(--text);">
                                            {{ $slot['label'] }}</p>
                                        <p class="text-[10px]" style="color: var(--muted);">{{ $slot['time'] }}</p>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="button" onclick="goStep(2)"
                            class="w-full py-3 rounded-xl font-bold text-sm transition-all hover:shadow-lg"
                            style="background: var(--green); color: #fff;">
                            Next: Delivery Details <i class="fa-solid fa-arrow-right ml-1"></i>
                        </button>
                    </div>

                    {{-- STEP 2 --}}
                    <div id="modal-step-2" class="p-5 space-y-4 hidden">

                        {{-- Saved addresses --}}
                        @if($savedAddresses->count() > 0)
                        <div>
                            <label class="block text-xs font-semibold mb-2" style="color: var(--text);"><i class="fa-solid fa-bookmark mr-1" style="color: var(--green);"></i>Saved Addresses</label>
                            <div class="space-y-2">
                                @foreach($savedAddresses as $addr)
                                <label class="saved-addr-card flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all hover:border-green-400"
                                    style="border-color: var(--border);">
                                    <input type="radio" name="_saved_addr" value="{{ $addr->id }}" class="hidden saved-addr-radio mt-0.5"
                                        data-location="{{ $addr->location_id }}"
                                        data-flat="{{ addslashes($addr->flat_no ?? '') }}"
                                        data-address="{{ addslashes($addr->address) }}">
                                    <i class="fa-solid fa-location-dot mt-0.5 flex-shrink-0 text-sm" style="color: var(--green);"></i>
                                    <div class="flex-1 min-w-0">
                                        @if($addr->label)<p class="text-xs font-bold" style="color: var(--text);">{{ $addr->label }}</p>@endif
                                        <p class="text-xs truncate" style="color: var(--muted);">{{ $addr->full_address }}</p>
                                        @if($addr->location)<p class="text-[10px]" style="color: var(--green);">{{ $addr->location->name }}</p>@endif
                                    </div>
                                    @if($addr->is_default)<span class="text-[10px] px-1.5 py-0.5 rounded-full flex-shrink-0" style="background: rgba(47,74,30,0.1); color: var(--green);">Default</span>@endif
                                </label>
                                @endforeach
                            </div>
                            <p class="text-[10px] mt-1.5" style="color: var(--muted);">Or fill in a new address below.</p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);"><i
                                    class="fa-solid fa-map-marker-alt mr-1" style="color: var(--green);"></i>Delivery
                                Location / Society</label>
                            <div class="relative mb-2">
                                <input type="text" id="locationSearch" placeholder="Search your society or area..."
                                    class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    style="border-color: var(--border);">
                                <i class="fa-solid fa-search absolute right-3 top-3 text-xs"
                                    style="color: var(--muted);"></i>
                            </div>
                            <select name="location_id" id="locationSelect" required
                                class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                style="border-color: var(--border);">
                                <option value="">— Select your society / area —</option>
                                @php $locations = \App\Models\Location::active()->ordered()->get(); @endphp
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" data-name="{{ strtolower($location->name) }}"
                                        data-area="{{ strtolower($location->area ?? '') }}"
                                        data-city="{{ strtolower($location->city ?? '') }}"
                                        data-sector="{{ strtolower($location->sector ?? '') }}"
                                        data-timing="{{ $location->delivery_timing ?? '' }}">
                                        {{ $location->name }}@if($location->area || $location->city) —
                                        {{ collect([$location->area, $location->city])->filter()->implode(', ') }}@endif
                                    </option>
                                @endforeach
                            </select>
                            <div id="locationTimingHint" class="hidden mt-2 px-3 py-2 rounded-lg text-xs"
                                style="background: rgba(47,74,30,0.06); color: var(--green);">
                                <i class="fa-solid fa-clock mr-1"></i><span id="locationTimingText"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);"><i
                                    class="fa-solid fa-door-open mr-1" style="color: var(--green);"></i>Flat / House / Door
                                No.</label>
                            <input type="text" id="flatNo" placeholder="e.g. A-204, Tower B, 3rd Floor"
                                class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                style="border-color: var(--border);">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);"><i
                                    class="fa-solid fa-location-dot mr-1" style="color: var(--green);"></i>Full Delivery
                                Address</label>
                            <textarea name="delivery_address" id="deliveryAddress" rows="3" required
                                class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
                                style="border-color: var(--border);"
                                placeholder="Building name, Street, Landmark, City"></textarea>
                        </div>
                        <div class="flex items-start gap-2 px-3 py-2.5 rounded-xl text-xs"
                            style="background: #fffbeb; border: 1px solid #fde68a;">
                            <i class="fa-solid fa-triangle-exclamation mt-0.5 flex-shrink-0" style="color: #d97706;"></i>
                            <span style="color: #92400e;">Make sure your address is complete and correct. Our delivery
                                person will use this to find you every day.</span>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="goStep(1)"
                                class="flex-1 py-3 rounded-xl font-semibold border-2 text-sm transition-all hover:bg-gray-50"
                                style="border-color: var(--border); color: var(--text);"><i
                                    class="fa-solid fa-arrow-left mr-1"></i> Back</button>
                            <button type="button" onclick="goStep(3)"
                                class="flex-1 py-3 rounded-xl font-bold text-sm transition-all hover:shadow-lg"
                                style="background: var(--green); color: #fff;">Review Order <i
                                    class="fa-solid fa-arrow-right ml-1"></i></button>
                        </div>
                    </div>

                    {{-- STEP 3 --}}
                    <div id="modal-step-3" class="hidden">
                        <div class="p-5 space-y-4">
                            <h4 class="font-bold text-sm" style="color: var(--text);">Order Summary</h4>

                            <div>
                                <label class="block text-xs font-semibold mb-1.5" style="color: var(--text);"><i
                                        class="fa-solid fa-tag mr-1" style="color: var(--green);"></i>Have a coupon?</label>
                                <div class="flex gap-2">
                                    <input type="text" id="couponInput" placeholder="Enter coupon code"
                                        class="flex-1 px-3 py-2.5 text-sm border-2 rounded-xl uppercase tracking-widest focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        style="border-color: var(--border);">
                                    <button type="button" onclick="applyCoupon()"
                                        class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all hover:shadow-md"
                                        style="background: var(--green); color: #fff;">Apply</button>
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
                                    <div class="flex justify-between px-4 py-2.5 text-sm"><span
                                            style="color: var(--muted);">Milk Type</span><span class="font-semibold"
                                            style="color: var(--text);" id="summaryMilkType">—</span></div>
                                    <div class="flex justify-between px-4 py-2.5 text-sm"><span
                                            style="color: var(--muted);">Quantity / Day</span><span class="font-semibold"
                                            style="color: var(--text);" id="summaryQty">—</span></div>
                                    <div class="flex justify-between px-4 py-2.5 text-sm"><span
                                            style="color: var(--muted);">Start Date</span><span class="font-semibold"
                                            style="color: var(--text);" id="summaryStartDate">—</span></div>
                                    <div class="flex justify-between px-4 py-2.5 text-sm"><span
                                            style="color: var(--muted);">Delivery Slot</span><span class="font-semibold"
                                            style="color: var(--text);" id="summarySlot">—</span></div>
                                    <div class="flex justify-between px-4 py-2.5 text-sm"><span
                                            style="color: var(--muted);">Location</span><span
                                            class="font-semibold text-right max-w-[55%]" style="color: var(--text);"
                                            id="summaryLocation">—</span></div>
                                    <div class="flex justify-between px-4 py-2.5 text-sm"><span
                                            style="color: var(--muted);">Address</span><span
                                            class="font-semibold text-right max-w-[55%] text-xs" style="color: var(--text);"
                                            id="summaryAddress">—</span></div>
                                    <div id="summaryDiscountRow" style="display:none;">
                                        <div class="flex justify-between px-4 py-2.5 text-sm">
                                            <span style="color: #16a34a;"><i class="fa-solid fa-tag mr-1"></i>Coupon (<span
                                                    id="summaryDiscountCode"></span>)</span>
                                            <span class="font-semibold" style="color: #16a34a;">−₹<span
                                                    id="summaryDiscountAmt">0</span></span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between px-4 py-3 font-bold text-base"
                                        style="background: rgba(47,74,30,0.04);">
                                        <span style="color: var(--text);">Total Amount</span>
                                        <span style="color: var(--green);">₹<span id="summaryTotal">0</span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="rounded-xl p-2" style="background: rgba(47,74,30,0.05);"><i
                                        class="fa-solid fa-shield-halved text-base mb-1" style="color: var(--green);"></i>
                                    <p class="text-[10px] font-semibold" style="color: var(--text);">Secure Pay</p>
                                </div>
                                <div class="rounded-xl p-2" style="background: rgba(47,74,30,0.05);"><i
                                        class="fa-solid fa-truck-fast text-base mb-1" style="color: var(--green);"></i>
                                    <p class="text-[10px] font-semibold" style="color: var(--text);">Daily Delivery</p>
                                </div>
                                <div class="rounded-xl p-2" style="background: rgba(47,74,30,0.05);"><i
                                        class="fa-solid fa-rotate-left text-base mb-1" style="color: var(--green);"></i>
                                    <p class="text-[10px] font-semibold" style="color: var(--text);">Easy Cancel</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>{{-- end #modalScrollBody --}}

            {{-- Step 3 Pay Footer — always visible at bottom of modal --}}
            <div id="step3Footer" class="hidden flex-shrink-0 border-t px-5 py-4 bg-white rounded-b-2xl flex gap-3"
                style="border-color: var(--border);">
                <button type="button" onclick="goStep(2)"
                    class="flex-1 py-3 rounded-xl font-semibold border-2 text-sm transition-all hover:bg-gray-50"
                    style="border-color: var(--border); color: var(--text);"><i class="fa-solid fa-arrow-left mr-1"></i>
                    Back</button>
                <button type="submit" form="buyPlanForm"
                    class="flex-1 py-3 rounded-xl font-bold text-sm transition-all hover:shadow-lg flex items-center justify-center gap-2"
                    style="background: var(--green); color: #fff;">
                    <i class="fa-solid fa-lock"></i> Pay ₹<span id="payBtnTotal">0</span>
                </button>
            </div>
        </div>
    </div>

    <style>
        .tab-btn {
            color: var(--muted);
            border-bottom: 3px solid transparent;
        }

        .tab-btn.active {
            color: var(--green);
            border-bottom-color: var(--green);
            background: rgba(47, 74, 30, 0.04);
        }

        .milk-type-card:has(.milk-type-radio:checked),
        .slot-card:has(.slot-radio:checked) {
            border-color: var(--green) !important;
            background: rgba(47, 74, 30, 0.05);
        }

        .qty-preset.active {
            background: var(--green);
            color: #fff;
            border-color: var(--green);
        }

        .saved-addr-card:has(.saved-addr-radio:checked) {
            border-color: var(--green) !important;
            background: rgba(47, 74, 30, 0.04);
        }

        .ob-milk-card:has(.ob-milk-radio:checked),
        .ob-slot-card:has(.ob-slot-radio:checked),
        .ob-saved-card:has(.ob-saved-radio:checked) {
            border-color: var(--green) !important;
            background: rgba(47, 74, 30, 0.04);
        }
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
        (function () { switchTab(localStorage.getItem('dashTab') || 'wallet'); })();

        // ── Modal state ───────────────────────────────────────────────
        let currentPlanPrice = 0, currentPlanDuration = '', currentPlanId = null;
        let appliedDiscount = 0, appliedCouponCode = '';

        function buyPlan(planId, planName, planPrice, planDuration) {
            currentPlanPrice = planPrice; currentPlanDuration = planDuration || '';
            currentPlanId = planId; appliedDiscount = 0; appliedCouponCode = '';
            document.getElementById('selectedPlanId').value = planId;
            document.getElementById('modalPlanName').textContent = planName;
            document.getElementById('modalPlanPrice').textContent = Number(planPrice).toLocaleString('en-IN');
            document.getElementById('modalPlanDuration').textContent = planDuration || '';
            document.getElementById('payBtnTotal').textContent = Number(planPrice).toLocaleString('en-IN');
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

        document.getElementById('buyPlanModal')?.addEventListener('click', function (e) { if (e.target === this) closeBuyModal(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeBuyModal(); });

        // ── Steps ─────────────────────────────────────────────────────
        function goStep(n) {
            if (n === 2 && !validateStep1()) return;
            if (n === 3 && !validateStep2()) return;
            if (n === 3) buildSummary();
            [1, 2, 3].forEach(i => {
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
            const loc = document.getElementById('locationSelect').value;
            const addr = document.getElementById('deliveryAddress').value.trim();
            if (!loc) { alert('Please select your delivery location.'); return false; }
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
            const locText = locSelect.options[locSelect.selectedIndex]?.text?.trim() || '—';
            const slotLabels = { morning: 'Morning (5–8 AM)', evening: 'Evening (5–8 PM)' };
            const milkLabels = { cow: 'Cow Milk (A2)', buffalo: 'Buffalo Milk', toned: 'Toned Milk', full_fat: 'Full Fat Milk' };
            const finalAmount = currentPlanPrice - appliedDiscount;

            document.getElementById('summaryPlanName').textContent = document.getElementById('modalPlanName').textContent;
            document.getElementById('summaryPlanDuration').textContent = currentPlanDuration;
            document.getElementById('summaryMilkType').textContent = milkLabels[milkRadio?.value] || '—';
            document.getElementById('summaryQty').textContent = qty + ' Litre';
            document.getElementById('summaryStartDate').textContent = formatDate(document.getElementById('startDate').value);
            document.getElementById('summarySlot').textContent = slotLabels[slotRadio?.value] || '—';
            document.getElementById('summaryLocation').textContent = locText;
            document.getElementById('summaryAddress').textContent = document.getElementById('deliveryAddress').value.trim();
            document.getElementById('summaryTotal').textContent = finalAmount.toLocaleString('en-IN');
            document.getElementById('payBtnTotal').textContent = finalAmount.toLocaleString('en-IN');
            if (appliedDiscount > 0) {
                document.getElementById('summaryDiscountRow').style.display = 'block';
                document.getElementById('summaryDiscountCode').textContent = appliedCouponCode;
                document.getElementById('summaryDiscountAmt').textContent = appliedDiscount.toLocaleString('en-IN');
            } else {
                document.getElementById('summaryDiscountRow').style.display = 'none';
            }
        }

        function formatDate(d) {
            if (!d) return '—';
            return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });
        }

        // ── Quantity ──────────────────────────────────────────────────
        let qty = 1;
        const QTY_OPTIONS = [1, 2, 3, 5, 8];
        function changeQty(delta) {
            const idx = QTY_OPTIONS.indexOf(qty);
            const next = QTY_OPTIONS[Math.max(0, Math.min(QTY_OPTIONS.length - 1, idx + delta))];
            setQty(next);
        }
        function setQty(val) {
            qty = val;
            document.getElementById('qtyInput').value = qty;
            document.querySelectorAll('.qty-preset').forEach(b => {
                const active = parseInt(b.textContent) === qty;
                b.style.background  = active ? 'var(--green)' : '';
                b.style.color       = active ? '#fff' : 'var(--muted)';
                b.style.borderColor = active ? 'var(--green)' : 'var(--border)';
            });
        }
        setQty(1);

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
                        document.getElementById('summaryTotal').textContent = final.toLocaleString('en-IN');
                        document.getElementById('payBtnTotal').textContent = final.toLocaleString('en-IN');
                        document.getElementById('summaryDiscountRow').style.display = 'block';
                        document.getElementById('summaryDiscountCode').textContent = data.coupon_code;
                        document.getElementById('summaryDiscountAmt').textContent = appliedDiscount.toLocaleString('en-IN');
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

        // ── Wallet Calendar (multi-month) ────────────────────────────
        @if($walletSubscription)
        (function() {
            const SUB_ID = {{ $walletSubscription->id }};
            const CSRF   = '{{ csrf_token() }}';
            let calYear  = {{ now()->year }};
            let calMonth = {{ now()->month }};
            const todayStr = '{{ now()->format('Y-m-d') }}';

            function renderCal(days) {
                const grid = document.getElementById('cal-grid');
                if (!grid) return;
                grid.innerHTML = days.map(d => {
                    let bg = d.inMonth ? '#fff' : '#fafafa';
                    let border = '#e5e7eb';
                    let dayColor = d.inMonth ? '#1f2937' : '#9ca3af';
                    let inner = '';

                    if (d.isToday) {
                        bg = 'linear-gradient(135deg,var(--green),#3d6b2e)';
                        border = 'var(--green)';
                        dayColor = '#fff';
                        inner = '<i class="fa-solid fa-star text-[9px] text-white"></i>';
                    } else if (d.txn && d.txn.type === 'debit') {
                        bg = '#fef3c7'; border = '#d97706';
                        inner = `<i class="fa-solid fa-droplet text-[9px]" style="color:#d97706;"></i>
                                 <p class="text-[9px] font-bold leading-tight" style="color:#92400e;">${d.txn.litres.toFixed(1)}L</p>
                                 <p class="text-[8px] leading-tight" style="color:#b45309;">−₹${Math.round(d.txn.amount)}</p>`;
                    } else if (d.txn && d.txn.type === 'credit') {
                        bg = '#dcfce7'; border = '#16a34a';
                        inner = `<i class="fa-solid fa-plus text-[9px]" style="color:#16a34a;"></i>
                                 <p class="text-[8px] font-bold leading-tight" style="color:#15803d;">+₹${Math.round(d.txn.amount)}</p>`;
                    } else if (d.delivery && d.inMonth) {
                        if (d.delivery.status === 'pending') {
                            bg = '#eff6ff'; border = '#93c5fd';
                            inner = `<i class="fa-solid fa-clock text-[9px]" style="color:#3b82f6;"></i>
                                     <p class="text-[8px] leading-tight font-semibold" style="color:#1d4ed8;">${d.delivery.qty.toFixed(1)}L</p>
                                     <p class="text-[8px] leading-tight" style="color:#3b82f6;">Pending</p>`;
                        } else if (d.delivery.status === 'skipped') {
                            bg = '#f3f4f6'; border = '#d1d5db';
                            inner = `<i class="fa-solid fa-ban text-[9px]" style="color:#9ca3af;"></i>
                                     <p class="text-[8px] leading-tight" style="color:#6b7280;">Skipped</p>`;
                        } else if (d.delivery.status === 'failed') {
                            inner = `<i class="fa-solid fa-circle-xmark text-[9px]" style="color:#ef4444;"></i>
                                     <p class="text-[8px] leading-tight" style="color:#dc2626;">Failed</p>`;
                        } else if (d.delivery.status === 'delivered') {
                            bg = '#fef3c7'; border = '#d97706';
                            inner = `<i class="fa-solid fa-droplet text-[9px]" style="color:#d97706;"></i>
                                     <p class="text-[9px] font-bold leading-tight" style="color:#92400e;">${d.delivery.qty.toFixed(1)}L</p>`;
                        }
                    }

                    const bgStyle = bg.startsWith('linear') ? `background:${bg}` : `background:${bg}`;
                    const scale   = d.isToday ? 'transform:scale(1.05); box-shadow:0 4px 6px rgba(0,0,0,0.1);' : '';
                    return `<div class="min-h-[72px] p-1.5 rounded-lg border-2 text-center transition-all"
                                 style="${bgStyle}; border-color:${border}; ${scale}">
                                <span class="text-xs font-bold block" style="color:${dayColor};">${d.day}</span>
                                ${inner}
                            </div>`;
                }).join('');
            }

            function loadCal() {
                const label = document.getElementById('cal-month-label');
                const nextBtn = document.getElementById('cal-next-btn');
                const now = new Date();
                // Disable next if already at current month
                if (nextBtn) nextBtn.style.opacity = (calYear > now.getFullYear() || (calYear === now.getFullYear() && calMonth >= now.getMonth() + 1)) ? '0.3' : '1';
                const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                if (label) label.textContent = monthNames[calMonth - 1] + ' ' + calYear;

                fetch(`/wallet/calendar?subscription_id=${SUB_ID}&year=${calYear}&month=${calMonth}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
                })
                .then(r => r.json())
                .then(data => renderCal(data.days))
                .catch(() => {});
            }

            window.calPrev = function() {
                calMonth--;
                if (calMonth < 1) { calMonth = 12; calYear--; }
                loadCal();
            };
            window.calNext = function() {
                const now = new Date();
                if (calYear > now.getFullYear() || (calYear === now.getFullYear() && calMonth >= now.getMonth() + 1)) return;
                calMonth++;
                if (calMonth > 12) { calMonth = 1; calYear++; }
                loadCal();
            };

            loadCal();
        })();
        @endif

        // ── Wallet top-up modal ───────────────────────────────────────
        function openTopupModal(subscriptionId) {
            document.getElementById('topupSubId').value = subscriptionId;
            document.getElementById('topupForm').action = '/wallet/' + subscriptionId + '/topup';
            const m = document.getElementById('topupModal');
            m.classList.remove('hidden'); m.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeTopupModal() {
            const m = document.getElementById('topupModal');
            m.classList.add('hidden'); m.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        
        document.getElementById('topupModal')?.addEventListener('click', function(e) { if (e.target === this) closeTopupModal(); });

        // ── Saved address selection ───────────────────────────────────
        document.querySelectorAll('.saved-addr-radio').forEach(radio => {
            radio.closest('label').addEventListener('click', function() {
                document.querySelectorAll('.saved-addr-card').forEach(c => c.style.borderColor = 'var(--border)');
                this.style.borderColor = 'var(--green)';
                radio.checked = true;
                const locId = radio.dataset.location;
                const flat  = radio.dataset.flat;
                const addr  = radio.dataset.address;
                if (locId) document.getElementById('locationSelect').value = locId;
                if (flat)  document.getElementById('flatNo').value = flat;
                document.getElementById('deliveryAddress').value = addr;
                // trigger timing hint
                document.getElementById('locationSelect').dispatchEvent(new Event('change'));
            });
        });

        // ── Location search ───────────────────────────────────────────
        document.getElementById('locationSearch')?.addEventListener('input', function () {
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

        document.getElementById('locationSelect')?.addEventListener('change', function () {
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

    {{-- ===== WALLET TOP-UP MODAL ===== --}}
    <div id="topupModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden items-center justify-center p-4"
        style="backdrop-filter: blur(6px);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 relative" onclick="event.stopPropagation()">
            <button onclick="closeTopupModal()" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100">
                <i class="fa-solid fa-times text-sm" style="color: var(--muted);"></i>
            </button>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(47,74,30,0.1);">
                    <i class="fa-solid fa-arrow-up" style="color: var(--green);"></i>
                </div>
                <div>
                    <h3 class="font-bold text-base" style="color: var(--text);">Top Up Wallet</h3>
                    <p class="text-xs" style="color: var(--muted);">Add balance to continue milk deliveries</p>
                </div>
            </div>
            <form id="topupForm" method="POST" action="" class="space-y-4">
                @csrf
                <input type="hidden" id="topupSubId" name="subscription_id">
                <div>
                    <label class="block text-xs font-semibold mb-2" style="color: var(--text);">Select Amount</label>
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        @foreach([200, 500, 1000, 1500, 2000, 3000] as $amt)
                        <button type="button" onclick="setTopupAmount({{ $amt }})"
                            class="topup-preset py-2 rounded-xl text-sm font-semibold border-2 transition-all"
                            style="border-color: var(--border); color: var(--muted);">
                            ₹{{ number_format($amt) }}
                        </button>
                        @endforeach
                    </div>
                    <input type="number" name="amount" id="topupAmount" placeholder="Or enter custom amount"
                        min="50" max="50000" step="1" required
                        class="w-full px-3 py-2.5 text-sm border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        style="border-color: var(--border);">
                    <p class="text-[10px] mt-1" style="color: var(--muted);">Min ₹50 · Max ₹50,000</p>
                </div>
                <button type="submit" class="w-full py-3 rounded-xl font-bold text-sm text-white transition-all hover:shadow-lg"
                    style="background: var(--green);">
                    <i class="fa-solid fa-lock mr-1"></i> Pay & Top Up
                </button>
            </form>
        </div>
    </div>

    <script>
        function setTopupAmount(amt) {
            document.getElementById('topupAmount').value = amt;
            document.querySelectorAll('.topup-preset').forEach(b => {
                const isActive = parseInt(b.textContent.replace(/[^0-9]/g, '')) === amt;
                b.style.background = isActive ? 'var(--green)' : '';
                b.style.color = isActive ? '#fff' : 'var(--muted)';
                b.style.borderColor = isActive ? 'var(--green)' : 'var(--border)';
            });
        }



        // ── Wallet Init form JS ───────────────────────────────────────
        let wiQty = 1;

        function wiSetQty(val) {
            wiQty = val;
            document.getElementById('wi-qty-input').value = wiQty;
            document.querySelectorAll('.wi-qty-btn').forEach(b => {
                const active = parseInt(b.dataset.qty) === wiQty;
                b.style.background  = active ? 'var(--green)' : '';
                b.style.color       = active ? '#fff' : 'var(--muted)';
                b.style.borderColor = active ? 'var(--green)' : 'var(--border)';
            });
            wiUpdatePreview();
        }

        function wiGetPpl() {
            const r = document.querySelector('.wi-milk-radio:checked');
            return r ? parseFloat(r.dataset.ppl) || 0 : 0;
        }

        function wiUpdatePreview() {
            const ppl   = wiGetPpl();
            const daily = ppl * wiQty;
            const pplEl = document.getElementById('wi-ppl');
            const dayEl = document.getElementById('wi-daily');
            const prev  = document.getElementById('wi-preview');
            if (pplEl) pplEl.textContent = ppl ? '₹' + ppl.toFixed(2) + '/L' : '—';
            if (dayEl) dayEl.textContent = daily ? '₹' + daily.toFixed(2) + '/day' : '—';
            if (prev)  ppl ? prev.classList.remove('hidden') : prev.classList.add('hidden');
            // step 3 days preview
            const amount = parseFloat(document.getElementById('wi-amount')?.value) || 0;
            const daysEl = document.getElementById('wi-days-preview');
            if (daysEl && daily && amount) {
                daysEl.textContent = '≈ ' + Math.floor(amount / daily) + ' days of delivery';
                daysEl.classList.remove('hidden');
            } else if (daysEl) {
                daysEl.classList.add('hidden');
            }
        }

        // Wizard step navigation
        function wiGoStep(n) {
            if (n === 2) {
                // no validation needed for step 1 — milk/qty/slot all have defaults
            }
            if (n === 3) {
                const loc  = document.getElementById('wi-location-select')?.value;
                const addr = document.getElementById('wi-address')?.value.trim();
                if (!loc)  { alert('Please select your delivery location.'); return; }
                if (!addr) { alert('Please enter your delivery address.'); return; }
                const flat = document.getElementById('wi-flat-no')?.value.trim();
                const addrEl = document.getElementById('wi-address');
                if (flat && addrEl && !addrEl.value.includes(flat)) {
                    addrEl.value = flat + ', ' + addrEl.value;
                }
                // populate step 3 summary
                const milkR = document.querySelector('.wi-milk-radio:checked');
                const milkLabels = { cow:'Cow Milk (A2)', buffalo:'Buffalo Milk', toned:'Toned Milk', full_fat:'Full Fat Milk' };
                const ppl = wiGetPpl();
                document.getElementById('wi-sum-milk').textContent  = milkLabels[milkR?.value] || milkR?.value || '—';
                document.getElementById('wi-sum-qty').textContent   = wiQty + 'L';
                document.getElementById('wi-sum-ppl').textContent   = ppl ? '₹' + ppl.toFixed(2) + '/L' : '—';
                document.getElementById('wi-sum-daily').textContent = ppl ? '₹' + (ppl * wiQty).toFixed(2) + '/day' : '—';
            }
            [1,2,3].forEach(i => {
                const el = document.getElementById('wi-step-' + i);
                if (el) el.classList.toggle('hidden', i !== n);
                const dot = document.getElementById('wi-dot-' + i);
                const lbl = document.getElementById('wi-lbl-' + i);
                if (dot) {
                    if (i < n)      { dot.style.background='var(--green)'; dot.style.color='#fff'; dot.innerHTML='<i class="fa-solid fa-check text-[9px]"></i>'; }
                    else if (i===n) { dot.style.background='var(--green)'; dot.style.color='#fff'; dot.textContent=i; }
                    else            { dot.style.background='#e5e7eb'; dot.style.color='#9ca3af'; dot.textContent=i; }
                }
                if (lbl) lbl.style.color = i <= n ? 'var(--green)' : 'var(--muted)';
            });
        }

        // milk card highlight + preview update
        document.querySelectorAll('.wi-milk-radio').forEach(r => {
            r.addEventListener('change', () => {
                document.querySelectorAll('.wi-milk-card').forEach(c => c.style.borderColor = 'var(--border)');
                r.closest('label').style.borderColor = 'var(--green)';
                wiUpdatePreview();
            });
        });
        const firstWiMilk = document.querySelector('.wi-milk-radio:checked');
        if (firstWiMilk) firstWiMilk.closest('label').style.borderColor = 'var(--green)';

        // slot card highlight
        document.querySelectorAll('.wi-slot-radio').forEach(r => {
            r.addEventListener('change', () => {
                document.querySelectorAll('.wi-slot-card').forEach(c => c.style.borderColor = 'var(--border)');
                r.closest('label').style.borderColor = 'var(--green)';
            });
        });
        const firstWiSlot = document.querySelector('.wi-slot-radio:checked');
        if (firstWiSlot) firstWiSlot.closest('label').style.borderColor = 'var(--green)';

        // saved address selection
        document.querySelectorAll('.wi-saved-radio').forEach(radio => {
            radio.closest('label').addEventListener('click', function() {
                document.querySelectorAll('.wi-saved-card').forEach(c => c.style.borderColor = 'var(--border)');
                this.style.borderColor = 'var(--green)';
                radio.checked = true;
                if (radio.dataset.location) document.getElementById('wi-location-select').value = radio.dataset.location;
                if (radio.dataset.flat)     document.getElementById('wi-flat-no').value = radio.dataset.flat;
                document.getElementById('wi-address').value = radio.dataset.address;
                document.getElementById('wi-location-select').dispatchEvent(new Event('change'));
            });
        });

        // amount preset buttons
        function wiSetAmount(amt) {
            const inp = document.getElementById('wi-amount');
            if (inp) { inp.value = amt; wiUpdatePreview(); }
            document.querySelectorAll('.wi-amt-preset').forEach(b => {
                const v = parseInt(b.textContent.replace(/[^0-9]/g, ''));
                b.style.background  = v === amt ? 'var(--green)' : '';
                b.style.color       = v === amt ? '#fff' : 'var(--muted)';
                b.style.borderColor = v === amt ? 'var(--green)' : 'var(--border)';
            });
        }
        document.getElementById('wi-amount')?.addEventListener('input', wiUpdatePreview);

        // location search
        document.getElementById('wi-loc-search')?.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            const sel  = document.getElementById('wi-location-select');
            sel.querySelectorAll('option').forEach(o => {
                if (!o.value) return;
                const txt = [o.dataset.name, o.dataset.area, o.dataset.city].join(' ');
                o.style.display = txt.includes(term) ? '' : 'none';
            });
            if (!term) sel.value = '';
            setTimeout(() => {
                const vis = Array.from(sel.querySelectorAll('option')).filter(o => o.value && o.style.display !== 'none');
                if (vis.length === 1) { sel.value = vis[0].value; sel.dispatchEvent(new Event('change')); }
            }, 100);
        });

        document.getElementById('wi-location-select')?.addEventListener('change', function() {
            const opt    = this.options[this.selectedIndex];
            const timing = opt?.dataset?.timing;
            const hint   = document.getElementById('wi-timing-hint');
            if (timing && this.value) {
                document.getElementById('wi-timing-text').textContent = 'Delivery timing: ' + timing;
                hint.classList.remove('hidden');
            } else {
                hint.classList.add('hidden');
            }
        });

        function wiSubmit() {
            const amount = document.getElementById('wi-amount')?.value;
            if (!amount || parseFloat(amount) < 1) { alert('Please enter a valid amount (min ₹1).'); return; }
            document.getElementById('walletInitForm').submit();
        }

        wiSetQty(1);
    </script>

@endsection
