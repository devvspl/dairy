@extends('layouts.app')

@section('title', 'All Locations Delivery Report')
@section('page-title', 'Delivery Report — All Locations')

@section('content')
<div class="space-y-5">

    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4" style="border-color: var(--green);">
        <p class="font-semibold" style="color: var(--green);">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-white rounded-xl shadow-sm p-4 border text-center" style="border-color: var(--border);">
            <p class="text-2xl font-bold" style="color: var(--text);">{{ $stats['total'] }}</p>
            <p class="text-xs mt-1" style="color: var(--muted);">Total</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border text-center" style="border-color: var(--border);">
            <p class="text-2xl font-bold" style="color: var(--green);">{{ $stats['delivered'] }}</p>
            <p class="text-xs mt-1" style="color: var(--muted);">Delivered</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border text-center" style="border-color: var(--border);">
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            <p class="text-xs mt-1" style="color: var(--muted);">Pending</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border text-center" style="border-color: var(--border);">
            <p class="text-2xl font-bold text-gray-500">{{ $stats['skipped'] }}</p>
            <p class="text-xs mt-1" style="color: var(--muted);">Skipped</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border text-center" style="border-color: var(--border);">
            <p class="text-2xl font-bold" style="color: var(--green);">{{ number_format($stats['quantity'], 1) }} L</p>
            <p class="text-xs mt-1" style="color: var(--muted);">Qty Delivered</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <form method="GET" action="{{ route('admin.deliveries.locations') }}" class="flex flex-wrap gap-2 items-end">
            <div>
                <label class="block text-xs font-medium mb-1" style="color: var(--muted);">Date</label>
                <input type="date" name="date" value="{{ $date }}"
                       class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
            </div>
            <div>
                <label class="block text-xs font-medium mb-1" style="color: var(--muted);">Location</label>
                <select name="location_id" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                    <option value="">All Locations</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium mb-1" style="color: var(--muted);">Status</label>
                <select name="status" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                    <option value="">All Status</option>
                    <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="skipped"   {{ request('status') === 'skipped'   ? 'selected' : '' }}>Skipped</option>
                    <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium mb-1" style="color: var(--muted);">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name / phone..."
                       class="px-3 py-2 border rounded-lg text-sm min-w-[160px]" style="border-color: var(--border);">
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-sm text-white" style="background: var(--green);">
                <i class="fa-solid fa-filter mr-1"></i>Filter
            </button>
            <a href="{{ route('admin.deliveries.locations') }}"
               class="px-4 py-2 rounded-lg border font-semibold text-sm" style="border-color: var(--border); color: var(--text);">
                Today
            </a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b" style="border-color: var(--border); background: rgba(47,74,30,0.05);">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">#</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Customer</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Location</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Address</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Plan</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Qty</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Time</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $i => $delivery)
                    @php $sub = $delivery->subscription; @endphp
                    <tr class="border-b hover:bg-gray-50" style="border-color: var(--border);">
                        <td class="px-4 py-3 text-sm" style="color: var(--muted);">{{ $deliveries->firstItem() + $i }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-sm" style="color: var(--text);">{{ $sub?->user?->name ?? '—' }}</div>
                            <div class="text-xs" style="color: var(--muted);"><i class="fa-solid fa-phone mr-1"></i>{{ $sub?->user?->phone ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm font-medium" style="color: var(--green);">{{ $sub?->location?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs max-w-[180px]" style="color: var(--muted);">{{ $sub?->delivery_address ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text);">{{ $sub?->membershipPlan?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm font-semibold" style="color: var(--green);">{{ $delivery->quantity_delivered }} L</td>
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
                            <button onclick="openEditModal({{ $delivery->id }}, '{{ $delivery->status }}', '{{ $delivery->quantity_delivered }}', '{{ $delivery->delivery_time }}', '{{ addslashes($delivery->notes ?? '') }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors hover:opacity-80"
                                    style="background: rgba(47,74,30,0.1); color: var(--green);">
                                <i class="fa-solid fa-pen-to-square"></i>
                                {{ $delivery->status === 'pending' ? 'Mark' : 'Edit' }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center" style="color: var(--muted);">
                            <i class="fa-solid fa-truck text-4xl mb-3 block"></i>
                            No deliveries found for this date / filter.
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

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold" style="color: var(--text);">Update Delivery</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Status</label>
                    <select name="status" id="editStatus" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <option value="pending">Pending</option>
                        <option value="delivered">Delivered</option>
                        <option value="skipped">Skipped</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Quantity (L)</label>
                    <input type="number" step="0.5" name="quantity_delivered" id="editQty"
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Delivery Time</label>
                    <input type="time" name="delivery_time" id="editTime"
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text);">Notes</label>
                    <textarea name="notes" id="editNotes" rows="2"
                              class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);"></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 py-2 rounded-lg border font-semibold" style="border-color: var(--border); color: var(--text);">Cancel</button>
                <button type="submit" class="flex-1 py-2 rounded-lg font-semibold text-white" style="background: var(--green);">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, status, qty, time, notes) {
    document.getElementById('editStatus').value = status;
    document.getElementById('editQty').value    = qty;
    const now = new Date();
    const cur = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');
    document.getElementById('editTime').value   = time ? time.substring(0,5) : cur;
    document.getElementById('editNotes').value  = notes || '';
    document.getElementById('editForm').action  = `/admin/deliveries/${id}/status`;
    const m = document.getElementById('editModal');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeEditModal() {
    const m = document.getElementById('editModal');
    m.classList.add('hidden'); m.classList.remove('flex');
}
document.getElementById('editModal').addEventListener('click', function(e) { if (e.target === this) closeEditModal(); });
</script>
@endsection
