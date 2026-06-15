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
    
    const totalBankPayments = parseFloat(data.total_bank_payments);
    const totalCredits = parseFloat(data.total_credits);
    const totalDebits = parseFloat(data.total_debits);
    const currentBalance = parseFloat(data.current_balance);
    const expectedBalance = totalCredits - totalDebits;
    const difference = currentBalance - expectedBalance;
    
    const isBalanced = Math.abs(difference) < 0.01; // Allow for rounding errors
    const bankMatch = Math.abs(totalBankPayments - totalCredits) < 0.01;
    
    let suggestions = [];
    let fixes = [];
    
    if (!isBalanced) {
        suggestions.push({
            type: 'error',
            icon: 'fa-exclamation-triangle',
            title: 'Balance Mismatch',
            message: `Current balance (₹${currentBalance.toFixed(2)}) doesn't match expected balance (₹${expectedBalance.toFixed(2)}). Difference: ₹${Math.abs(difference).toFixed(2)}`,
            action: 'Click "Sync Balance" to recalculate balance from transactions.'
        });
        fixes.push({
            type: 'sync_balance',
            label: 'Sync Balance',
            icon: 'fa-sync',
            description: 'Recalculate balance from all wallet transactions'
        });
    }
    
    if (!bankMatch) {
        const bankDiff = totalBankPayments - totalCredits;
        suggestions.push({
            type: 'warning',
            icon: 'fa-exclamation-circle',
            title: 'Bank Payment Mismatch',
            message: `Bank payments (₹${totalBankPayments.toFixed(2)}) don't match wallet credits (₹${totalCredits.toFixed(2)}). Difference: ₹${Math.abs(bankDiff).toFixed(2)}`,
            action: bankDiff > 0 
                ? 'Click "Sync Bank to Wallet" to credit missing payments to wallet.'
                : 'Wallet has more credits than bank payments. Manual review needed.'
        });
        if (bankDiff > 0) {
            fixes.push({
                type: 'sync_bank_to_wallet',
                label: 'Sync Bank to Wallet',
                icon: 'fa-building-columns',
                description: 'Credit missing bank payments to wallet'
            });
        }
    }
    
    if (totalDebits > totalCredits) {
        suggestions.push({
            type: 'error',
            icon: 'fa-triangle-exclamation',
            title: 'Negative Balance Allowed',
            message: `Total debits (₹${totalDebits.toFixed(2)}) exceed total credits (₹${totalCredits.toFixed(2)})`,
            action: 'This should not happen. Click "Fix Negative Balance" to correct.'
        });
        fixes.push({
            type: 'prevent_negative',
            label: 'Fix Negative Balance',
            icon: 'fa-plus',
            description: 'Add credit to bring balance to zero'
        });
    }
    
    if (isBalanced && bankMatch && suggestions.length === 0) {
        suggestions.push({
            type: 'success',
            icon: 'fa-circle-check',
            title: 'All Clear!',
            message: 'All accounts are balanced and reconciled correctly.',
            action: 'No action needed. Continue monitoring regular transactions.'
        });
    }
    
    let html = `
        <div class="space-y-4">
            <!-- Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-white rounded-xl p-4 border" style="border-color: var(--border);">
                    <p class="text-xs font-semibold mb-1" style="color: var(--muted);">Bank Payments</p>
                    <p class="text-xl font-bold" style="color: var(--green);">₹${totalBankPayments.toFixed(2)}</p>
                </div>
                <div class="bg-white rounded-xl p-4 border" style="border-color: var(--border);">
                    <p class="text-xs font-semibold mb-1" style="color: var(--muted);">Wallet Credits</p>
                    <p class="text-xl font-bold text-green-600">₹${totalCredits.toFixed(2)}</p>
                </div>
                <div class="bg-white rounded-xl p-4 border" style="border-color: var(--border);">
                    <p class="text-xs font-semibold mb-1" style="color: var(--muted);">Wallet Debits</p>
                    <p class="text-xl font-bold text-red-600">₹${totalDebits.toFixed(2)}</p>
                </div>
                <div class="bg-white rounded-xl p-4 border" style="border-color: var(--border);">
                    <p class="text-xs font-semibold mb-1" style="color: var(--muted);">Current Balance</p>
                    <p class="text-xl font-bold" style="color: var(--text);">₹${currentBalance.toFixed(2)}</p>
                </div>
            </div>
            
            <!-- Reconciliation Status -->
            <div class="bg-${isBalanced ? 'green' : 'red'}-50 rounded-xl p-4 border-2 border-${isBalanced ? 'green' : 'red'}-200">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-${isBalanced ? 'check-circle' : 'times-circle'} text-2xl text-${isBalanced ? 'green' : 'red'}-600"></i>
                    <div class="flex-1">
                        <p class="font-bold text-${isBalanced ? 'green' : 'red'}-700">${isBalanced ? 'Books are Balanced' : 'Books Need Reconciliation'}</p>
                        <p class="text-sm text-${isBalanced ? 'green' : 'red'}-600">
                            Expected: ₹${expectedBalance.toFixed(2)} | Actual: ₹${currentBalance.toFixed(2)}
                            ${!isBalanced ? ` | Diff: ₹${Math.abs(difference).toFixed(2)}` : ''}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Fixes -->
            ${fixes.length > 0 ? `
            <div class="bg-blue-50 rounded-xl p-4 border-2 border-blue-200">
                <h4 class="font-bold text-sm mb-3 text-blue-700">
                    <i class="fa-solid fa-wrench mr-2"></i>Quick Fixes Available
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-${fixes.length > 1 ? '2' : '1'} gap-2">
                    ${fixes.map(fix => `
                        <button onclick="applyFix('${fix.type}')" 
                                class="flex items-center gap-3 p-3 rounded-lg border-2 border-blue-300 bg-white hover:bg-blue-50 transition-all">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid ${fix.icon} text-blue-600"></i>
                            </div>
                            <div class="text-left flex-1">
                                <p class="font-bold text-sm text-blue-700">${fix.label}</p>
                                <p class="text-xs text-blue-600">${fix.description}</p>
                            </div>
                            <i class="fa-solid fa-chevron-right text-blue-400"></i>
                        </button>
                    `).join('')}
                </div>
            </div>
            ` : ''}
            
            <!-- Suggestions -->
            <div class="space-y-3">
                <h4 class="font-bold text-sm" style="color: var(--text);">
                    <i class="fa-solid fa-lightbulb mr-2" style="color: var(--green);"></i>Recommendations
                </h4>
    `;
    
    suggestions.forEach(sug => {
        const colors = {
            error: { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-700', icon: 'text-red-600' },
            warning: { bg: 'bg-yellow-50', border: 'border-yellow-200', text: 'text-yellow-700', icon: 'text-yellow-600' },
            success: { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-700', icon: 'text-green-600' }
        };
        const color = colors[sug.type];
        
        html += `
            <div class="p-4 rounded-lg border-2 ${color.bg} ${color.border}">
                <div class="flex items-start gap-3">
                    <i class="fa-solid ${sug.icon} text-lg ${color.icon} mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-bold text-sm ${color.text} mb-1">${sug.title}</p>
                        <p class="text-sm ${color.text} mb-2">${sug.message}</p>
                        <p class="text-xs" style="color: var(--muted);"><strong>Action:</strong> ${sug.action}</p>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `
            </div>
        </div>
    `;
    
    container.innerHTML = html;
}

function applyFix(fixType) {
    if (!confirm('Are you sure you want to apply this fix? This will modify the wallet balance and transactions.')) {
        return;
    }
    
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Applying fix...';
    
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
            alert('✅ ' + data.message);
            // Reload payment history to show updated data
            loadPaymentHistory();
            // Reload page to update wallet balance display
            window.location.reload();
        } else {
            alert('❌ Error: ' + data.message);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    })
    .catch(error => {
        alert('❌ An error occurred while applying the fix');
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
