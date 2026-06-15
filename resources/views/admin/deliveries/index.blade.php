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
            <div class="flex-1">
                <h3 class="font-bold text-lg" style="color: var(--text);">{{ $subscription->user->name }}</h3>
                <p class="text-sm" style="color: var(--muted);">
                    {{ $subscription->membershipPlan?->name ?? 'Milk Wallet' }} —
                    {{ $subscription->start_date->format('M d, Y') }} to {{ $subscription->end_date->format('M d, Y') }}
                </p>
                @if(!$subscription->membership_plan_id || $subscription->membershipPlan?->isOnDemand())
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    <span class="px-2 py-0.5 text-xs rounded-full font-semibold bg-blue-100 text-blue-800">🛒 On-Demand / Wallet</span>
                    <span class="text-sm" style="color: var(--muted);">Qty/day: <strong style="color:var(--text);">{{ $subscription->quantity_per_day }} L</strong></span>
                    <span class="text-sm" style="color: var(--muted);">Rate: <strong style="color:var(--text);">₹{{ number_format($subscription->price_per_litre, 2) }}/L</strong></span>
                </div>
                @endif
            </div>
            
            {{-- Wallet Balance Card --}}
            @if(!$subscription->membership_plan_id || $subscription->membershipPlan?->isOnDemand())
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border-2 min-w-[200px]" style="border-color: var(--green);">
                <p class="text-xs font-semibold mb-1" style="color: var(--green);">
                    <i class="fa-solid fa-wallet mr-1"></i>Wallet Balance
                </p>
                <p class="text-2xl font-bold mb-1" style="color: var(--green);">₹{{ number_format($subscription->wallet_balance, 2) }}</p>
                <p class="text-xs mb-2" style="color: var(--muted);">Total: ₹{{ number_format($subscription->wallet_total, 2) }}</p>
                
                @if($walletStats)
                <div class="flex items-center gap-3 text-xs mb-2 pb-2 border-t pt-2" style="border-color: rgba(47,74,30,0.2);">
                    <div>
                        <p style="color: var(--muted);">Credits</p>
                        <p class="font-bold text-green-700">₹{{ number_format($walletStats['total_credits'], 2) }}</p>
                    </div>
                    <div>
                        <p style="color: var(--muted);">Debits</p>
                        <p class="font-bold text-red-600">₹{{ number_format($walletStats['total_debits'], 2) }}</p>
                    </div>
                    <div>
                        <p style="color: var(--muted);">Txns</p>
                        <p class="font-bold" style="color: var(--text);">{{ $walletStats['transaction_count'] }}</p>
                    </div>
                </div>
                @endif
                
                <button onclick="openPaymentHistory()" class="text-xs font-semibold hover:underline" style="color: var(--green);">
                    <i class="fa-solid fa-history mr-1"></i>View Payment History
                </button>
            </div>
            @endif
        </div>
        
        <div class="flex items-center gap-2 flex-wrap mt-4">
            <form method="POST" action="{{ route('admin.subscriptions.deliveries.generate', $subscription) }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-lg font-semibold" style="background-color: var(--green); color: #fff;">
                    <i class="fa-solid fa-calendar-plus mr-2"></i>
                    {{ (!$subscription->membership_plan_id || $subscription->membershipPlan?->isOnDemand()) ? 'Generate Daily Entries' : 'Generate Schedule' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.subscriptions.deliveries.reset', $subscription) }}"
                  onsubmit="return confirm('Delete ALL delivery entries for this subscription? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-lg font-semibold" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;">
                    <i class="fa-solid fa-rotate-left mr-2"></i>Reset
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

// Payment History Modal
function openPaymentHistory() {
    const modal = document.getElementById('paymentHistoryModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Load payment history via AJAX
    loadPaymentHistory();
}

function closePaymentHistory() {
    const modal = document.getElementById('paymentHistoryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function loadPaymentHistory() {
    const container = document.getElementById('paymentHistoryContent');
    container.innerHTML = '<div class="text-center py-8"><i class="fa-solid fa-spinner fa-spin text-2xl" style="color: var(--green);"></i></div>';
    
    fetch('/admin/subscriptions/{{ $subscription->id }}/payment-history')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderPaymentHistory(data.transactions);
            } else {
                container.innerHTML = '<p class="text-center py-8 text-red-600">Failed to load payment history</p>';
            }
        })
        .catch(error => {
            container.innerHTML = '<p class="text-center py-8 text-red-600">Error loading payment history</p>';
        });
}

function renderPaymentHistory(transactions) {
    const container = document.getElementById('paymentHistoryContent');
    
    if (transactions.length === 0) {
        container.innerHTML = '<p class="text-center py-8" style="color: var(--muted);">No payment history found.</p>';
        return;
    }
    
    let html = '<div class="space-y-3">';
    
    transactions.forEach(txn => {
        const isCredit = txn.type === 'credit';
        const icon = isCredit ? 'fa-arrow-up' : 'fa-arrow-down';
        const bgColor = isCredit ? 'bg-green-50' : 'bg-red-50';
        const textColor = isCredit ? 'text-green-700' : 'text-red-700';
        const borderColor = isCredit ? 'border-green-200' : 'border-red-200';
        const amountPrefix = isCredit ? '+' : '-';
        
        html += `
            <div class="flex items-start gap-3 p-3 rounded-lg border ${bgColor}" style="border-color: ${borderColor};">
                <div class="w-10 h-10 rounded-full flex items-center justify-center ${bgColor}">
                    <i class="fa-solid ${icon} ${textColor}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1">
                            <p class="text-sm font-semibold ${textColor}">${amountPrefix}₹${parseFloat(txn.amount).toFixed(2)}</p>
                            <p class="text-xs" style="color: var(--muted);">${txn.description || '-'}</p>
                            ${txn.litres ? `<p class="text-xs mt-1" style="color: var(--muted);">Quantity: ${txn.litres}L</p>` : ''}
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-semibold" style="color: var(--text);">₹${parseFloat(txn.balance_after).toFixed(2)}</p>
                            <p class="text-xs" style="color: var(--muted);">Balance</p>
                        </div>
                    </div>
                    <p class="text-xs mt-1" style="color: var(--muted);">
                        <i class="fa-solid fa-calendar mr-1"></i>${txn.date}
                    </p>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
}
</script>

{{-- Payment History Modal --}}
<div id="paymentHistoryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" onclick="if(event.target === this) closePaymentHistory()">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] flex flex-col" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-6 border-b" style="border-color: var(--border);">
            <div>
                <h3 class="text-xl font-bold" style="color: var(--text);">
                    <i class="fa-solid fa-history mr-2" style="color: var(--green);"></i>Payment History
                </h3>
                <p class="text-sm mt-1" style="color: var(--muted);">All wallet transactions for this subscription</p>
            </div>
            <button onclick="closePaymentHistory()" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100">
                <i class="fa-solid fa-times text-sm" style="color: var(--muted);"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6" id="paymentHistoryContent">
            <div class="text-center py-8">
                <i class="fa-solid fa-spinner fa-spin text-2xl" style="color: var(--green);"></i>
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
