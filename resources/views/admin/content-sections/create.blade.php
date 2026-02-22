@extends('layouts.app')

@section('title', 'Create Content Section')
@section('page-title', 'Create Content Section')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Create Content Section</h2>
        <a href="{{ route('admin.content-sections.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.content-sections.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Section Key * <span class="text-xs font-normal" style="color: var(--muted);">(unique identifier)</span></label>
                    <input type="text" name="section_key" value="{{ old('section_key') }}" required class="w-full px-3 py-2 border rounded-lg font-mono" style="border-color: var(--border);">
                    @error('section_key')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Kicker</label>
                    <input type="text" name="kicker" value="{{ old('kicker') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('kicker')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Points <span class="text-xs font-normal" style="color: var(--muted);">(JSON array)</span></label>
                    <textarea name="points" rows="3" class="w-full px-3 py-2 border rounded-lg font-mono text-sm" style="border-color: var(--border);">{{ old('points') }}</textarea>
                    @error('points')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Buttons <span class="text-xs font-normal" style="color: var(--muted);">(JSON array)</span></label>
                    <textarea name="buttons" rows="3" class="w-full px-3 py-2 border rounded-lg font-mono text-sm" style="border-color: var(--border);">{{ old('buttons') }}</textarea>
                    @error('buttons')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Image Path</label>
                        <input type="text" name="image" value="{{ old('image') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        @error('image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Video ID</label>
                        <input type="text" name="video_id" value="{{ old('video_id') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        @error('video_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Gallery Images <span class="text-xs font-normal" style="color: var(--muted);">(JSON array)</span></label>
                    <textarea name="gallery_images" rows="2" class="w-full px-3 py-2 border rounded-lg font-mono text-sm" style="border-color: var(--border);">{{ old('gallery_images') }}</textarea>
                    @error('gallery_images')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Meta <span class="text-xs font-normal" style="color: var(--muted);">(JSON object)</span></label>
                    <textarea name="meta" rows="2" class="w-full px-3 py-2 border rounded-lg font-mono text-sm" style="border-color: var(--border);">{{ old('meta') }}</textarea>
                    @error('meta')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm" style="color: var(--text);">Active</span>
                    </label>
                </div>
            </div>
            <div class="flex space-x-3 mt-6">
                <button type="submit" class="px-4 py-2 rounded-lg text-white font-medium" style="background-color: var(--green);">Create Section</button>
                <a href="{{ route('admin.content-sections.index') }}" class="px-4 py-2 rounded-lg border" style="border-color: var(--border);">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
