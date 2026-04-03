@extends('layouts.app')

@section('title', $location->name . ' - Deliveries')
@section('page-title', $location->name)

@section('content')
<div class="space-y-5">

    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4" style="border-color: var(--green);">
        <p class="font-semibold" style="color: var(--green);">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Location Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold" style="color: var(--text);">{{ $location->name }}</h2>
            <p class="text-xs" style="color: var(--muted);">
                @if($location->area){{ $location->area }}{{ $location->city ? ', '.$location->city : '' }}@endif
                @if($location->delivery_timing) &bull; <i class="fa-solid fa-clock mr-1"></i>{{ $location->delivery_timing }}@endif
            </p>
        </div>
        <a href="{{ route('delivery.dashboard') }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border font-semibold text-sm hover:bg-gray-50"
           style="border-color: var(--border); color: var(--text);">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-white rounded-lg shadow-sm p-3 border text-center" style="border-color: var(--border);">
            <p class="text-2xl font-bold" style="color: var(--text);">{{ $stats['total'] }}</p>
            <p class="text-xs" style="color: var(--muted);">Total</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-3 border text-center" style="border-color: var(--border);">
            <p class="text-2xl font-bold" style="color: var(--green);">{{ $stats['delivered'] }}</p>
            <p class="text-xs" style="color: var(--muted);">Delivered</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-3 border text-center" style="border-color: var(--border);">
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            <p class="text-xs" style="color: var(--muted);">Pending</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-3 border text-center" style="border-color: var(--border);">
            <p class="text-2xl font-bold text-gray-500">{{ $stats['skipped'] }}</p>
            <p class="text-xs" style="color: var(--muted);">Skipped</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-3 border text-center" style="border-color: var(--border);">
            <p class="text-2xl font-bold" style="color: var(--green);">{{ $stats['quantity'] }} L</p>
            <p class="text-xs" style="color: var(--muted);">Total Qty</p>
        </div>
    </div>

    <!-- Filters + Export -->
    <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
        <form method="GET" action="{{ route('delivery.location', $location) }}" id="filterForm"
              class="flex flex-wrap gap-2 items-center">
            <input type="date" name="date" value="{{ $date }}"
                   class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
            <select name="status" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                <option value="">All Status</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="skipped"   {{ request('status') === 'skipped'   ? 'selected' : '' }}>Skipped</option>
                <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>Failed</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search name / phone..."
                   class="px-3 py-2 border rounded-lg text-sm min-w-[180px]" style="border-color: var(--border);">
            <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-sm text-white" style="background-color: var(--green);">
                <i class="fa-solid fa-filter mr-1"></i>Filter
            </button>
            <a href="{{ route('delivery.location', $location) }}"
               class="px-4 py-2 rounded-lg border font-semibold text-sm" style="border-color: var(--border); color: var(--text);">
                Today
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

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b" style="border-color: var(--border); background-color: rgba(47,74,30,0.05);">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">#</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Customer</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Address</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Milk / Plan</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Qty</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Wallet</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Time</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $i => $delivery)
                    @php
                        $sub      = $delivery->subscription;
                        $customer = $sub->user;
                        $plan     = $sub->membershipPlan;
                    @endphp
                    <tr class="border-b hover:bg-gray-50" style="border-color: var(--border);">
                        <td class="px-4 py-3 text-sm" style="color: var(--muted);">
                            {{ $deliveries->firstItem() + $i }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-sm" style="color: var(--text);">{{ $customer->name }}</div>
                            <div class="text-xs" style="color: var(--muted);">
                                <i class="fa-solid fa-phone mr-1"></i>{{ $customer->phone ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm max-w-[180px]" style="color: var(--muted);">
                            {{ $sub->delivery_address ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium" style="color: var(--text);">
                                {{ $plan?->name ?? 'Milk Wallet' }}
                            </div>
                            @if($sub->milk_type)
                            <div class="text-xs mt-0.5" style="color: var(--muted);">
                                {{ ucfirst(str_replace('_',' ',$sub->milk_type)) }}
                                @if($sub->price_per_litre) · ₹{{ number_format($sub->price_per_litre,2) }}/L @endif
                            </div>
                            @endif
                            @if($sub->delivery_slot)
                            <div class="text-xs" style="color: var(--muted);">
                                <i class="fa-solid fa-clock mr-0.5"></i>{{ ucfirst($sub->delivery_slot) }}
                            </div>
                            @endif
                            {{-- Delivery status badge --}}
                            @if($sub->delivery_status !== 'active')
                            <span class="inline-block mt-1 px-1.5 py-0.5 text-[10px] rounded-full font-semibold
                                {{ $sub->delivery_status === 'paused'  ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $sub->delivery_status === 'stopped' ? 'bg-red-100 text-red-700'       : '' }}">
                                <i class="fa-solid {{ $sub->delivery_status === 'paused' ? 'fa-pause' : 'fa-stop' }} mr-0.5"></i>
                                {{ ucfirst($sub->delivery_status) }}
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold" style="color: var(--green);">
                            {{ $delivery->quantity_delivered }} L
                        </td>
                        <td class="px-4 py-3">
                            @if($sub->wallet_balance !== null)
                            <div class="text-sm font-semibold" style="color: var(--green);">₹{{ number_format($sub->wallet_balance,2) }}</div>
                            <div class="text-[10px]" style="color: var(--muted);">of ₹{{ number_format($sub->wallet_total,2) }}</div>
                            @else
                            <span style="color: var(--muted);">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full font-semibold
                                {{ $delivery->status === 'delivered' ? 'bg-green-100 text-green-800'  : '' }}
                                {{ $delivery->status === 'pending'   ? 'bg-yellow-100 text-yellow-800': '' }}
                                {{ $delivery->status === 'skipped'   ? 'bg-gray-100 text-gray-800'    : '' }}
                                {{ $delivery->status === 'failed'    ? 'bg-red-100 text-red-800'      : '' }}">
                                {{ ucfirst($delivery->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm" style="color: var(--muted);">
                            {{ $delivery->delivery_time ? \Carbon\Carbon::parse($delivery->delivery_time)->format('h:i A') : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @if(in_array($sub->delivery_status ?? 'active', ['paused', 'stopped']))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold"
                                    style="background: {{ ($sub->delivery_status === 'stopped') ? 'rgba(180,0,0,0.08)' : 'rgba(180,96,0,0.08)' }};
                                           color: {{ ($sub->delivery_status === 'stopped') ? '#b40000' : '#b46000' }};">
                                    <i class="fa-solid {{ ($sub->delivery_status === 'stopped') ? 'fa-stop' : 'fa-pause' }} text-[10px]"></i>
                                    {{ ucfirst($sub->delivery_status) }} — no delivery
                                </span>
                            @else
                                <button onclick="openModal({{ $delivery->id }}, '{{ $delivery->status }}', '{{ $delivery->quantity_delivered }}', '{{ $delivery->delivery_time }}', '{{ addslashes($delivery->notes ?? '') }}')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors hover:opacity-80"
                                        style="background: rgba(47,74,30,0.1); color: var(--green);">
                                    <i class="fa-solid {{ $delivery->status === 'pending' ? 'fa-check' : 'fa-pen-to-square' }}"></i>
                                    {{ $delivery->status === 'pending' ? 'Mark' : 'Edit' }}
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center" style="color: var(--muted);">
                            <i class="fa-solid fa-truck text-4xl mb-3 block" style="color: var(--muted);"></i>
                            No deliveries for this date / filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deliveries->hasPages())
        <div class="px-4 py-3 border-t" style="border-color: var(--border);">
            {{ $deliveries->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Update Modal -->
<div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-start justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 mt-16 mx-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold" style="color: var(--text);">Update Delivery</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="updateForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Status</label>
                    <select name="status" id="statusSelect" required
                            class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <option value="pending">Pending</option>
                        <option value="delivered">Delivered</option>
                        <option value="skipped">Skipped</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Quantity (L)</label>
                    <input type="number" step="0.5" name="quantity_delivered" id="qtyInput"
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Delivery Time</label>
                    <input type="time" name="delivery_time" id="timeInput"
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Notes</label>
                    <textarea name="notes" id="notesInput" rows="2"
                              class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);"
                              placeholder="Leave empty to use default note"></textarea>
                    <p id="notesHint" class="text-xs mt-1" style="color: var(--muted);"></p>
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="button" onclick="closeModal()"
                        class="flex-1 py-2 rounded-lg border font-semibold" style="border-color: var(--border); color: var(--text);">
                    Cancel
                </button>
                <button type="submit" class="flex-1 py-2 rounded-lg font-semibold text-white" style="background-color: var(--green);">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Exports Offcanvas -->
<div id="exportsPanel" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-40" onclick="closeExportsPanel()"></div>
    <div id="exportsPanelDrawer"
         class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col"
         style="transform: translateX(100%); transition: transform 0.3s ease;">
        <div class="flex items-center justify-between px-5 py-4 border-b" style="background-color: #2F4A1E;">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-folder-open text-white"></i>
                <h3 class="font-bold text-white text-base">Generated Exports</h3>
            </div>
            <button onclick="closeExportsPanel()" class="text-white hover:text-gray-200 text-xl leading-none">&times;</button>
        </div>
        <div class="flex-1 overflow-y-auto p-4">
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
            Showing last 30 exports &bull; <code>storage/exports/location-deliveries/</code>
        </div>
    </div>
</div>

<script>
const EXPORT_URL       = '{{ route('delivery.location.export', $location) }}';
const EXPORTS_LIST_URL = '{{ route('delivery.location.exports.list', $location) }}';
const CSRF_TOKEN       = '{{ csrf_token() }}';
const BASE_URL         = '/delivery/location/{{ $location->id }}/update/';

function getFilters() {
    const d = document.querySelector('[name=date]').value;
    const s = document.querySelector('[name=status]').value;
    const q = document.querySelector('[name=search]').value;
    const p = new URLSearchParams({ date: d });
    if (s) p.set('status', s);
    if (q) p.set('search', q);
    return p.toString();
}

function generateExport() {
    const btn     = document.getElementById('exportBtn');
    const icon    = document.getElementById('exportIcon');
    const btnText = document.getElementById('exportBtnText');

    btn.disabled = true;
    icon.className = 'fa-solid fa-spinner fa-spin';
    btnText.textContent = 'Generating...';

    fetch(EXPORT_URL + '?' + getFilters(), {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN },
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
    fetch(`/delivery/exports/${id}`, {
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

const DEFAULT_NOTES = {
    delivered : 'Delivered successfully.',
    skipped   : 'Delivery skipped.',
    failed    : 'Delivery failed.',
    pending   : 'Marked as pending.',
};

document.getElementById('statusSelect').addEventListener('change', function () {
    const note = DEFAULT_NOTES[this.value] || '';
    document.getElementById('notesInput').value = note;
    document.getElementById('notesHint').textContent = note ? '💬 Default: ' + note : '';
});

function openModal(id, status, qty, time, notes) {
    document.getElementById('statusSelect').value = status;
    document.getElementById('qtyInput').value     = qty;
    // Strip seconds from time if present (e.g. "11:35:00" → "11:35"), default to current time
    const now = new Date();
    const currentTime = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');
    document.getElementById('timeInput').value    = time ? time.substring(0, 5) : currentTime;
    // Show existing note or the default for the current status
    const note = notes || DEFAULT_NOTES[status] || '';
    document.getElementById('notesInput').value   = note;
    document.getElementById('notesHint').textContent = DEFAULT_NOTES[status] ? 'Default: ' + DEFAULT_NOTES[status] : '';
    document.getElementById('updateForm').action  = BASE_URL + id;
    const m = document.getElementById('updateModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
    m.scrollTop = 0;
}

function closeModal() {
    const m = document.getElementById('updateModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
}
</script>
@endsection
