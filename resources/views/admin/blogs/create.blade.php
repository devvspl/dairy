@extends('layouts.app')

@section('title', 'Create Blog')
@section('page-title', 'Create Blog')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Create New Blog</h2>
        <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.blogs.store') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Slug <span class="text-xs font-normal" style="color: var(--muted);">(auto-generated if empty)</span></label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Excerpt</label>
                <textarea name="excerpt" rows="3" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('excerpt') }}</textarea>
                @error('excerpt')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Content</label>
                <textarea name="content" rows="8" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('content') }}</textarea>
                @error('content')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Tag</label>
                    <input type="text" name="tag" value="{{ old('tag') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('tag')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Image Path</label>
                    <input type="text" name="image" value="{{ old('image') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
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
            <button type="submit" class="px-4 py-2 rounded-lg text-white font-medium" style="background-color: var(--green);">Create Blog</button>
            <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2 rounded-lg border" style="border-color: var(--border);">Cancel</a>
        </div>
    </form>
</div>
@endsection
