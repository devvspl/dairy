@extends('layouts.app')

@section('title', 'About Page Settings')
@section('page-title', 'About Page Settings')

@section('content')
<div class="space-y-4 lg:space-y-6">
    @if(session('success'))
        <div class="p-4 rounded-lg border" style="background-color: #f0fdf4; border-color: var(--green); color: #166534;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.about-page.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Hero Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Hero Section</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Main banner at the top</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Hero Title</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $aboutPage->hero_title) }}"
                        class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Hero Description</label>
                    <textarea name="hero_description" rows="3" class="w-full px-3 py-2 border rounded-lg" 
                        style="border-color: var(--border);">{{ old('hero_description', $aboutPage->hero_description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Button 1 Text</label>
                        <input type="text" name="hero_button_1_text" value="{{ old('hero_button_1_text', $aboutPage->hero_button_1_text) }}"
                            class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Button 1 Link</label>
                        <input type="text" name="hero_button_1_link" value="{{ old('hero_button_1_link', $aboutPage->hero_button_1_link) }}"
                            class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Button 2 Text</label>
                        <input type="text" name="hero_button_2_text" value="{{ old('hero_button_2_text', $aboutPage->hero_button_2_text) }}"
                            class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Button 2 Link</label>
                        <input type="text" name="hero_button_2_link" value="{{ old('hero_button_2_link', $aboutPage->hero_button_2_link) }}"
                            class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Hero Image</label>
                    @if($aboutPage->hero_image)
                        <img src="{{ asset($aboutPage->hero_image) }}" class="max-w-xs rounded mb-2">
                    @endif
                    <input type="file" name="hero_image_file" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    <input type="text" name="hero_image" value="{{ old('hero_image', $aboutPage->hero_image) }}" 
                        placeholder="Or enter image path" class="w-full px-3 py-2 border rounded-lg mt-2" style="border-color: var(--border);">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium" style="color: var(--text);">Hero Badges</label>
                        <button type="button" onclick="addHeroBadge()" class="px-3 py-1 rounded text-white text-sm" style="background-color: var(--green);">Add Badge</button>
                    </div>
                    <div id="heroBadgesContainer" class="space-y-2">
                        @if($aboutPage->hero_badges && count($aboutPage->hero_badges) > 0)
                            @foreach($aboutPage->hero_badges as $badge)
                                <div class="flex gap-2 badge-item">
                                    <input type="text" name="hero_badge_icons[]" value="{{ $badge['icon'] }}" placeholder="Icon (e.g., fa-shield-heart)" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                    <input type="text" name="hero_badge_texts[]" value="{{ $badge['text'] }}" placeholder="Badge text" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 text-red-600">Remove</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Overview Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Overview Section</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Company overview with image</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Overview Title</label>
                    <input type="text" name="overview_title" value="{{ old('overview_title', $aboutPage->overview_title) }}"
                        class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Overview Description</label>
                    <textarea name="overview_description" rows="3" class="w-full px-3 py-2 border rounded-lg" 
                        style="border-color: var(--border);">{{ old('overview_description', $aboutPage->overview_description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Overview Image</label>
                    @if($aboutPage->overview_image)
                        <img src="{{ asset($aboutPage->overview_image) }}" class="max-w-xs rounded mb-2">
                    @endif
                    <input type="file" name="overview_image_file" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    <input type="text" name="overview_image" value="{{ old('overview_image', $aboutPage->overview_image) }}" 
                        placeholder="Or enter image path" class="w-full px-3 py-2 border rounded-lg mt-2" style="border-color: var(--border);">
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Badge Rating</label>
                        <input type="text" name="overview_badge_rating" value="{{ old('overview_badge_rating', $aboutPage->overview_badge_rating) }}"
                            class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Badge Text</label>
                        <input type="text" name="overview_badge_text" value="{{ old('overview_badge_text', $aboutPage->overview_badge_text) }}"
                            class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Button Text</label>
                        <input type="text" name="overview_button_text" value="{{ old('overview_button_text', $aboutPage->overview_button_text) }}"
                            class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Button Link</label>
                        <input type="text" name="overview_button_link" value="{{ old('overview_button_link', $aboutPage->overview_button_link) }}"
                            class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium" style="color: var(--text);">Overview Checks</label>
                        <button type="button" onclick="addOverviewCheck()" class="px-3 py-1 rounded text-white text-sm" style="background-color: var(--green);">Add Check</button>
                    </div>
                    <div id="overviewChecksContainer" class="space-y-3">
                        @if($aboutPage->overview_checks && count($aboutPage->overview_checks) > 0)
                            @foreach($aboutPage->overview_checks as $check)
                                <div class="p-3 border rounded check-item" style="border-color: var(--border);">
                                    <div class="flex gap-2 mb-2">
                                        <input type="text" name="overview_check_icons[]" value="{{ $check['icon'] }}" placeholder="Icon" class="w-1/3 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                        <input type="text" name="overview_check_titles[]" value="{{ $check['title'] }}" placeholder="Title" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                        <button type="button" onclick="this.closest('.check-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
                                    </div>
                                    <input type="text" name="overview_check_descriptions[]" value="{{ $check['description'] }}" placeholder="Description" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- USPs Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">USPs Section</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Unique selling points</p>
                </div>
                <button type="button" onclick="addUSP()" class="px-4 py-2 rounded text-white text-sm" style="background-color: var(--green);">Add USP</button>
            </div>

            <div id="uspsContainer" class="space-y-3">
                @if($aboutPage->usps && count($aboutPage->usps) > 0)
                    @foreach($aboutPage->usps as $usp)
                        <div class="p-3 border rounded usp-item" style="border-color: var(--border);">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="usp_icons[]" value="{{ $usp['icon'] }}" placeholder="Icon" class="w-1/4 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                <input type="text" name="usp_titles[]" value="{{ $usp['title'] }}" placeholder="Title" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                <button type="button" onclick="this.closest('.usp-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
                            </div>
                            <input type="text" name="usp_descriptions[]" value="{{ $usp['description'] }}" placeholder="Description" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Counters Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Counters Section</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Statistics and numbers</p>
                </div>
                <button type="button" onclick="addCounter()" class="px-4 py-2 rounded text-white text-sm" style="background-color: var(--green);">Add Counter</button>
            </div>

            <div id="countersContainer" class="space-y-3">
                @if($aboutPage->counters && count($aboutPage->counters) > 0)
                    @foreach($aboutPage->counters as $counter)
                        <div class="flex gap-2 counter-item">
                            <input type="text" name="counter_icons[]" value="{{ $counter['icon'] }}" placeholder="Icon" class="w-1/4 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <input type="text" name="counter_numbers[]" value="{{ $counter['number'] }}" placeholder="Number" class="w-1/4 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <input type="text" name="counter_texts[]" value="{{ $counter['text'] }}" placeholder="Text" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <button type="button" onclick="this.closest('.counter-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Why Choose Us Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Why Choose Us Section</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Reasons to choose your company</p>
                </div>
                <button type="button" onclick="addWhyItem()" class="px-4 py-2 rounded text-white text-sm" style="background-color: var(--green);">Add Item</button>
            </div>

            <div id="whyItemsContainer" class="space-y-3 mb-4">
                @if($aboutPage->why_items && count($aboutPage->why_items) > 0)
                    @foreach($aboutPage->why_items as $item)
                        <div class="p-3 border rounded why-item" style="border-color: var(--border);">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="why_titles[]" value="{{ $item['title'] }}" placeholder="Title" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                <button type="button" onclick="this.closest('.why-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
                            </div>
                            <input type="text" name="why_descriptions[]" value="{{ $item['description'] }}" placeholder="Description" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="border-t pt-4" style="border-color: var(--border);">
                <h3 class="font-semibold mb-3" style="color: var(--text);">Promise Box</h3>
                <div class="space-y-3">
                    <input type="text" name="why_promise_title" value="{{ old('why_promise_title', $aboutPage->why_promise_title) }}" placeholder="Promise Title" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    <textarea name="why_promise_description" rows="3" placeholder="Promise Description" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('why_promise_description', $aboutPage->why_promise_description) }}</textarea>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="why_promise_button_text" value="{{ old('why_promise_button_text', $aboutPage->why_promise_button_text) }}" placeholder="Button Text" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <input type="text" name="why_promise_button_link" value="{{ old('why_promise_button_link', $aboutPage->why_promise_button_link) }}" placeholder="Button Link" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Team Section</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Team members</p>
                </div>
                <button type="button" onclick="addTeamMember()" class="px-4 py-2 rounded text-white text-sm" style="background-color: var(--green);">Add Member</button>
            </div>

            <div id="teamMembersContainer" class="space-y-3">
                @if($aboutPage->team_members && count($aboutPage->team_members) > 0)
                    @foreach($aboutPage->team_members as $index => $member)
                        <div class="p-3 border rounded team-item" style="border-color: var(--border);">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="team_names[]" value="{{ $member['name'] }}" placeholder="Name" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                <input type="text" name="team_roles[]" value="{{ $member['role'] }}" placeholder="Role" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                <button type="button" onclick="this.closest('.team-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
                            </div>
                            @if(isset($member['image']) && $member['image'])
                                <div class="mb-2">
                                    <img src="{{ asset($member['image']) }}" class="h-20 w-20 object-cover rounded" alt="Team member">
                                </div>
                            @endif
                            <div class="mb-2">
                                <label class="block text-xs mb-1" style="color: var(--muted);">Upload Image</label>
                                <input type="file" name="team_image_files[]" accept="image/*" class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                            </div>
                            <input type="text" name="team_images[]" value="{{ $member['image'] }}" placeholder="Or enter image path" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">FAQ Section</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Frequently asked questions</p>
                </div>
                <button type="button" onclick="addFAQ()" class="px-4 py-2 rounded text-white text-sm" style="background-color: var(--green);">Add FAQ</button>
            </div>

            <div id="faqsContainer" class="space-y-3">
                @if($aboutPage->faqs && count($aboutPage->faqs) > 0)
                    @foreach($aboutPage->faqs as $faq)
                        <div class="p-3 border rounded faq-item" style="border-color: var(--border);">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="faq_questions[]" value="{{ $faq['question'] }}" placeholder="Question" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                <button type="button" onclick="this.closest('.faq-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
                            </div>
                            <textarea name="faq_answers[]" rows="2" placeholder="Answer" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ $faq['answer'] }}</textarea>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Contact Form Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4 pb-4 border-b" style="border-color: var(--border);">
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">Contact Form Section</h2>
                    <p class="text-sm mt-1" style="color: var(--muted);">Bottom contact form</p>
                </div>
            </div>

            <div class="space-y-3">
                <input type="text" name="contact_form_title" value="{{ old('contact_form_title', $aboutPage->contact_form_title) }}" placeholder="Form Title" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                <textarea name="contact_form_description" rows="2" placeholder="Form Description" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('contact_form_description', $aboutPage->contact_form_description) }}</textarea>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $aboutPage->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded" style="color: var(--green);">
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

<script>
function addHeroBadge() {
    const html = `<div class="flex gap-2 badge-item">
        <input type="text" name="hero_badge_icons[]" placeholder="Icon (e.g., fa-shield-heart)" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
        <input type="text" name="hero_badge_texts[]" placeholder="Badge text" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 text-red-600">Remove</button>
    </div>`;
    document.getElementById('heroBadgesContainer').insertAdjacentHTML('beforeend', html);
}

function addOverviewCheck() {
    const html = `<div class="p-3 border rounded check-item" style="border-color: var(--border);">
        <div class="flex gap-2 mb-2">
            <input type="text" name="overview_check_icons[]" placeholder="Icon" class="w-1/3 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            <input type="text" name="overview_check_titles[]" placeholder="Title" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            <button type="button" onclick="this.closest('.check-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
        </div>
        <input type="text" name="overview_check_descriptions[]" placeholder="Description" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
    </div>`;
    document.getElementById('overviewChecksContainer').insertAdjacentHTML('beforeend', html);
}

function addUSP() {
    const html = `<div class="p-3 border rounded usp-item" style="border-color: var(--border);">
        <div class="flex gap-2 mb-2">
            <input type="text" name="usp_icons[]" placeholder="Icon" class="w-1/4 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            <input type="text" name="usp_titles[]" placeholder="Title" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            <button type="button" onclick="this.closest('.usp-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
        </div>
        <input type="text" name="usp_descriptions[]" placeholder="Description" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
    </div>`;
    document.getElementById('uspsContainer').insertAdjacentHTML('beforeend', html);
}

function addCounter() {
    const html = `<div class="flex gap-2 counter-item">
        <input type="text" name="counter_icons[]" placeholder="Icon" class="w-1/4 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
        <input type="text" name="counter_numbers[]" placeholder="Number" class="w-1/4 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
        <input type="text" name="counter_texts[]" placeholder="Text" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
        <button type="button" onclick="this.closest('.counter-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
    </div>`;
    document.getElementById('countersContainer').insertAdjacentHTML('beforeend', html);
}

function addWhyItem() {
    const html = `<div class="p-3 border rounded why-item" style="border-color: var(--border);">
        <div class="flex gap-2 mb-2">
            <input type="text" name="why_titles[]" placeholder="Title" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            <button type="button" onclick="this.closest('.why-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
        </div>
        <input type="text" name="why_descriptions[]" placeholder="Description" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
    </div>`;
    document.getElementById('whyItemsContainer').insertAdjacentHTML('beforeend', html);
}

function addTeamMember() {
    const html = `<div class="p-3 border rounded team-item" style="border-color: var(--border);">
        <div class="flex gap-2 mb-2">
            <input type="text" name="team_names[]" placeholder="Name" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            <input type="text" name="team_roles[]" placeholder="Role" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            <button type="button" onclick="this.closest('.team-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
        </div>
        <div class="mb-2">
            <label class="block text-xs mb-1" style="color: var(--muted);">Upload Image</label>
            <input type="file" name="team_image_files[]" accept="image/*" class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
        </div>
        <input type="text" name="team_images[]" placeholder="Or enter image path" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
    </div>`;
    document.getElementById('teamMembersContainer').insertAdjacentHTML('beforeend', html);
}

function addFAQ() {
    const html = `<div class="p-3 border rounded faq-item" style="border-color: var(--border);">
        <div class="flex gap-2 mb-2">
            <input type="text" name="faq_questions[]" placeholder="Question" class="flex-1 px-3 py-2 border rounded-lg" style="border-color: var(--border);">
            <button type="button" onclick="this.closest('.faq-item').remove()" class="px-3 py-2 text-red-600">Remove</button>
        </div>
        <textarea name="faq_answers[]" rows="2" placeholder="Answer" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);"></textarea>
    </div>`;
    document.getElementById('faqsContainer').insertAdjacentHTML('beforeend', html);
}
</script>
@endsection
