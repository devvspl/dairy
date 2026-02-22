@extends('layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Edit Product</h2>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Name *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Price *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('price')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Order *</label>
                    <input type="number" name="order" value="{{ old('order', $product->order) }}" required min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Meta</label>
                <input type="text" name="meta" value="{{ old('meta', $product->meta) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                @error('meta')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Badge</label>
                    <input type="text" name="badge" value="{{ old('badge', $product->badge) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('badge')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Badge Color</label>
                    <input type="text" name="badge_color" value="{{ old('badge_color', $product->badge_color) }}" placeholder="#ffffff" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('badge_color')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Rating</label>
                    <input type="number" name="rating" value="{{ old('rating', $product->rating) }}" min="0" max="5" step="0.1" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('rating')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Reviews Count</label>
                    <input type="number" name="reviews_count" value="{{ old('reviews_count', $product->reviews_count) }}" min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('reviews_count')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Variants <span class="text-xs font-normal" style="color: var(--muted);">(JSON array)</span></label>
                <textarea name="variants" rows="3" class="w-full px-3 py-2 border rounded-lg font-mono text-sm" style="border-color: var(--border);">{{ old('variants', $product->variants ? json_encode($product->variants) : '') }}</textarea>
                @error('variants')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Image Path</label>
                <input type="text" name="image" value="{{ old('image', $product->image) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                @error('image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm" style="color: var(--text);">Featured</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm" style="color: var(--text);">Active</span>
                </label>
            </div>
        </div>

        <div class="flex space-x-3 mt-6">
            <button type="submit" class="px-4 py-2 rounded-lg text-white font-medium" style="background-color: var(--green);">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg border" style="border-color: var(--border);">Cancel</a>
        </div>
    </form>
</div>
@endsection
