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

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        <!-- Basic Information -->
        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Basic Information</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Slug (URL)</label>
                        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="auto-generated-from-name">
                        <p class="text-xs mt-1" style="color: var(--muted);">Leave empty to auto-generate from product name</p>
                        @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        @error('sku')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Type</label>
                    <select name="type_id" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <option value="">Select Type</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('type_id', $product->type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('type_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Category</label>
                    <select name="category_id" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->title }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Short Description</label>
                    <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('short_description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Full Description</label>
                    <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('description', $product->description) }}</textarea>
                    @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Pricing</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Price *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('price')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">MRP</label>
                    <input type="number" name="mrp" value="{{ old('mrp', $product->mrp) }}" min="0" step="0.01" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('mrp')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Discount %</label>
                    <input type="number" name="discount_percent" value="{{ old('discount_percent', $product->discount_percent) }}" min="0" max="100" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('discount_percent')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Stock -->
        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Stock</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Stock Status</label>
                    <select name="stock_status" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <option value="available" {{ old('stock_status', $product->stock_status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="limited" {{ old('stock_status', $product->stock_status) == 'limited' ? 'selected' : '' }}>Limited Stock</option>
                    </select>
                    @error('stock_status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('stock_quantity')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Product Details</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Shelf Life</label>
                    <input type="text" name="shelf_life" value="{{ old('shelf_life', $product->shelf_life) }}" placeholder="e.g., 2 Days" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('shelf_life')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Storage Temp</label>
                    <input type="text" name="storage_temp" value="{{ old('storage_temp', $product->storage_temp) }}" placeholder="e.g., 0–4°C" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('storage_temp')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Best For</label>
                    <input type="text" name="best_for" value="{{ old('best_for', $product->best_for) }}" placeholder="e.g., Tea, Coffee" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('best_for')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Images</h3>
            
            @if($product->images && count($product->images) > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Current Images</label>
                <div class="grid grid-cols-5 gap-2">
                    @foreach($product->images as $img)
                    <img src="{{ asset($img) }}" alt="Product" class="w-full h-24 object-cover rounded border">
                    @endforeach
                </div>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Upload New Images (Multiple)</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                <p class="text-xs mt-1" style="color: var(--muted);">Max 2MB per image. First image will be the main image.</p>
                @error('images.*')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Or Enter Image Path</label>
                <input type="text" name="image" value="{{ old('image', $product->image) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                @error('image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Pack Sizes -->
        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Pack Sizes</h3>
            <div id="packSizesContainer">
                @if($product->pack_sizes && count($product->pack_sizes) > 0)
                    @foreach($product->pack_sizes as $index => $size)
                    <div class="flex gap-2 mb-2 pack-size-row">
                        <input type="text" name="pack_sizes[]" value="{{ $size }}" placeholder="e.g., 500ml" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg">Remove</button>
                    </div>
                    @endforeach
                @else
                    <div class="flex gap-2 mb-2 pack-size-row">
                        <input type="text" name="pack_sizes[]" placeholder="e.g., 500ml" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg">Remove</button>
                    </div>
                @endif
            </div>
            <button type="button" onclick="addPackSize()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg">Add Pack Size</button>
        </div>

        <!-- Delivery Slots -->
        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Delivery Slots</h3>
            <div id="deliverySlotsContainer">
                @if($product->delivery_slots && count($product->delivery_slots) > 0)
                    @foreach($product->delivery_slots as $index => $slot)
                    <div class="flex gap-2 mb-2 delivery-slot-row">
                        <input type="text" name="delivery_slots[]" value="{{ $slot }}" placeholder="e.g., Morning" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg">Remove</button>
                    </div>
                    @endforeach
                @else
                    <div class="flex gap-2 mb-2 delivery-slot-row">
                        <input type="text" name="delivery_slots[]" placeholder="e.g., Morning" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg">Remove</button>
                    </div>
                @endif
            </div>
            <button type="button" onclick="addDeliverySlot()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg">Add Delivery Slot</button>
        </div>

        <!-- Features -->
        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Features</h3>
            <div id="featuresContainer">
                @if($product->features && count($product->features) > 0)
                    @foreach($product->features as $index => $feature)
                    <div class="grid grid-cols-3 gap-2 mb-2 feature-row">
                        <input type="text" name="features[{{ $index }}][icon]" value="{{ $feature['icon'] ?? '' }}" placeholder="Icon (e.g., ✓)" class="px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <input type="text" name="features[{{ $index }}][title]" value="{{ $feature['title'] ?? '' }}" placeholder="Title" class="px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <div class="flex gap-2">
                            <input type="text" name="features[{{ $index }}][description]" value="{{ $feature['description'] ?? '' }}" placeholder="Description" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <button type="button" onclick="this.closest('.feature-row').remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg">Remove</button>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="grid grid-cols-3 gap-2 mb-2 feature-row">
                        <input type="text" name="features[0][icon]" placeholder="Icon (e.g., ✓)" class="px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <input type="text" name="features[0][title]" placeholder="Title" class="px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <div class="flex gap-2">
                            <input type="text" name="features[0][description]" placeholder="Description" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <button type="button" onclick="this.closest('.feature-row').remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg">Remove</button>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" onclick="addFeature()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg">Add Feature</button>
        </div>

        <!-- Rating & Reviews -->
        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Rating & Reviews</h3>
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
        </div>

        <!-- Badge & Meta -->
        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Badge & Meta</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Badge</label>
                    <input type="text" name="badge" value="{{ old('badge', $product->badge) }}" placeholder="e.g., Best Seller" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('badge')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Badge Color</label>
                    <input type="text" name="badge_color" value="{{ old('badge_color', $product->badge_color) }}" placeholder="green" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('badge_color')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Order</label>
                    <input type="number" name="order" value="{{ old('order', $product->order) }}" required min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Meta</label>
                <input type="text" name="meta" value="{{ old('meta', $product->meta) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                @error('meta')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Status -->
        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Status</h3>
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

<script>
let featureIndex = {{ $product->features ? count($product->features) : 1 }};

function addPackSize() {
    const container = document.getElementById('packSizesContainer');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2 pack-size-row';
    div.innerHTML = `
        <input type="text" name="pack_sizes[]" placeholder="e.g., 500ml" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg">Remove</button>
    `;
    container.appendChild(div);
}

function addDeliverySlot() {
    const container = document.getElementById('deliverySlotsContainer');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2 delivery-slot-row';
    div.innerHTML = `
        <input type="text" name="delivery_slots[]" placeholder="e.g., Morning" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg">Remove</button>
    `;
    container.appendChild(div);
}

function addFeature() {
    const container = document.getElementById('featuresContainer');
    const div = document.createElement('div');
    div.className = 'grid grid-cols-3 gap-2 mb-2 feature-row';
    div.innerHTML = `
        <input type="text" name="features[${featureIndex}][icon]" placeholder="Icon (e.g., ✓)" class="px-3 py-2 border rounded-lg" style="border-color: var(--border);">
        <input type="text" name="features[${featureIndex}][title]" placeholder="Title" class="px-3 py-2 border rounded-lg" style="border-color: var(--border);">
        <div class="flex gap-2">
            <input type="text" name="features[${featureIndex}][description]" placeholder="Description" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            <button type="button" onclick="this.closest('.feature-row').remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg">Remove</button>
        </div>
    `;
    container.appendChild(div);
    featureIndex++;
}
</script>
@endsection
