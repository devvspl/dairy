@extends('layouts.app')

@section('title', 'Create Location')
@section('page-title', 'Create Location')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Create Location Page</h2>
        <a href="{{ route('admin.locations.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.locations.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-6">
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Basic Information</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Location Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., ACE Divino">
                            @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="auto-generated">
                            <p class="text-xs mt-1" style="color: var(--muted);">Leave empty to auto-generate from name</p>
                            @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Sector</label>
                            <input type="text" name="sector" value="{{ old('sector') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., 1">
                            @error('sector')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Area</label>
                            <input type="text" name="area" value="{{ old('area') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., Greater Noida West">
                            @error('area')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">City</label>
                            <input type="text" name="city" value="{{ old('city') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., Noida">
                            @error('city')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Hero Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., Bottle Milk Delivery in Sector 1, Greater Noida West">
                        @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Brief description...">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Banner Image</label>
                        <input type="file" name="banner_image_file" accept="image/*" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <p class="text-xs mt-1" style="color: var(--muted);">Recommended: 1920x600px</p>
                        @error('banner_image_file')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Building Information -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Building Information</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Building Name</label>
                            <input type="text" name="building_name" value="{{ old('building_name') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., ACE Divino">
                            @error('building_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Building Type</label>
                            <input type="text" name="building_type" value="{{ old('building_type') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., Society, Apartment">
                            @error('building_type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Delivery Timing</label>
                            <input type="text" name="delivery_timing" value="{{ old('delivery_timing') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., 5:30 AM – 8:30 AM">
                            @error('delivery_timing')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Delivery Point</label>
                            <input type="text" name="delivery_point" value="{{ old('delivery_point') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., Flat / gate / guard">
                            @error('delivery_point')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Handling Info</label>
                        <textarea name="handling_info" rows="2" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Sealed bottles, hygienic delivery...">{{ old('handling_info') }}</textarea>
                        @error('handling_info')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Address & Map -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Address & Map</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Full Address</label>
                        <textarea name="address" rows="2" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Complete address...">{{ old('address') }}</textarea>
                        @error('address')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Google Maps Embed URL</label>
                        <input type="text" name="map_embed_url" value="{{ old('map_embed_url') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="https://www.google.com/maps/embed?...">
                        <p class="text-xs mt-1" style="color: var(--muted);">Get embed URL from Google Maps</p>
                        @error('map_embed_url')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Contact Information</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Phone Number</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="+91XXXXXXXXXX">
                            @error('contact_phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">WhatsApp Number</label>
                            <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="91XXXXXXXXXX">
                            @error('contact_whatsapp')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">SEO Settings</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="SEO title...">
                        @error('meta_title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="SEO description...">{{ old('meta_description') }}</textarea>
                        @error('meta_description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Status & Order -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Status & Order</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded" style="accent-color: var(--green);">
                            <label for="is_active" class="ml-2 text-sm font-medium" style="color: var(--text);">Active</label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Display Order</label>
                            <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="0">
                            @error('order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-sm" style="color: var(--muted);">
                Note: Additional sections (Hero Badges, Route Steps, Highlights, FAQs, etc.) can be added after creating the location.
            </p>

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-4 border-t" style="border-color: var(--border); padding-top: 1.5rem;">
                <button type="submit" class="px-6 py-2 rounded-lg text-white font-medium transition-colors hover:opacity-90" style="background-color: var(--green);">
                    Create Location
                </button>
                <a href="{{ route('admin.locations.index') }}" class="px-6 py-2 rounded-lg font-medium transition-colors hover:bg-gray-50" style="color: var(--text); border: 1px solid var(--border);">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
