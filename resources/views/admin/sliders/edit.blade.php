@extends('layouts.app')

@section('title', 'Edit Slider')
@section('page-title', 'Edit Slider')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Edit Slider</h2>
        <a href="{{ route('admin.sliders.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.sliders.update', $slider) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Kicker</label>
                    <input type="text" name="kicker" value="{{ old('kicker', $slider->kicker) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('kicker')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $slider->title) }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Subtitle</label>
                    <textarea name="subtitle" rows="3" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('subtitle', $slider->subtitle) }}</textarea>
                    @error('subtitle')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Button Text</label>
                        <input type="text" name="button_text" value="{{ old('button_text', $slider->button_text) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        @error('button_text')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Button Link</label>
                        <input type="text" name="button_link" value="{{ old('button_link', $slider->button_link) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        @error('button_link')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Link Text</label>
                        <input type="text" name="link_text" value="{{ old('link_text', $slider->link_text) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        @error('link_text')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Link URL</label>
                        <input type="text" name="link_url" value="{{ old('link_url', $slider->link_url) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        @error('link_url')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Image Path</label>
                    <input type="text" name="image" value="{{ old('image', $slider->image) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Order *</label>
                    <input type="number" name="order" value="{{ old('order', $slider->order) }}" required min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm" style="color: var(--text);">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex space-x-3 mt-6">
                <button type="submit" class="px-4 py-2 rounded-lg text-white font-medium" style="background-color: var(--green);">
                    Update Slider
                </button>
                <a href="{{ route('admin.sliders.index') }}" class="px-4 py-2 rounded-lg border" style="border-color: var(--border); color: var(--text);">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
