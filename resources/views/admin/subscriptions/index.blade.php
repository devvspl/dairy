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
                    @foreach(['pending','paid','failed','refunded'] as $p)
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
            <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-sm" style="background-color: var(--green); color: #fff;">
                <i class="fa-solid fa-filter mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.subscriptions.index') }}" class="px-4 py-2 rounded-lg border font-semibold text-sm" style="border-color: var(--border); color: var(--text);">
                Clear
            </a>
            <div class="ml-auto flex gap-2">
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
                            <td class="px-4 py-3 text-sm font-medium" style="color: var(--text);">{{ $subscription->membershipPlan->name }}</td>
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
                                ₹{{ number_format($subscription->amount_paid ?? $subscription->membershipPlan->price, 2) }}
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

<script>
const EXPORT_URL       = '{{ route('admin.subscriptions.export') }}';
const EXPORTS_LIST_URL = '{{ route('admin.subscriptions.exports.list') }}';
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
</script>
@endsection
