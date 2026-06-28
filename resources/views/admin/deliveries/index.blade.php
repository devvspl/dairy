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
    <div class="bg-white rounded-lg shadow-sm border" style="border-color: var(--border);">
        <!-- Header Section -->
        <div class="flex items-start justify-between p-6 border-b" style="border-color: var(--border);">
            <!-- Customer Info -->
            <div class="flex items-start gap-4 flex-1">
                <div class="w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-xl" style="background-color: var(--green);">
                    {{ strtoupper(substr($subscription->user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-xl mb-2" style="color: var(--text);">{{ $subscription->user->name }}</h3>
                    
                    <div class="flex items-center gap-4 mb-3">
                        @if(!$subscription->membership_plan_id || $subscription->membershipPlan?->isOnDemand())
                            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-blue-50 text-blue-800 border border-blue-200">
                                <i class="fa-solid fa-shopping-cart mr-2"></i>On-Demand / Wallet
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-green-50 text-green-800 border border-green-200">
                                <i class="fa-solid fa-crown mr-2"></i>{{ $subscription->membershipPlan->name }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-medium" style="color: var(--muted);">Duration:</span>
                            <p class="font-semibold" style="color: var(--text);">{{ $subscription->start_date->format('M d, Y') }} - {{ $subscription->end_date->format('M d, Y') }}</p>
                        </div>
                        @if(!$subscription->membership_plan_id || $subscription->membershipPlan?->isOnDemand())
                        <div>
                            <span class="font-medium" style="color: var(--muted);">Daily Quantity:</span>
                            <p class="font-semibold" style="color: var(--text);">{{ $subscription->quantity_per_day }}L</p>
                        </div>
                        <div>
                            <span class="font-medium" style="color: var(--muted);">Rate:</span>
                            <p class="font-semibold" style="color: var(--text);">₹{{ number_format($subscription->price_per_litre, 2) }}/L</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Wallet Balance Card -->
            @if(!$subscription->membership_plan_id || $subscription->membershipPlan?->isOnDemand())
            <div class="bg-green-50 rounded-lg p-4 border-2 min-w-[280px]" style="border-color: var(--green);">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-wallet" style="color: var(--green);"></i>
                        <span class="text-sm font-bold" style="color: var(--green);">Wallet Balance</span>
                    </div>
                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 font-semibold">Active</span>
                </div>
                
                <div class="text-center mb-4">
                    <p class="text-3xl font-bold mb-1" style="color: var(--green);">₹{{ number_format($subscription->wallet_balance, 2) }}</p>
                    <p class="text-xs" style="color: var(--muted);">Available Balance</p>
                </div>
                
                @if($walletStats)
                <div class="grid grid-cols-3 gap-2 mb-3">
                    <div class="text-center p-2 rounded bg-white border" style="border-color: var(--border);">
                        <p class="text-xs font-medium" style="color: var(--muted);">Credits</p>
                        <p class="text-sm font-bold text-green-700">₹{{ number_format($walletStats['total_credits'], 2) }}</p>
                    </div>
                    <div class="text-center p-2 rounded bg-white border" style="border-color: var(--border);">
                        <p class="text-xs font-medium" style="color: var(--muted);">Debits</p>
                        <p class="text-sm font-bold text-red-600">₹{{ number_format($walletStats['total_debits'], 2) }}</p>
                    </div>
                    <div class="text-center p-2 rounded bg-white border" style="border-color: var(--border);">
                        <p class="text-xs font-medium" style="color: var(--muted);">Txns</p>
                        <p class="text-sm font-bold" style="color: var(--text);">{{ $walletStats['transaction_count'] }}</p>
                    </div>
                </div>
                @endif
                
                <button onclick="openPaymentHistory()" class="w-full py-2 px-4 rounded font-semibold text-sm transition-all text-white" style="background-color: var(--green);">
                    <i class="fa-solid fa-history mr-2"></i>View Payment History
                </button>
            </div>
            @endif
        </div>

        <!-- Quick Stats Row -->
        <div class="px-6 py-4 bg-gray-50 border-b" style="border-color: var(--border);">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white border shadow-sm" style="border-color: var(--border);">
                    <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
                        <i class="fa-solid fa-list text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-blue-700">Total Deliveries</p>
                        <p class="text-lg font-bold text-blue-900">{{ $subscription->deliveryLogs()->count() }}</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white border shadow-sm" style="border-color: var(--border);">
                    <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center">
                        <i class="fa-solid fa-check-circle text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-green-700">Delivered</p>
                        <p class="text-lg font-bold text-green-900">{{ $subscription->deliveredCount() }}</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white border shadow-sm" style="border-color: var(--border);">
                    <div class="w-10 h-10 rounded-lg bg-yellow-500 flex items-center justify-center">
                        <i class="fa-solid fa-clock text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-yellow-700">Pending</p>
                        <p class="text-lg font-bold text-yellow-900">{{ $subscription->pendingCount() }}</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 p-3 rounded-lg bg-white border shadow-sm" style="border-color: var(--border);">
                    <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center">
                        <i class="fa-solid fa-droplet text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-purple-700">Total Volume</p>
                        <p class="text-lg font-bold text-purple-900">{{ $subscription->totalQuantityDelivered() }}L</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="px-6 py-4">
            <div class="flex items-center justify-between gap-4">
                <!-- Primary Actions -->
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('admin.subscriptions.deliveries.generate', $subscription) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold text-white shadow hover:shadow-md transition-all" style="background-color: var(--green);">
                            <i class="fa-solid fa-calendar-plus"></i>
                            <span>{{ (!$subscription->membership_plan_id || $subscription->membershipPlan?->isOnDemand()) ? 'Generate Daily Entries' : 'Generate Schedule' }}</span>
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.subscriptions.deliveries.reset', $subscription) }}"
                          onsubmit="return confirm('Delete ALL delivery entries for this subscription? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold text-red-700 bg-red-50 border-2 border-red-200 hover:bg-red-100 transition-all">
                            <i class="fa-solid fa-rotate-left"></i>
                            <span>Reset Schedule</span>
                        </button>
                    </form>
                </div>
                
                <!-- Quick Action Buttons -->
                {{-- <div class="flex items-center gap-2">
                    <button onclick="openPaymentHistory()" title="Payment History" class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-200 text-blue-600 hover:bg-blue-100 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-sm"></i>
                    </button>
                    <button onclick="exportDeliveries()" title="Export Data" class="w-10 h-10 rounded-lg bg-gray-50 border text-gray-600 hover:bg-gray-100 transition-all flex items-center justify-center" style="border-color: var(--border);">
                        <i class="fa-solid fa-download text-sm"></i>
                    </button>
                    <button onclick="window.print()" title="Print" class="w-10 h-10 rounded-lg bg-gray-50 border text-gray-600 hover:bg-gray-100 transition-all flex items-center justify-center" style="border-color: var(--border);">
                        <i class="fa-solid fa-print text-sm"></i>
                    </button>
                    <a href="{{ route('admin.subscriptions.show', $subscription) }}" title="View Full Subscription" class="w-10 h-10 rounded-lg bg-gray-50 border text-gray-600 hover:bg-gray-100 transition-all flex items-center justify-center" style="border-color: var(--border);">
                        <i class="fa-solid fa-external-link-alt text-sm"></i>
                    </a>
                </div> --}}
            </div>
            
            <!-- Additional Info Row -->
            <div class="mt-4 pt-4 border-t grid grid-cols-1 md:grid-cols-3 gap-4 text-sm" style="border-color: var(--border);">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check" style="color: var(--green);"></i>
                    <span style="color: var(--muted);">Last Updated:</span>
                    <span class="font-semibold" style="color: var(--text);">{{ $subscription->updated_at->diffForHumans() }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-map-marker-alt" style="color: var(--green);"></i>
                    <span style="color: var(--muted);">Location:</span>
                    <span class="font-semibold" style="color: var(--text);">{{ $subscription->location->name ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-phone" style="color: var(--green);"></i>
                    <span style="color: var(--muted);">Contact:</span>
                    <span class="font-semibold" style="color: var(--text);">{{ $subscription->user->mobile ?? 'N/A' }}</span>
                </div>
            </div>
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
                        @php $isWallet = !$subscription->membership_plan_id || $subscription->membershipPlan?->isOnDemand(); @endphp
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Day</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Quantity</th>
                        @if($isWallet)
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
                        @if($isWallet)
                        <td class="px-4 py-3">
                            @if($delivery->status === 'delivered')
                                @php $dailyDeduction = $delivery->quantity_delivered * $subscription->price_per_litre; @endphp
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-red-600">-₹{{ number_format($dailyDeduction, 2) }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-red-50 text-red-600 font-semibold">
                                        Debited
                                    </span>
                                </div>
                                <p class="text-xs mt-0.5" style="color: var(--muted);">
                                    {{ $delivery->quantity_delivered }}L × ₹{{ number_format($subscription->price_per_litre, 2) }}
                                </p>
                            @else
                                <span class="text-sm" style="color:var(--muted);">—</span>
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
                                <button onclick="openHistoryModal({{ $delivery->id }})"
                                        title="View History"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors hover:opacity-80"
                                        style="background: rgba(59,130,246,0.1); color: #2563eb;">
                                    <i class="fa-solid fa-history"></i> History
                                </button>
                                @if($delivery->status === 'pending')
                                <form method="POST" action="{{ route('admin.deliveries.forward', $delivery) }}"
                                      onsubmit="return confirm('Forward this delivery to the next day?')" class="inline">
                                    @csrf
                                    <button type="submit" title="Forward to next day"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors hover:opacity-80"
                                            style="background: rgba(251,146,60,0.1); color: #ea580c;">
                                        <i class="fa-solid fa-forward-step"></i> Forward
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $isWallet ? 8 : 7 }}" class="px-4 py-8 text-center" style="color: var(--muted);">
                            No delivery logs found. Click "{{ $isWallet ? 'Generate Daily Entries' : 'Generate Schedule' }}" to create delivery entries.
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

<!-- Delivery History Modal -->
<div id="historyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[85vh] flex flex-col">
        <div class="flex items-center justify-between p-6 border-b" style="border-color: var(--border);">
            <div>
                <h3 class="text-xl font-bold" style="color: var(--text);">
                    <i class="fa-solid fa-history mr-2" style="color: #2563eb;"></i>Delivery History
                </h3>
                <p class="text-sm mt-1" style="color: var(--muted);">Track all changes and updates to this delivery</p>
            </div>
            <button onclick="closeHistoryModal()" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100">
                <i class="fa-solid fa-times text-sm" style="color: var(--muted);"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6">
            <div id="historyContent" class="space-y-4">
                <div class="text-center py-8">
                    <i class="fa-solid fa-spinner fa-spin text-2xl text-blue-600"></i>
                    <p class="text-sm mt-2" style="color: var(--muted);">Loading history...</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 border-t text-center" style="border-color: var(--border);">
            <button onclick="closeHistoryModal()" class="px-6 py-2 rounded-lg font-semibold border" style="border-color: var(--border); color: var(--text);">
                Close
            </button>
        </div>
    </div>
</div>

<script>
// Cache bust - v2024.06.16.1
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

// History Modal Functions
function openHistoryModal(deliveryId) {
    const modal = document.getElementById('historyModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Load delivery history
    loadDeliveryHistory(deliveryId);
}

function closeHistoryModal() {
    const modal = document.getElementById('historyModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function loadDeliveryHistory(deliveryId) {
    const content = document.getElementById('historyContent');
    content.innerHTML = `
        <div class="text-center py-8">
            <i class="fa-solid fa-spinner fa-spin text-2xl text-blue-600"></i>
            <p class="text-sm mt-2" style="color: var(--muted);">Loading history...</p>
        </div>
    `;
    
    fetch(`/admin/deliveries/${deliveryId}/history`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderDeliveryHistory(data.history);
            } else {
                content.innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <i class="fa-solid fa-exclamation-triangle text-2xl"></i>
                        <p class="text-sm mt-2">Failed to load delivery history</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            content.innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <i class="fa-solid fa-wifi text-2xl"></i>
                    <p class="text-sm mt-2">Network error. Please try again.</p>
                </div>
            `;
        });
}

function renderDeliveryHistory(history) {
    const content = document.getElementById('historyContent');
    
    if (history.length === 0) {
        content.innerHTML = `
            <div class="text-center py-8" style="color: var(--muted);">
                <i class="fa-solid fa-clock text-3xl mb-3"></i>
                <p class="text-lg font-semibold mb-2">No History Yet</p>
                <p class="text-sm">This delivery hasn't been modified since creation.</p>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="mb-4">
            <div class="flex items-center gap-2 text-sm" style="color: var(--muted);">
                <i class="fa-solid fa-list"></i>
                <span>${history.length} change${history.length !== 1 ? 's' : ''} recorded</span>
            </div>
        </div>
        <div class="space-y-4">
    `;
    
    history.forEach((record, index) => {
        const isFirst = index === 0;
        const actionIcons = {
            'status_change': { icon: 'fa-exchange-alt', color: 'text-blue-600', bg: 'bg-blue-50' },
            'quantity_change': { icon: 'fa-balance-scale', color: 'text-purple-600', bg: 'bg-purple-50' },
            'person_change': { icon: 'fa-user-edit', color: 'text-green-600', bg: 'bg-green-50' },
            'time_change': { icon: 'fa-clock', color: 'text-orange-600', bg: 'bg-orange-50' },
            'note_added': { icon: 'fa-plus-circle', color: 'text-indigo-600', bg: 'bg-indigo-50' },
            'note_updated': { icon: 'fa-edit', color: 'text-indigo-600', bg: 'bg-indigo-50' },
            'bottle_status_change': { icon: 'fa-wine-bottle', color: 'text-teal-600', bg: 'bg-teal-50' },
            'delivery_created': { icon: 'fa-plus', color: 'text-green-600', bg: 'bg-green-50' },
            'delivery_forwarded': { icon: 'fa-forward', color: 'text-yellow-600', bg: 'bg-yellow-50' },
        };
        
        const actionStyle = actionIcons[record.action_type] || { icon: 'fa-edit', color: 'text-gray-600', bg: 'bg-gray-50' };
        
        html += `
            <div class="relative ${isFirst ? 'ring-2 ring-blue-200' : ''}">
                <div class="flex gap-4 p-4 rounded-xl border bg-white hover:shadow-md transition-shadow" style="border-color: var(--border);">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full ${actionStyle.bg} flex items-center justify-center">
                            <i class="fa-solid ${actionStyle.icon} ${actionStyle.color}"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex-1">
                                <h4 class="font-semibold text-sm" style="color: var(--text);">${record.description}</h4>
                                <p class="text-xs" style="color: var(--muted);">
                                    by ${record.changed_by} • ${record.changed_at_human}
                                </p>
                            </div>
                            ${isFirst ? '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">Latest</span>' : ''}
                        </div>
                        
                        ${record.changes.length > 0 ? `
                        <div class="bg-gray-50 rounded-lg p-3 mt-2">
                            <p class="text-xs font-semibold mb-2" style="color: var(--muted);">CHANGES MADE:</p>
                            <div class="space-y-1">
                                ${record.changes.map(change => `
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="font-medium" style="color: var(--text);">${change.label}:</span>
                                        <span class="px-1 py-0.5 bg-red-100 text-red-700 rounded">${change.old || 'None'}</span>
                                        <i class="fa-solid fa-arrow-right text-gray-400"></i>
                                        <span class="px-1 py-0.5 bg-green-100 text-green-700 rounded">${change.new || 'None'}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        ` : ''}
                        
                        <div class="flex items-center gap-4 mt-3 text-xs" style="color: var(--muted);">
                            <div class="flex items-center gap-1">
                                <i class="fa-solid fa-calendar"></i>
                                <span>${record.changed_at}</span>
                            </div>
                            ${record.ip_address ? `
                                <div class="flex items-center gap-1">
                                    <i class="fa-solid fa-globe"></i>
                                    <span class="font-mono">${record.ip_address}</span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    content.innerHTML = html;
}

// Export function
function exportDeliveries() {
    alert('Export functionality will be implemented soon!');
    // TODO: Implement export to Excel/PDF
}

// Payment History Modal
let currentHistoryData = null;

function openPaymentHistory() {
    const modal = document.getElementById('paymentHistoryModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Load all data
    loadPaymentHistory();
}

function closePaymentHistory() {
    const modal = document.getElementById('paymentHistoryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function switchHistoryTab(tab) {
    // Update tab buttons
    ['bankTab', 'walletTab', 'reconciliationTab'].forEach(tabId => {
        const btn = document.getElementById(tabId);
        if (tabId === tab + 'Tab') {
            btn.style.color = 'var(--green)';
            btn.style.borderColor = 'var(--green)';
        } else {
            btn.style.color = 'var(--muted)';
            btn.style.borderColor = 'transparent';
        }
    });
    
    // Update tab content
    ['bankContent', 'walletContent', 'reconciliationContent'].forEach(contentId => {
        const content = document.getElementById(contentId);
        if (contentId === tab + 'Content') {
            content.classList.remove('hidden');
        } else {
            content.classList.add('hidden');
        }
    });
}

function loadPaymentHistory() {
    fetch('/admin/subscriptions/{{ $subscription->id }}/payment-history')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentHistoryData = data;
                // Store reconciliation history globally for renderReconciliation
                window._reconciliationHistory = data.reconciliation_history || [];
                renderBankPayments(data.bank_payments);
                renderWalletHistory(data.wallet_transactions);
                renderReconciliation(data.reconciliation);
            } else {
                showError('Failed to load payment history');
            }
        })
        .catch(error => {
            showError('Error loading payment history');
        });
}

function renderBankPayments(payments) {
    const container = document.getElementById('bankContent');
    
    if (payments.length === 0) {
        container.innerHTML = '<p class="text-center py-8" style="color: var(--muted);">No bank payments found.</p>';
        return;
    }
    
    let totalAmount = 0;
    let html = '<div class="space-y-3">';
    
    payments.forEach(payment => {
        totalAmount += parseFloat(payment.amount);
        const statusColor = payment.status === 'success' ? 'text-green-700' : payment.status === 'pending' ? 'text-yellow-700' : 'text-red-700';
        const statusBg = payment.status === 'success' ? 'bg-green-50' : payment.status === 'pending' ? 'bg-yellow-50' : 'bg-red-50';
        
        html += `
            <div class="flex items-start gap-3 p-4 rounded-lg border bg-white hover:shadow-md transition-shadow" style="border-color: var(--border);">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-green-50">
                    <i class="fa-solid fa-building-columns text-lg" style="color: var(--green);"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div class="flex-1">
                            <p class="text-lg font-bold" style="color: var(--green);">₹${parseFloat(payment.amount).toFixed(2)}</p>
                            <p class="text-sm font-semibold" style="color: var(--text);">${payment.payment_method || 'PhonePe'}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full font-semibold ${statusBg} ${statusColor}">
                            ${payment.status.charAt(0).toUpperCase() + payment.status.slice(1)}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs" style="color: var(--muted);">
                        <div>
                            <i class="fa-solid fa-hashtag mr-1"></i>
                            <span class="font-mono">${payment.order_id || '-'}</span>
                        </div>
                        <div>
                            <i class="fa-solid fa-calendar mr-1"></i>
                            ${payment.date}
                        </div>
                        ${payment.transaction_id ? `
                        <div class="col-span-2">
                            <i class="fa-solid fa-receipt mr-1"></i>
                            <span class="font-mono text-[10px]">${payment.transaction_id}</span>
                        </div>
                        ` : ''}
                        ${payment.status === 'pending' ? `
                        <div class="col-span-2 mt-2">
                            <button onclick="verifyPendingPayment('${payment.order_id}', this)"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white transition-all hover:opacity-90"
                                style="background:#d97706;">
                                <i class="fa-solid fa-rotate"></i> Verify with PhonePe
                            </button>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    
    // Add summary
    html = `
        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 mb-4 border-2" style="border-color: var(--green);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold" style="color: var(--green);">Total Bank Payments</p>
                    <p class="text-xs" style="color: var(--muted);">${payments.length} transaction(s)</p>
                </div>
                <p class="text-2xl font-bold" style="color: var(--green);">₹${totalAmount.toFixed(2)}</p>
            </div>
        </div>
    ` + html;
    
    container.innerHTML = html;
}

function verifyPendingPayment(orderId, btn) {
    if (!confirm('Verify this payment with PhonePe? If paid, it will be credited to the wallet.')) return;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Verifying...';

    fetch('{{ route("admin.subscriptions.verify-pending-payment") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ order_id: orderId }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.status === 'success') {
            btn.outerHTML = `<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white" style="background:#16a34a;"><i class="fa-solid fa-check-circle"></i>${data.message}</span>`;
        } else if (data.success) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-clock mr-1"></i>' + data.message;
            btn.style.background = '#6b7280';
        } else {
            alert(data.message || 'Verification failed.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-rotate mr-1"></i>Retry';
        }
    })
    .catch(() => {
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-rotate mr-1"></i>Retry';
    });
}

function renderWalletHistory(transactions) {
    const container = document.getElementById('walletContent');
    
    if (transactions.length === 0) {
        container.innerHTML = '<p class="text-center py-8" style="color: var(--muted);">No wallet transactions found.</p>';
        return;
    }
    
    let totalCredits = 0;
    let totalDebits = 0;
    let html = '<div class="space-y-2">';
    
    transactions.forEach(txn => {
        const isCredit = txn.type === 'credit';
        if (isCredit) {
            totalCredits += parseFloat(txn.amount);
        } else {
            totalDebits += parseFloat(txn.amount);
        }
        
        const icon = isCredit ? 'fa-arrow-up' : 'fa-arrow-down';
        const bgColor = isCredit ? 'bg-green-50' : 'bg-red-50';
        const textColor = isCredit ? 'text-green-700' : 'text-red-700';
        const borderColor = isCredit ? 'border-green-200' : 'border-red-200';
        const amountPrefix = isCredit ? '+' : '-';
        
        html += `
            <div class="flex items-start gap-3 p-3 rounded-lg border ${bgColor}" style="border-color: var(--border);">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid ${icon} ${textColor}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1">
                            <p class="text-sm font-bold ${textColor}">${amountPrefix}₹${parseFloat(txn.amount).toFixed(2)}</p>
                            <p class="text-xs truncate" style="color: var(--muted);">${txn.description || '-'}</p>
                            ${txn.litres ? `<p class="text-xs mt-1" style="color: var(--muted);"><i class="fa-solid fa-droplet mr-1"></i>${txn.litres}L</p>` : ''}
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-bold" style="color: var(--text);">₹${parseFloat(txn.balance_after).toFixed(2)}</p>
                            <p class="text-[10px]" style="color: var(--muted);">Balance</p>
                        </div>
                    </div>
                    <p class="text-[10px] mt-1" style="color: var(--muted);">
                        <i class="fa-solid fa-clock mr-1"></i>${txn.date} ${txn.time}
                    </p>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    
    // Add summary
    html = `
        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="bg-green-50 rounded-xl p-3 border-2 border-green-200">
                <p class="text-xs font-semibold text-green-700 mb-1">Credits</p>
                <p class="text-lg font-bold text-green-700">₹${totalCredits.toFixed(2)}</p>
            </div>
            <div class="bg-red-50 rounded-xl p-3 border-2 border-red-200">
                <p class="text-xs font-semibold text-red-700 mb-1">Debits</p>
                <p class="text-lg font-bold text-red-700">₹${totalDebits.toFixed(2)}</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-3 border-2 border-blue-200">
                <p class="text-xs font-semibold text-blue-700 mb-1">Net</p>
                <p class="text-lg font-bold text-blue-700">₹${(totalCredits - totalDebits).toFixed(2)}</p>
            </div>
        </div>
    ` + html;
    
    container.innerHTML = html;
}

function renderReconciliation(data) {
    const container = document.getElementById('reconciliationContent');
    
    const r = data;
    const isBalanced           = r.is_balanced;
    const difference           = parseFloat(r.difference);
    const bankMatched          = r.bank_matched;
    const bankDiff             = parseFloat(r.bank_diff);
    const hasStaleAdjustments  = r.has_stale_adjustments || false;
    // Support both old and new field names
    const realCredits = parseFloat(r.real_credits ?? r.total_credits ?? 0);
    const realDebits  = parseFloat(r.real_debits  ?? r.total_debits  ?? 0);

    // ── Status Banner ────────────────────────────────────────────────────────
    const statusBanner = isBalanced && !hasStaleAdjustments
        ? `<div class="flex items-center gap-3 p-4 rounded-lg border border-green-300 bg-green-50 mb-4">
               <i class="fa-solid fa-circle-check text-green-600 text-xl"></i>
               <div>
                   <p class="font-bold text-green-700">Books are Balanced</p>
                   <p class="text-sm text-green-600">Expected ₹${r.expected_balance.toFixed(2)} = Actual ₹${r.actual_balance.toFixed(2)}</p>
               </div>
           </div>`
        : isBalanced && hasStaleAdjustments
        ? `<div class="flex items-center gap-3 p-4 rounded-lg border border-yellow-300 bg-yellow-50 mb-4">
               <i class="fa-solid fa-triangle-exclamation text-yellow-600 text-xl"></i>
               <div>
                   <p class="font-bold text-yellow-700">Ledger Has Stale Adjustments</p>
                   <p class="text-sm text-yellow-600">Balance appears correct but stale reconciliation entries (₹${(r.adjustment_credits + r.adjustment_debits).toFixed(2)}) exist — click "Clean Stale Adjustments" to fully reconcile.</p>
               </div>
           </div>`
        : `<div class="flex items-center gap-3 p-4 rounded-lg border border-red-300 bg-red-50 mb-4">
               <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>
               <div>
                   <p class="font-bold text-red-700">Reconciliation Required</p>
                   <p class="text-sm text-red-600">Expected ₹${r.expected_balance.toFixed(2)} ≠ Actual ₹${r.actual_balance.toFixed(2)} — Difference: ₹${Math.abs(difference).toFixed(2)}</p>
               </div>
           </div>`;

    // ── Summary Table ────────────────────────────────────────────────────────
    const summaryTable = `
        <div class="bg-white border rounded-lg overflow-hidden mb-4" style="border-color: var(--border);">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b" style="border-color: var(--border);">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold" style="color: var(--muted);">Metric</th>
                        <th class="px-4 py-2 text-right font-semibold" style="color: var(--muted);">Amount</th>
                        <th class="px-4 py-2 text-left font-semibold" style="color: var(--muted);">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border);">
                    <tr>
                        <td class="px-4 py-3" style="color: var(--text);">Bank Payments (received)</td>
                        <td class="px-4 py-3 text-right font-bold" style="color: var(--green);">₹${r.bank_total.toFixed(2)}</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 text-xs rounded bg-blue-50 text-blue-700 font-semibold">Source</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3" style="color: var(--text);">
                            Wallet Credits <span class="text-xs text-gray-400">(real top-ups only)</span>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-green-600">₹${r.real_credits.toFixed(2)}</td>
                        <td class="px-4 py-3">${bankMatched
                            ? '<span class="px-2 py-0.5 text-xs rounded bg-green-50 text-green-700 font-semibold">✓ Matches bank</span>'
                            : `<span class="px-2 py-0.5 text-xs rounded bg-red-50 text-red-700 font-semibold">✗ Diff ₹${Math.abs(bankDiff).toFixed(2)}</span>`
                        }</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3" style="color: var(--text);">
                            Wallet Debits <span class="text-xs text-gray-400">(from transactions)</span>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-red-600">₹${r.delivery_debits.toFixed(2)}</td>
                        <td class="px-4 py-3">${
                            r.debit_mismatch
                                ? `<span class="px-2 py-0.5 text-xs rounded bg-red-50 text-red-700 font-semibold">✗ Mismatch</span>`
                                : `<span class="px-2 py-0.5 text-xs rounded bg-gray-50 text-gray-700 font-semibold">Spent</span>`
                        }</td>
                    </tr>
                    ${r.expected_debits_from_logs !== undefined ? `
                    <tr>
                        <td class="px-4 py-3" style="color: var(--text);">
                            Expected Debits <span class="text-xs text-gray-400">(from ${r.delivered_count ?? 0} delivered logs)</span>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-orange-600">₹${r.expected_debits_from_logs.toFixed(2)}</td>
                        <td class="px-4 py-3">${
                            r.debit_mismatch
                                ? `<span class="px-2 py-0.5 text-xs rounded bg-orange-50 text-orange-700 font-semibold">Ground Truth</span>`
                                : `<span class="px-2 py-0.5 text-xs rounded bg-green-50 text-green-700 font-semibold">✓ Matches</span>`
                        }</td>
                    </tr>` : ''}
                    <tr class="bg-gray-50">
                        <td class="px-4 py-3 font-semibold" style="color: var(--text);">Expected Balance <span class="text-xs font-normal text-gray-400">(Credits − Debits)</span></td>
                        <td class="px-4 py-3 text-right font-bold" style="color: var(--text);">₹${r.expected_balance.toFixed(2)}</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 text-xs rounded bg-blue-50 text-blue-700 font-semibold">Calculated</span></td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-4 py-3 font-semibold" style="color: var(--text);">Actual Balance <span class="text-xs font-normal text-gray-400">(wallet_balance field)</span></td>
                        <td class="px-4 py-3 text-right font-bold" style="color: var(--text);">₹${r.actual_balance.toFixed(2)}</td>
                        <td class="px-4 py-3">${isBalanced
                            ? '<span class="px-2 py-0.5 text-xs rounded bg-green-50 text-green-700 font-semibold">✓ Correct</span>'
                            : '<span class="px-2 py-0.5 text-xs rounded bg-red-50 text-red-700 font-semibold">✗ Mismatch</span>'
                        }</td>
                    </tr>
                    <tr class="${isBalanced ? 'bg-green-50' : 'bg-red-50'}">
                        <td class="px-4 py-3 font-bold" style="color: var(--text);">Difference</td>
                        <td class="px-4 py-3 text-right font-bold ${isBalanced ? 'text-green-700' : 'text-red-700'}">
                            ${isBalanced ? '₹0.00' : (difference > 0 ? '+' : '−') + '₹' + Math.abs(difference).toFixed(2)}
                        </td>
                        <td class="px-4 py-3 font-semibold ${isBalanced ? 'text-green-700' : 'text-red-700'}">${isBalanced ? 'Balanced ✓' : 'Mismatch ✗'}</td>
                    </tr>
                </tbody>
            </table>
        </div>
       ${(r.adjustment_credits > 0 || r.adjustment_debits > 0) ? `
        <div class="flex items-start gap-2 p-3 rounded-lg bg-yellow-50 border border-yellow-200 text-xs mb-4">
            <i class="fa-solid fa-info-circle text-yellow-600 mt-0.5"></i>
            <div class="text-yellow-800">
                <span class="font-semibold">Prior reconciliation adjustments in ledger:</span>
                adjustment credits ₹${r.adjustment_credits.toFixed(2)}, adjustment debits ₹${r.adjustment_debits.toFixed(2)}.
                These are excluded from balance calculation and will be removed when a new fix is applied.
            </div>
        </div>` : ''}`;

    // ── Last Reconciled ──────────────────────────────────────────────────────
    const lastReconciledRow = (r.last_reconciled_at || r.last_reconciled_by)
        ? `<div class="flex items-center gap-4 p-3 rounded-lg bg-gray-50 border text-sm mb-4" style="border-color: var(--border);">
               <i class="fa-solid fa-clock-rotate-left text-gray-400"></i>
               <span style="color: var(--muted);">Last reconciled:</span>
               <span class="font-semibold" style="color: var(--text);">${r.last_reconciled_by ?? '—'}</span>
               <span style="color: var(--muted);">${r.last_reconciled_at ? new Date(r.last_reconciled_at).toLocaleString('en-IN') : '—'}</span>
               ${r.last_fix_type ? `<span class="px-2 py-0.5 text-xs rounded bg-blue-50 text-blue-700">${r.last_fix_type.replace(/_/g,' ')}</span>` : ''}
           </div>`
        : `<div class="flex items-center gap-3 p-3 rounded-lg bg-yellow-50 border border-yellow-200 text-sm mb-4">
               <i class="fa-solid fa-exclamation-circle text-yellow-600"></i>
               <span class="text-yellow-700">Never reconciled.</span>
           </div>`;

    // ── Quick Fix Actions ────────────────────────────────────────────────────
    const fixes = [];

    // Delivery debits mismatch: actual debit transactions don't match delivered log totals
    const deliveryDebitMismatch = r.debit_mismatch ?? false;
    const expectedDebitsFromLogs = r.expected_debits_from_logs ?? r.delivery_debits;

    // Show warning if debits don't match
    let debitMismatchWarning = '';
    if (deliveryDebitMismatch) {
        const missingAmount = Math.abs(expectedDebitsFromLogs - r.delivery_debits);
        debitMismatchWarning = `
        <div class="flex items-start gap-3 p-4 rounded-lg border border-orange-300 bg-orange-50 mb-4">
            <i class="fa-solid fa-exclamation-triangle text-orange-600 text-xl"></i>
            <div>
                <p class="font-bold text-orange-700">Delivery Debit Mismatch Detected</p>
                <p class="text-sm text-orange-600">
                    Actual spent from deliveries: <strong>₹${expectedDebitsFromLogs.toFixed(2)}</strong><br>
                    Wallet debits recorded: <strong>₹${r.delivery_debits.toFixed(2)}</strong><br>
                    Missing debit transactions: <strong>₹${missingAmount.toFixed(2)}</strong>
                </p>
                <p class="text-xs text-orange-500 mt-1">
                    ℹ️ Click "Recalculate Delivery Debits" below to fix this issue.
                </p>
            </div>
        </div>`;
    }

    // Case 1: balance is wrong (expected != actual)
    if (!isBalanced) {
        fixes.push({
            type: 'rebuild_from_ledger',
            label: 'Rebuild Balance from Ledger',
            desc: `Set balance = Bank ₹${r.bank_total.toFixed(2)} − Delivered ₹${r.delivery_debits.toFixed(2)} = ₹${r.expected_balance.toFixed(2)}`,
            icon: 'fa-calculator',
            safe: true
        });
    }

    // Case 2: stale reconciliation adjustment entries exist in ledger
    if (hasStaleAdjustments) {
        fixes.push({
            type: 'rebuild_from_ledger',
            label: 'Clean Stale Adjustments & Rebuild',
            desc: `Removes stale adj. entries (credits ₹${r.adjustment_credits.toFixed(2)}, debits ₹${r.adjustment_debits.toFixed(2)}) and sets correct balance`,
            icon: 'fa-broom',
            safe: true
        });
    }

    // Case 3: delivery debit transactions don't match what was delivered
    // Always show this so admin can fix the ₹80 missing debit scenario
    if (deliveryDebitMismatch) {
        fixes.push({
            type: 'recalculate_debits',
            label: 'Recalculate Delivery Debits ⚠️',
            desc: `Fix missing debits: Expected ₹${expectedDebitsFromLogs.toFixed(2)} but only ₹${r.delivery_debits.toFixed(2)} recorded`,
            icon: 'fa-rotate',
            safe: false,
            priority: true
        });
    } else {
        fixes.push({
            type: 'recalculate_debits',
            label: 'Recalculate Delivery Debits',
            desc: `Verify all delivery transactions are correctly debited`,
            icon: 'fa-rotate',
            safe: false
        });
    }

    // Case 4: fix from deliveries (hard reset using bank − deliveries formula)
    fixes.push({
        type: 'fix_from_deliveries',
        label: 'Fix Balance from Deliveries',
        desc: `Hard reset: Bank ₹${r.bank_total.toFixed(2)} − Delivered debits ₹${r.delivery_debits.toFixed(2)} = ₹${r.expected_balance.toFixed(2)}`,
        icon: 'fa-truck',
        safe: false
    });

    const fixActions = fixes.length > 0
        ? `<div class="mb-4">
               <h4 class="text-sm font-bold mb-2 flex items-center gap-2" style="color: var(--text);">
                   <i class="fa-solid fa-wrench text-blue-600"></i> Quick Fix Actions
               </h4>
               <div class="space-y-2">
                   ${fixes.map(f => `
                       <button onclick="applyFix('${f.type}')"
                               class="w-full flex items-center gap-3 px-4 py-3 rounded-lg border text-left hover:${f.priority ? 'bg-orange-50' : 'bg-blue-50'} transition-colors ${f.priority ? 'border-orange-300 bg-orange-50' : ''}"
                               style="border-color: ${f.priority ? '#fb923c' : 'var(--border)'};">
                           <div class="w-9 h-9 rounded-lg ${f.priority ? 'bg-orange-100 border-orange-300' : 'bg-blue-50 border-blue-200'} border flex items-center justify-center flex-shrink-0">
                               <i class="fa-solid ${f.icon} ${f.priority ? 'text-orange-600' : 'text-blue-600'} text-sm"></i>
                           </div>
                           <div class="flex-1 min-w-0">
                               <p class="font-semibold text-sm" style="color: var(--text);">${f.label}</p>
                               <p class="text-xs" style="color: var(--muted);">${f.desc}</p>
                           </div>
                           ${!f.safe ? `<span class="text-xs ${f.priority ? 'text-orange-600 bg-orange-100 border-orange-300' : 'text-orange-600 bg-orange-50 border-orange-200'} font-semibold px-2 py-1 rounded border">Modifies data</span>` : ''}
                           <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
                       </button>`).join('')}
               </div>
           </div>`
        : '';

    // "Mark Reconciled" button — only when truly balanced AND no stale adjustments
    const markReconciledBtn = (isBalanced && !hasStaleAdjustments)
        ? `<button onclick="applyFix('mark_reconciled')"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border-2 border-green-300 text-green-700 bg-green-50 hover:bg-green-100 font-semibold text-sm transition-colors mb-4">
               <i class="fa-solid fa-check-circle"></i> Mark as Reconciled
           </button>`
        : '';

    // ── History Table ────────────────────────────────────────────────────────
    let historySection = '';
    if (window._reconciliationHistory && window._reconciliationHistory.length > 0) {
        const rows = window._reconciliationHistory.map(h => `
            <tr class="border-b hover:bg-gray-50" style="border-color: var(--border);">
                <td class="px-3 py-2 text-xs" style="color: var(--muted);">${h.performed_at}</td>
                <td class="px-3 py-2">
                    <span class="px-2 py-0.5 text-xs rounded bg-blue-50 text-blue-700 font-semibold">${h.fix_label}</span>
                </td>
                <td class="px-3 py-2 text-xs text-right font-mono ${h.difference >= 0 ? 'text-green-700' : 'text-red-700'}">
                    ${h.difference >= 0 ? '+' : ''}₹${h.difference.toFixed(2)}
                </td>
                <td class="px-3 py-2 text-xs" style="color: var(--text);">${h.performed_by}</td>
                <td class="px-3 py-2">
                    <span class="px-2 py-0.5 text-xs rounded ${h.status === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} font-semibold">${h.status}</span>
                </td>
            </tr>`).join('');

        historySection = `
            <div>
                <h4 class="text-sm font-bold mb-2 flex items-center gap-2" style="color: var(--text);">
                    <i class="fa-solid fa-clock-rotate-left" style="color: var(--green);"></i> Reconciliation History
                </h4>
                <div class="border rounded-lg overflow-hidden" style="border-color: var(--border);">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b" style="border-color: var(--border);">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-semibold" style="color: var(--muted);">Date</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold" style="color: var(--muted);">Action</th>
                                <th class="px-3 py-2 text-right text-xs font-semibold" style="color: var(--muted);">Δ Balance</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold" style="color: var(--muted);">By</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold" style="color: var(--muted);">Result</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>`;
    }

    container.innerHTML = statusBanner + summaryTable + lastReconciledRow + fixActions + markReconciledBtn + historySection;
}

function applyFix(fixType) {
    const labels = {
        'rebuild_from_ledger': 'Rebuild Balance from Ledger',
        'fix_from_deliveries': 'Fix Balance from Deliveries',
        'recalculate_credits': 'Recalculate Credits',
        'recalculate_debits':  'Recalculate Debits',
        'mark_reconciled':     'Mark as Reconciled',
    };
    const label = labels[fixType] || fixType;

    const warn = fixType !== 'mark_reconciled'
        ? '\n\n⚠️ This will modify wallet data. An audit log will be created.'
        : '';

    if (!confirm(`Apply fix: "${label}"?${warn}`)) return;

    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Applying...';

    fetch('/admin/subscriptions/{{ $subscription->id }}/fix-reconciliation', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ fix_type: fixType })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const msg = data.skipped
                ? 'ℹ️ ' + data.message
                : '✅ ' + data.message;
            alert(msg);
            // Reload full history to show updated reconciliation
            window._reconciliationHistory = [];
            loadPaymentHistory();
            if (!data.skipped) {
                // Reload page to reflect updated wallet balance in header
                setTimeout(() => window.location.reload(), 1200);
            }
        } else {
            alert('❌ Error: ' + data.message);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    })
    .catch(() => {
        alert('❌ Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    });
}

function showError(message) {
    ['bankContent', 'walletContent', 'reconciliationContent'].forEach(contentId => {
        document.getElementById(contentId).innerHTML = 
            `<p class="text-center py-8 text-red-600">${message}</p>`;
    });
}
</script>

{{-- Payment History Modal --}}
<div id="paymentHistoryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" onclick="if(event.target === this) closePaymentHistory()">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[85vh] flex flex-col" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-6 border-b" style="border-color: var(--border);">
            <div>
                <h3 class="text-xl font-bold" style="color: var(--text);">
                    <i class="fa-solid fa-history mr-2" style="color: var(--green);"></i>Transaction History
                </h3>
                <p class="text-sm mt-1" style="color: var(--muted);">Bank payments and wallet transactions</p>
            </div>
            <button onclick="closePaymentHistory()" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100">
                <i class="fa-solid fa-times text-sm" style="color: var(--muted);"></i>
            </button>
        </div>
        
        {{-- Tabs --}}
        <div class="flex border-b" style="border-color: var(--border);">
            <button onclick="switchHistoryTab('bank')" id="bankTab" class="flex-1 px-6 py-3 font-semibold transition-all border-b-2" style="color: var(--green); border-color: var(--green);">
                <i class="fa-solid fa-building-columns mr-2"></i>Bank Payments
            </button>
            <button onclick="switchHistoryTab('wallet')" id="walletTab" class="flex-1 px-6 py-3 font-semibold transition-all border-b-2 border-transparent" style="color: var(--muted);">
                <i class="fa-solid fa-wallet mr-2"></i>Wallet History
            </button>
            <button onclick="switchHistoryTab('reconciliation')" id="reconciliationTab" class="flex-1 px-6 py-3 font-semibold transition-all border-b-2 border-transparent" style="color: var(--muted);">
                <i class="fa-solid fa-chart-line mr-2"></i>Reconciliation
            </button>
        </div>
        
        {{-- Tab Contents --}}
        <div class="flex-1 overflow-y-auto p-6">
            {{-- Bank Payments Tab --}}
            <div id="bankContent" class="tab-content">
                <div class="text-center py-8">
                    <i class="fa-solid fa-spinner fa-spin text-2xl" style="color: var(--green);"></i>
                </div>
            </div>
            
            {{-- Wallet History Tab --}}
            <div id="walletContent" class="tab-content hidden">
                <div class="text-center py-8">
                    <i class="fa-solid fa-spinner fa-spin text-2xl" style="color: var(--green);"></i>
                </div>
            </div>
            
            {{-- Reconciliation Tab --}}
            <div id="reconciliationContent" class="tab-content hidden">
                <div class="text-center py-8">
                    <i class="fa-solid fa-spinner fa-spin text-2xl" style="color: var(--green);"></i>
                </div>
            </div>
        </div>
        
        <div class="p-4 border-t text-center" style="border-color: var(--border);">
            <button onclick="closePaymentHistory()" class="px-6 py-2 rounded-lg font-semibold border" style="border-color: var(--border); color: var(--text);">
                Close
            </button>
        </div>
    </div>
</div>
@endsection
