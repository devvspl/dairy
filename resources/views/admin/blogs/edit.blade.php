@extends('layouts.app')

@section('title', 'Edit Blog')
@section('page-title', 'Edit Blog')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Edit Blog</h2>
        <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title *</label>
                <input type="text" name="title" value="{{ old('title', $blog->title) }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $blog->slug) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Featured Image</label>
                @if($blog->image)
                    <div class="mb-2">
                        <img src="{{ asset($blog->image) }}" alt="Current featured image" class="w-48 h-32 object-cover rounded border">
                        <p class="text-xs mt-1" style="color: var(--muted);">Current image</p>
                    </div>
                @endif
                <input type="file" name="featured_image" accept="image/*" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                <p class="text-xs mt-1" style="color: var(--muted);">Max 2MB. Leave empty to keep current image.</p>
                @error('featured_image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Excerpt</label>
                <textarea name="excerpt" rows="3" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('excerpt', $blog->excerpt) }}</textarea>
                @error('excerpt')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Content</label>
                <textarea id="editor1" name="content" rows="8" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('content', $blog->content) }}</textarea>
                @error('content')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Tag</label>
                    <input type="text" name="tag" value="{{ old('tag', $blog->tag) }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('tag')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Order *</label>
                    <input type="number" name="order" value="{{ old('order', $blog->order) }}" required min="0" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm" style="color: var(--text);">Featured</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $blog->is_active) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm" style="color: var(--text);">Active</span>
                </label>
            </div>
        </div>

        <div class="flex space-x-3 mt-6">
            <button type="submit" class="px-4 py-2 rounded-lg text-white font-medium" style="background-color: var(--green);">Update Blog</button>
            <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2 rounded-lg border" style="border-color: var(--border);">Cancel</a>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.2/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#editor1',
    height: 450,
    plugins: 'advlist lists link image table preview fullscreen charmap paste codesample code',
    toolbar: `bold italic underline | alignleft aligncenter alignright alignjustify | 
              fontsizeselect | forecolor backcolor | numlist bullist | indent outdent | 
              link image | table | preview fullscreen | charmap code`,
    
    automatic_uploads: true,
    images_upload_url: '{{ route("admin.blogs.upload-image") }}',
    
    images_upload_handler: function (blobInfo, success, failure) {
        var xhr, formData;
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', '{{ route("admin.blogs.upload-image") }}');
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        
        xhr.onload = function() {
            var json;
            if (xhr.status != 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
            json = JSON.parse(xhr.responseText);
            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
            success(json.location);
        };
        
        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        xhr.send(formData);
    },
    
    file_picker_types: 'image',
    file_picker_callback: function(callback, value, meta) {
        if (meta.filetype === 'image') {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function() {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function() {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    callback(blobInfo.blobUri(), { title: file.name });
                };
                reader.readAsDataURL(file);
            };
            input.click();
        }
    }
});
</script>
@endsection
