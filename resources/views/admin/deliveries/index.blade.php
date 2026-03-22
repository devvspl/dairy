@extends('layouts.app')

@section('title', 'Delivery Logs')
@section('page-title', 'Delivery Logs - ' . $subscription->user->name)

@section('content')
<div class="space-y-6">
    <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="inline-flex items-center text-sm font-semibold hover:underline" style="color: var(--green);">
        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Subscription
    </a>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4" style="border-color: var(--green);">
        <p class="font-semibold" style="color: var(--green);">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Subscription Info -->
    <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h3 class="font-bold text-lg" style="color: var(--text);">{{ $subscription->user->name }}</h3>
                <p class="text-sm" style="color: var(--muted);">{{ $subscription->membershipPlan->name }} — {{ $subscription->start_date->format('M d, Y') }} to {{ $subscription->end_date->format('M d, Y') }}</p>
                @if($subscription->membershipPlan->isOnDemand())
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    <span class="px-2 py-0.5 text-xs rounded-full font-semibold bg-blue-100 text-blue-800">🛒 On-Demand / Wallet</span>
                    <span class="text-sm" style="color: var(--muted);">Qty/day: <strong style="color:var(--text);">{{ $subscription->quantity_per_day }} L</strong></span>
                    <span class="text-sm" style="color: var(--muted);">Rate: <strong style="color:var(--text);">₹{{ number_format($subscription->price_per_litre, 2) }}/L</strong></span>
                    <span class="text-sm" style="color: var(--muted);">Wallet: <strong style="color:var(--green);">₹{{ number_format($subscription->wallet_balance, 2) }}</strong> / ₹{{ number_format($subscription->wallet_total, 2) }}</span>
                </div>
                @endif
            </div>
            <form method="POST" action="{{ route('admin.subscriptions.deliveries.generate', $subscription) }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-lg font-semibold" style="background-color: var(--green); color: #fff;">
                    <i class="fa-solid fa-calendar-plus mr-2"></i>
                    {{ $subscription->membershipPlan->isOnDemand() ? 'Generate Daily Entries' : 'Generate Schedule' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Total Deliveries</p>
            <p class="text-2xl font-bold" style="color: var(--text);">{{ $subscription->deliveryLogs()->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Delivered</p>
            <p class="text-2xl font-bold" style="color: var(--green);">{{ $subscription->deliveredCount() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $subscription->pendingCount() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Total Delivered</p>
            <p class="text-2xl font-bold" style="color: var(--green);">{{ $subscription->totalQuantityDelivered() }} L</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text);">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="skipped" {{ request('status') === 'skipped' ? 'selected' : '' }}>Skipped</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text);">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text);">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-lg font-semibold" style="background-color: var(--green); color: #fff;">
                    Filter
                </button>
                <a href="{{ route('admin.subscriptions.deliveries.index', $subscription) }}" class="px-4 py-2 rounded-lg border font-semibold" style="border-color: var(--border); color: var(--text);">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Deliveries Table -->
    <div class="bg-white rounded-lg shadow-sm border" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b" style="border-color: var(--border); background-color: rgba(47, 74, 30, 0.05);">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Day</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Quantity</th>
                        @if($subscription->membershipPlan->isOnDemand())
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Wallet Debit</th>
                        @endif
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Time</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Marked By</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                    <tr class="border-b hover:bg-gray-50" style="border-color: var(--border);">
                        <td class="px-4 py-3 text-sm" style="color: var(--text);">{{ $delivery->delivery_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--muted);">{{ $delivery->delivery_date->format('l') }}</td>
                        <td class="px-4 py-3 text-sm font-semibold" style="color: var(--green);">{{ $delivery->quantity_delivered }} L</td>
                        @if($subscription->membershipPlan->isOnDemand())
                        <td class="px-4 py-3 text-sm font-semibold text-red-600">
                            @if($delivery->status === 'delivered')
                                ₹{{ number_format($delivery->quantity_delivered * $subscription->price_per_litre, 2) }}
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>
                        @endif
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
                        <td class="px-4 py-3 text-sm" style="color: var(--muted);">
                            {{ $delivery->markedBy ? $delivery->markedBy->name : '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button onclick="openUpdateModal({{ $delivery->id }}, '{{ $delivery->status }}', '{{ $delivery->quantity_delivered }}', '{{ $delivery->delivery_time }}')"
                                        title="Update"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors hover:opacity-80"
                                        style="background: rgba(47,74,30,0.1); color: var(--green);">
                                    <i class="fa-solid fa-pen-to-square"></i> Update
                                </button>
                                @if($delivery->status === 'pending')
                                <form method="POST" action="{{ route('admin.deliveries.forward', $delivery) }}"
                                      onsubmit="return confirm('Forward this delivery to the next day?')" class="inline">
                                    @csrf
                                    <button type="submit" title="Forward to next day"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors hover:opacity-80"
                                            style="background: rgba(59,130,246,0.1); color: #2563eb;">
                                        <i class="fa-solid fa-forward-step"></i> Forward
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $subscription->membershipPlan->isOnDemand() ? 8 : 7 }}" class="px-4 py-8 text-center" style="color: var(--muted);">
                            No delivery logs found. Click "{{ $subscription->membershipPlan->isOnDemand() ? 'Generate Daily Entries' : 'Generate Schedule' }}" to create delivery entries.
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

<!-- Update Delivery Modal -->
<div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold mb-4" style="color: var(--text);">Update Delivery</h3>
        
        <form id="updateForm" method="POST">
            @csrf
            
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
function openUpdateModal(id, status, quantity, time) {
    document.getElementById('statusSelect').value = status;
    document.getElementById('quantityInput').value = quantity;
    document.getElementById('timeInput').value = time || '';
    document.getElementById('updateForm').action = `/admin/deliveries/${id}/status`;
    
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
