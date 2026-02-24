@extends('layouts.app')

@section('title', 'Profile Settings')
@section('page-title', 'Profile Settings')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Profile Information -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex items-center space-x-4 mb-6">
            <div class="w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-bold overflow-hidden" style="background-color: var(--green);">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset(auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <h2 class="text-xl font-bold" style="color: var(--text);">{{ auth()->user()->name }}</h2>
                <p class="text-sm" style="color: var(--muted);">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" x-data="{ loading: false, imagePreview: '{{ auth()->user()->profile_image ? asset(auth()->user()->profile_image) : '' }}' }" @submit="loading = true">
            @csrf
            @method('PUT')

            <!-- Profile Image Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Profile Image</label>
                <div class="flex items-center space-x-4">
                    <div class="w-24 h-24 rounded-full flex items-center justify-center text-white text-3xl font-bold overflow-hidden border-2" style="background-color: var(--green); border-color: var(--border);">
                        <template x-if="imagePreview">
                            <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!imagePreview">
                            <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </template>
                    </div>
                    <div class="flex-1">
                        <input 
                            type="file" 
                            id="profile_image" 
                            name="profile_image"
                            accept="image/jpeg,image/jpg,image/png,image/gif"
                            class="hidden"
                            @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => imagePreview = e.target.result;
                                    reader.readAsDataURL(file);
                                }
                            "
                        >
                        <label 
                            for="profile_image" 
                            class="inline-block px-4 py-2 text-sm font-medium text-white rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                            style="background-color: var(--green);"
                        >
                            Choose Image
                        </label>
                        <p class="text-xs mt-2" style="color: var(--muted);">JPG, PNG or GIF. Max size 2MB.</p>
                        @error('profile_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium mb-2" style="color: var(--text);">Full Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', auth()->user()->name) }}"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all @error('name') border-red-500 @enderror"
                        style="border-color: var(--border); color: var(--text);"
                        onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium mb-2" style="color: var(--text);">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', auth()->user()->email) }}"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all @error('email') border-red-500 @enderror"
                        style="border-color: var(--border); color: var(--text);"
                        onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                        required
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="phone" class="block text-sm font-medium mb-2" style="color: var(--text);">Phone Number</label>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone', auth()->user()->phone) }}"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all @error('phone') border-red-500 @enderror"
                        style="border-color: var(--border); color: var(--text);"
                        onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button 
                    type="submit"
                    :disabled="loading"
                    class="px-6 py-3 text-white rounded-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    style="background-color: var(--green);"
                >
                    <span x-show="!loading">Save Changes</span>
                    <span x-show="loading" class="flex items-center">
                        <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <h3 class="text-lg font-semibold mb-4" style="color: var(--text);">Change Password</h3>
        
        <form method="POST" action="{{ route('profile.password.update') }}" x-data="{ loading: false, showCurrent: false, showNew: false, showConfirm: false }" @submit="loading = true">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium mb-2" style="color: var(--text);">Current Password</label>
                    <div class="relative">
                        <input 
                            :type="showCurrent ? 'text' : 'password'" 
                            id="current_password" 
                            name="current_password"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all @error('current_password') border-red-500 @enderror"
                            style="border-color: var(--border); color: var(--text);"
                            onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                            onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                            required
                        >
                        <button 
                            type="button" 
                            @click="showCurrent = !showCurrent"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 hover:opacity-70 transition-opacity"
                            style="color: var(--muted);"
                        >
                            <svg x-show="!showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2" style="color: var(--text);">New Password</label>
                        <div class="relative">
                            <input 
                                :type="showNew ? 'text' : 'password'" 
                                id="password" 
                                name="password"
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all @error('password') border-red-500 @enderror"
                                style="border-color: var(--border); color: var(--text);"
                                onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                                onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                                required
                            >
                            <button 
                                type="button" 
                                @click="showNew = !showNew"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 hover:opacity-70 transition-opacity"
                                style="color: var(--muted);"
                            >
                                <svg x-show="!showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: var(--text);">Confirm New Password</label>
                        <div class="relative">
                            <input 
                                :type="showConfirm ? 'text' : 'password'" 
                                id="password_confirmation" 
                                name="password_confirmation"
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all"
                                style="border-color: var(--border); color: var(--text);"
                                onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                                onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                                required
                            >
                            <button 
                                type="button" 
                                @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 hover:opacity-70 transition-opacity"
                                style="color: var(--muted);"
                            >
                                <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button 
                    type="submit"
                    :disabled="loading"
                    class="px-6 py-3 text-white rounded-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    style="background-color: var(--green);"
                >
                    <span x-show="!loading">Update Password</span>
                    <span x-show="loading" class="flex items-center">
                        <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Updating...
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- Account Information -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <h3 class="text-lg font-semibold mb-4" style="color: var(--text);">Account Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 rounded-lg" style="background-color: rgba(47, 74, 30, 0.05);">
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Member Since</p>
                <p class="text-base font-semibold" style="color: var(--text);">{{ auth()->user()->created_at->format('F j, Y') }}</p>
            </div>

            <div class="p-4 rounded-lg" style="background-color: rgba(47, 74, 30, 0.05);">
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Last Updated</p>
                <p class="text-base font-semibold" style="color: var(--text);">{{ auth()->user()->updated_at->format('F j, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
