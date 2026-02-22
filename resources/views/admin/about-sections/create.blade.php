@extends('layouts.app')

@section('title', 'Create About Section')
@section('page-title', 'Create About Section')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('admin.about-sections.index') }}" class="inline-flex items-center px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium" style="border-color: var(--border); color: var(--text);">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to About Sections
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <form method="POST" action="{{ route('admin.about-sections.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Kicker (Optional)</label>
                    <input 
                        type="text" 
                        name="kicker" 
                        value="{{ old('kicker') }}"
                        placeholder="e.g., About Us"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                        style="border-color: var(--border); color: var(--text);"
                    >
                    @error('kicker')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Order</label>
                    <input 
                        type="number" 
                        name="order" 
                        value="{{ old('order', 0) }}"
                        min="0"
                        required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                        style="border-color: var(--border); color: var(--text);"
                    >
                    @error('order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title <span class="text-red-600">*</span></label>
                <input 
                    type="text" 
                    name="title" 
                    value="{{ old('title') }}"
                    placeholder="e.g., Clean, Honest Essentials — Made to Feel Premium"
                    required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                    style="border-color: var(--border); color: var(--text);"
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description <span class="text-red-600">*</span></label>
                <textarea 
                    name="description" 
                    rows="5"
                    required
                    placeholder="Enter the about section description..."
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                    style="border-color: var(--border); color: var(--text);"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t pt-6" style="border-color: var(--border);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text);">Image</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Upload Image</label>
                        <input 
                            type="file" 
                            name="image_file" 
                            accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                            id="imageFile"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                            style="border-color: var(--border); color: var(--text);"
                            onchange="previewImage(event)"
                        >
                        <p class="mt-1 text-xs" style="color: var(--muted);">Accepts: jpeg, png, jpg, gif, webp (max 2MB)</p>
                        @error('image_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="imagePreview" class="hidden">
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Preview</label>
                        <img id="previewImg" src="" alt="Preview" class="max-w-xs rounded-lg border" style="border-color: var(--border);">
                    </div>

                    <div class="text-center text-sm" style="color: var(--muted);">OR</div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Manual Image Path</label>
                        <input 
                            type="text" 
                            name="image" 
                            value="{{ old('image') }}"
                            placeholder="e.g., images/transport.png"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                            style="border-color: var(--border); color: var(--text);"
                        >
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="border-t pt-6" style="border-color: var(--border);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text);">Button</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Button Text</label>
                        <input 
                            type="text" 
                            name="button_text" 
                            value="{{ old('button_text') }}"
                            placeholder="e.g., Know More"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                            style="border-color: var(--border); color: var(--text);"
                        >
                        @error('button_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Button Link</label>
                        <input 
                            type="text" 
                            name="button_link" 
                            value="{{ old('button_link') }}"
                            placeholder="e.g., /about"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                            style="border-color: var(--border); color: var(--text);"
                        >
                        @error('button_link')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="border-t pt-6" style="border-color: var(--border);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text);">Mini Items (Features)</h3>
                
                <div class="space-y-6">
                    <div class="p-4 border rounded-lg" style="border-color: var(--border);">
                        <h4 class="font-medium mb-3" style="color: var(--text);">Item 1</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title</label>
                                <input 
                                    type="text" 
                                    name="mini_item_1_title" 
                                    value="{{ old('mini_item_1_title') }}"
                                    placeholder="e.g., Clean Standards"
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                    style="border-color: var(--border); color: var(--text);"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Text</label>
                                <input 
                                    type="text" 
                                    name="mini_item_1_text" 
                                    value="{{ old('mini_item_1_text') }}"
                                    placeholder="e.g., Transparent sourcing & processes"
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                    style="border-color: var(--border); color: var(--text);"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border rounded-lg" style="border-color: var(--border);">
                        <h4 class="font-medium mb-3" style="color: var(--text);">Item 2</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title</label>
                                <input 
                                    type="text" 
                                    name="mini_item_2_title" 
                                    value="{{ old('mini_item_2_title') }}"
                                    placeholder="e.g., Packaging"
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                    style="border-color: var(--border); color: var(--text);"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Text</label>
                                <input 
                                    type="text" 
                                    name="mini_item_2_text" 
                                    value="{{ old('mini_item_2_text') }}"
                                    placeholder="e.g., Refined look & better protection"
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                    style="border-color: var(--border); color: var(--text);"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6" style="border-color: var(--border);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text);">Badge</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Badge Rating</label>
                        <input 
                            type="text" 
                            name="badge_rating" 
                            value="{{ old('badge_rating') }}"
                            placeholder="e.g., 4.8/5"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                            style="border-color: var(--border); color: var(--text);"
                        >
                        @error('badge_rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Badge Text</label>
                        <input 
                            type="text" 
                            name="badge_text" 
                            value="{{ old('badge_text') }}"
                            placeholder="e.g., Average customer rating"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                            style="border-color: var(--border); color: var(--text);"
                        >
                        @error('badge_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="border-t pt-6" style="border-color: var(--border);">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded"
                        style="color: var(--green);"
                    >
                    <span class="text-sm font-medium" style="color: var(--text);">Active</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t" style="border-color: var(--border);">
                <a href="{{ route('admin.about-sections.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium" style="border-color: var(--border); color: var(--text);">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 rounded-lg text-white text-sm font-medium hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                    Create About Section
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
