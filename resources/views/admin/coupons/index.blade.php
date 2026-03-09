@extends('layouts.app')

@section('title', 'Discount Coupons')
@section('page-title', 'Discount Coupons')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4 flex items-start gap-3" style="border-color: var(--green);">
        <i class="fa-solid fa-check-circle text-xl" style="color: var(--green);"></i>
        <div class="flex-1">
            <p class="font-semibold" style="color: var(--green);">Success!</p>
            <p class="text-sm" style="color: var(--text);">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Total Coupons</p>
                    <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <i class="fa-solid fa-ticket text-xl" style="color: var(--green);"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Active Coupons</p>
                    <p class="text-2xl font-bold mt-1 text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-green-100">
                    <i class="fa-solid fa-check-circle text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Expired</p>
                    <p class="text-2xl font-bold mt-1 text-red-600">{{ $stats['expired'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-red-100">
                    <i class="fa-solid fa-clock text-xl text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Total Usage</p>
                    <p class="text-2xl font-bold mt-1 text-blue-600">{{ $stats['total_usage'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-100">
                    <i class="fa-solid fa-chart-line text-xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">
                    <i class="fa-solid fa-ticket mr-2" style="color: var(--green);"></i>Manage Coupons
                </h1>
                <p class="text-sm lg:text-base mt-1" style="color: var(--muted);">
                    Create and manage discount coupons for your customers
                </p>
            </div>
            <a href="{{ route('admin.coupons.create') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition-colors hover:opacity-90"
               style="background-color: var(--green); color: #fff;">
                <i class="fa-solid fa-plus mr-2"></i>Create Coupon
            </a>
        </div>
    </div>

    <!-- Coupons List -->
    <div class="bg-white rounded-xl shadow-sm border" style="border-color: var(--border);">
        @if($coupons->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border); background-color: rgba(47, 74, 30, 0.02);">
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Code</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Name</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Type</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Value</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Usage</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Valid Until</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Status</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                        <tr class="hover:bg-gray-50 transition-colors" style="border-bottom: 1px solid var(--border);">
                            <td class="px-4 lg:px-6 py-4">
                                <span class="font-bold text-sm px-2 py-1 rounded" style="background-color: rgba(47, 74, 30, 0.1); color: var(--green);">{{ $coupon->code }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-sm font-medium" style="color: var(--text);">{{ $coupon->name }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded" style="background-color: #f3f4f6; color: var(--text);">
                                    {{ ucfirst($coupon->type) }}
                                </span>
                                @if($coupon->apply_to_specific_items)
                                <br>
                                <span class="text-xs mt-1" style="color: var(--muted);">
                                    @if($coupon->membershipPlans->count() > 0)
                                        {{ $coupon->membershipPlans->count() }} plan(s)
                                    @endif
                                    @if($coupon->products->count() > 0)
                                        {{ $coupon->products->count() }} product(s)
                                    @endif
                                </span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-sm font-bold" style="color: var(--green);">
                                    @if($coupon->type === 'percentage')
                                        {{ $coupon->value }}%
                                    @else
                                        ₹{{ number_format($coupon->value, 0) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-sm" style="color: var(--text);">
                                    {{ $coupon->times_used }}
                                    @if($coupon->usage_limit)
                                        / {{ $coupon->usage_limit }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-sm" style="color: var(--muted);">{{ $coupon->valid_until->format('M d, Y') }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                {!! $coupon->status_badge !!}
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors hover:bg-gray-100"
                                       style="color: var(--green); border: 1px solid var(--border);">
                                        <i class="fa-solid fa-edit mr-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors hover:bg-red-50"
                                                style="color: #dc2626; border: 1px solid var(--border);">
                                            <i class="fa-solid fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($coupons->hasPages())
            <div class="px-4 lg:px-6 py-4 border-t" style="border-color: var(--border);">
                {{ $coupons->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12 px-4">
                <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <i class="fa-solid fa-ticket text-3xl" style="color: var(--green);"></i>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--text);">No Coupons Yet</h3>
                <p class="text-sm mb-6" style="color: var(--muted);">Create your first discount coupon to attract customers.</p>
                <a href="{{ route('admin.coupons.create') }}" 
                   class="inline-flex items-center px-6 py-3 rounded-lg text-sm font-semibold transition-colors hover:opacity-90"
                   style="background-color: var(--green); color: #fff;">
                    <i class="fa-solid fa-plus mr-2"></i>Create First Coupon
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
