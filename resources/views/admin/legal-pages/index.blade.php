@extends('layouts.app')

@section('title', $pageTitle)
@section('page-title', $pageTitle)

@section('content')
<div class="space-y-4 lg:space-y-6">
    @if(session('success'))
        <div class="p-4 rounded-lg border" style="background-color: #f0fdf4; border-color: var(--green); color: #166534;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.legal-pages.update', $pageKey) }}" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Basic Information</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Page title and hero section</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Page Title</label>
                    <input type="text" name="title" value="{{ old('title', $legalPage->title) }}" required
                        class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Hero Description</label>
                    <textarea name="hero_description" rows="3" class="w-full px-3 py-2 border rounded-lg" 
                        style="border-color: var(--border);">{{ old('hero_description', $legalPage->hero_description) }}</textarea>
                    <p class="text-xs mt-1" style="color: var(--muted);">Short description shown in the hero section</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Last Updated Date</label>
                    <input type="text" name="last_updated" value="{{ old('last_updated', $legalPage->last_updated) }}" 
                        placeholder="e.g., February 22, 2026"
                        class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Page Content</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Main content with rich text editor</p>
                </div>
            </div>

            <div>
                <textarea name="content" id="tinymce-editor" class="w-full">{{ old('content', $legalPage->content) }}</textarea>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Contact Information</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Contact details for inquiries</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $legalPage->contact_email) }}"
                        class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Contact Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $legalPage->contact_phone) }}"
                        class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Contact Address</label>
                    <textarea name="contact_address" rows="3" class="w-full px-3 py-2 border rounded-lg" 
                        style="border-color: var(--border);">{{ old('contact_address', $legalPage->contact_address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $legalPage->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded" style="color: var(--green);">
                <span class="text-sm font-medium" style="color: var(--text);">Active</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 rounded-lg text-white text-sm font-medium" style="background-color: var(--green);">
                Save Changes
            </button>
        </div>
    </form>
</div>

<!-- TinyMCE Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#tinymce-editor',
        height: 600,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic underline strikethrough | ' +
            'alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | removeformat | help',
        content_style: 'body { font-family:system-ui,Arial,sans-serif; font-size:15px; line-height:1.6; color:#1f2a1a; }',
        branding: false,
    });
</script>
@endsection
