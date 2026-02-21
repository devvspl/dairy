@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold" style="color: var(--text);">Edit User</h2>
                <p class="text-sm mt-1" style="color: var(--muted);">Update user information</p>
            </div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}" x-data="{ loading: false, showPassword: false, showPasswordConfirmation: false }" @submit="loading = true">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium mb-2" style="color: var(--text);">Full Name <span class="text-red-600">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all @error('name') border-red-500 @enderror"
                        style="border-color: var(--border); color: var(--text);"
                        onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                        required
                        autofocus
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium mb-2" style="color: var(--text);">Email Address <span class="text-red-600">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}"
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

                <div>
                    <label for="phone" class="block text-sm font-medium mb-2" style="color: var(--text);">Phone Number</label>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all @error('phone') border-red-500 @enderror"
                        style="border-color: var(--border); color: var(--text);"
                        onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t" style="border-color: var(--border);">
                    <p class="text-sm font-medium mb-4" style="color: var(--text);">Change Password (leave blank to keep current password)</p>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-medium mb-2" style="color: var(--text);">New Password</label>
                            <div class="relative">
                                <input 
                                    :type="showPassword ? 'text' : 'password'" 
                                    id="password" 
                                    name="password"
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all @error('password') border-red-500 @enderror"
                                    style="border-color: var(--border); color: var(--text);"
                                    onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                                >
                                <button 
                                    type="button" 
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 hover:opacity-70 transition-opacity"
                                    style="color: var(--muted);"
                                >
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
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
                                    :type="showPasswordConfirmation ? 'text' : 'password'" 
                                    id="password_confirmation" 
                                    name="password_confirmation"
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all"
                                    style="border-color: var(--border); color: var(--text);"
                                    onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                                >
                                <button 
                                    type="button" 
                                    @click="showPasswordConfirmation = !showPasswordConfirmation"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 hover:opacity-70 transition-opacity"
                                    style="color: var(--muted);"
                                >
                                    <svg x-show="!showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-6">
                <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
                    Cancel
                </a>
                <button 
                    type="submit"
                    :disabled="loading"
                    class="inline-flex items-center justify-center px-6 py-3 text-white rounded-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed"
                    style="background-color: var(--green);"
                >
                    <span x-show="!loading">Update User</span>
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
@endsection
