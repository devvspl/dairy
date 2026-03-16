@extends('layouts.app')

@section('title', 'Order ' . $productOrder->order_id)
@section('page-title', 'Order Details')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold" style="color: var(--text);">{{ $productOrder->order_id }}</h2>
            <p class="text-xs" style="color: var(--muted);">Placed on {{ $productOrder->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <a href="{{ route('member.product-orders.index') }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border font-semibold text-sm hover:bg-gray-50"
           style="border-color: var(--border); color: var(--text);">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    @php
        $badge = match($productOrder->status) {
            'success'   => ['cls' => 'bg-green-100 text-green-800', 'icon' => 'fa-check-circle'],
            'pending'   => ['cls' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-clock'],
            'failed'    => ['cls' => 'bg-red-100 text-red-800', 'icon' => 'fa-times-circle'],
            'cancelled' => ['cls' => 'bg-gray-100 text-gray-600', 'icon' => 'fa-ban'],
            default     => ['cls' => 'bg-gray-100 text-gray-600', 'icon' => 'fa-circle'],
        };
    @endphp

    <!-- Status Card -->
    <div class="bg-white rounded-xl shadow-sm border p-5" style="border-color: var(--border);">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <p class="text-xs font-medium mb-1" style="color: var(--muted);">Order Status</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full font-semibold text-sm {{ $badge['cls'] }}">
                    <i class="fa-solid {{ $badge['icon'] }}"></i>
                    {{ ucfirst($productOrder->status) }}
                </span>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium mb-1" style="color: var(--muted);">Total Amount</p>
                <p class="text-2xl font-bold" style="color: var(--green);">₹{{ number_format($productOrder->amount, 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t" style="border-color: var(--border);">
            <div>
                <p class="text-xs" style="color: var(--muted);">Payment Method</p>
                <p class="text-sm font-semibold mt-0.5" style="color: var(--text);">
                    {{ ucfirst(str_replace('_', ' ', $productOrder->payment_method)) }}
                </p>
            </div>
            @if($productOrder->transaction_id)
            <div>
                <p class="text-xs" style="color: var(--muted);">Transaction ID</p>
                <p class="text-sm font-mono font-semibold mt-0.5 break-all" style="color: var(--text);">{{ $productOrder->transaction_id }}</p>
            </div>
            @endif
            @if($productOrder->paid_at)
            <div>
                <p class="text-xs" style="color: var(--muted);">Paid At</p>
                <p class="text-sm font-semibold mt-0.5" style="color: var(--text);">{{ $productOrder->paid_at->format('d M Y, h:i A') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Items -->
    @if($productOrder->items && count($productOrder->items))
    <div class="bg-white rounded-xl shadow-sm border" style="border-color: var(--border);">
        <div class="px-5 py-4 border-b" style="border-color: var(--border);">
            <h3 class="font-bold text-sm" style="color: var(--text);">
                <i class="fa-solid fa-box mr-2" style="color: var(--green);"></i>Items Ordered
            </h3>
        </div>
        <div class="divide-y" style="border-color: var(--border);">
            @foreach($productOrder->items as $item)
            <div class="px-5 py-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    @if(!empty($item['image']))
                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] ?? '' }}"
                         class="w-12 h-12 rounded-lg object-cover border" style="border-color: var(--border);">
                    @else
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47,74,30,0.08);">
                        <i class="fa-solid fa-box text-lg" style="color: var(--green);"></i>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-semibold" style="color: var(--text);">{{ $item['name'] ?? 'Product' }}</p>
                        @if(!empty($item['variant'])) <p class="text-xs" style="color: var(--muted);">{{ $item['variant'] }}</p> @endif
                        <p class="text-xs" style="color: var(--muted);">Qty: {{ $item['qty'] ?? 1 }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold" style="color: var(--green);">
                        ₹{{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 2) }}
                    </p>
                    <p class="text-xs" style="color: var(--muted);">₹{{ number_format($item['price'] ?? 0, 2) }} each</p>
                </div>
            </div>
            @endforeach
        </div>
        <!-- Total row -->
        <div class="px-5 py-3 border-t flex justify-between items-center" style="border-color: var(--border); background-color: rgba(47,74,30,0.03);">
            <span class="font-bold text-sm" style="color: var(--text);">Total</span>
            <span class="font-bold text-base" style="color: var(--green);">₹{{ number_format($productOrder->amount, 2) }}</span>
        </div>
    </div>
    @endif

    <!-- Delivery Address -->
    @if($productOrder->delivery_address)
    <div class="bg-white rounded-xl shadow-sm border p-5" style="border-color: var(--border);">
        <h3 class="font-bold text-sm mb-2" style="color: var(--text);">
            <i class="fa-solid fa-location-dot mr-2" style="color: var(--green);"></i>Delivery Address
        </h3>
        <p class="text-sm" style="color: var(--muted);">{{ $productOrder->delivery_address }}</p>
    </div>
    @endif

</div>
@endsection
