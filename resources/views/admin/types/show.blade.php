@extends('layouts.app')

@section('title', 'View Type')
@section('page-title', 'View Type')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Type Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.types.edit', $type) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors" style="background-color: var(--brand);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.types.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Basic Information -->
        <div>
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Basic Information</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--muted);">Name</label>
                    <div class="text-lg font-semibold" style="color: var(--text);">{{ $type->name }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--muted);">Slug</label>
                    <code class="text-sm px-2 py-1 rounded" style="background-color: var(--soft); color: var(--text);">{{ $type->slug }}</code>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: var(--muted);">Description</label>
                    <div style="color: var(--text);">{{ $type->description ?? 'No description' }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--muted);">Icon</label>
                    @if($type->icon)
                    <div class="flex items-center gap-2">
                        <i class="fa-solid {{ $type->icon }} text-2xl" style="color: var(--brand);"></i>
                        <code class="text-sm px-2 py-1 rounded" style="background-color: var(--soft); color: var(--text);">{{ $type->icon }}</code>
                    </div>
                    @else
                    <div style="color: var(--muted);">No icon</div>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--muted);">Order</label>
                    <div class="text-lg font-semibold" style="color: var(--text);">{{ $type->order }}</div>
                </div>
                @if($type->image)
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: var(--muted);">Image</label>
                    <img src="{{ asset($type->image) }}" alt="{{ $type->name }}" class="w-32 h-32 object-cover rounded-lg border" style="border-color: var(--border);">
                </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        <div>
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Statistics</h3>
            <div class="grid grid-cols-4 gap-4">
                <div class="p-4 rounded-lg" style="background-color: var(--soft);">
                    <div class="text-sm font-medium" style="color: var(--muted);">Products</div>
                    <div class="text-2xl font-bold mt-1" style="color: var(--brand);">{{ $type->products->count() }}</div>
                </div>
                <div class="p-4 rounded-lg" style="background-color: var(--soft);">
                    <div class="text-sm font-medium" style="color: var(--muted);">Status</div>
                    <div class="mt-1">
                        @if($type->is_active)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                        @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                        @endif
                    </div>
                </div>
                <div class="p-4 rounded-lg" style="background-color: var(--soft);">
                    <div class="text-sm font-medium" style="color: var(--muted);">Created</div>
                    <div class="text-sm font-bold mt-1" style="color: var(--text);">{{ $type->created_at->format('M d, Y') }}</div>
                    <div class="text-xs" style="color: var(--muted);">{{ $type->created_at->diffForHumans() }}</div>
                </div>
                <div class="p-4 rounded-lg" style="background-color: var(--soft);">
                    <div class="text-sm font-medium" style="color: var(--muted);">Updated</div>
                    <div class="text-sm font-bold mt-1" style="color: var(--text);">{{ $type->updated_at->format('M d, Y') }}</div>
                    <div class="text-xs" style="color: var(--muted);">{{ $type->updated_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>

        <!-- Products -->
        @if($type->products->count() > 0)
        <div>
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Products ({{ $type->products->count() }})</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border);">
                            <th class="text-left py-3 px-4 font-semibold" style="color: var(--text);">Name</th>
                            <th class="text-left py-3 px-4 font-semibold" style="color: var(--text);">SKU</th>
                            <th class="text-left py-3 px-4 font-semibold" style="color: var(--text);">Price</th>
                            <th class="text-left py-3 px-4 font-semibold" style="color: var(--text);">Status</th>
                            <th class="text-right py-3 px-4 font-semibold" style="color: var(--text);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($type->products->take(10) as $product)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td class="py-3 px-4">
                                <div class="font-medium" style="color: var(--text);">{{ $product->name }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <code class="text-sm px-2 py-1 rounded" style="background-color: var(--soft); color: var(--text);">{{ $product->sku ?? 'N/A' }}</code>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-semibold" style="color: var(--brand);">₹{{ number_format($product->price, 2) }}</span>
                            </td>
                            <td class="py-3 px-4">
                                @if($product->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-sm font-medium" style="color: var(--brand);">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($type->products->count() > 10)
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.products.index', ['type' => $type->id]) }}" class="text-sm font-medium" style="color: var(--brand);">
                        View all {{ $type->products->count() }} products →
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
