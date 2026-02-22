@extends('layouts.app')

@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Edit Announcement</h2>
        <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Basic Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title *</label>
                        <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Message * (HTML allowed)</label>
                        <textarea name="message" rows="3" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('message', $announcement->message) }}</textarea>
                        <p class="text-xs mt-1" style="color: var(--muted);">Use &lt;b&gt; for bold text</p>
                        @error('message')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Icon (Emoji)</label>
                            <input type="text" name="icon" value="{{ old('icon', $announcement->icon) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            @error('icon')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Order *</label>
                            <input type="number" name="order" value="{{ old('order', $announcement->order) }}" required min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            @error('order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Link URL</label>
                            <input type="url" name="link" value="{{ old('link', $announcement->link) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            @error('link')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Link Text</label>
                            <input type="text" name="link_text" value="{{ old('link_text', $announcement->link_text) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            @error('link_text')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Status</h3>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded" style="color: var(--brand);">
                    <label for="is_active" class="ml-2 text-sm font-medium" style="color: var(--text);">Active</label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="px-6 py-2 rounded-lg text-white font-medium transition-colors" style="background-color: #2f4a1e;">
                    Update Announcement
                </button>
                <a href="{{ route('admin.announcements.index') }}" class="px-6 py-2 rounded-lg font-medium transition-colors hover:bg-gray-100" style="color: var(--text); border: 1px solid var(--border);">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
