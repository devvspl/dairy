@extends('layouts.app')

@section('title', 'Invoice - ' . $order->order_id)
@section('page-title', 'Invoice')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Invoice Container -->
    <div class="bg-white rounded-xl shadow-lg border" style="border-color: var(--border);">
        <!-- Header Actions -->
        <div class="p-4 border-b flex items-center justify-between" style="border-color: var(--border);">
            <a href="{{ route('payment.history') }}" 
               class="px-4 py-2 rounded-lg font-semibold border transition-colors hover:bg-gray-50"
               style="border-color: var(--border); color: var(--text);">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back
            </a>
            <div class="flex gap-2">
                <button onclick="window.print()" 
                        class="px-4 py-2 rounded-lg font-semibold border transition-colors hover:bg-gray-50"
                        style="border-color: var(--border); color: var(--text);">
                    <i class="fa-solid fa-print mr-2"></i>Print
                </button>
                <button onclick="downloadInvoice()" 
                        class="px-4 py-2 rounded-lg font-semibold text-white transition-all hover:shadow-lg"
                        style="background-color: var(--green);">
                    <i class="fa-solid fa-download mr-2"></i>Download
                </button>
            </div>
        </div>

        <!-- Invoice Content -->
        <div id="invoiceContent" class="p-8">
            <!-- Company Header -->
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold mb-2" style="color: var(--green);">
                        {{ config('app.name', 'Dairy Management') }}
                    </h1>
                    <p class="text-sm" style="color: var(--muted);">Fresh Milk Delivery Service</p>
                    <p class="text-sm mt-2" style="color: var(--text);">
                        <i class="fa-solid fa-location-dot mr-1"></i>Your Business Address<br>
                        <i class="fa-solid fa-phone mr-1"></i>+91 1234567890<br>
                        <i class="fa-solid fa-envelope mr-1"></i>info@dairy.com
                    </p>
                </div>
                <div class="text-right">
                    <div class="inline-block px-4 py-2 rounded-lg mb-2" style="background-color: rgba(34, 197, 94, 0.1);">
                        <span class="text-sm font-bold text-green-700">PAID</span>
                    </div>
                    <h2 class="text-2xl font-bold mb-1" style="color: var(--text);">INVOICE</h2>
                    <p class="text-sm" style="color: var(--muted);">
                        #{{ $order->order_id }}
                    </p>
                </div>
            </div>

            <!-- Customer & Invoice Details -->
            <div class="grid grid-cols-2 gap-8 mb-8">
                <!-- Bill To -->
                <div>
                    <h3 class="text-sm font-bold mb-3 uppercase" style="color: var(--muted);">Bill To</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="font-bold text-lg mb-1" style="color: var(--text);">{{ $order->user->name }}</p>
                        <p class="text-sm" style="color: var(--text);">
                            <i class="fa-solid fa-envelope mr-1" style="color: var(--muted);"></i>{{ $order->user->email }}
                        </p>
                        @if($order->user->phone)
                        <p class="text-sm" style="color: var(--text);">
                            <i class="fa-solid fa-phone mr-1" style="color: var(--muted);"></i>{{ $order->user->phone }}
                        </p>
                        @endif
                        @php
                            $subscription = $order->user->subscriptions()
                                ->where('transaction_id', $order->transaction_id)
                                ->first();
                        @endphp
                        @if($subscription && $subscription->delivery_address)
                        <p class="text-sm mt-2" style="color: var(--text);">
                            <i class="fa-solid fa-location-dot mr-1" style="color: var(--muted);"></i>
                            {{ $subscription->delivery_address }}
                        </p>
                        @endif
                        @if($subscription && $subscription->location)
                        <p class="text-sm mt-1 font-semibold" style="color: var(--green);">
                            <i class="fa-solid fa-map-marker-alt mr-1"></i>
                            Location: {{ $subscription->location->name }}
                            @if($subscription->location->area || $subscription->location->city)
                                ({{ collect([$subscription->location->area, $subscription->location->city])->filter()->implode(', ') }})
                            @endif
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Invoice Details -->
                <div>
                    <h3 class="text-sm font-bold mb-3 uppercase" style="color: var(--muted);">Invoice Details</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span style="color: var(--muted);">Invoice Date:</span>
                            <span class="font-semibold" style="color: var(--text);">{{ $order->paid_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span style="color: var(--muted);">Invoice Time:</span>
                            <span class="font-semibold" style="color: var(--text);">{{ $order->paid_at->format('h:i A') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span style="color: var(--muted);">Transaction ID:</span>
                            <span class="font-semibold" style="color: var(--text);">{{ $order->transaction_id }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span style="color: var(--muted);">Payment Method:</span>
                            <span class="font-semibold" style="color: var(--text);">PhonePe</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-8">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2" style="border-color: var(--green);">
                            <th class="text-left py-3 px-4 font-bold" style="color: var(--text);">Description</th>
                            <th class="text-center py-3 px-4 font-bold" style="color: var(--text);">Duration</th>
                            <th class="text-right py-3 px-4 font-bold" style="color: var(--text);">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b" style="border-color: var(--border);">
                            <td class="py-4 px-4">
                                <p class="font-bold text-lg" style="color: var(--text);">{{ $order->membershipPlan->name }} Membership</p>
                                <p class="text-sm mt-1" style="color: var(--muted);">{{ $order->membershipPlan->description }}</p>
                                @if($order->membershipPlan->features && count($order->membershipPlan->features) > 0)
                                <ul class="mt-2 space-y-1">
                                    @foreach(array_slice($order->membershipPlan->features, 0, 3) as $feature)
                                    <li class="text-xs flex items-start">
                                        <i class="fa-solid fa-check text-xs mt-0.5 mr-2" style="color: var(--green);"></i>
                                        <span style="color: var(--text);">{{ $feature }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="font-semibold" style="color: var(--text);">{{ $order->membershipPlan->duration }}</span>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <span class="font-bold text-lg" style="color: var(--text);">₹{{ number_format($order->amount, 2) }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="flex justify-end mb-8">
                <div class="w-80">
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span style="color: var(--muted);">Subtotal:</span>
                            <span class="font-semibold" style="color: var(--text);">₹{{ number_format($order->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span style="color: var(--muted);">Tax (0%):</span>
                            <span class="font-semibold" style="color: var(--text);">₹0.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span style="color: var(--muted);">Discount:</span>
                            <span class="font-semibold" style="color: var(--text);">₹0.00</span>
                        </div>
                        <div class="border-t-2 pt-3" style="border-color: var(--green);">
                            <div class="flex justify-between">
                                <span class="text-lg font-bold" style="color: var(--text);">Total Amount:</span>
                                <span class="text-2xl font-bold" style="color: var(--green);">₹{{ number_format($order->amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Details -->
            @if($subscription)
            <div class="bg-green-50 rounded-lg p-6 mb-8 border-2" style="border-color: var(--green);">
                <h3 class="font-bold text-lg mb-3 flex items-center" style="color: var(--green);">
                    <i class="fa-solid fa-calendar-check mr-2"></i>Subscription Details
                </h3>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs font-semibold mb-1" style="color: var(--muted);">Start Date</p>
                        <p class="font-bold" style="color: var(--text);">{{ $subscription->start_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold mb-1" style="color: var(--muted);">End Date</p>
                        <p class="font-bold" style="color: var(--text);">{{ $subscription->end_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold mb-1" style="color: var(--muted);">Status</p>
                        <span class="inline-block px-3 py-1 text-xs rounded-full font-bold bg-green-100 text-green-700">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Footer Notes -->
            <div class="border-t pt-6" style="border-color: var(--border);">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h4 class="font-bold mb-2" style="color: var(--text);">Terms & Conditions</h4>
                        <ul class="text-xs space-y-1" style="color: var(--muted);">
                            <li>• Milk will be delivered as per the schedule</li>
                            <li>• Quality guaranteed or money back</li>
                            <li>• Contact support for any issues</li>
                            <li>• Subscription can be paused or cancelled</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-2" style="color: var(--text);">Need Help?</h4>
                        <p class="text-xs mb-2" style="color: var(--muted);">
                            If you have any questions about this invoice, please contact us:
                        </p>
                        <p class="text-xs" style="color: var(--text);">
                            <i class="fa-solid fa-envelope mr-1"></i>support@dairy.com<br>
                            <i class="fa-solid fa-phone mr-1"></i>+91 1234567890
                        </p>
                    </div>
                </div>
            </div>

            <!-- Thank You Message -->
            <div class="text-center mt-8 pt-6 border-t" style="border-color: var(--border);">
                <p class="text-lg font-bold mb-1" style="color: var(--green);">Thank You for Your Business!</p>
                <p class="text-sm" style="color: var(--muted);">We appreciate your trust in our service</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoiceContent, #invoiceContent * {
        visibility: visible;
    }
    #invoiceContent {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
}
</style>

<script>
function downloadInvoice() {
    window.print();
}
</script>
@endsection
