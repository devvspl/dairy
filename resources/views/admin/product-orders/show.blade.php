@extends('layouts.app')

@section('title', 'Order ' . $productOrder->order_id)
@section('page-title', 'Product Order')

@section('content')
<div class="space-y-5 max-w-3xl">

    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-mono" style="color: var(--muted);">{{ $productOrder->order_id }}</p>
        </div>
        <a href="{{ route('admin.product-orders.index') }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border text-sm font-semibold"
           style="border-color: var(--border); color: var(--text);">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Status + Amount -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-xs mb-1" style="color: var(--muted);">Status</p>
            <span class="px-2 py-1 text-xs rounded-full font-semibold
                {{ $productOrder->status === 'success'   ? 'bg-green-100 text-green-800'  : '' }}
                {{ $productOrder->status === 'pending'   ? 'bg-yellow-100 text-yellow-800': '' }}
                {{ $productOrder->status === 'failed'    ? 'bg-red-100 text-red-800'      : '' }}
                {{ $productOrder->status === 'cancelled' ? 'bg-gray-100 text-gray-800'    : '' }}">
                {{ ucfirst($productOrder->status) }}
            </span>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-xs mb-1" style="color: var(--muted);">Amount</p>
            <p class="text-xl font-bold" style="color: var(--green);">₹{{ number_format($productOrder->amount, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-xs mb-1" style="color: var(--muted);">Payment</p>
            <p class="text-sm font-semibold" style="color: var(--text);">{{ ucfirst($productOrder->payment_method) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border" style="border-color: var(--border);">
            <p class="text-xs mb-1" style="color: var(--muted);">Date</p>
            <p class="text-sm font-semibold" style="color: var(--text);">{{ $productOrder->created_at->format('d M Y') }}</p>
        </div>
    </div>

    <!-- Customer -->
    <div class="bg-white rounded-lg shadow-sm p-5 border" style="border-color: var(--border);">
        <h3 class="font-bold mb-3" style="color: var(--text);">Customer Details</h3>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div><span style="color: var(--muted);">Name:</span> <span class="font-medium" style="color: var(--text);">{{ $productOrder->customer_name }}</span></div>
            <div><span style="color: var(--muted);">Phone:</span> <span class="font-medium" style="color: var(--text);">{{ $productOrder->customer_phone }}</span></div>
            @if($productOrder->customer_email)
            <div><span style="color: var(--muted);">Email:</span> <span class="font-medium" style="color: var(--text);">{{ $productOrder->customer_email }}</span></div>
            @endif
            <div class="col-span-2"><span style="color: var(--muted);">Address:</span> <span class="font-medium" style="color: var(--text);">{{ $productOrder->delivery_address }}</span></div>
        </div>
    </div>

    <!-- Items -->
    <div class="bg-white rounded-lg shadow-sm border" style="border-color: var(--border);">
        <div class="px-5 py-4 border-b font-bold" style="border-color: var(--border); color: var(--text);">Items Ordered</div>
        <table class="w-full">
            <thead class="border-b text-xs" style="border-color: var(--border); background: #fafafa;">
                <tr>
                    <th class="px-5 py-2 text-left font-semibold" style="color: var(--muted);">Product</th>
                    <th class="px-5 py-2 text-right font-semibold" style="color: var(--muted);">Price</th>
                    <th class="px-5 py-2 text-right font-semibold" style="color: var(--muted);">Qty</th>
                    <th class="px-5 py-2 text-right font-semibold" style="color: var(--muted);">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productOrder->items as $item)
                <tr class="border-b" style="border-color: var(--border);">
                    <td class="px-5 py-3 text-sm font-medium" style="color: var(--text);">{{ $item['name'] }}</td>
                    <td class="px-5 py-3 text-sm text-right" style="color: var(--muted);">₹{{ number_format($item['price'], 2) }}</td>
                    <td class="px-5 py-3 text-sm text-right" style="color: var(--muted);">{{ $item['quantity'] }}</td>
                    <td class="px-5 py-3 text-sm text-right font-bold" style="color: var(--green);">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="px-5 py-3 text-right font-bold" style="color: var(--text);">Grand Total</td>
                    <td class="px-5 py-3 text-right font-bold text-lg" style="color: var(--green);">₹{{ number_format($productOrder->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($productOrder->transaction_id)
    <div class="bg-white rounded-lg shadow-sm p-5 border text-sm" style="border-color: var(--border);">
        <span style="color: var(--muted);">Transaction ID:</span>
        <span class="font-mono font-semibold ml-2" style="color: var(--text);">{{ $productOrder->transaction_id }}</span>
    </div>
    @endif

</div>
@endsection
