@extends('layouts.app')

@section('title', 'Payment Failed')
@section('page-title', 'Payment Failed')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
        <!-- Failure Icon -->
        <div class="w-20 h-20 rounded-full mx-auto mb-6 flex items-center justify-center" style="background-color: rgba(239, 68, 68, 0.1);">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>

        <!-- Failure Message -->
        <h1 class="text-3xl font-bold mb-3" style="color: var(--text);">Payment Failed</h1>
        <p class="text-lg mb-6" style="color: var(--muted);">
            @if(session('error'))
                {{ session('error') }}
            @else
                Unfortunately, your payment could not be processed.
            @endif
        </p>

        <!-- Reasons -->
        <div class="bg-red-50 rounded-xl p-6 mb-6 text-left border-2 border-red-200">
            <h3 class="font-bold text-lg mb-3 flex items-center text-red-700">
                <i class="fa-solid fa-info-circle mr-2"></i>Common Reasons
            </h3>
            <ul class="space-y-2 text-sm">
                <li class="flex items-start">
                    <i class="fa-solid fa-circle text-xs mt-1 mr-2 text-red-500"></i>
                    <span style="color: var(--text);">Insufficient balance in your account</span>
                </li>
                <li class="flex items-start">
                    <i class="fa-solid fa-circle text-xs mt-1 mr-2 text-red-500"></i>
                    <span style="color: var(--text);">Payment was cancelled by you</span>
                </li>
                <li class="flex items-start">
                    <i class="fa-solid fa-circle text-xs mt-1 mr-2 text-red-500"></i>
                    <span style="color: var(--text);">Network or connectivity issues</span>
                </li>
                <li class="flex items-start">
                    <i class="fa-solid fa-circle text-xs mt-1 mr-2 text-red-500"></i>
                    <span style="color: var(--text);">Bank declined the transaction</span>
                </li>
            </ul>
        </div>

        <!-- What to Do -->
        <div class="bg-blue-50 rounded-xl p-6 mb-6 text-left border-2 border-blue-200">
            <h3 class="font-bold text-lg mb-3 flex items-center text-blue-700">
                <i class="fa-solid fa-lightbulb mr-2"></i>What You Can Do
            </h3>
            <ul class="space-y-2 text-sm">
                <li class="flex items-start">
                    <i class="fa-solid fa-check-circle mt-0.5 mr-2 text-blue-600"></i>
                    <span style="color: var(--text);">Check your account balance and try again</span>
                </li>
                <li class="flex items-start">
                    <i class="fa-solid fa-check-circle mt-0.5 mr-2 text-blue-600"></i>
                    <span style="color: var(--text);">Try using a different payment method</span>
                </li>
                <li class="flex items-start">
                    <i class="fa-solid fa-check-circle mt-0.5 mr-2 text-blue-600"></i>
                    <span style="color: var(--text);">Contact your bank if the issue persists</span>
                </li>
                <li class="flex items-start">
                    <i class="fa-solid fa-check-circle mt-0.5 mr-2 text-blue-600"></i>
                    <span style="color: var(--text);">Reach out to our support team for assistance</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('member.dashboard') }}" 
               class="flex-1 py-3 px-6 rounded-lg font-bold transition-all hover:shadow-lg text-white"
               style="background-color: var(--green);">
                <i class="fa-solid fa-rotate-left mr-2"></i>Try Again
            </a>
            <a href="{{ route('contact') }}" 
               class="flex-1 py-3 px-6 rounded-lg font-bold border transition-colors hover:bg-gray-50"
               style="border-color: var(--border); color: var(--text);">
                <i class="fa-solid fa-headset mr-2"></i>Contact Us
            </a>
        </div>
    </div>
</div>
@endsection
