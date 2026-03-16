@extends('layouts.app')

@section('title', 'My Product Orders')
@section('page-title', 'My Orders')

@section('content')
<div class="space-y-5">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold" style="color: var(--text);">Product Orders</h2>
            <p class="text-xs" style="color: var(--muted);">All your product purchase history</p>
        </div>
        <a href="{{ route('member.dashboard') }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border font-semibold text-sm hover:bg-gray-50"
           style="border-color: var(--border); color: var(--text);">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search order ID..."
                   class="px-3 py-2 border rounded-lg text-sm min-w-[180px]" style="border-color: var(--border);">
            <select name="status" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                <option value="">All Status</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                <option value="success"   {{ request('status') === 'success'   ? 'selected' : '' }}>Success</option>
                <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>Failed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-sm text-white" style="background-color: var(--green);">
                <i class="fa-solid fa-filter mr-1"></i>Filter
            </button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('member.product-orders.index') }}"
               class="px-4 py-2 rounded-lg border font-semibold text-sm" style="border-color: var(--border); color: var(--text);">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm border" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b" style="border-color: var(--border); background-color: rgba(47,74,30,0.05);">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Order ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Items</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Amount</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php
                        $badge = match($order->status) {
                            'success'   => 'bg-green-100 text-green-800',
                            'pending'   => 'bg-yellow-100 text-yellow-800',
                            'failed'    => 'bg-red-100 text-red-800',
                            'cancelled' => 'bg-gray-100 text-gray-600',
                            default     => 'bg-gray-100 text-gray-600',
                        };
                        $itemCount = $order->items ? count($order->items) : 0;
                        $firstItem = $order->items[0]['name'] ?? '—';
                    @endphp
                    <tr class="border-b hover:bg-gray-50" style="border-color: var(--border);">
                        <td class="px-4 py-3">
                            <span class="font-mono text-sm font-semibold" style="color: var(--text);">{{ $order->order_id }}</span>
                            @if($order->transaction_id)
                            <div class="text-xs mt-0.5" style="color: var(--muted);">TXN: {{ $order->transaction_id }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-sm" style="color: var(--text);">{{ $firstItem }}</span>
                            @if($itemCount > 1)
                            <span class="text-xs ml-1" style="color: var(--muted);">+{{ $itemCount - 1 }} more</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-sm" style="color: var(--green);">₹{{ number_format($order->amount, 0) }}</span>
                            <div class="text-xs" style="color: var(--muted);">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $badge }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm" style="color: var(--muted);">
                            {{ $order->created_at->format('d M Y') }}
                            <div class="text-xs">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('member.product-orders.show', $order) }}"
                               class="text-sm font-semibold hover:underline" style="color: var(--green);">
                                <i class="fa-solid fa-eye mr-1"></i>View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center" style="color: var(--muted);">
                            <i class="fa-solid fa-bag-shopping text-4xl mb-3 block opacity-30"></i>
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
@endsection
