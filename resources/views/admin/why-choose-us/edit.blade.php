@extends('layouts.app')

@section('title', 'Edit Why Choose Us Item')
@section('page-title', 'Edit Why Choose Us Item')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Edit Why Choose Us Item</h2>
        <a href="{{ route('admin.whychooseus.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.whychooseus.update', $whyChooseUs) }}">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $whyChooseUs->title) }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('description', $whyChooseUs->description) }}</textarea>
                    @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">SVG Path</label>
                    <textarea name="svg_path" rows="4" class="w-full px-3 py-2 border rounded-lg font-mono text-sm" style="border-color: var(--border);">{{ old('svg_path', $whyChooseUs->svg_path) }}</textarea>
                    @error('svg_path')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Order *</label>
                    <input type="number" name="order" value="{{ old('order', $whyChooseUs->order) }}" required min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $whyChooseUs->is_active) ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm" style="color: var(--text);">Active</span>
                    </label>
                </div>
            </div>
            <div class="flex space-x-3 mt-6">
                <button type="submit" class="px-4 py-2 rounded-lg text-white font-medium" style="background-color: var(--green);">Update Item</button>
                <a href="{{ route('admin.whychooseus.index') }}" class="px-4 py-2 rounded-lg border" style="border-color: var(--border);">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
