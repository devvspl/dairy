@extends('layouts.app')

@section('title', 'Product Orders')
@section('page-title', 'Product Orders')

@section('content')
@php $srEnabled = \App\Models\ShiprocketSetting::instance()->enabled; @endphp
<div class="space-y-5">

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Total Orders</p>
            <p class="text-3xl font-bold" style="color: var(--text);">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Successful</p>
            <p class="text-3xl font-bold" style="color: var(--green);">{{ $stats['success'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Revenue</p>
            <p class="text-3xl font-bold" style="color: var(--green);">₹{{ number_format($stats['revenue'], 0) }}</p>
        </div>
    </div>

    <!-- Filters + Export -->
    <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
        <form method="GET" action="{{ route('admin.product-orders.index') }}" id="filterForm"
              class="flex flex-wrap gap-2 items-center">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Order ID / name / phone / email..."
                   class="px-3 py-2 border rounded-lg text-sm min-w-[200px]" style="border-color: var(--border);">

            <select name="status" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                <option value="">All Status</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                <option value="success"   {{ request('status') === 'success'   ? 'selected' : '' }}>Success</option>
                <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>Failed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <select name="product_id" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                <option value="">All Products</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
                @endforeach
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">

            <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-sm text-white" style="background-color: var(--green);">
                <i class="fa-solid fa-filter mr-1"></i>Filter
            </button>
            <a href="{{ route('admin.product-orders.index') }}"
               class="px-4 py-2 rounded-lg border font-semibold text-sm" style="border-color: var(--border); color: var(--text);">
                Reset
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

    <!-- Bulk Action Toolbar (hidden until rows selected) -->
    @if($srEnabled)
    <div id="bulkToolbar"
         class="hidden items-center gap-3 bg-white rounded-lg shadow-sm px-4 py-3 border"
         style="border-color: var(--green);">
        <span class="text-sm font-semibold" style="color: var(--text);">
            <span id="selectedCount">0</span> order(s) selected
        </span>
        <div class="h-4 w-px bg-gray-300"></div>
        <button onclick="bulkAssignShiprocket()"
                id="bulkAssignBtn"
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-lg text-sm font-semibold text-white"
                style="background-color: var(--green);">
            <i class="fa-solid fa-truck-fast" id="bulkAssignIcon"></i>
            <span id="bulkAssignText">Assign to Shiprocket</span>
        </button>
        <button onclick="clearSelection()"
                class="text-sm font-medium hover:underline" style="color: var(--muted);">
            Clear selection
        </button>
    </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b" style="border-color: var(--border); background-color: rgba(47,74,30,0.05);">
                    <tr>
                        @if($srEnabled)
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"
                                   class="w-4 h-4 rounded accent-green-600 cursor-pointer">
                        </th>
                        @endif
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Order ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Customer</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Items</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Amount</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Payment</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Status</th>
                        @if($srEnabled)
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Shiprocket</th>
                        @endif
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-b hover:bg-gray-50 order-row" style="border-color: var(--border);"
                        data-id="{{ $order->id }}"
                        data-assigned="{{ $order->shiprocket_order_id ? '1' : '0' }}">
                        @if($srEnabled)
                        <td class="px-4 py-3 w-10">
                            @if(!$order->shiprocket_order_id)
                            <input type="checkbox" class="row-check w-4 h-4 rounded accent-green-600 cursor-pointer"
                                   value="{{ $order->id }}" onchange="onRowCheck()">
                            @else
                            <span class="block w-4 h-4 flex items-center justify-center">
                                <i class="fa-solid fa-circle-check text-green-500 text-sm"></i>
                            </span>
                            @endif
                        </td>
                        @endif
                        <td class="px-4 py-3 text-sm font-mono" style="color: var(--text);">{{ $order->order_id }}</td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium" style="color: var(--text);">{{ $order->customer_name }}</div>
                            <div class="text-xs" style="color: var(--muted);">{{ $order->customer_phone }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm" style="color: var(--muted);">
                            {{ collect($order->items)->sum('quantity') }} item(s)
                        </td>
                        <td class="px-4 py-3 text-sm font-bold" style="color: var(--green);">
                            ₹{{ number_format($order->amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-sm" style="color: var(--muted);">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? '-')) }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full font-semibold
                                {{ $order->status === 'success'   ? 'bg-green-100 text-green-800'  : '' }}
                                {{ $order->status === 'pending'   ? 'bg-yellow-100 text-yellow-800': '' }}
                                {{ $order->status === 'failed'    ? 'bg-red-100 text-red-800'      : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-gray-100 text-gray-800'    : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        @if($srEnabled)
                        <td class="px-4 py-3">
                            @if($order->shiprocket_order_id)
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full font-semibold bg-blue-50 text-blue-700">
                                    <i class="fa-solid fa-truck-fast text-xs"></i>
                                    {{ $order->shiprocket_status ?? 'Assigned' }}
                                </span>
                            @else
                                <span class="text-xs" style="color: var(--muted);">—</span>
                            @endif
                        </td>
                        @endif
                        <td class="px-4 py-3 text-sm" style="color: var(--muted);">
                            {{ $order->created_at->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.product-orders.show', $order) }}"
                               class="text-sm font-semibold hover:underline" style="color: var(--green);">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $srEnabled ? 10 : 8 }}" class="px-4 py-10 text-center" style="color: var(--muted);">
                            <i class="fa-solid fa-box text-4xl mb-3 block" style="color: var(--muted);"></i>
                            No orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="px-4 py-3 border-t" style="border-color: var(--border);">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Bulk Assign Result Modal -->
@if($srEnabled)
<div id="bulkResultModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-40" onclick="closeBulkResult()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 p-6 z-10">
        <h3 class="text-lg font-bold mb-4" style="color: var(--text);">
            <i class="fa-solid fa-truck-fast mr-2" style="color: var(--green);"></i>Bulk Assignment Result
        </h3>
        <div id="bulkResultBody" class="space-y-3 text-sm"></div>
        <button onclick="closeBulkResult()"
                class="mt-5 w-full px-4 py-2 rounded-lg font-semibold text-white" style="background-color: var(--green);">
            Done
        </button>
    </div>
</div>
@endif

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
            Showing last 30 exports &bull; <code>storage/exports/product-orders/</code>
        </div>
    </div>
</div>

<script>
const EXPORT_URL        = '{{ route('admin.product-orders.export') }}';
const EXPORTS_LIST_URL  = '{{ route('admin.product-orders.exports.list') }}';
const BULK_ASSIGN_URL   = '{{ route('admin.product-orders.shiprocket.bulk-assign') }}';
const CSRF_TOKEN        = '{{ csrf_token() }}';

// ─── Selection ────────────────────────────────────────────────────────────────

function getChecked() {
    return [...document.querySelectorAll('.row-check:checked')].map(c => c.value);
}

function onRowCheck() {
    const checked = getChecked();
    document.getElementById('selectedCount').textContent = checked.length;
    const toolbar = document.getElementById('bulkToolbar');
    if (toolbar) toolbar.classList.toggle('hidden', checked.length === 0);
    toolbar && toolbar.classList.toggle('flex', checked.length > 0);

    // sync select-all state
    const all   = document.querySelectorAll('.row-check');
    const selAll = document.getElementById('selectAll');
    if (selAll) selAll.indeterminate = checked.length > 0 && checked.length < all.length;
    if (selAll) selAll.checked = checked.length === all.length && all.length > 0;
}

function toggleSelectAll(master) {
    document.querySelectorAll('.row-check').forEach(c => c.checked = master.checked);
    onRowCheck();
}

function clearSelection() {
    document.querySelectorAll('.row-check').forEach(c => c.checked = false);
    const selAll = document.getElementById('selectAll');
    if (selAll) { selAll.checked = false; selAll.indeterminate = false; }
    onRowCheck();
}

// ─── Bulk Assign ──────────────────────────────────────────────────────────────

function bulkAssignShiprocket() {
    const ids = getChecked();
    if (!ids.length) return;
    if (!confirm(`Assign ${ids.length} order(s) to Shiprocket?`)) return;

    const btn  = document.getElementById('bulkAssignBtn');
    const icon = document.getElementById('bulkAssignIcon');
    const text = document.getElementById('bulkAssignText');
    btn.disabled   = true;
    icon.className = 'fa-solid fa-spinner fa-spin';
    text.textContent = 'Assigning...';

    fetch(BULK_ASSIGN_URL, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ids }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showBulkResult(data);
        } else {
            alert(data.message);
        }
    })
    .catch(() => alert('Request failed. Please try again.'))
    .finally(() => {
        btn.disabled   = false;
        icon.className = 'fa-solid fa-truck-fast';
        text.textContent = 'Assign to Shiprocket';
    });
}

function showBulkResult(data) {
    const body = document.getElementById('bulkResultBody');
    let html = '';

    if (data.assigned_count > 0) {
        html += `<div class="flex items-center gap-2 p-3 rounded-lg bg-green-50 border border-green-200">
            <i class="fa-solid fa-circle-check text-green-600"></i>
            <span class="font-semibold text-green-700">${data.assigned_count} order(s) assigned successfully</span>
        </div>`;
    }
    if (data.skipped_count > 0) {
        html += `<div class="flex items-center gap-2 p-3 rounded-lg bg-yellow-50 border border-yellow-200">
            <i class="fa-solid fa-circle-exclamation text-yellow-600"></i>
            <span class="text-yellow-700">${data.skipped_count} already assigned — skipped</span>
        </div>`;
    }
    if (data.failed_count > 0) {
        const failList = data.failed.map(f =>
            `<li class="text-xs mt-1"><span class="font-mono">${f.order_id}</span>: ${f.reason}</li>`
        ).join('');
        html += `<div class="p-3 rounded-lg bg-red-50 border border-red-200">
            <div class="flex items-center gap-2 mb-1">
                <i class="fa-solid fa-circle-xmark text-red-600"></i>
                <span class="font-semibold text-red-700">${data.failed_count} order(s) failed</span>
            </div>
            <ul class="text-red-600 pl-2">${failList}</ul>
        </div>`;
    }

    body.innerHTML = html;
    const modal = document.getElementById('bulkResultModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeBulkResult() {
    const modal = document.getElementById('bulkResultModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    clearSelection();
    location.reload();
}

// ─── Export ───────────────────────────────────────────────────────────────────

function getFilters() {
    const form = document.getElementById('filterForm');
    const data = new FormData(form);
    const p    = new URLSearchParams();
    for (const [k, v] of data.entries()) { if (v) p.set(k, v); }
    return p.toString();
}

function generateExport() {
    const btn     = document.getElementById('exportBtn');
    const icon    = document.getElementById('exportIcon');
    const btnText = document.getElementById('exportBtnText');
    btn.disabled  = true;
    icon.className = 'fa-solid fa-spinner fa-spin';
    btnText.textContent = 'Generating...';

    fetch(EXPORT_URL + '?' + getFilters(), {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const a = document.createElement('a');
            a.href = data.download_url; a.download = data.filename;
            document.body.appendChild(a); a.click(); a.remove();
        } else { alert('Export failed. Please try again.'); }
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
                    <span class="text-xs" style="color:#6B7280;"><i class="fa-solid fa-database w-3 mr-1"></i>Size: <strong style="color:#111827;">${e.file_size ?? '-'}</strong></span>
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
    fetch(`/admin/product-orders/exports/${id}`, {
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
