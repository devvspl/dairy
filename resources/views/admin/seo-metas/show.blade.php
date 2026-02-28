@extends('layouts.app')

@section('title', 'View SEO Meta')
@section('page-title', 'View SEO Meta')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">SEO Meta Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.seo-metas.edit', $seoMeta) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--green);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </a>
            <a href="{{ route('admin.seo-metas.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Page Information -->
        <div>
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Page Information</h3>
            <div class="p-4 rounded-lg border" style="border-color: var(--border); background-color: #f9fafb;">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--muted);">Page URL</label>
                        <p class="text-base font-semibold" style="color: var(--text);">{{ $seoMeta->page_url }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meta Tags -->
        <div>
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Meta Tags</h3>
            <div class="space-y-4">
                <div class="p-4 rounded-lg border" style="border-color: var(--border); background-color: #f9fafb;">
                    <label class="block text-sm font-medium mb-2" style="color: var(--muted);">Meta Title</label>
                    <p class="text-base" style="color: var(--text);">{{ $seoMeta->meta_title }}</p>
                    <p class="text-xs mt-1" style="color: var(--muted);">Length: {{ strlen($seoMeta->meta_title) }} characters</p>
                </div>

                <div class="p-4 rounded-lg border" style="border-color: var(--border); background-color: #f9fafb;">
                    <label class="block text-sm font-medium mb-2" style="color: var(--muted);">Meta Description</label>
                    <p class="text-base" style="color: var(--text);">{{ $seoMeta->meta_description }}</p>
                    <p class="text-xs mt-1" style="color: var(--muted);">Length: {{ strlen($seoMeta->meta_description) }} characters</p>
                </div>

                @if($seoMeta->meta_keywords)
                <div class="p-4 rounded-lg border" style="border-color: var(--border); background-color: #f9fafb;">
                    <label class="block text-sm font-medium mb-2" style="color: var(--muted);">Meta Keywords</label>
                    <p class="text-base" style="color: var(--text);">{{ $seoMeta->meta_keywords }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Advanced Settings -->
        <div>
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Advanced Settings</h3>
            <div class="space-y-4">
                @if($seoMeta->canonical_url)
                <div class="p-4 rounded-lg border" style="border-color: var(--border); background-color: #f9fafb;">
                    <label class="block text-sm font-medium mb-2" style="color: var(--muted);">Canonical URL</label>
                    <p class="text-base" style="color: var(--text);">{{ $seoMeta->canonical_url }}</p>
                </div>
                @endif

                <div class="p-4 rounded-lg border" style="border-color: var(--border); background-color: #f9fafb;">
                    <label class="block text-sm font-medium mb-2" style="color: var(--muted);">Robots Meta Tag</label>
                    <span class="px-3 py-1 text-sm rounded-full {{ str_contains($seoMeta->robots, 'noindex') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ $seoMeta->robots }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Preview -->
        <div>
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Search Engine Preview</h3>
            <div class="p-6 rounded-lg border" style="border-color: var(--border); background-color: #ffffff;">
                <div class="max-w-2xl">
                    <div class="text-blue-600 text-xl mb-1 hover:underline cursor-pointer">{{ $seoMeta->meta_title }}</div>
                    <div class="text-green-700 text-sm mb-2">{{ $seoMeta->canonical_url ?: url($seoMeta->page_url) }}</div>
                    <div class="text-gray-600 text-sm">{{ $seoMeta->meta_description }}</div>
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div>
            <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-lg" style="background-color: #f0f4ed;">
                    <div class="text-sm font-medium" style="color: var(--muted);">Created</div>
                    <div class="text-sm font-bold mt-1" style="color: var(--text);">{{ $seoMeta->created_at->format('M d, Y h:i A') }}</div>
                </div>
                <div class="p-4 rounded-lg" style="background-color: #f0f4ed;">
                    <div class="text-sm font-medium" style="color: var(--muted);">Last Updated</div>
                    <div class="text-sm font-bold mt-1" style="color: var(--text);">{{ $seoMeta->updated_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 pt-4 border-t" style="border-color: var(--border);">
            <a href="{{ route('admin.seo-metas.edit', $seoMeta) }}" class="px-6 py-2 rounded-lg text-white font-medium transition-colors hover:opacity-90" style="background-color: var(--green);">
                Edit SEO Meta
            </a>
            <form method="POST" action="{{ route('admin.seo-metas.destroy', $seoMeta) }}" onsubmit="return confirm('Are you sure you want to delete this SEO meta?');">
                @csrf @method('DELETE')
                <button type="submit" class="px-6 py-2 rounded-lg font-medium transition-colors bg-red-600 text-white hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
