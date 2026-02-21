@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 border" style="border-color: var(--border);">
    <div class="text-center mb-6 sm:mb-8">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: rgba(47, 74, 30, 0.1);">
            <svg class="w-8 h-8" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold" style="color: var(--text);">Reset Password</h2>
        <p class="mt-2 text-sm sm:text-base" style="color: var(--muted);">Enter your new password</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" x-data="{ loading: false, showPassword: false, showPasswordConfirmation: false }" @submit="loading = true">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium mb-2" style="color: var(--text);">Email Address</label>
            <input 
                type="email" 
                id="email" 
                value="{{ $email }}"
                class="w-full px-4 py-3 border rounded-lg bg-gray-50"
                style="border-color: var(--border); color: var(--muted);"
                disabled
            >
        </div>

        <div class="mb-4">
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
                    required
                    autofocus
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

        <div class="mb-6">
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
                    required
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

        <button 
            type="submit" 
            :disabled="loading"
            class="w-full text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
            style="background-color: var(--green);"
        >
            <span x-show="!loading">Reset Password</span>
            <span x-show="loading" class="flex items-center">
                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Resetting...
            </span>
        </button>
    </form>
</div>
@endsection
