@extends('layouts.public')

@section('title', 'Order Placed Successfully')

@section('content')
<div style="max-width:640px;margin:48px auto;padding:0 16px;">
    <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
        <div style="width:72px;height:72px;border-radius:50%;background:rgba(34,197,94,.1);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <svg style="width:36px;height:36px;color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 style="font-size:26px;font-weight:800;color:#1f2a1a;margin:0 0 8px;">Order Placed!</h1>
        <p style="color:#6a7a63;margin:0 0 24px;">Your payment was successful. We'll process your order shortly.</p>

        <div style="background:#f6f8f2;border-radius:14px;padding:20px;text-align:left;margin-bottom:24px;">
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="color:#6a7a63;font-size:14px;">Order ID</span>
                <span style="font-weight:700;color:#1f2a1a;font-size:14px;">{{ $order->order_id }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                <span style="color:#6a7a63;font-size:14px;">Subtotal</span>
                <span style="font-weight:600;color:#1f2a1a;font-size:14px;">₹{{ number_format($order->amount + $order->discount_amount, 2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="color:#16a34a;font-size:14px;">Coupon ({{ $order->coupon_code }})</span>
                <span style="font-weight:700;color:#16a34a;font-size:14px;">-₹{{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="color:#6a7a63;font-size:14px;">Amount Paid</span>
                <span style="font-weight:800;color:#2f4a1e;font-size:18px;">₹{{ number_format($order->amount, 2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="color:#6a7a63;font-size:14px;">Deliver To</span>
                <span style="font-weight:600;color:#1f2a1a;font-size:14px;text-align:right;max-width:60%;">{{ $order->delivery_address }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:#6a7a63;font-size:14px;">Date</span>
                <span style="font-weight:600;color:#1f2a1a;font-size:14px;">{{ $order->paid_at->format('d M Y, h:i A') }}</span>
            </div>
        </div>

        <!-- Items -->
        <div style="text-align:left;margin-bottom:24px;">
            <p style="font-weight:700;color:#1f2a1a;margin:0 0 10px;">Items Ordered</p>
            @foreach($order->items as $item)
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #e7e7e7;">
                <span style="color:#1f2a1a;font-size:14px;">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                <span style="font-weight:700;color:#2f4a1e;font-size:14px;">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
            </div>
            @endforeach
        </div>

        <a href="{{ route('products') }}"
           style="display:inline-block;background:#2f4a1e;color:#fff;padding:12px 28px;border-radius:12px;font-weight:700;text-decoration:none;">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
