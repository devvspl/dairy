@extends('layouts.app')

@section('title', 'Create SEO Meta')
@section('page-title', 'Create SEO Meta')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Create SEO Meta</h2>
        <a href="{{ route('admin.seo-metas.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.seo-metas.store') }}">
        @csrf
        
        <div class="space-y-6">
            <!-- Page Information -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Page Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Page URL (Slug) *</label>
                        <input type="text" name="page_url" value="{{ old('page_url') }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., /about, /products, /contact">
                        <p class="text-xs mt-1" style="color: var(--muted);">Enter the page URL or slug (e.g., /about, /products/milk, /blog/post-slug)</p>
                        @error('page_url')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Meta Tags -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Meta Tags</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Meta Title *</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Page title for search engines (50-60 characters)">
                        <p class="text-xs mt-1" style="color: var(--muted);">Recommended length: 50-60 characters</p>
                        @error('meta_title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Meta Description *</label>
                        <textarea name="meta_description" rows="3" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Brief description for search results (150-160 characters)">{{ old('meta_description') }}</textarea>
                        <p class="text-xs mt-1" style="color: var(--muted);">Recommended length: 150-160 characters</p>
                        @error('meta_description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Meta Keywords</label>
                        <textarea name="meta_keywords" rows="2" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="keyword1, keyword2, keyword3">{{ old('meta_keywords') }}</textarea>
                        <p class="text-xs mt-1" style="color: var(--muted);">Comma-separated keywords (optional, less important for modern SEO)</p>
                        @error('meta_keywords')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Advanced Settings</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Canonical URL</label>
                        <input type="url" name="canonical_url" value="{{ old('canonical_url') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="https://example.com/page">
                        <p class="text-xs mt-1" style="color: var(--muted);">Specify the preferred URL for this page (helps prevent duplicate content issues)</p>
                        @error('canonical_url')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Robots Meta Tag *</label>
                        <select name="robots" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <option value="index,follow" {{ old('robots') == 'index,follow' ? 'selected' : '' }}>index, follow (Default - Allow indexing and following links)</option>
                            <option value="noindex,follow" {{ old('robots') == 'noindex,follow' ? 'selected' : '' }}>noindex, follow (Don't index but follow links)</option>
                            <option value="index,nofollow" {{ old('robots') == 'index,nofollow' ? 'selected' : '' }}>index, nofollow (Index but don't follow links)</option>
                            <option value="noindex,nofollow" {{ old('robots') == 'noindex,nofollow' ? 'selected' : '' }}>noindex, nofollow (Don't index or follow links)</option>
                        </select>
                        <p class="text-xs mt-1" style="color: var(--muted);">Controls how search engines crawl and index this page</p>
                        @error('robots')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-4 border-t" style="border-color: var(--border); padding-top: 1.5rem;">
                <button type="submit" class="px-6 py-2 rounded-lg text-white font-medium transition-colors hover:opacity-90" style="background-color: var(--green);">
                    Create SEO Meta
                </button>
                <a href="{{ route('admin.seo-metas.index') }}" class="px-6 py-2 rounded-lg font-medium transition-colors hover:bg-gray-50" style="color: var(--text); border: 1px solid var(--border);">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
