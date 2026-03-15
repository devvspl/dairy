@extends('layouts.app')

@section('title', 'Today\'s Deliveries')
@section('page-title', 'Today\'s Deliveries - ' . now()->format('M d, Y'))

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4" style="border-color: var(--green);">
        <p class="font-semibold" style="color: var(--green);">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Total Deliveries</p>
            <p class="text-3xl font-bold" style="color: var(--text);">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Delivered</p>
            <p class="text-3xl font-bold" style="color: var(--green);">{{ $stats['delivered'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Total Quantity</p>
            <p class="text-3xl font-bold" style="color: var(--green);">{{ $stats['total_quantity'] }} L</p>
        </div>
    </div>

    <!-- Filters + Export Actions -->
    <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
        <form method="GET" action="{{ route('admin.deliveries.today') }}" class="flex flex-wrap gap-3 items-center">
            <select name="status" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                <option value="">All Statuses</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="skipped"   {{ request('status') === 'skipped'   ? 'selected' : '' }}>Skipped</option>
                <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-sm" style="background-color: var(--green); color: #fff;">
                <i class="fa-solid fa-filter mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.deliveries.today') }}" class="px-4 py-2 rounded-lg border font-semibold text-sm" style="border-color: var(--border); color: var(--text);">
                Clear
            </a>
            <div class="ml-auto flex gap-2">
                <!-- Generate Export -->
                <button type="button" onclick="generateExport()"
                        id="exportBtn"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-semibold text-sm text-white"
                        style="background-color: #1D6F42;">
                    <i class="fa-solid fa-file-excel" id="exportIcon"></i>
                    <span id="exportBtnText">Export Excel</span>
                </button>
                <!-- View Exports -->
                <button type="button" onclick="openExportsPanel()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-semibold text-sm border"
                        style="border-color: #1D6F42; color: #1D6F42;">
                    <i class="fa-solid fa-folder-open"></i> Exports
                </button>
            </div>
        </form>
    </div>

    <!-- Deliveries Table -->
    <div class="bg-white rounded-lg shadow-sm border" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b" style="border-color: var(--border); background-color: rgba(47, 74, 30, 0.05);">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Customer</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Plan</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Quantity</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Time</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                    <tr class="border-b hover:bg-gray-50" style="border-color: var(--border);">
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium" style="color: var(--text);">{{ $delivery->subscription->user->name }}</div>
                            <div class="text-xs" style="color: var(--muted);">{{ $delivery->subscription->user->phone }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text);">{{ $delivery->subscription->membershipPlan->name }}</td>
                        <td class="px-4 py-3 text-sm font-semibold" style="color: var(--green);">{{ $delivery->quantity_delivered }} L</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full font-semibold
                                {{ $delivery->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $delivery->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $delivery->status === 'skipped' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $delivery->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($delivery->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm" style="color: var(--muted);">
                            {{ $delivery->delivery_time ? \Carbon\Carbon::parse($delivery->delivery_time)->format('h:i A') : '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <button onclick="openUpdateModal({{ $delivery->id }}, '{{ $delivery->status }}', '{{ $delivery->quantity_delivered }}', '{{ $delivery->delivery_time }}')" 
                                        class="text-sm font-semibold hover:underline" style="color: var(--green);">
                                    <i class="fa-solid fa-edit mr-1"></i>Update
                                </button>
                                @if($delivery->status === 'pending')
                                <form method="POST" action="{{ route('admin.deliveries.forward', $delivery) }}" 
                                      onsubmit="return confirm('Forward this delivery to the next scheduled day?')" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm font-semibold hover:underline text-blue-600">
                                        <i class="fa-solid fa-forward mr-1"></i>Forward
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center" style="color: var(--muted);">
                            No deliveries scheduled for today.
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

<!-- Exports Offcanvas -->
<div id="exportsPanel" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-40" onclick="closeExportsPanel()"></div>
    <!-- Panel -->
    <div id="exportsPanelDrawer"
         class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col"
         style="transform: translateX(100%); transition: transform 0.3s ease;">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color: var(--border); background-color: #2F4A1E;">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-folder-open text-white"></i>
                <h3 class="font-bold text-white text-base">Generated Exports</h3>
            </div>
            <button onclick="closeExportsPanel()" class="text-white hover:text-gray-200 text-xl leading-none">&times;</button>
        </div>
        <!-- Body -->
        <div class="flex-1 overflow-y-auto p-4" id="exportsPanelBody">
            <div class="flex items-center justify-center h-32 text-gray-400 text-sm" id="exportsLoading">
                <i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading...
            </div>
            <div id="exportsList" class="space-y-3 hidden"></div>
            <div id="exportsEmpty" class="hidden flex-col items-center justify-center h-32 text-gray-400 text-sm text-center">
                <i class="fa-solid fa-file-excel text-3xl mb-2" style="color: #1D6F42;"></i>
                <p>No exports generated yet.</p>
            </div>
        </div>
        <!-- Footer -->
        <div class="px-5 py-3 border-t text-xs text-gray-400" style="border-color: var(--border);">
            Showing last 30 exports &bull; Files stored in <code>storage/exports/deliveries/</code>
        </div>
    </div>
</div>

<!-- Update Delivery Modal -->
<div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold mb-4" style="color: var(--text);">Update Delivery</h3>
        
        <form id="updateForm" method="POST">
            @csrf
            <input type="hidden" id="deliveryId" name="delivery_id">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Status</label>
                    <select name="status" id="statusSelect" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <option value="pending">Pending</option>
                        <option value="delivered">Delivered</option>
                        <option value="skipped">Skipped</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Quantity (Liters)</label>
                    <input type="number" step="0.5" name="quantity_delivered" id="quantityInput" 
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Delivery Time</label>
                    <input type="time" name="delivery_time" id="timeInput" 
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);"></textarea>
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeUpdateModal()" class="flex-1 py-2 rounded-lg border font-semibold" style="border-color: var(--border); color: var(--text);">
                    Cancel
                </button>
                <button type="submit" class="flex-1 py-2 rounded-lg font-semibold" style="background-color: var(--green); color: #fff;">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const EXPORT_URL  = '{{ route('admin.deliveries.today.export') }}';
const EXPORTS_LIST_URL = '{{ route('admin.deliveries.exports.list') }}';
const CSRF_TOKEN  = '{{ csrf_token() }}';
const CURRENT_STATUS = '{{ request('status') }}';

/* ── Generate Export ─────────────────────────────────────── */
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
        body: JSON.stringify({ status: CURRENT_STATUS }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Auto-download
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

/* ── Offcanvas ───────────────────────────────────────────── */
function openExportsPanel() {
    const panel   = document.getElementById('exportsPanel');
    const drawer  = document.getElementById('exportsPanelDrawer');
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
                <!-- Card Header -->
                <div class="flex items-center gap-2 px-3 py-2"
                     style="background: ${e.exists ? '#F0FDF4' : '#FFF5F5'}; border-bottom: 1px solid ${e.exists ? '#BBF7D0' : '#FECACA'};">
                    <i class="fa-solid fa-file-excel text-sm flex-shrink-0" style="color: #1D6F42;"></i>
                    <span class="text-xs font-semibold truncate flex-1" style="color: #111827;" title="${e.filename}">${e.filename}</span>
                    ${!e.exists ? `<span class="text-xs font-medium text-red-500 flex-shrink-0">Missing</span>` : ''}
                </div>
                <!-- Meta -->
                <div class="px-3 py-2 bg-white grid grid-cols-2 gap-x-4 gap-y-1">
                    <span class="text-xs" style="color:#6B7280;"><i class="fa-solid fa-tag w-3 mr-1"></i>Filter: <strong style="color:#111827;">${e.filter_status}</strong></span>
                    <span class="text-xs" style="color:#6B7280;"><i class="fa-solid fa-list-ol w-3 mr-1"></i>Rows: <strong style="color:#111827;">${e.row_count}</strong></span>
                    <span class="text-xs" style="color:#6B7280;"><i class="fa-solid fa-database w-3 mr-1"></i>Size: <strong style="color:#111827;">${e.file_size}</strong></span>
                    <span class="text-xs" style="color:#6B7280;"><i class="fa-solid fa-user w-3 mr-1"></i>${e.generated_by}</span>
                </div>
                <!-- Footer: timestamp + actions -->
                <div class="flex items-center justify-between gap-2 px-3 py-2 border-t" style="border-color:#E5E7EB; background:#FAFAFA;">
                    <span class="text-xs" style="color:#9CA3AF;">
                        <i class="fa-regular fa-clock mr-1"></i>${e.created_at}
                    </span>
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
    fetch(`/admin/deliveries/exports/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`export-row-${id}`)?.remove();
            // Show empty state if no rows left
            if (!document.getElementById('exportsList').children.length) {
                document.getElementById('exportsList').classList.add('hidden');
                document.getElementById('exportsEmpty').classList.remove('hidden');
            }
        }
    });
}

/* ── Update Delivery Modal ───────────────────────────────── */
function openUpdateModal(id, status, quantity, time) {
    document.getElementById('deliveryId').value   = id;
    document.getElementById('statusSelect').value = status;
    document.getElementById('quantityInput').value = quantity;
    document.getElementById('timeInput').value    = time || '';
    document.getElementById('updateForm').action  = `/admin/deliveries/${id}/status`;

    const modal = document.getElementById('updateModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeUpdateModal() {
    const modal = document.getElementById('updateModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection
