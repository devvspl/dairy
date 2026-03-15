@extends('layouts.app')

@section('title', 'Subscription Details')
@section('page-title', 'Subscription #' . $subscription->id)

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center text-sm font-semibold hover:underline" style="color: var(--green);">
        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Subscriptions
    </a>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4" style="border-color: var(--green);">
        <p class="font-semibold" style="color: var(--green);">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User & Plan Info -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Subscription Details</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">User</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $subscription->user->name }}</p>
                        <p class="text-sm" style="color: var(--muted);">{{ $subscription->user->email }}</p>
                        <p class="text-sm" style="color: var(--muted);">{{ $subscription->user->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Membership Plan</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $subscription->membershipPlan->name }}</p>
                        <p class="text-sm" style="color: var(--muted);">{{ $subscription->membershipPlan->duration }}</p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t" style="border-color: var(--border);">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Start Date</p>
                            <p class="font-semibold" style="color: var(--text);">{{ $subscription->start_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium mb-1" style="color: var(--muted);">End Date</p>
                            <p class="font-semibold" style="color: var(--text);">{{ $subscription->end_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Days Remaining</p>
                            <p class="font-semibold" style="color: var(--green);">{{ $subscription->daysRemaining() }} days</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Created</p>
                            <p class="font-semibold" style="color: var(--text);">{{ $subscription->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t" style="border-color: var(--border);">
                    <a href="{{ route('admin.subscriptions.deliveries.index', $subscription) }}" class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-sm" style="background-color: var(--green); color: #fff;">
                        <i class="fa-solid fa-truck mr-2"></i>View Delivery Logs
                    </a>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Delivery Address</h3>
                <p class="text-sm" style="color: var(--text);">{{ $subscription->delivery_address }}</p>
            </div>

            <!-- Day-wise Schedule -->
            @if($subscription->membershipPlan->day_wise_schedule)
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Weekly Delivery Schedule</h3>
                
                <div class="grid grid-cols-7 gap-2">
                    @php
                        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    @endphp
                    @foreach($days as $day)
                        @php
                            $hasDelivery = $subscription->membershipPlan->hasDeliveryOnDay($day);
                            $quantity = $subscription->membershipPlan->getDayQuantity($day);
                        @endphp
                        <div class="text-center p-3 rounded-lg border" style="border-color: {{ $hasDelivery ? 'var(--green)' : 'var(--border)' }}; background-color: {{ $hasDelivery ? 'rgba(47, 74, 30, 0.05)' : '#f9f9f9' }};">
                            <p class="text-xs font-bold mb-1" style="color: var(--text);">{{ $day }}</p>
                            @if($hasDelivery)
                                <p class="text-lg font-bold" style="color: var(--green);">{{ $quantity }}L</p>
                            @else
                                <p class="text-xs" style="color: var(--muted);">No delivery</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t grid grid-cols-3 gap-4" style="border-color: var(--border);">
                    <div class="text-center">
                        <p class="text-sm" style="color: var(--muted);">Delivery Days</p>
                        <p class="text-xl font-bold" style="color: var(--green);">{{ $subscription->membershipPlan->getDeliveryDaysCount() }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm" style="color: var(--muted);">Weekly Total</p>
                        <p class="text-xl font-bold" style="color: var(--green);">{{ $subscription->membershipPlan->getTotalWeeklyQuantity() }} L</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm" style="color: var(--muted);">Avg per Day</p>
                        <p class="text-xl font-bold" style="color: var(--green);">
                            {{ number_format($subscription->membershipPlan->getTotalWeeklyQuantity() / max($subscription->membershipPlan->getDeliveryDaysCount(), 1), 1) }} L
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Notes</h3>
                
                @if($subscription->notes)
                <div class="bg-gray-50 rounded-lg p-4 mb-4 whitespace-pre-wrap text-sm" style="color: var(--text);">{{ $subscription->notes }}</div>
                @else
                <p class="text-sm mb-4" style="color: var(--muted);">No notes added yet.</p>
                @endif

                <form method="POST" action="{{ route('admin.subscriptions.add-note', $subscription) }}">
                    @csrf
                    <textarea name="notes" rows="3" required class="w-full px-3 py-2 border rounded-lg mb-2" style="border-color: var(--border);" placeholder="Add a note..."></textarea>
                    <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-sm" style="background-color: var(--green); color: #fff;">
                        <i class="fa-solid fa-plus mr-2"></i>Add Note
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Management -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Status</h3>
                
                <form method="POST" action="{{ route('admin.subscriptions.update-status', $subscription) }}">
                    @csrf
                    <select name="status" class="w-full px-3 py-2 border rounded-lg mb-3" style="border-color: var(--border);">
                        <option value="pending" {{ $subscription->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ $subscription->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="paused" {{ $subscription->status === 'paused' ? 'selected' : '' }}>Paused</option>
                        <option value="cancelled" {{ $subscription->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="expired" {{ $subscription->status === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg font-semibold" style="background-color: var(--green); color: #fff;">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Payment Management -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Payment Details</h3>
                
                <div class="space-y-3 mb-4">
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">Payment Method</p>
                        <p class="font-semibold" style="color: var(--text);">{{ ucfirst(str_replace('_', ' ', $subscription->payment_method)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">Amount</p>
                        <p class="text-xl font-bold" style="color: var(--green);">₹{{ number_format($subscription->amount_paid ?? $subscription->membershipPlan->price, 2) }}</p>
                    </div>
                    @if($subscription->transaction_id)
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">Transaction ID</p>
                        <p class="text-sm font-mono" style="color: var(--text);">{{ $subscription->transaction_id }}</p>
                    </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.subscriptions.update-payment', $subscription) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--text);">Payment Status</label>
                        <select name="payment_status" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <option value="pending" {{ $subscription->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $subscription->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $subscription->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $subscription->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--text);">Transaction ID</label>
                        <input type="text" name="transaction_id" value="{{ $subscription->transaction_id }}" 
                               class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--text);">Amount Paid</label>
                        <input type="number" step="0.01" name="amount_paid" value="{{ $subscription->amount_paid ?? $subscription->membershipPlan->price }}" 
                               class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg font-semibold" style="background-color: var(--green); color: #fff;">
                        Update Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
