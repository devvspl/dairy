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
                            @php $subtotal = collect($productOrder->items)->sum(fn($i) => $i['price'] * $i['quantity']); @endphp
                            @if($productOrder->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-sm" style="color: var(--muted);">Subtotal</td>
                                <td class="px-6 py-2 text-right text-sm" style="color: var(--muted);">₹{{ number_format($subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-sm font-medium">
                                    <span style="color:#16a34a;">Discount</span>
                                    @if($productOrder->coupon_code)
                                        <span class="ml-1 px-2 py-0.5 rounded text-xs font-bold" style="background:rgba(22,163,74,0.1);color:#16a34a;">{{ $productOrder->coupon_code }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-2 text-right text-sm font-semibold" style="color:#16a34a;">-₹{{ number_format($productOrder->discount_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="border-t" style="border-color: var(--border);">
                                <td colspan="3" class="px-6 py-3 text-right font-bold" style="color: var(--text);">Amount Paid</td>
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

            <!-- Shiprocket Card -->
            <div class="bg-white rounded-lg shadow-sm border" style="border-color: var(--border);">
                <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color: var(--border);">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-truck-fast" style="color: var(--green);"></i>
                        <h3 class="font-bold" style="color: var(--text);">Shiprocket Delivery</h3>
                    </div>
                    @if($shiprocketSetting)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-semibold">Active</span>
                    @else
                        <a href="{{ route('admin.settings.shiprocket') }}"
                           class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 font-semibold hover:underline">
                            Configure
                        </a>
                    @endif
                </div>

                <div class="p-5">
                    @if($productOrder->isShiprocketAssigned())
                        <!-- Already assigned -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 text-sm">
                                <i class="fa-solid fa-circle-check text-green-600"></i>
                                <span class="font-semibold text-green-700">Assigned to Shiprocket</span>
                            </div>

                            <div class="space-y-2 text-sm">
                                @if($productOrder->shiprocket_order_id)
                                <div class="flex justify-between">
                                    <span style="color: var(--muted);">SR Order ID</span>
                                    <span class="font-mono font-semibold" style="color: var(--text);">{{ $productOrder->shiprocket_order_id }}</span>
                                </div>
                                @endif
                                @if($productOrder->shiprocket_awb)
                                <div class="flex justify-between">
                                    <span style="color: var(--muted);">AWB Code</span>
                                    <span class="font-mono font-semibold" style="color: var(--text);">{{ $productOrder->shiprocket_awb }}</span>
                                </div>
                                @endif
                                @if($productOrder->shiprocket_courier)
                                <div class="flex justify-between">
                                    <span style="color: var(--muted);">Courier</span>
                                    <span class="font-semibold" style="color: var(--text);">{{ $productOrder->shiprocket_courier }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between items-center">
                                    <span style="color: var(--muted);">Status</span>
                                    <span id="srStatusBadge"
                                          class="px-2 py-0.5 text-xs rounded-full font-semibold bg-blue-100 text-blue-700">
                                        {{ $productOrder->shiprocket_status ?? 'NEW' }}
                                    </span>
                                </div>
                                @if($productOrder->shiprocket_assigned_at)
                                <div class="flex justify-between">
                                    <span style="color: var(--muted);">Assigned</span>
                                    <span class="text-xs" style="color: var(--text);">{{ $productOrder->shiprocket_assigned_at->format('d M Y, h:i A') }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="flex gap-2 pt-2">
                                @if($productOrder->shiprocket_awb)
                                <button onclick="srTrack()"
                                        id="srTrackBtn"
                                        class="flex-1 px-3 py-2 rounded-lg border text-sm font-semibold"
                                        style="border-color: var(--green); color: var(--green);">
                                    <i class="fa-solid fa-location-dot mr-1"></i>Track
                                </button>
                                @endif
                                <button onclick="srCancel()"
                                        id="srCancelBtn"
                                        class="flex-1 px-3 py-2 rounded-lg border text-sm font-semibold border-red-300 text-red-600">
                                    <i class="fa-solid fa-xmark mr-1"></i>Cancel
                                </button>
                            </div>

                            <!-- Track result -->
                            <div id="srTrackResult" class="hidden rounded-lg p-3 text-xs bg-gray-50 border" style="border-color: var(--border);">
                            </div>
                        </div>

                    @elseif($shiprocketSetting)
                        <!-- Assign checkbox -->
                        <div class="space-y-4">
                            <p class="text-sm" style="color: var(--muted);">Assign this order to Shiprocket for courier delivery.</p>

                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" id="srAssignCheck"
                                       class="mt-0.5 w-4 h-4 rounded accent-green-600 cursor-pointer">
                                <div>
                                    <span class="text-sm font-semibold" style="color: var(--text);">
                                        Assign to Shiprocket
                                    </span>
                                    <p class="text-xs mt-0.5" style="color: var(--muted);">
                                        Creates a shipment order in your Shiprocket account and generates AWB.
                                    </p>
                                </div>
                            </label>

                            <button onclick="srAssign()"
                                    id="srAssignBtn"
                                    disabled
                                    class="w-full px-4 py-2 rounded-lg font-semibold text-sm text-white disabled:opacity-40 disabled:cursor-not-allowed transition-opacity"
                                    style="background-color: var(--green);">
                                <i class="fa-solid fa-truck-fast mr-2" id="srAssignIcon"></i>
                                <span id="srAssignText">Assign to Shiprocket</span>
                            </button>

                            <div id="srAssignResult" class="hidden rounded-lg p-3 text-sm font-medium"></div>
                        </div>

                    @else
                        <div class="text-center py-4">
                            <i class="fa-solid fa-truck-fast text-3xl mb-2 block" style="color: var(--muted);"></i>
                            <p class="text-sm mb-3" style="color: var(--muted);">Shiprocket is not configured.</p>
                            <a href="{{ route('admin.settings.shiprocket') }}"
                               class="inline-flex items-center gap-1 text-sm font-semibold hover:underline" style="color: var(--green);">
                                <i class="fa-solid fa-gear text-xs"></i> Configure Shiprocket
                            </a>
                        </div>
                    @endif
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

<script>
const CSRF = '{{ csrf_token() }}';

@if(!$productOrder->isShiprocketAssigned() && $shiprocketSetting->enabled)
document.getElementById('srAssignCheck').addEventListener('change', function () {
    document.getElementById('srAssignBtn').disabled = !this.checked;
});

function srAssign() {
    const btn  = document.getElementById('srAssignBtn');
    const icon = document.getElementById('srAssignIcon');
    const text = document.getElementById('srAssignText');
    const res  = document.getElementById('srAssignResult');

    btn.disabled   = true;
    icon.className = 'fa-solid fa-spinner fa-spin mr-2';
    text.textContent = 'Assigning...';
    res.classList.add('hidden');

    fetch('{{ route('admin.product-orders.shiprocket.assign', $productOrder) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => r.json())
    .then(data => {
        res.classList.remove('hidden');
        if (data.success) {
            res.className = 'rounded-lg p-3 text-sm font-medium bg-green-50 text-green-700 border border-green-200';
            res.textContent = data.message;
            setTimeout(() => location.reload(), 1500);
        } else {
            res.className = 'rounded-lg p-3 text-sm font-medium bg-red-50 text-red-700 border border-red-200';
            res.textContent = data.message;
            btn.disabled   = false;
            icon.className = 'fa-solid fa-truck-fast mr-2';
            text.textContent = 'Assign to Shiprocket';
        }
    })
    .catch(() => {
        res.classList.remove('hidden');
        res.className = 'rounded-lg p-3 text-sm font-medium bg-red-50 text-red-700 border border-red-200';
        res.textContent = 'Request failed. Please try again.';
        btn.disabled   = false;
        icon.className = 'fa-solid fa-truck-fast mr-2';
        text.textContent = 'Assign to Shiprocket';
    });
}
@endif

@if($productOrder->isShiprocketAssigned())
function srTrack() {
    const btn = document.getElementById('srTrackBtn');
    const res = document.getElementById('srTrackResult');
    if (btn) btn.disabled = true;

    fetch('{{ route('admin.product-orders.shiprocket.track', $productOrder) }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => r.json())
    .then(data => {
        res.classList.remove('hidden');
        if (data.success) {
            const status = data.status ?? 'Unknown';
            document.getElementById('srStatusBadge').textContent = status;
            res.innerHTML = `<span class="font-semibold">Current Status:</span> ${status}`;
        } else {
            res.textContent = data.message;
        }
    })
    .catch(() => { res.textContent = 'Tracking request failed.'; res.classList.remove('hidden'); })
    .finally(() => { if (btn) btn.disabled = false; });
}

function srCancel() {
    if (!confirm('Cancel this Shiprocket shipment? This cannot be undone.')) return;
    const btn = document.getElementById('srCancelBtn');
    btn.disabled = true;
    btn.textContent = 'Cancelling...';

    fetch('{{ route('admin.product-orders.shiprocket.cancel', $productOrder) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
        if (data.success) location.reload();
        else { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-xmark mr-1"></i>Cancel'; }
    });
}
@endif
</script>
@endsection
