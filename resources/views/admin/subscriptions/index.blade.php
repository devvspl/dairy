@extends('layouts.app')

@section('title', 'User Subscriptions')
@section('page-title', 'User Subscriptions')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text);">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="User name or email" 
                       class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text);">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="paused" {{ request('status') === 'paused' ? 'selected' : '' }}>Paused</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text);">Payment Status</label>
                <select name="payment_status" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    <option value="">All Payment Statuses</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-lg font-semibold" style="background-color: var(--green); color: #fff;">
                    <i class="fa-solid fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.subscriptions.index') }}" class="px-4 py-2 rounded-lg border font-semibold" style="border-color: var(--border); color: var(--text);">
                    <i class="fa-solid fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white rounded-lg shadow-sm border" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b" style="border-color: var(--border); background-color: rgba(47, 74, 30, 0.05);">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">User</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Plan</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Duration</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Payment</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Amount</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                    <tr class="border-b hover:bg-gray-50" style="border-color: var(--border);">
                        <td class="px-4 py-3 text-sm" style="color: var(--text);">#{{ $subscription->id }}</td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium" style="color: var(--text);">{{ $subscription->user->name }}</div>
                            <div class="text-xs" style="color: var(--muted);">{{ $subscription->user->email }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text);">{{ $subscription->membershipPlan->name }}</td>
                        <td class="px-4 py-3">
                            <div class="text-xs" style="color: var(--muted);">{{ $subscription->start_date->format('M d, Y') }}</div>
                            <div class="text-xs" style="color: var(--muted);">to {{ $subscription->end_date->format('M d, Y') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full font-semibold
                                {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $subscription->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $subscription->status === 'paused' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $subscription->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $subscription->status === 'expired' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full font-semibold
                                {{ $subscription->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $subscription->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $subscription->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $subscription->payment_status === 'refunded' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($subscription->payment_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold" style="color: var(--green);">
                            ₹{{ number_format($subscription->amount_paid ?? $subscription->membershipPlan->price, 2) }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.subscriptions.show', $subscription) }}" 
                               class="text-sm font-semibold hover:underline" style="color: var(--green);">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center" style="color: var(--muted);">
                            No subscriptions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
        <div class="px-4 py-3 border-t" style="border-color: var(--border);">
            {{ $subscriptions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
