@extends('layouts.app')

@section('title', 'View Product')
@section('page-title', 'Product Details')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold" style="color: var(--text);">{{ $product->name }}</h2>
            @if($product->sku)
                <p class="text-sm mt-1" style="color: var(--muted);">SKU: {{ $product->sku }}</p>
            @endif
            @if($product->slug)
                <p class="text-sm mt-1" style="color: var(--muted);">Slug: {{ $product->slug }}</p>
            @endif
        </div>
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </div>

    <!-- Product Images -->
    @if($product->image || ($product->images && count($product->images) > 0))
    <div class="mb-6 pb-6 border-b" style="border-color: var(--border);">
        <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Product Images</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @if($product->image)
                <div class="aspect-square rounded-lg overflow-hidden border" style="border-color: var(--border);">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
            @endif
            @if($product->images && count($product->images) > 0)
                @foreach($product->images as $image)
                    <div class="aspect-square rounded-lg overflow-hidden border" style="border-color: var(--border);">
                        <img src="{{ asset($image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    @endif

    <!-- Basic Information -->
    <div class="mb-6 pb-6 border-b" style="border-color: var(--border);">
        <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Category -->
            @if($product->category_id && $product->category)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Category</p>
                <p class="text-base font-semibold" style="color: var(--text);">{{ $product->category->name }}</p>
            </div>
            @endif

            <!-- Type -->
            @if($product->type_id && $product->type)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Type</p>
                <p class="text-base font-semibold" style="color: var(--text);">{{ $product->type->name }}</p>
            </div>
            @endif

            <!-- Status -->
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Status</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($product->is_featured)
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Featured</span>
                    @endif
                </div>
            </div>

            <!-- Order -->
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Display Order</p>
                <p class="text-base font-semibold" style="color: var(--text);">{{ $product->order }}</p>
            </div>
        </div>

        @if($product->short_description || $product->description)
        <div class="mt-4 space-y-3">
            @if($product->short_description)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Short Description</p>
                <p class="text-base" style="color: var(--text);">{{ $product->short_description }}</p>
            </div>
            @endif

            @if($product->description)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Full Description</p>
                <div class="prose max-w-none text-base" style="color: var(--text);">{!! $product->description !!}</div>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Pricing -->
    <div class="mb-6 pb-6 border-b" style="border-color: var(--border);">
        <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Pricing</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Price</p>
                <p class="text-2xl font-bold" style="color: var(--green);">${{ number_format($product->price, 2) }}</p>
            </div>

            @if($product->mrp)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">MRP</p>
                <p class="text-2xl font-bold" style="color: var(--text);">${{ number_format($product->mrp, 2) }}</p>
            </div>
            @endif

            @if($product->discount_percent)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Discount</p>
                <p class="text-2xl font-bold text-red-600">{{ $product->discount_percent }}%</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Stock -->
    <div class="mb-6 pb-6 border-b" style="border-color: var(--border);">
        <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Stock</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($product->stock_status)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Stock Status</p>
                <p class="text-base font-semibold capitalize" style="color: var(--text);">{{ str_replace('_', ' ', $product->stock_status) }}</p>
            </div>
            @endif

            @if($product->stock_quantity)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Stock Quantity</p>
                <p class="text-base font-semibold" style="color: var(--text);">{{ $product->stock_quantity }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Product Details -->
    @if($product->shelf_life || $product->storage_temp || $product->best_for)
    <div class="mb-6 pb-6 border-b" style="border-color: var(--border);">
        <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Product Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if($product->shelf_life)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Shelf Life</p>
                <p class="text-base" style="color: var(--text);">{{ $product->shelf_life }}</p>
            </div>
            @endif

            @if($product->storage_temp)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Storage Temperature</p>
                <p class="text-base" style="color: var(--text);">{{ $product->storage_temp }}</p>
            </div>
            @endif

            @if($product->best_for)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Best For</p>
                <p class="text-base" style="color: var(--text);">{{ $product->best_for }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Pack Sizes & Variants -->
    @if(($product->pack_sizes && is_array($product->pack_sizes) && count(array_filter($product->pack_sizes)) > 0) || ($product->variants && is_array($product->variants) && count(array_filter($product->variants)) > 0))
    <div class="mb-6 pb-6 border-b" style="border-color: var(--border);">
        <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Pack Sizes & Variants</h3>
        
        @if($product->pack_sizes && is_array($product->pack_sizes) && count(array_filter($product->pack_sizes)) > 0)
        <div class="mb-4">
            <p class="text-sm font-medium mb-2" style="color: var(--muted);">Pack Sizes</p>
            <div class="flex flex-wrap gap-2">
                @foreach(array_filter($product->pack_sizes) as $size)
                    <span class="px-3 py-1 text-sm rounded-lg border" style="border-color: var(--border); color: var(--text);">{{ is_array($size) ? json_encode($size) : $size }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if($product->variants && is_array($product->variants) && count(array_filter($product->variants)) > 0)
        <div>
            <p class="text-sm font-medium mb-2" style="color: var(--muted);">Variants</p>
            <div class="flex flex-wrap gap-2">
                @foreach(array_filter($product->variants) as $variant)
                    <span class="px-3 py-1 text-sm font-medium rounded-lg border" style="border-color: var(--green); color: var(--green); background-color: rgba(47, 74, 30, 0.05);">
                        {{ is_array($variant) ? json_encode($variant) : $variant }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Delivery Slots -->
    @if($product->delivery_slots && is_array($product->delivery_slots) && count(array_filter($product->delivery_slots)) > 0)
    <div class="mb-6 pb-6 border-b" style="border-color: var(--border);">
        <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Delivery Slots</h3>
        <div class="flex flex-wrap gap-2">
            @foreach(array_filter($product->delivery_slots) as $slot)
                <span class="px-3 py-1 text-sm rounded-lg border" style="border-color: var(--border); color: var(--text);">{{ is_array($slot) ? json_encode($slot) : $slot }}</span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Features -->
    @if($product->features && is_array($product->features))
    <div class="mb-6 pb-6 border-b" style="border-color: var(--border);">
        <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Features</h3>
        @php
            $hasValidFeatures = false;
            foreach($product->features as $feature) {
                if (is_array($feature) || is_object($feature)) {
                    $featureArray = (array) $feature;
                    if (!empty(array_filter($featureArray))) {
                        $hasValidFeatures = true;
                        break;
                    }
                } elseif (!empty($feature)) {
                    $hasValidFeatures = true;
                    break;
                }
            }
        @endphp
        
        @if($hasValidFeatures)
            <div class="space-y-3">
                @foreach($product->features as $feature)
                    @if(is_array($feature) || is_object($feature))
                        @php $featureArray = (array) $feature; @endphp
                        @if(!empty(array_filter($featureArray)))
                            <div class="p-3 bg-gray-50 rounded-lg">
                                @if(!empty($featureArray['icon']))
                                    <div class="font-medium mb-1" style="color: var(--text);">{{ $featureArray['icon'] }}</div>
                                @endif
                                @if(!empty($featureArray['title']))
                                    <div class="font-semibold mb-1" style="color: var(--text);">{{ $featureArray['title'] }}</div>
                                @endif
                                @if(!empty($featureArray['description']))
                                    <div class="text-sm" style="color: var(--muted);">{{ $featureArray['description'] }}</div>
                                @endif
                            </div>
                        @endif
                    @elseif(!empty($feature))
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-base" style="color: var(--text);">{{ $feature }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p class="text-sm italic" style="color: var(--muted);">No features available</p>
        @endif
    </div>
    @endif

    <!-- Rating & Reviews -->
    @if($product->rating)
    <div class="mb-6 pb-6 border-b" style="border-color: var(--border);">
        <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Rating & Reviews</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Rating</p>
                <p class="text-2xl font-bold" style="color: var(--green);">{{ $product->rating }} ⭐</p>
            </div>

            @if($product->reviews_count)
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Reviews Count</p>
                <p class="text-2xl font-bold" style="color: var(--text);">{{ number_format($product->reviews_count) }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Badge & Meta -->
    @if($product->badge || $product->meta)
    <div class="mb-6 pb-6 border-b" style="border-color: var(--border);">
        <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Badge & Meta</h3>
        
        @if($product->badge)
        <div class="mb-4">
            <p class="text-sm font-medium mb-2" style="color: var(--muted);">Badge</p>
            <div class="flex items-center space-x-3">
                @if($product->badge_color)
                    <div class="w-12 h-12 rounded border" style="background-color: {{ $product->badge_color }}; border-color: var(--border);"></div>
                @endif
                <div>
                    <p class="text-base font-semibold" style="color: var(--text);">{{ $product->badge }}</p>
                    @if($product->badge_color)
                        <p class="text-sm" style="color: var(--muted);">Color: {{ $product->badge_color }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($product->meta)
        <div>
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Meta Tags</p>
            <p class="text-base" style="color: var(--text);">{{ $product->meta }}</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Delete Product -->
    <div class="pt-6">
        <h3 class="text-lg font-semibold mb-2 text-red-600">Danger Zone</h3>
        <p class="text-sm text-gray-600 mb-4">Once you delete this product, there is no going back. Please be certain.</p>
        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors">
                Delete Product
            </button>
        </form>
    </div>
</div>
@endsection
