@extends('layouts.app')

@section('title', 'Create Type')
@section('page-title', 'Create Type')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Create Type</h2>
        <a href="{{ route('admin.types.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.types.store') }}">
        @csrf
        
        <div class="space-y-6">
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Basic Information</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., Bottle Milk">
                            @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="auto-generated-from-name">
                            <p class="text-xs mt-1" style="color: var(--muted);">Leave empty to auto-generate from name</p>
                            @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Brief description of this type">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Icon (FontAwesome)</label>
                            <input type="text" name="icon" value="{{ old('icon') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="fa-bottle-water">
                            <p class="text-xs mt-1" style="color: var(--muted);">Example: fa-bottle-water, fa-glass, fa-cheese</p>
                            @error('icon')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Order *</label>
                            <input type="number" name="order" value="{{ old('order', 0) }}" required min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <p class="text-xs mt-1" style="color: var(--muted);">Display order (lower numbers appear first)</p>
                            @error('order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Image URL</label>
                        <input type="text" name="image" value="{{ old('image') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="images/types/bottle-milk.jpg">
                        @error('image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Status</h3>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded" style="color: var(--brand);">
                    <label for="is_active" class="ml-2 text-sm font-medium" style="color: var(--text);">Active</label>
                </div>
                <p class="text-xs mt-1" style="color: var(--muted);">Inactive types won't be shown in filters</p>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="px-6 py-2 rounded-lg text-white font-medium transition-colors" style="background-color: var(--brand);">
                    Create Type
                </button>
                <a href="{{ route('admin.types.index') }}" class="px-6 py-2 rounded-lg font-medium transition-colors" style="color: var(--text); border: 1px solid var(--border);">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
