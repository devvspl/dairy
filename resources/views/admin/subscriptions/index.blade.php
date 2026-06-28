@extends('layouts.app')

@section('title', 'User Subscriptions')
@section('page-title', 'User Subscriptions')

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4" style="border-color: var(--green);">
        <p class="font-semibold" style="color: var(--green);">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $allSubs = \App\Models\UserSubscription::query();
        @endphp
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Total</p>
            <p class="text-3xl font-bold" style="color: var(--text);">{{ (clone $allSubs)->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Active</p>
            <p class="text-3xl font-bold" style="color: var(--green);">{{ (clone $allSubs)->where('status','active')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ (clone $allSubs)->where('status','pending')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Expired / Cancelled</p>
            <p class="text-3xl font-bold text-red-500">{{ (clone $allSubs)->whereIn('status',['expired','cancelled'])->count() }}</p>
        </div>
    </div>

    <!-- Duplicate Credits Alert -->
    <div id="duplicateCreditsSection" class="hidden">
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden" style="border-color: #fca5a5;">
            <div class="flex items-center justify-between px-4 py-3" style="background: #fef2f2; border-bottom: 1px solid #fca5a5;">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                    <span class="text-sm font-bold text-red-700">Duplicate Credits Detected</span>
                    <span id="duplicateCount" class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-700"></span>
                </div>
                <button type="button" onclick="document.getElementById('duplicateCreditsSection').classList.add('hidden')"
                    class="text-red-400 hover:text-red-600 text-sm"><i class="fa-solid fa-times"></i></button>
            </div>
            <div id="duplicateList" class="divide-y" style="border-color: var(--border);"></div>
        </div>
    </div>

    <!-- Pending Payments Alert -->
    <div id="pendingPaymentsSection" class="hidden">
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden" style="border-color: #fde68a;">
            <div class="flex items-center justify-between px-4 py-3" style="background: #fffbeb; border-bottom: 1px solid #fde68a;">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-clock text-yellow-600"></i>
                    <span class="text-sm font-bold text-yellow-700">Pending Payments</span>
                    <span id="pendingPaymentsCount" class="text-xs font-semibold px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700"></span>
                </div>
                <button type="button" onclick="document.getElementById('pendingPaymentsSection').classList.add('hidden')"
                    class="text-yellow-400 hover:text-yellow-600 text-sm"><i class="fa-solid fa-times"></i></button>
            </div>
            <div id="pendingPaymentsList" class="divide-y" style="border-color: var(--border);"></div>
        </div>
    </div>

    <!-- Filters + Export Actions -->
    <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}" id="filterForm"
              class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium mb-1" style="color: var(--muted);">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Name or email"
                       class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
            </div>
            <div class="min-w-[140px]">
                <label class="block text-xs font-medium mb-1" style="color: var(--muted);">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                    <option value="">All Statuses</option>
                    @foreach(['pending','active','paused','cancelled','expired'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium mb-1" style="color: var(--muted);">Payment</label>
                <select name="payment_status" class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                    <option value="">All Payments</option>
                    @foreach(['pending','paid','failed'] as $p)
                    <option value="{{ $p }}" {{ request('payment_status') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium mb-1" style="color: var(--muted);">Location</label>
                <select name="location_id" class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                    <option value="">All Locations</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->name }}{{ $loc->area ? ' - '.$loc->area : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-xs font-medium mb-1" style="color: var(--muted);">Frequency</label>
                <select name="delivery_frequency" class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                    <option value="">All Frequencies</option>
                    @foreach(['daily'=>'Daily','alternate'=>'Alternate Days','weekly'=>'Weekly','monthly'=>'Monthly'] as $fVal => $fLabel)
                    <option value="{{ $fVal }}" {{ request('delivery_frequency') === $fVal ? 'selected' : '' }}>{{ $fLabel }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-sm" style="background-color: var(--green); color: #fff;">
                <i class="fa-solid fa-filter mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.subscriptions.index') }}" class="px-4 py-2 rounded-lg border font-semibold text-sm" style="border-color: var(--border); color: var(--text);">
                Clear
            </a>
            <div class="ml-auto flex gap-2">
                <button type="button" onclick="openAllPaymentsPanel()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-semibold text-sm text-white"
                        style="background-color: #7c3aed;">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>All Payments</span>
                </button>
                <button type="button" onclick="generateExport()"
                        id="exportBtn"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-semibold text-sm text-white"
                        style="background-color: #1D6F42;">
                    <i class="fa-solid fa-file-excel" id="exportIcon"></i>
                    <span id="exportBtnText">Export Excel</span>
                </button>
                <button type="button" onclick="openExportsPanel()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-semibold text-sm border"
                        style="border-color: #1D6F42; color: #1D6F42;">
                    <i class="fa-solid fa-folder-open"></i> Exports
                </button>
            </div>
        </form>
    </div>

    <!-- Subscriptions Table -->
    <div class="space-y-4">
        @forelse($subscriptions as $userId => $userSubs)
        @php $user = $userSubs->first()->user; @endphp
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden" style="border-color: var(--border);">
            <!-- Member Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b"
                 style="background-color: rgba(47,74,30,0.06); border-color: var(--border);">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                         style="background-color: var(--green);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-sm" style="color: var(--text);">{{ $user->name }}</div>
                        <div class="text-xs" style="color: var(--muted);">
                            {{ $user->email }}
                            @if($user->phone) &bull; {{ $user->phone }} @endif
                        </div>
                    </div>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-white border" style="border-color: var(--border); color: var(--muted);">
                    {{ $userSubs->count() }} subscription{{ $userSubs->count() > 1 ? 's' : '' }}
                </span>
            </div>
            <!-- Subscriptions for this member -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="text-xs" style="background-color: #fafafa;">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold" style="color: var(--muted);">ID</th>
                            <th class="px-4 py-2 text-left font-semibold" style="color: var(--muted);">Plan</th>
                            <th class="px-4 py-2 text-left font-semibold" style="color: var(--muted);">Location</th>
                            <th class="px-4 py-2 text-left font-semibold" style="color: var(--muted);">Duration</th>
                            <th class="px-4 py-2 text-left font-semibold" style="color: var(--muted);">Status</th>
                            <th class="px-4 py-2 text-left font-semibold" style="color: var(--muted);">Payment</th>
                            <th class="px-4 py-2 text-left font-semibold" style="color: var(--muted);">Amount</th>
                            <th class="px-4 py-2 text-left font-semibold" style="color: var(--muted);"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userSubs as $subscription)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm" style="color: var(--muted);">#{{ $subscription->id }}</td>
                            <td class="px-4 py-3 text-sm font-medium" style="color: var(--text);">
                                {{ $subscription->membershipPlan->name ?? 'Milk Wallet' }}
                                @php
                                    $subDs = $subscription->deliverySettings;
                                    $subFreqVal = $subDs ? ($subDs->delivery_frequency ?? 'daily') : 'daily';
                                    $subFreqBadge = match($subFreqVal) {
                                        'alternate' => 'Alternate',
                                        'weekly' => 'Weekly' . ($subDs && $subDs->preferred_day !== null && $subDs->preferred_day <= 6 ? ' (' . ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][$subDs->preferred_day] . ')' : ''),
                                        'monthly' => 'Monthly' . ($subDs && $subDs->preferred_day !== null ? ' (Day ' . $subDs->preferred_day . ')' : ''),
                                        default => null,
                                    };
                                @endphp
                                @if($subFreqBadge)
                                <div class="text-[10px] font-semibold mt-0.5" style="color: var(--green);">
                                    <i class="fa-solid fa-calendar-week mr-0.5"></i>{{ $subFreqBadge }}
                                </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($subscription->location)
                                    <div class="font-medium" style="color: var(--text);">{{ $subscription->location->name }}</div>
                                    @if($subscription->location->area)
                                    <div class="text-xs" style="color: var(--muted);">{{ $subscription->location->area }}</div>
                                    @endif
                                @else
                                    <span style="color: var(--muted);">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs" style="color: var(--muted);">{{ $subscription->start_date->format('d M Y') }}</div>
                                <div class="text-xs" style="color: var(--muted);">to {{ $subscription->end_date->format('d M Y') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full font-semibold
                                    {{ $subscription->status === 'active'    ? 'bg-green-100 text-green-800'  : '' }}
                                    {{ $subscription->status === 'pending'   ? 'bg-yellow-100 text-yellow-800': '' }}
                                    {{ $subscription->status === 'paused'    ? 'bg-blue-100 text-blue-800'    : '' }}
                                    {{ $subscription->status === 'cancelled' ? 'bg-red-100 text-red-800'      : '' }}
                                    {{ $subscription->status === 'expired'   ? 'bg-gray-100 text-gray-800'    : '' }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full font-semibold
                                    {{ $subscription->payment_status === 'paid'     ? 'bg-green-100 text-green-800'  : '' }}
                                    {{ $subscription->payment_status === 'pending'  ? 'bg-yellow-100 text-yellow-800': '' }}
                                    {{ $subscription->payment_status === 'failed'   ? 'bg-red-100 text-red-800'      : '' }}
                                    {{ $subscription->payment_status === 'refunded' ? 'bg-gray-100 text-gray-800'    : '' }}">
                                    {{ ucfirst($subscription->payment_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold" style="color: var(--green);">
                                ₹{{ number_format($subscription->amount_paid ?? $subscription->membershipPlan?->price ?? 0, 2) }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.subscriptions.show', $subscription) }}"
                                   class="text-sm font-semibold hover:underline" style="color: var(--green);">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm border p-8 text-center" style="border-color: var(--border); color: var(--muted);">
            No subscriptions found.
        </div>
        @endforelse
    </div>
</div>

<!-- Exports Offcanvas -->
<div id="exportsPanel" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-40" onclick="closeExportsPanel()"></div>
    <div id="exportsPanelDrawer"
         class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col"
         style="transform: translateX(100%); transition: transform 0.3s ease;">
        <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color: var(--border); background-color: #2F4A1E;">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-folder-open text-white"></i>
                <h3 class="font-bold text-white text-base">Generated Exports</h3>
            </div>
            <button onclick="closeExportsPanel()" class="text-white hover:text-gray-200 text-xl leading-none">&times;</button>
        </div>
        <div class="flex-1 overflow-y-auto p-4" id="exportsPanelBody">
            <div class="flex items-center justify-center h-32 text-gray-400 text-sm" id="exportsLoading">
                <i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading...
            </div>
            <div id="exportsList" class="space-y-3 hidden"></div>
            <div id="exportsEmpty" class="hidden text-center py-10 text-gray-400 text-sm">
                <i class="fa-solid fa-file-excel text-3xl mb-2 block" style="color: #1D6F42;"></i>
                No exports generated yet.
            </div>
        </div>
        <div class="px-5 py-3 border-t text-xs text-gray-400" style="border-color: var(--border);">
            Showing last 30 exports &bull; <code>storage/exports/subscriptions/</code>
        </div>
    </div>
</div>

<!-- All Payments Offcanvas -->
<div id="allPaymentsPanel" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-40" onclick="closeAllPaymentsPanel()"></div>
    <div id="allPaymentsPanelDrawer"
         class="absolute top-0 right-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col"
         style="transform: translateX(100%); transition: transform 0.3s ease;">
        <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color: var(--border); background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-white"></i>
                <h3 class="font-bold text-white text-base">All Payment Transactions</h3>
            </div>
            <button onclick="closeAllPaymentsPanel()" class="text-white hover:text-gray-200 text-xl leading-none">&times;</button>
        </div>
        {{-- Filters --}}
        <div class="px-5 py-3 border-b flex flex-wrap gap-2 items-center" style="border-color: var(--border); background: #faf5ff;">
            <select id="ap-status-filter" onchange="loadAllPayments()" class="px-3 py-1.5 border rounded-lg text-xs" style="border-color: var(--border);">
                <option value="">All Status</option>
                <option value="success">Success</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
            </select>
            <input type="text" id="ap-search-filter" onkeyup="debounceLoadPayments()" placeholder="Search name, phone, order..."
                class="px-3 py-1.5 border rounded-lg text-xs min-w-[160px]" style="border-color: var(--border);">
            <span id="ap-total-label" class="ml-auto text-xs font-semibold" style="color: var(--muted);"></span>
        </div>
        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-4" id="allPaymentsBody">
            <div class="flex items-center justify-center h-32 text-gray-400 text-sm" id="apLoading">
                <i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading...
            </div>
            <div id="apList" class="space-y-2 hidden"></div>
            <div id="apEmpty" class="hidden text-center py-10 text-gray-400 text-sm">
                <i class="fa-solid fa-credit-card text-3xl mb-2 block" style="color: #7c3aed;"></i>
                No payments found.
            </div>
        </div>
        <div class="px-5 py-3 border-t flex items-center justify-between" style="border-color: var(--border);">
            <span class="text-xs text-gray-400">Last 200 transactions</span>
            <div id="ap-summary" class="text-xs font-semibold" style="color: var(--green);"></div>
        </div>
    </div>
</div>

<script>
const EXPORT_URL       = '{{ route('admin.subscriptions.export') }}';
const EXPORTS_LIST_URL = '{{ route('admin.subscriptions.exports.list') }}';
const ALL_PAYMENTS_URL = '{{ route('admin.subscriptions.all-payments') }}';
const CSRF_TOKEN       = '{{ csrf_token() }}';

function getFilters() {
    const form = document.getElementById('filterForm');
    const data = new FormData(form);
    const out  = {};
    for (const [k, v] of data.entries()) { if (v) out[k] = v; }
    return out;
}

function generateExport() {
    const btn     = document.getElementById('exportBtn');
    const icon    = document.getElementById('exportIcon');
    const btnText = document.getElementById('exportBtnText');

    btn.disabled = true;
    icon.className = 'fa-solid fa-spinner fa-spin';
    btnText.textContent = 'Generating...';

    fetch(EXPORT_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify(getFilters()),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const a = document.createElement('a');
            a.href = data.download_url;
            a.download = data.filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
        } else {
            alert('Export failed. Please try again.');
        }
    })
    .catch(() => alert('Export failed. Please try again.'))
    .finally(() => {
        btn.disabled = false;
        icon.className = 'fa-solid fa-file-excel';
        btnText.textContent = 'Export Excel';
    });
}

function openExportsPanel() {
    const panel  = document.getElementById('exportsPanel');
    const drawer = document.getElementById('exportsPanelDrawer');
    panel.classList.remove('hidden');
    setTimeout(() => drawer.style.transform = 'translateX(0)', 10);
    loadExports();
}

function closeExportsPanel() {
    const drawer = document.getElementById('exportsPanelDrawer');
    drawer.style.transform = 'translateX(100%)';
    setTimeout(() => document.getElementById('exportsPanel').classList.add('hidden'), 300);
}

function loadExports() {
    document.getElementById('exportsLoading').classList.remove('hidden');
    document.getElementById('exportsList').classList.add('hidden');
    document.getElementById('exportsEmpty').classList.add('hidden');

    fetch(EXPORTS_LIST_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(data => {
        document.getElementById('exportsLoading').classList.add('hidden');
        const list = document.getElementById('exportsList');

        if (!data.exports || data.exports.length === 0) {
            document.getElementById('exportsEmpty').classList.remove('hidden');
            return;
        }

        list.innerHTML = data.exports.map(e => `
            <div class="rounded-lg border overflow-hidden" id="export-row-${e.id}"
                 style="border-color: ${e.exists ? '#BBF7D0' : '#FECACA'};">
                <div class="flex items-center gap-2 px-3 py-2"
                     style="background: ${e.exists ? '#F0FDF4' : '#FFF5F5'}; border-bottom: 1px solid ${e.exists ? '#BBF7D0' : '#FECACA'};">
                    <i class="fa-solid fa-file-excel text-sm flex-shrink-0" style="color: #1D6F42;"></i>
                    <span class="text-xs font-semibold truncate flex-1" style="color: #111827;" title="${e.filename}">${e.filename}</span>
                    ${!e.exists ? `<span class="text-xs font-medium text-red-500 flex-shrink-0">Missing</span>` : ''}
                </div>
                <div class="px-3 py-2 bg-white grid grid-cols-2 gap-x-4 gap-y-1">
                    <span class="text-xs" style="color:#6B7280;"><i class="fa-solid fa-tag w-3 mr-1"></i>Filter: <strong style="color:#111827;">${e.filter_status}</strong></span>
                    <span class="text-xs" style="color:#6B7280;"><i class="fa-solid fa-list-ol w-3 mr-1"></i>Rows: <strong style="color:#111827;">${e.row_count}</strong></span>
                    <span class="text-xs" style="color:#6B7280;"><i class="fa-solid fa-database w-3 mr-1"></i>Size: <strong style="color:#111827;">${e.file_size}</strong></span>
                    <span class="text-xs" style="color:#6B7280;"><i class="fa-solid fa-user w-3 mr-1"></i>${e.generated_by}</span>
                </div>
                <div class="flex items-center justify-between gap-2 px-3 py-2 border-t" style="border-color:#E5E7EB; background:#FAFAFA;">
                    <span class="text-xs" style="color:#9CA3AF;"><i class="fa-regular fa-clock mr-1"></i>${e.created_at}</span>
                    <div class="flex items-center gap-2">
                        ${e.exists ? `
                        <a href="${e.download_url}" download="${e.filename}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold text-white"
                           style="background-color:#1D6F42;">
                            <i class="fa-solid fa-download"></i> Download
                        </a>` : ''}
                        <button onclick="deleteExport(${e.id})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold border"
                                style="border-color:#FECACA; color:#DC2626; background:#FFF5F5;">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        `).join('');

        list.classList.remove('hidden');
    })
    .catch(() => {
        document.getElementById('exportsLoading').classList.add('hidden');
        document.getElementById('exportsList').innerHTML =
            '<p class="text-sm text-red-500 text-center">Failed to load exports.</p>';
        document.getElementById('exportsList').classList.remove('hidden');
    });
}

function deleteExport(id) {
    if (!confirm('Delete this export file?')) return;
    fetch(`/admin/subscriptions/exports/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`export-row-${id}`)?.remove();
            if (!document.getElementById('exportsList').children.length) {
                document.getElementById('exportsList').classList.add('hidden');
                document.getElementById('exportsEmpty').classList.remove('hidden');
            }
        }
    });
}

// ── All Payments Panel ───────────────────────────────────────────
function openAllPaymentsPanel() {
    const panel  = document.getElementById('allPaymentsPanel');
    const drawer = document.getElementById('allPaymentsPanelDrawer');
    panel.classList.remove('hidden');
    setTimeout(() => drawer.style.transform = 'translateX(0)', 10);
    loadAllPayments();
}

function closeAllPaymentsPanel() {
    const drawer = document.getElementById('allPaymentsPanelDrawer');
    drawer.style.transform = 'translateX(100%)';
    setTimeout(() => document.getElementById('allPaymentsPanel').classList.add('hidden'), 300);
}

let apDebounceTimer = null;
function debounceLoadPayments() {
    clearTimeout(apDebounceTimer);
    apDebounceTimer = setTimeout(loadAllPayments, 400);
}

function loadAllPayments() {
    document.getElementById('apLoading').classList.remove('hidden');
    document.getElementById('apList').classList.add('hidden');
    document.getElementById('apEmpty').classList.add('hidden');

    const status = document.getElementById('ap-status-filter').value;
    const search = document.getElementById('ap-search-filter').value;
    const params = new URLSearchParams();
    if (status) params.set('status', status);
    if (search) params.set('search', search);

    fetch(ALL_PAYMENTS_URL + '?' + params.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('apLoading').classList.add('hidden');

        if (!data.success || data.payments.length === 0) {
            document.getElementById('apEmpty').classList.remove('hidden');
            document.getElementById('ap-total-label').textContent = '0 transactions';
            document.getElementById('ap-summary').textContent = '';
            return;
        }

        document.getElementById('ap-total-label').textContent = data.payments.length + ' transaction(s) · Total: ₹' + data.total_amount.toLocaleString('en-IN', {minimumFractionDigits:2});
        document.getElementById('ap-summary').innerHTML = `<span style="color:var(--green);">Success: ₹${data.success_amount.toLocaleString('en-IN')}</span> · <span style="color:#d97706;">Pending: ₹${data.pending_amount.toLocaleString('en-IN')}</span>`;

        const list = document.getElementById('apList');
        list.innerHTML = data.payments.map(p => {
            const statusColor = p.status === 'success' ? '#16a34a' : p.status === 'pending' ? '#d97706' : '#dc2626';
            const statusBg = p.status === 'success' ? '#f0fdf4' : p.status === 'pending' ? '#fffbeb' : '#fef2f2';
            return `
            <div class="flex items-center gap-3 p-3 rounded-lg border hover:shadow-sm transition-shadow" style="border-color: var(--border); background: ${statusBg};">
                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0" style="background: ${statusBg}; border: 2px solid ${statusColor};">
                    <i class="fa-solid ${p.status === 'success' ? 'fa-check' : p.status === 'pending' ? 'fa-clock' : 'fa-times'} text-xs" style="color: ${statusColor};"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-bold" style="color:var(--text);">${p.user_name}</span>
                        <span class="text-xs" style="color:var(--muted);">${p.user_phone}</span>
                        <span class="px-1.5 py-0.5 text-[10px] rounded-full font-bold" style="background:${statusBg};color:${statusColor};border:1px solid ${statusColor};">${p.status.toUpperCase()}</span>
                    </div>
                    <div class="flex items-center gap-3 mt-0.5 text-[11px]" style="color:var(--muted);">
                        <span class="font-mono">${p.order_id}</span>
                        <span>${p.date}</span>
                        <span>${p.time}</span>
                        ${p.subscription_id ? '<span>Sub #' + p.subscription_id + '</span>' : '<span class="text-purple-600">New Wallet</span>'}
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold" style="color: ${statusColor};">₹${p.amount.toLocaleString('en-IN', {minimumFractionDigits:2})}</p>
                </div>
            </div>`;
        }).join('');

        list.classList.remove('hidden');
    })
    .catch(() => {
        document.getElementById('apLoading').classList.add('hidden');
        document.getElementById('apList').innerHTML = '<p class="text-sm text-red-500 text-center">Failed to load payments.</p>';
        document.getElementById('apList').classList.remove('hidden');
    });
}

// ── Duplicate Credits Detection ──────────────────────────────────
(function() {
    fetch('{{ route("admin.subscriptions.duplicate-credits") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success || data.count === 0) return;

        document.getElementById('duplicateCreditsSection').classList.remove('hidden');
        document.getElementById('duplicateCount').textContent = data.count + ' issue' + (data.count > 1 ? 's' : '');

        const list = document.getElementById('duplicateList');
        list.innerHTML = data.duplicates.map(d => `
            <div class="flex items-center justify-between px-4 py-3 hover:bg-red-50" id="dup-${d.order_id}">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-bold" style="color:var(--text);">${d.user_name}</span>
                        <span class="text-xs" style="color:var(--muted);">${d.user_phone}</span>
                        <span class="text-xs font-mono px-1.5 py-0.5 rounded bg-gray-100" style="color:var(--muted);">Sub #${d.subscription_id}</span>
                    </div>
                    <div class="flex items-center gap-3 mt-1 text-xs" style="color:var(--muted);">
                        <span><i class="fa-solid fa-receipt mr-0.5"></i>Order: <strong class="text-gray-700">${d.order_id}</strong></span>
                        <span><i class="fa-solid fa-copy mr-0.5 text-red-500"></i><strong class="text-red-600">${d.total_credits}x</strong> credited (${d.extra_credits} extra)</span>
                        <span><i class="fa-solid fa-indian-rupee-sign mr-0.5"></i>Extra: <strong class="text-red-600">₹${d.extra_amount.toLocaleString('en-IN')}</strong></span>
                    </div>
                    <div class="text-[10px] mt-0.5" style="color:var(--muted);">
                        First: ${d.first_credited_at} · Last: ${d.last_credited_at}
                    </div>
                </div>
                <button onclick="fixDuplicate('${d.order_id}', ${d.subscription_id}, this)"
                    class="flex-shrink-0 px-3 py-2 rounded-lg text-xs font-bold text-white transition-all hover:opacity-90"
                    style="background:#dc2626;">
                    <i class="fa-solid fa-wrench mr-1"></i>Fix (Reverse ₹${d.extra_amount.toLocaleString('en-IN')})
                </button>
            </div>
        `).join('');
    })
    .catch(() => {});
})();

function fixDuplicate(orderId, subscriptionId, btn) {
    if (!confirm('This will reverse the duplicate credit and deduct ₹ from the wallet. Continue?')) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Fixing...';

    fetch('{{ route("admin.subscriptions.fix-duplicate-credit") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ order_id: orderId, subscription_id: subscriptionId }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('dup-' + orderId);
            if (row) {
                row.innerHTML = `
                    <div class="flex items-center gap-2 px-4 py-3 w-full">
                        <i class="fa-solid fa-circle-check text-green-600"></i>
                        <span class="text-sm font-semibold text-green-700">${data.message}</span>
                        <span class="text-xs ml-auto" style="color:var(--muted);">New balance: ₹${data.new_balance.toLocaleString('en-IN', {minimumFractionDigits:2})}</span>
                    </div>
                `;
            }
        } else {
            alert(data.message || 'Fix failed.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-wrench mr-1"></i>Retry';
        }
    })
    .catch(() => {
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-wrench mr-1"></i>Retry';
    });
}

// ── Pending Payments Detection ───────────────────────────────────
(function() {
    fetch('{{ route("admin.subscriptions.pending-payments") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success || data.count === 0) return;

        document.getElementById('pendingPaymentsSection').classList.remove('hidden');
        document.getElementById('pendingPaymentsCount').textContent = data.count + ' payment' + (data.count > 1 ? 's' : '');

        const list = document.getElementById('pendingPaymentsList');
        list.innerHTML = data.payments.map(p => `
            <div class="flex items-center justify-between px-4 py-3 hover:bg-yellow-50" id="pending-${p.order_id}">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-bold" style="color:var(--text);">${p.user_name}</span>
                        <span class="text-xs" style="color:var(--muted);">${p.user_phone}</span>
                        ${p.subscription_id ? `<span class="text-xs font-mono px-1.5 py-0.5 rounded bg-gray-100" style="color:var(--muted);">Sub #${p.subscription_id}</span>` : '<span class="text-xs font-mono px-1.5 py-0.5 rounded bg-blue-50 text-blue-600">New Wallet</span>'}
                    </div>
                    <div class="flex items-center gap-3 mt-1 text-xs" style="color:var(--muted);">
                        <span><i class="fa-solid fa-receipt mr-0.5"></i>${p.order_id}</span>
                        <span><i class="fa-solid fa-indian-rupee-sign mr-0.5"></i><strong class="text-yellow-700">₹${p.amount.toLocaleString('en-IN')}</strong></span>
                        <span><i class="fa-solid fa-clock mr-0.5"></i>${p.age}</span>
                    </div>
                </div>
                <div class="flex-shrink-0 flex gap-2">
                    <button onclick="verifyPending('${p.order_id}', this)"
                        class="px-3 py-2 rounded-lg text-xs font-bold text-white transition-all hover:opacity-90"
                        style="background:#d97706;">
                        <i class="fa-solid fa-rotate mr-1"></i>Verify
                    </button>
                    <button onclick="markFailed('${p.order_id}', this)"
                        class="px-3 py-2 rounded-lg text-xs font-bold transition-all hover:opacity-90 border"
                        style="border-color:#fca5a5; color:#dc2626; background:#fff5f5;">
                        <i class="fa-solid fa-times mr-1"></i>Mark Failed
                    </button>
                </div>
            </div>
        `).join('');
    })
    .catch(() => {});
})();

function verifyPending(orderId, btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Checking...';

    fetch('{{ route("admin.subscriptions.verify-pending-payment") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ order_id: orderId }),
    })
    .then(r => r.json())
    .then(data => {
        const row = document.getElementById('pending-' + orderId);
        if (data.success && data.status === 'success') {
            if (row) row.innerHTML = `
                <div class="flex items-center gap-2 w-full px-2">
                    <i class="fa-solid fa-circle-check text-green-600"></i>
                    <span class="text-sm font-semibold text-green-700">${data.message}</span>
                </div>`;
        } else if (data.success) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-clock mr-1"></i>' + (data.message || 'Not paid');
            btn.style.background = '#6b7280';
            setTimeout(() => { btn.style.background = '#d97706'; btn.innerHTML = '<i class="fa-solid fa-rotate mr-1"></i>Verify'; }, 3000);
        } else {
            alert(data.message || 'Verification failed.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-rotate mr-1"></i>Retry';
        }
    })
    .catch(() => {
        alert('Network error.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-rotate mr-1"></i>Retry';
    });
}

function markFailed(orderId, btn) {
    if (!confirm('Mark this payment as failed? This means the customer never paid.')) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>...';

    fetch('{{ route("admin.subscriptions.verify-pending-payment") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ order_id: orderId, mark_failed: true }),
    })
    .then(r => r.json())
    .then(data => {
        const row = document.getElementById('pending-' + orderId);
        if (data.success) {
            if (row) row.innerHTML = `
                <div class="flex items-center gap-2 w-full px-2">
                    <i class="fa-solid fa-circle-xmark text-gray-500"></i>
                    <span class="text-sm font-semibold text-gray-600">Marked as failed</span>
                </div>`;
        } else {
            alert(data.message || 'Failed.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-times mr-1"></i>Mark Failed';
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-times mr-1"></i>Mark Failed';
    });
}
</script>
@endsection
