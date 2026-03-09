@extends('layouts.app')

@section('title', 'Edit Coupon')
@section('page-title', 'Edit Coupon')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Edit Discount Coupon</h2>
        <a href="{{ route('admin.coupons.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Basic Information</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Coupon Code *</label>
                            <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required class="w-full px-3 py-2 border rounded-lg uppercase" style="border-color: var(--border);" placeholder="e.g., SAVE20">
                            <p class="text-xs mt-1" style="color: var(--muted);">Will be auto-converted to uppercase</p>
                            @error('code')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Coupon Name *</label>
                            <input type="text" name="name" value="{{ old('name', $coupon->name) }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., 20% Off on Membership">
                            @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Describe the coupon offer...">{{ old('description', $coupon->description) }}</textarea>
                        @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Discount Settings -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Discount Settings</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Discount Type *</label>
                            <select name="type" id="type" required onchange="updateValueLabel()" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                            </select>
                            @error('type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);"><span id="valueLabel">Discount Value ({{ $coupon->type == 'percentage' ? '%' : '₹' }})</span> *</label>
                            <input type="number" name="value" id="value" value="{{ old('value', $coupon->value) }}" required step="0.01" min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., 20">
                            @error('value')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Minimum Purchase Amount (₹)</label>
                            <input type="number" name="min_purchase_amount" value="{{ old('min_purchase_amount', $coupon->min_purchase_amount) }}" step="0.01" min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="0">
                            <p class="text-xs mt-1" style="color: var(--muted);">Minimum order value to use this coupon</p>
                            @error('min_purchase_amount')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Maximum Discount Amount (₹)</label>
                            <input type="number" name="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" step="0.01" min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Leave empty for no limit">
                            <p class="text-xs mt-1" style="color: var(--muted);">Cap the maximum discount (for percentage type)</p>
                            @error('max_discount_amount')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Limits -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Usage Limits</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Total Usage Limit</label>
                            <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Leave empty for unlimited">
                            <p class="text-xs mt-1" style="color: var(--muted);">Currently used: {{ $coupon->times_used }} times</p>
                            @error('usage_limit')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Usage Per User *</label>
                            <input type="number" name="usage_per_user" value="{{ old('usage_per_user', $coupon->usage_per_user) }}" required min="1" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="1">
                            <p class="text-xs mt-1" style="color: var(--muted);">How many times each user can use this coupon</p>
                            @error('usage_per_user')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validity Period -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Validity Period</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Valid From *</label>
                            <input type="date" name="valid_from" value="{{ old('valid_from', $coupon->valid_from->format('Y-m-d')) }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            @error('valid_from')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Valid Until *</label>
                            <input type="date" name="valid_until" value="{{ old('valid_until', $coupon->valid_until->format('Y-m-d')) }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            @error('valid_until')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applicability -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Applicability</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Applicable To *</label>
                        <select name="applicable_to" id="applicable_to" required onchange="toggleSpecificItems()" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <option value="all" {{ old('applicable_to', $coupon->applicable_to) == 'all' ? 'selected' : '' }}>All (Membership & Products)</option>
                            <option value="membership" {{ old('applicable_to', $coupon->applicable_to) == 'membership' ? 'selected' : '' }}>Membership Plans Only</option>
                            <option value="products" {{ old('applicable_to', $coupon->applicable_to) == 'products' ? 'selected' : '' }}>Products Only</option>
                        </select>
                        <p class="text-xs mt-1" style="color: var(--muted);">Where this coupon can be applied</p>
                        @error('applicable_to')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" name="apply_to_specific_items" id="apply_to_specific_items" value="1" {{ old('apply_to_specific_items', $coupon->apply_to_specific_items) ? 'checked' : '' }} onchange="toggleSpecificItems()" class="w-4 h-4 rounded" style="accent-color: var(--green);">
                            <label for="apply_to_specific_items" class="ml-2 text-sm font-medium" style="color: var(--text);">Apply to specific items only</label>
                        </div>
                        <p class="text-xs mt-1" style="color: var(--muted);">Select specific membership plans or products for this coupon</p>
                    </div>

                    <!-- Specific Membership Plans -->
                    <div id="membership_plans_section" style="display: none;">
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Select Membership Plans</label>
                        <input type="text" id="search_plans" placeholder="Search membership plans..." class="w-full px-3 py-2 border rounded-lg mb-2" style="border-color: var(--border);" onkeyup="filterPlans()">
                        <div class="border rounded-lg p-3 max-h-48 overflow-y-auto" style="border-color: var(--border);" id="plans_list">
                            @foreach($membershipPlans as $plan)
                            <div class="flex items-center py-2 plan-item" data-name="{{ strtolower($plan->name) }}">
                                <input type="checkbox" name="membership_plan_ids[]" value="{{ $plan->id }}" id="plan_{{ $plan->id }}" {{ in_array($plan->id, old('membership_plan_ids', $coupon->membershipPlans->pluck('id')->toArray())) ? 'checked' : '' }} class="w-4 h-4 rounded" style="accent-color: var(--green);">
                                <label for="plan_{{ $plan->id }}" class="ml-2 text-sm" style="color: var(--text);">
                                    {{ $plan->name }} 
                                    <span class="text-xs" style="color: var(--muted);">(₹{{ number_format($plan->price, 0) }}/{{ $plan->duration }})</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @if($coupon->membershipPlans->count() > 0)
                        <p class="text-xs mt-2" style="color: var(--green);">
                            <i class="fa-solid fa-check-circle"></i> Currently applied to {{ $coupon->membershipPlans->count() }} membership plan(s)
                        </p>
                        @endif
                    </div>

                    <!-- Specific Products -->
                    <div id="products_section" style="display: none;">
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Select Products</label>
                        <input type="text" id="search_products" placeholder="Search products..." class="w-full px-3 py-2 border rounded-lg mb-2" style="border-color: var(--border);" onkeyup="filterProducts()">
                        <div class="border rounded-lg p-3 max-h-48 overflow-y-auto" style="border-color: var(--border);" id="products_list">
                            @foreach($products as $product)
                            <div class="flex items-center py-2 product-item" data-name="{{ strtolower($product->name) }}" data-category="{{ $product->category ? strtolower($product->category->name) : '' }}">
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" id="product_{{ $product->id }}" {{ in_array($product->id, old('product_ids', $coupon->products->pluck('id')->toArray())) ? 'checked' : '' }} class="w-4 h-4 rounded" style="accent-color: var(--green);">
                                <label for="product_{{ $product->id }}" class="ml-2 text-sm" style="color: var(--text);">
                                    {{ $product->name }}
                                    @if($product->category)
                                    <span class="text-xs" style="color: var(--muted);">({{ $product->category->name }})</span>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @if($coupon->products->count() > 0)
                        <p class="text-xs mt-2" style="color: var(--green);">
                            <i class="fa-solid fa-check-circle"></i> Currently applied to {{ $coupon->products->count() }} product(s)
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Statistics</h3>
                <div class="grid grid-cols-4 gap-4">
                    <div class="p-4 rounded-lg" style="background-color: #f0f4ed;">
                        <div class="text-sm font-medium" style="color: var(--muted);">Times Used</div>
                        <div class="text-2xl font-bold mt-1" style="color: var(--green);">{{ $coupon->times_used }}</div>
                    </div>
                    <div class="p-4 rounded-lg" style="background-color: #f0f4ed;">
                        <div class="text-sm font-medium" style="color: var(--muted);">Status</div>
                        <div class="text-sm font-bold mt-1" style="color: var(--text);">{!! $coupon->status_badge !!}</div>
                    </div>
                    <div class="p-4 rounded-lg" style="background-color: #f0f4ed;">
                        <div class="text-sm font-medium" style="color: var(--muted);">Created</div>
                        <div class="text-sm font-bold mt-1" style="color: var(--text);">{{ $coupon->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="p-4 rounded-lg" style="background-color: #f0f4ed;">
                        <div class="text-sm font-medium" style="color: var(--muted);">Updated</div>
                        <div class="text-sm font-bold mt-1" style="color: var(--text);">{{ $coupon->updated_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Status</h3>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded" style="accent-color: var(--green);">
                    <label for="is_active" class="ml-2 text-sm font-medium" style="color: var(--text);">Active</label>
                </div>
                <p class="text-xs mt-1" style="color: var(--muted);">Inactive coupons cannot be used by customers</p>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-4 border-t" style="border-color: var(--border); padding-top: 1.5rem;">
                <button type="submit" class="px-6 py-2 rounded-lg text-white font-medium transition-colors hover:opacity-90" style="background-color: var(--green);">
                    Update Coupon
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="px-6 py-2 rounded-lg font-medium transition-colors hover:bg-gray-50" style="color: var(--text); border: 1px solid var(--border);">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<script>
function updateValueLabel() {
    const type = document.getElementById('type').value;
    const label = document.getElementById('valueLabel');
    label.textContent = type === 'percentage' ? 'Discount Value (%)' : 'Discount Amount (₹)';
}

function toggleSpecificItems() {
    const applicableTo = document.getElementById('applicable_to').value;
    const applyToSpecific = document.getElementById('apply_to_specific_items').checked;
    const membershipSection = document.getElementById('membership_plans_section');
    const productsSection = document.getElementById('products_section');

    if (applyToSpecific) {
        if (applicableTo === 'all') {
            membershipSection.style.display = 'block';
            productsSection.style.display = 'block';
        } else if (applicableTo === 'membership') {
            membershipSection.style.display = 'block';
            productsSection.style.display = 'none';
        } else if (applicableTo === 'products') {
            membershipSection.style.display = 'none';
            productsSection.style.display = 'block';
        }
    } else {
        membershipSection.style.display = 'none';
        productsSection.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleSpecificItems();
});

function filterPlans() {
    const searchValue = document.getElementById('search_plans').value.toLowerCase();
    const planItems = document.querySelectorAll('.plan-item');
    
    planItems.forEach(item => {
        const planName = item.getAttribute('data-name');
        if (planName.includes(searchValue)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

function filterProducts() {
    const searchValue = document.getElementById('search_products').value.toLowerCase();
    const productItems = document.querySelectorAll('.product-item');
    
    productItems.forEach(item => {
        const productName = item.getAttribute('data-name');
        const categoryName = item.getAttribute('data-category');
        if (productName.includes(searchValue) || categoryName.includes(searchValue)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
@endsection
