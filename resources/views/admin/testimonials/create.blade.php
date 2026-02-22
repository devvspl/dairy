@extends('layouts.app')

@section('title', 'Create Testimonial')
@section('page-title', 'Create Testimonial')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Create New Testimonial</h2>
        <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.testimonials.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('location')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Testimonial Text *</label>
                    <textarea name="text" rows="4" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('text') }}</textarea>
                    @error('text')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Avatar Path</label>
                    <input type="text" name="avatar" value="{{ old('avatar') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('avatar')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Order *</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" required min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm" style="color: var(--text);">Featured</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm" style="color: var(--text);">Active</span>
                    </label>
                </div>
            </div>
            <div class="flex space-x-3 mt-6">
                <button type="submit" class="px-4 py-2 rounded-lg text-white font-medium" style="background-color: var(--green);">Create Testimonial</button>
                <a href="{{ route('admin.testimonials.index') }}" class="px-4 py-2 rounded-lg border" style="border-color: var(--border);">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
