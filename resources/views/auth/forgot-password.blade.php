@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 border" style="border-color: var(--border);">
    <div class="text-center mb-6 sm:mb-8">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: rgba(47, 74, 30, 0.1);">
            <svg class="w-8 h-8" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold" style="color: var(--text);">Forgot Password?</h2>
        <p class="mt-2 text-sm sm:text-base" style="color: var(--muted);">Enter your email to receive a reset link</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 sm:p-4 rounded-lg border text-sm" style="background-color: #f0f9f4; border-color: var(--green); color: var(--green-dark);">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium mb-2" style="color: var(--text);">Email Address</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email') }}"
                class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all @error('email') border-red-500 @enderror"
                style="border-color: var(--border); color: var(--text);"
                onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                required 
                autofocus
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button 
            type="submit" 
            :disabled="loading"
            class="w-full text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
            style="background-color: var(--green);"
        >
            <span x-show="!loading">Send Reset Link</span>
            <span x-show="loading" class="flex items-center">
                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Sending...
            </span>
        </button>
    </form>

    <p class="text-center text-sm mt-6" style="color: var(--muted);">
        Remember your password? 
        <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color: var(--green);">Sign in</a>
    </p>
</div>
@endsection
