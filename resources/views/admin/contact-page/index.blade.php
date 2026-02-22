@extends('layouts.app')

@section('title', 'Contact Page Settings')
@section('page-title', 'Contact Page Settings')

@section('content')
<div class="space-y-4 lg:space-y-6">
    @if(session('success'))
        <div class="p-4 rounded-lg border" style="background-color: #f0fdf4; border-color: var(--green); color: #166534;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.contact-page.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Hero Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Hero Section</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Main banner at the top of contact page</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Hero Title</label>
                    <input 
                        type="text" 
                        name="hero_title" 
                        value="{{ old('hero_title', $contactPage->hero_title) }}"
                        placeholder="e.g., Let's talk—quick support, clear answers."
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                        style="border-color: var(--border); color: var(--text);"
                    >
                    @error('hero_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Hero Description</label>
                    <textarea 
                        name="hero_description" 
                        rows="3"
                        placeholder="Enter hero description..."
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                        style="border-color: var(--border); color: var(--text);"
                    >{{ old('hero_description', $contactPage->hero_description) }}</textarea>
                    @error('hero_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Hero Phone</label>
                        <input 
                            type="text" 
                            name="hero_phone" 
                            value="{{ old('hero_phone', $contactPage->hero_phone) }}"
                            placeholder="e.g., +911234567890"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                            style="border-color: var(--border); color: var(--text);"
                        >
                        @error('hero_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Hero Email</label>
                        <input 
                            type="email" 
                            name="hero_email" 
                            value="{{ old('hero_email', $contactPage->hero_email) }}"
                            placeholder="e.g., hello@example.com"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                            style="border-color: var(--border); color: var(--text);"
                        >
                        @error('hero_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Hero Background Image</label>
                    
                    @if($contactPage->hero_image)
                        <div class="mb-3">
                            <p class="text-sm mb-2" style="color: var(--muted);">Current Image:</p>
                            <img src="{{ asset($contactPage->hero_image) }}" alt="Current" class="max-w-xs rounded-lg border" style="border-color: var(--border);">
                        </div>
                    @endif
                    
                    <input 
                        type="file" 
                        name="hero_image_file" 
                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                        id="heroImageFile"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                        style="border-color: var(--border); color: var(--text);"
                        onchange="previewImage(event, 'heroPreview')"
                    >
                    <p class="mt-1 text-xs" style="color: var(--muted);">Accepts: jpeg, png, jpg, gif, webp (max 2MB)</p>
                    @error('hero_image_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <div id="heroPreview" class="hidden mt-3">
                        <p class="text-sm mb-2" style="color: var(--muted);">Preview:</p>
                        <img id="heroPreviewImg" src="" alt="Preview" class="max-w-xs rounded-lg border" style="border-color: var(--border);">
                    </div>

                    <div class="text-center text-sm my-2" style="color: var(--muted);">OR</div>

                    <input 
                        type="text" 
                        name="hero_image" 
                        value="{{ old('hero_image', $contactPage->hero_image) }}"
                        placeholder="e.g., images/contact-us.webp"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                        style="border-color: var(--border); color: var(--text);"
                    >
                    <p class="mt-1 text-xs" style="color: var(--muted);">Manual image path (relative to public folder)</p>
                </div>
            </div>
        </div>

        <!-- Contact Cards Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Contact Information Cards</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Phone, Email, and Address cards</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Phone Card -->
                <div class="p-4 border rounded-lg" style="border-color: var(--border);">
                    <h3 class="font-semibold mb-3" style="color: var(--text);">Phone Card</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title</label>
                            <input 
                                type="text" 
                                name="phone_title" 
                                value="{{ old('phone_title', $contactPage->phone_title) }}"
                                placeholder="e.g., Call Us"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                style="border-color: var(--border); color: var(--text);"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description</label>
                            <textarea 
                                name="phone_description" 
                                rows="2"
                                placeholder="Enter description..."
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                style="border-color: var(--border); color: var(--text);"
                            >{{ old('phone_description', $contactPage->phone_description) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Phone Number</label>
                            <input 
                                type="text" 
                                name="phone_number" 
                                value="{{ old('phone_number', $contactPage->phone_number) }}"
                                placeholder="e.g., +91 12345 67890"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                style="border-color: var(--border); color: var(--text);"
                            >
                        </div>
                    </div>
                </div>

                <!-- Email Card -->
                <div class="p-4 border rounded-lg" style="border-color: var(--border);">
                    <h3 class="font-semibold mb-3" style="color: var(--text);">Email Card</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title</label>
                            <input 
                                type="text" 
                                name="email_title" 
                                value="{{ old('email_title', $contactPage->email_title) }}"
                                placeholder="e.g., Email"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                style="border-color: var(--border); color: var(--text);"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description</label>
                            <textarea 
                                name="email_description" 
                                rows="2"
                                placeholder="Enter description..."
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                style="border-color: var(--border); color: var(--text);"
                            >{{ old('email_description', $contactPage->email_description) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Email Address</label>
                            <input 
                                type="email" 
                                name="email_address" 
                                value="{{ old('email_address', $contactPage->email_address) }}"
                                placeholder="e.g., hello@example.com"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                style="border-color: var(--border); color: var(--text);"
                            >
                        </div>
                    </div>
                </div>

                <!-- Address Card -->
                <div class="p-4 border rounded-lg" style="border-color: var(--border);">
                    <h3 class="font-semibold mb-3" style="color: var(--text);">Address Card</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Title</label>
                            <input 
                                type="text" 
                                name="address_title" 
                                value="{{ old('address_title', $contactPage->address_title) }}"
                                placeholder="e.g., Visit"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                style="border-color: var(--border); color: var(--text);"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description</label>
                            <textarea 
                                name="address_description" 
                                rows="2"
                                placeholder="Enter description..."
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                style="border-color: var(--border); color: var(--text);"
                            >{{ old('address_description', $contactPage->address_description) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Full Address</label>
                            <textarea 
                                name="address_full" 
                                rows="2"
                                placeholder="Enter full address..."
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                style="border-color: var(--border); color: var(--text);"
                            >{{ old('address_full', $contactPage->address_full) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Map Section</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Google Maps integration</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Map Title</label>
                    <input 
                        type="text" 
                        name="map_title" 
                        value="{{ old('map_title', $contactPage->map_title) }}"
                        placeholder="e.g., Find us on map"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                        style="border-color: var(--border); color: var(--text);"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Map Embed URL</label>
                    <textarea 
                        name="map_embed_url" 
                        rows="3"
                        placeholder="Paste Google Maps embed URL here..."
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all font-mono text-sm"
                        style="border-color: var(--border); color: var(--text);"
                    >{{ old('map_embed_url', $contactPage->map_embed_url) }}</textarea>
                    <p class="mt-1 text-xs" style="color: var(--muted);">Get embed URL from Google Maps → Share → Embed a map</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Map Link (Open in Maps)</label>
                    <input 
                        type="url" 
                        name="map_link" 
                        value="{{ old('map_link', $contactPage->map_link) }}"
                        placeholder="e.g., https://maps.google.com/..."
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                        style="border-color: var(--border); color: var(--text);"
                    >
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">FAQ Section</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Frequently Asked Questions</p>
                </div>
                <button type="button" onclick="addFaq()" class="px-4 py-2 rounded-lg text-white text-sm font-medium hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                    Add FAQ
                </button>
            </div>

            <div id="faqContainer" class="space-y-4">
                @if($contactPage->faqs && count($contactPage->faqs) > 0)
                    @foreach($contactPage->faqs as $index => $faq)
                        <div class="faq-item p-4 border rounded-lg" style="border-color: var(--border);">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold" style="color: var(--text);">FAQ #<span class="faq-number">{{ $index + 1 }}</span></h3>
                                <button type="button" onclick="removeFaq(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Remove
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Question</label>
                                    <input 
                                        type="text" 
                                        name="faq_questions[]" 
                                        value="{{ $faq['question'] }}"
                                        placeholder="Enter question..."
                                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                        style="border-color: var(--border); color: var(--text);"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Answer</label>
                                    <textarea 
                                        name="faq_answers[]" 
                                        rows="2"
                                        placeholder="Enter answer..."
                                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                                        style="border-color: var(--border); color: var(--text);"
                                    >{{ $faq['answer'] }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <label class="flex items-center space-x-3 cursor-pointer">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    value="1"
                    {{ old('is_active', $contactPage->is_active) ? 'checked' : '' }}
                    class="w-4 h-4 rounded"
                    style="color: var(--green);"
                >
                <span class="text-sm font-medium" style="color: var(--text);">Active (Show contact page)</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <button type="submit" class="px-6 py-3 rounded-lg text-white text-sm font-medium hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(event, previewId) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId + 'Img').src = e.target.result;
            document.getElementById(previewId).classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function addFaq() {
    const container = document.getElementById('faqContainer');
    const count = container.querySelectorAll('.faq-item').length + 1;
    
    const faqHtml = `
        <div class="faq-item p-4 border rounded-lg" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold" style="color: var(--text);">FAQ #<span class="faq-number">${count}</span></h3>
                <button type="button" onclick="removeFaq(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">
                    Remove
                </button>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Question</label>
                    <input 
                        type="text" 
                        name="faq_questions[]" 
                        placeholder="Enter question..."
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                        style="border-color: var(--border); color: var(--text);"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Answer</label>
                    <textarea 
                        name="faq_answers[]" 
                        rows="2"
                        placeholder="Enter answer..."
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none transition-all"
                        style="border-color: var(--border); color: var(--text);"
                    ></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', faqHtml);
}

function removeFaq(button) {
    const faqItem = button.closest('.faq-item');
    faqItem.remove();
    updateFaqNumbers();
}

function updateFaqNumbers() {
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach((item, index) => {
        item.querySelector('.faq-number').textContent = index + 1;
    });
}
</script>
@endsection
