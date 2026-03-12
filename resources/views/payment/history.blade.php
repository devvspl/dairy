@extends('layouts.app')

@section('title', 'Payment History')
@section('page-title', 'Payment History')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--text);">
                    <i class="fa-solid fa-history mr-2" style="color: var(--green);"></i>Payment History
                </h1>
                <p class="text-sm mt-1" style="color: var(--muted);">View all your payment transactions</p>
            </div>
            <a href="{{ route('member.dashboard') }}" 
               class="px-4 py-2 rounded-lg font-semibold border transition-colors hover:bg-gray-50"
               style="border-color: var(--border); color: var(--text);">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Orders List -->
    @forelse($orders as $order)
    <div class="bg-white rounded-xl shadow-sm p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Order Info -->
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h3 class="font-bold text-lg" style="color: var(--text);">
                        {{ $order->membershipPlan->name }}
                    </h3>
                    @if($order->isSuccess())
                    <span class="px-3 py-1 text-xs rounded-full font-bold bg-green-100 text-green-700">
                        <i class="fa-solid fa-check-circle mr-1"></i>Success
                    </span>
                    @elseif($order->isPending())
                    <span class="px-3 py-1 text-xs rounded-full font-bold bg-yellow-100 text-yellow-700">
                        <i class="fa-solid fa-clock mr-1"></i>Pending
                    </span>
                    @else
                    <span class="px-3 py-1 text-xs rounded-full font-bold bg-red-100 text-red-700">
                        <i class="fa-solid fa-times-circle mr-1"></i>Failed
                    </span>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                    <div>
                        <p style="color: var(--muted);">Order ID</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $order->order_id }}</p>
                    </div>
                    <div>
                        <p style="color: var(--muted);">Transaction ID</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $order->transaction_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p style="color: var(--muted);">Date</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p style="color: var(--muted);">Payment Method</p>
                        <p class="font-semibold" style="color: var(--text);">{{ ucfirst($order->payment_method) }}</p>
                    </div>
                </div>
            </div>

            <!-- Amount -->
            <div class="text-right">
                <p class="text-2xl font-bold" style="color: var(--green);">₹{{ number_format($order->amount, 2) }}</p>
                @if($order->paid_at)
                <p class="text-xs mt-1" style="color: var(--muted);">
                    Paid on {{ $order->paid_at->format('M d, Y') }}
                </p>
                @endif
                @if($order->isSuccess())
                <a href="{{ route('payment.invoice', $order) }}" 
                   class="inline-block mt-2 px-3 py-1 text-xs rounded-lg font-semibold border transition-colors hover:bg-gray-50"
                   style="border-color: var(--border); color: var(--text);">
                    <i class="fa-solid fa-file-invoice mr-1"></i>View Invoice
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm p-12 text-center border" style="border-color: var(--border);">
        <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
            <i class="fa-solid fa-receipt text-2xl" style="color: var(--green);"></i>
        </div>
        <h3 class="text-xl font-bold mb-2" style="color: var(--text);">No Payment History</h3>
        <p class="mb-6" style="color: var(--muted);">You haven't made any payments yet.</p>
        <a href="{{ route('member.dashboard') }}" 
           class="inline-flex items-center px-6 py-3 rounded-lg font-bold transition-all hover:shadow-lg text-white"
           style="background-color: var(--green);">
            <i class="fa-solid fa-shopping-cart mr-2"></i>Browse Plans
        </a>
    </div>
    @endforelse

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
