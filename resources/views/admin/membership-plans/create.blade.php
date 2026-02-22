@extends('layouts.app')

@section('title', 'Create Membership Plan')
@section('page-title', 'Create Membership Plan')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Create New Membership Plan</h2>
        <a href="{{ route('admin.membership-plans.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.membership-plans.store') }}">
        @csrf
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Slug (optional, auto-generated)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Price *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('price')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Duration *</label>
                    <select name="duration" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <option value="month" {{ old('duration') == 'month' ? 'selected' : '' }}>Month</option>
                        <option value="year" {{ old('duration') == 'year' ? 'selected' : '' }}>Year</option>
                    </select>
                    @error('duration')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Order</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Badge (e.g., Starter, Most Popular)</label>
                    <input type="text" name="badge" value="{{ old('badge') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('badge')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Icon (FontAwesome class, e.g., fa-fire)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="fa-fire" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('icon')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Features</label>
                <div id="features-container" class="space-y-2">
                    @if(old('features'))
                        @foreach(old('features') as $index => $feature)
                        <div class="flex gap-2">
                            <input type="text" name="features[]" value="{{ $feature }}" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Feature">
                            <button type="button" class="px-3 py-2 rounded-lg border text-red-600 hover:bg-red-50 remove-feature" style="border-color: var(--border);">Remove</button>
                        </div>
                        @endforeach
                    @else
                    <div class="flex gap-2">
                        <input type="text" name="features[]" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Feature 1">
                        <button type="button" class="px-3 py-2 rounded-lg border text-red-600 hover:bg-red-50 remove-feature" style="border-color: var(--border);">Remove</button>
                    </div>
                    @endif
                </div>
                <button type="button" class="mt-2 px-3 py-2 rounded-lg text-sm font-medium" style="background-color: var(--green); color: white;" id="add-feature">+ Add Feature</button>
            </div>

            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm" style="color: var(--text);">Featured Plan</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm" style="color: var(--text);">Active</span>
                </label>
            </div>
        </div>

        <div class="flex space-x-3 mt-6">
            <button type="submit" class="px-4 py-2 rounded-lg text-white font-medium" style="background-color: var(--green);">Create Plan</button>
            <a href="{{ route('admin.membership-plans.index') }}" class="px-4 py-2 rounded-lg border" style="border-color: var(--border);">Cancel</a>
        </div>
    </form>
</div>

<script>
document.getElementById('add-feature').addEventListener('click', function() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="features[]" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="New feature">
        <button type="button" class="px-3 py-2 rounded-lg border text-red-600 hover:bg-red-50 remove-feature" style="border-color: var(--border);">Remove</button>
    `;
    container.appendChild(div);
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-feature')) {
        e.target.closest('.flex').remove();
    }
});
</script>
@endsection
