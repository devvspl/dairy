@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', auth()->user()->isAdmin() ? 'Admin Dashboard' : 'Member Dashboard')

@section('content')
    <div class="space-y-4 lg:space-y-6">
        <!-- Welcome Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                <div>
                    <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">
                        Welcome, {{ auth()->user()->name }}! 👋
                    </h1>
                    <p class="text-sm lg:text-base mt-1" style="color: var(--muted);">
                        {{ now()->format('l, F j, Y') }}
                    </p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                            style="background-color: {{ auth()->user()->isAdmin() ? '#2F4A1E' : '#f1cc24' }}; 
                                   color: {{ auth()->user()->isAdmin() ? '#fff' : '#1f2a1a' }};">
                            @if(auth()->user()->isAdmin())
                                <i class="fa-solid fa-user-shield mr-1"></i> Admin
                            @else
                                <i class="fa-solid fa-user mr-1"></i> Member
                            @endif
                        </span>
                    </div>
                </div>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition-colors"
                       style="background-color: var(--green); color: #fff;">
                        <i class="fa-solid fa-gauge mr-2"></i>
                        Go to Admin Panel
                    </a>
                @endif
            </div>
        </div>

        @if(auth()->user()->isMember())
        <!-- Member Quick Links -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('products') }}" class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                        <i class="fa-solid fa-box-open text-xl" style="color: var(--green);"></i>
                    </div>
                    <div>
                        <h3 class="font-bold" style="color: var(--text);">Products</h3>
                        <p class="text-sm" style="color: var(--muted);">Browse catalog</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('membership') }}" class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(241, 204, 36, 0.1);">
                        <i class="fa-solid fa-id-card-clip text-xl" style="color: #f1cc24;"></i>
                    </div>
                    <div>
                        <h3 class="font-bold" style="color: var(--text);">Membership</h3>
                        <p class="text-sm" style="color: var(--muted);">View plans</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('blogs') }}" class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                        <i class="fa-solid fa-pen-to-square text-xl" style="color: var(--green);"></i>
                    </div>
                    <div>
                        <h3 class="font-bold" style="color: var(--text);">Blogs</h3>
                        <p class="text-sm" style="color: var(--muted);">Read articles</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('contact') }}" class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(241, 204, 36, 0.1);">
                        <i class="fa-solid fa-envelope text-xl" style="color: #f1cc24;"></i>
                    </div>
                    <div>
                        <h3 class="font-bold" style="color: var(--text);">Contact</h3>
                        <p class="text-sm" style="color: var(--muted);">Get in touch</p>
                    </div>
                </div>
            </a>
        </div>
        @endif
    </div>
@endsection
