@extends('layouts.app')

@section('title', 'View Why Choose Us')
@section('page-title', 'Why Choose Us Details')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color: var(--text);">{{ $whyChooseUs->title }}</h2>
        </div>
        
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <a href="{{ route('admin.why-choose-us.edit', $whyChooseUs) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.why-choose-us.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Title</p>
                    <p class="text-base font-semibold" style="color: var(--text);">{{ $whyChooseUs->title }}</p>
                </div>
            </div>
        </div>

        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Order</p>
                    <p class="text-base font-semibold" style="color: var(--text);">{{ $whyChooseUs->order }}</p>
                </div>
            </div>
        </div>

        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Status</p>
                    <span class="px-2 py-1 text-xs rounded-full {{ $whyChooseUs->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $whyChooseUs->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        @if($whyChooseUs->description)
        <div class="md:col-span-2 p-4 rounded-lg border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-2" style="color: var(--muted);">Description</p>
            <p class="text-base" style="color: var(--text);">{{ $whyChooseUs->description }}</p>
        </div>
        @endif

        @if($whyChooseUs->svg_path)
        <div class="md:col-span-2 p-4 rounded-lg border" style="border-color: var(--border);">
            <p class="text-sm font-medium mb-2" style="color: var(--muted);">SVG Path</p>
            <pre class="text-sm font-mono bg-gray-50 p-3 rounded overflow-x-auto" style="color: var(--text);">{{ $whyChooseUs->svg_path }}</pre>
        </div>
        @endif
    </div>

    <div class="mt-6 pt-6 border-t" style="border-color: var(--border);">
        <form method="POST" action="{{ route('admin.why-choose-us.destroy', $whyChooseUs) }}" onsubmit="return confirm('Are you sure you want to delete this item?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors">
                Delete Item
            </button>
        </form>
    </div>
</div>
@endsection
