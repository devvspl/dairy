@extends('layouts.app')

@section('title', 'Product Orders')
@section('page-title', 'Product Orders')

@section('content')
<div class="space-y-5">

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $all = \App\Models\ProductOrder::query();
        @endphp
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Total Orders</p>
            <p class="text-3xl font-bold" style="color: var(--text);">{{ (clone $all)->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Successful</p>
            <p class="text-3xl font-bold" style="color: var(--green);">{{ (clone $all)->where('status','success')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ (clone $all)->where('status','pending')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Revenue</p>
            <p class="text-3xl font-bold" style="color: var(--green);">₹{{ number_format((clone $all)->where('status','success')->sum('amount'), 0) }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b" style="border-color: var(--border); background-color: rgba(47,74,30,0.05);">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Order ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Customer</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Items</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Amount</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold" style="color: var(--text);"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-b hover:bg-gray-50" style="border-color: var(--border);">
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
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full font-semibold
                                {{ $order->status === 'success'   ? 'bg-green-100 text-green-800'  : '' }}
                                {{ $order->status === 'pending'   ? 'bg-yellow-100 text-yellow-800': '' }}
                                {{ $order->status === 'failed'    ? 'bg-red-100 text-red-800'      : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-gray-100 text-gray-800'    : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
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
                        <td colspan="7" class="px-4 py-10 text-center" style="color: var(--muted);">No product orders yet.</td>
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
