@extends('layouts.app')

@section('title', 'Payment Successful')
@section('page-title', 'Payment Successful')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
        <!-- Success Icon -->
        <div class="w-20 h-20 rounded-full mx-auto mb-6 flex items-center justify-center" style="background-color: rgba(34, 197, 94, 0.1);">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Success Message -->
        <h1 class="text-3xl font-bold mb-3" style="color: var(--text);">Payment Successful! 🎉</h1>
        <p class="text-lg mb-6" style="color: var(--muted);">
            Your membership has been activated successfully.
        </p>

        <!-- Order Details -->
        <div class="bg-gray-50 rounded-xl p-6 mb-6 text-left">
            <h3 class="font-bold text-lg mb-4" style="color: var(--text);">Order Details</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span style="color: var(--muted);">Order ID:</span>
                    <span class="font-semibold" style="color: var(--text);">{{ $order->order_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span style="color: var(--muted);">Transaction ID:</span>
                    <span class="font-semibold" style="color: var(--text);">{{ $order->transaction_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span style="color: var(--muted);">Plan:</span>
                    <span class="font-semibold" style="color: var(--text);">{{ $order->membershipPlan->name ?? 'Milk Plan' }}</span>
                </div>
                <div class="flex justify-between">
                    <span style="color: var(--muted);">Amount Paid:</span>
                    <span class="font-bold text-xl" style="color: var(--green);">₹{{ number_format($order->amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span style="color: var(--muted);">Payment Date:</span>
                    <span class="font-semibold" style="color: var(--text);">{{ $order->paid_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="flex justify-between">
                    <span style="color: var(--muted);">Payment Method:</span>
                    <span class="font-semibold" style="color: var(--text);">PhonePe</span>
                </div>
            </div>
        </div>

        <!-- What's Next -->
        <div class="bg-green-50 rounded-xl p-6 mb-6 text-left border-2" style="border-color: var(--green);">
            <h3 class="font-bold text-lg mb-3 flex items-center" style="color: var(--green);">
                <i class="fa-solid fa-lightbulb mr-2"></i>What's Next?
            </h3>
            <ul class="space-y-2 text-sm">
                <li class="flex items-start">
                    <i class="fa-solid fa-check-circle mt-0.5 mr-2" style="color: var(--green);"></i>
                    <span style="color: var(--text);">Your membership is now active and ready to use</span>
                </li>
                <li class="flex items-start">
                    <i class="fa-solid fa-check-circle mt-0.5 mr-2" style="color: var(--green);"></i>
                    <span style="color: var(--text);">Fresh milk will be delivered as per your plan schedule</span>
                </li>
                <li class="flex items-start">
                    <i class="fa-solid fa-check-circle mt-0.5 mr-2" style="color: var(--green);"></i>
                    <span style="color: var(--text);">Track your deliveries from your dashboard</span>
                </li>
                <li class="flex items-start">
                    <i class="fa-solid fa-check-circle mt-0.5 mr-2" style="color: var(--green);"></i>
                    <span style="color: var(--text);">Contact support if you need any assistance</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('member.dashboard') }}" 
               class="flex-1 py-3 px-6 rounded-lg font-bold transition-all hover:shadow-lg text-white"
               style="background-color: var(--green);">
                <i class="fa-solid fa-home mr-2"></i>Go to Dashboard
            </a>
            <a href="{{ route('payment.invoice', $order) }}" 
               class="flex-1 py-3 px-6 rounded-lg font-bold border transition-colors hover:bg-gray-50"
               style="border-color: var(--border); color: var(--text);">
                <i class="fa-solid fa-file-invoice mr-2"></i>View Invoice
            </a>
        </div>
    </div>
</div>
@endsection
