@extends('layouts.app')

@section('title', 'Order ' . $productOrder->order_id)
@section('page-title', 'Order #' . $productOrder->order_id)

@section('content')
<div class="space-y-6">

    <!-- Back -->
    <a href="{{ route('admin.product-orders.index') }}"
       class="inline-flex items-center text-sm font-semibold hover:underline" style="color: var(--green);">
        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Product Orders
    </a>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4" style="border-color: var(--green);">
        <p class="font-semibold" style="color: var(--green);">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Main Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Customer Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Name</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $productOrder->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Phone</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $productOrder->customer_phone }}</p>
                    </div>
                    @if($productOrder->customer_email)
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Email</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $productOrder->customer_email }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Order Date</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $productOrder->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    @if($productOrder->paid_at)
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Paid At</p>
                        <p class="font-semibold" style="color: var(--text);">{{ $productOrder->paid_at->format('d M Y, h:i A') }}</p>
                    </div>
                    @endif
                </div>

                @if($productOrder->delivery_address)
                <div class="mt-4 pt-4 border-t" style="border-color: var(--border);">
                    <p class="text-sm font-medium mb-1" style="color: var(--muted);">Delivery Address</p>
                    <p class="text-sm" style="color: var(--text);">{{ $productOrder->delivery_address }}</p>
                </div>
                @endif
            </div>

            <!-- Items Ordered -->
            <div class="bg-white rounded-lg shadow-sm border" style="border-color: var(--border);">
                <div class="px-6 py-4 border-b font-bold" style="border-color: var(--border); color: var(--text);">
                    <i class="fa-solid fa-box mr-2" style="color: var(--green);"></i>Items Ordered
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b" style="border-color: var(--border); background-color: rgba(47,74,30,0.05);">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold" style="color: var(--text);">Product</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold" style="color: var(--text);">Price</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold" style="color: var(--text);">Qty</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold" style="color: var(--text);">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productOrder->items as $item)
                            <tr class="border-b hover:bg-gray-50" style="border-color: var(--border);">
                                <td class="px-6 py-3 text-sm font-medium" style="color: var(--text);">{{ $item['name'] }}</td>
                                <td class="px-6 py-3 text-sm text-right" style="color: var(--muted);">₹{{ number_format($item['price'], 2) }}</td>
                                <td class="px-6 py-3 text-sm text-right" style="color: var(--muted);">{{ $item['quantity'] }}</td>
                                <td class="px-6 py-3 text-sm text-right font-bold" style="color: var(--green);">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t" style="border-color: var(--border); background-color: rgba(47,74,30,0.03);">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right font-bold" style="color: var(--text);">Grand Total</td>
                                <td class="px-6 py-3 text-right font-bold text-lg" style="color: var(--green);">₹{{ number_format($productOrder->amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Transaction Info -->
            @if($productOrder->transaction_id)
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Transaction Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Order ID</p>
                        <p class="font-mono text-sm font-semibold" style="color: var(--text);">{{ $productOrder->order_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Transaction ID</p>
                        <p class="font-mono text-sm font-semibold" style="color: var(--text);">{{ $productOrder->transaction_id }}</p>
                    </div>
                </div>
            </div>
            @endif

        </div>

        <!-- Right: Sidebar -->
        <div class="space-y-6">

            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Order Status</h3>

                <div class="mb-4">
                    <span class="px-3 py-1.5 text-sm rounded-full font-semibold
                        {{ $productOrder->status === 'success'   ? 'bg-green-100 text-green-800'  : '' }}
                        {{ $productOrder->status === 'pending'   ? 'bg-yellow-100 text-yellow-800': '' }}
                        {{ $productOrder->status === 'failed'    ? 'bg-red-100 text-red-800'      : '' }}
                        {{ $productOrder->status === 'cancelled' ? 'bg-gray-100 text-gray-800'    : '' }}">
                        {{ ucfirst($productOrder->status) }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.product-orders.update-status', $productOrder) }}">
                    @csrf
                    <select name="status" class="w-full px-3 py-2 border rounded-lg mb-3" style="border-color: var(--border);">
                        <option value="pending"   {{ $productOrder->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="success"   {{ $productOrder->status === 'success'   ? 'selected' : '' }}>Success</option>
                        <option value="failed"    {{ $productOrder->status === 'failed'    ? 'selected' : '' }}>Failed</option>
                        <option value="cancelled" {{ $productOrder->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg font-semibold text-white" style="background-color: var(--green);">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Payment Summary -->
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Payment Summary</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">Amount</p>
                        <p class="text-2xl font-bold" style="color: var(--green);">₹{{ number_format($productOrder->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">Payment Method</p>
                        <p class="font-semibold" style="color: var(--text);">{{ ucfirst(str_replace('_', ' ', $productOrder->payment_method ?? 'N/A')) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">Items</p>
                        <p class="font-semibold" style="color: var(--text);">{{ collect($productOrder->items)->sum('quantity') }} item(s)</p>
                    </div>
                </div>
            </div>

            <!-- Linked User -->
            @if($productOrder->user)
            <div class="bg-white rounded-lg shadow-sm p-6 border" style="border-color: var(--border);">
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Linked Account</h3>
                <div class="space-y-2">
                    <p class="font-semibold" style="color: var(--text);">{{ $productOrder->user->name }}</p>
                    <p class="text-sm" style="color: var(--muted);">{{ $productOrder->user->email }}</p>
                    <p class="text-sm" style="color: var(--muted);">{{ $productOrder->user->phone }}</p>
                    <a href="{{ route('admin.users.show', $productOrder->user) }}"
                       class="inline-flex items-center gap-1 text-sm font-semibold hover:underline mt-1" style="color: var(--green);">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i> View User
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
