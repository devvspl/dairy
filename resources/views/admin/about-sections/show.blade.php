@extends('layouts.app')

@section('title', 'View About Section')
@section('page-title', 'View About Section')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.about-sections.index') }}" class="inline-flex items-center px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium" style="border-color: var(--border); color: var(--text);">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to About Sections
        </a>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.about-sections.edit', $aboutSection) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-white text-sm font-medium hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            
            <form method="POST" action="{{ route('admin.about-sections.destroy', $aboutSection) }}" onsubmit="return confirm('Are you sure you want to delete this about section?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-600 rounded-lg text-red-600 text-sm font-medium hover:bg-red-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="space-y-6">
            <div class="flex items-center justify-between pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-2xl font-bold" style="color: var(--text);">{{ $aboutSection->title }}</h2>
                    @if($aboutSection->kicker)
                        <p class="text-sm mt-1" style="color: var(--muted);">{{ $aboutSection->kicker }}</p>
                    @endif
                </div>
                <span class="px-3 py-1 text-sm rounded-full {{ $aboutSection->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $aboutSection->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            @if($aboutSection->image)
                <div>
                    <h3 class="text-lg font-semibold mb-3" style="color: var(--text);">Image</h3>
                    <img src="{{ asset($aboutSection->image) }}" alt="{{ $aboutSection->title }}" class="max-w-2xl rounded-lg border" style="border-color: var(--border);">
                </div>
            @endif

            <div>
                <h3 class="text-lg font-semibold mb-3" style="color: var(--text);">Description</h3>
                <p class="text-base leading-relaxed" style="color: var(--text);">{{ $aboutSection->description }}</p>
            </div>

            @if($aboutSection->button_text || $aboutSection->button_link)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if($aboutSection->button_text)
                        <div>
                            <h3 class="text-lg font-semibold mb-3" style="color: var(--text);">Button Text</h3>
                            <p class="text-base" style="color: var(--text);">{{ $aboutSection->button_text }}</p>
                        </div>
                    @endif

                    @if($aboutSection->button_link)
                        <div>
                            <h3 class="text-lg font-semibold mb-3" style="color: var(--text);">Button Link</h3>
                            <a href="{{ $aboutSection->button_link }}" class="text-base hover:underline" style="color: var(--green);">{{ $aboutSection->button_link }}</a>
                        </div>
                    @endif
                </div>
            @endif

            @if($aboutSection->mini_items && count($aboutSection->mini_items) > 0)
                <div>
                    <h3 class="text-lg font-semibold mb-3" style="color: var(--text);">Mini Items (Features)</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach($aboutSection->mini_items as $index => $item)
                            <div class="p-4 border rounded-lg" style="border-color: var(--border);">
                                <h4 class="font-medium mb-2" style="color: var(--text);">{{ $item['title'] ?? 'Item ' . ($index + 1) }}</h4>
                                <p class="text-sm" style="color: var(--muted);">{{ $item['text'] ?? 'N/A' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($aboutSection->badge_rating || $aboutSection->badge_text)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if($aboutSection->badge_rating)
                        <div>
                            <h3 class="text-lg font-semibold mb-3" style="color: var(--text);">Badge Rating</h3>
                            <p class="text-base" style="color: var(--text);">{{ $aboutSection->badge_rating }}</p>
                        </div>
                    @endif

                    @if($aboutSection->badge_text)
                        <div>
                            <h3 class="text-lg font-semibold mb-3" style="color: var(--text);">Badge Text</h3>
                            <p class="text-base" style="color: var(--text);">{{ $aboutSection->badge_text }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pt-6 border-t" style="border-color: var(--border);">
                <div>
                    <h3 class="text-sm font-semibold mb-2" style="color: var(--muted);">Order</h3>
                    <p class="text-base" style="color: var(--text);">{{ $aboutSection->order }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold mb-2" style="color: var(--muted);">Created At</h3>
                    <p class="text-base" style="color: var(--text);">{{ $aboutSection->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold mb-2" style="color: var(--muted);">Updated At</h3>
                    <p class="text-base" style="color: var(--text);">{{ $aboutSection->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
