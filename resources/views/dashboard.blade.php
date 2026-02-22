@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">Welcome, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-sm lg:text-base mt-1" style="color: var(--muted);">
                    {{ now()->format('l, F j, Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
        <a href="{{ route('admin.dashboard') }}" class="bg-white rounded-xl shadow-sm p-6 border hover:shadow-md transition-all" style="border-color: var(--border);">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Admin</p>
                    <p class="text-lg font-bold" style="color: var(--text);">Dashboard</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="bg-white rounded-xl shadow-sm p-6 border hover:shadow-md transition-all" style="border-color: var(--border);">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Manage</p>
                    <p class="text-lg font-bold" style="color: var(--text);">Users</p>
                </div>
            </div>
        </a>

        <a href="{{ route('profile.edit') }}" class="bg-white rounded-xl shadow-sm p-6 border hover:shadow-md transition-all" style="border-color: var(--border);">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Your</p>
                    <p class="text-lg font-bold" style="color: var(--text);">Profile</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Content Management -->
    <div class="bg-white rounded-xl shadow-sm p-6 border" style="border-color: var(--border);">
        <h2 class="text-lg font-semibold mb-4" style="color: var(--text);">Content Management</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            <a href="{{ route('admin.sliders.index') }}" class="p-4 rounded-lg border hover:shadow-md transition-all text-center" style="border-color: var(--border);">
                <p class="text-sm font-medium" style="color: var(--text);">Sliders</p>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="p-4 rounded-lg border hover:shadow-md transition-all text-center" style="border-color: var(--border);">
                <p class="text-sm font-medium" style="color: var(--text);">Categories</p>
            </a>
            <a href="{{ route('admin.products.index') }}" class="p-4 rounded-lg border hover:shadow-md transition-all text-center" style="border-color: var(--border);">
                <p class="text-sm font-medium" style="color: var(--text);">Products</p>
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="p-4 rounded-lg border hover:shadow-md transition-all text-center" style="border-color: var(--border);">
                <p class="text-sm font-medium" style="color: var(--text);">Testimonials</p>
            </a>
            <a href="{{ route('admin.blogs.index') }}" class="p-4 rounded-lg border hover:shadow-md transition-all text-center" style="border-color: var(--border);">
                <p class="text-sm font-medium" style="color: var(--text);">Blogs</p>
            </a>
            <a href="{{ route('admin.why-choose-us.index') }}" class="p-4 rounded-lg border hover:shadow-md transition-all text-center" style="border-color: var(--border);">
                <p class="text-sm font-medium" style="color: var(--text);">Why Choose Us</p>
            </a>
            <a href="{{ route('admin.usps.index') }}" class="p-4 rounded-lg border hover:shadow-md transition-all text-center" style="border-color: var(--border);">
                <p class="text-sm font-medium" style="color: var(--text);">USPs</p>
            </a>
            <a href="{{ route('admin.content-sections.index') }}" class="p-4 rounded-lg border hover:shadow-md transition-all text-center" style="border-color: var(--border);">
                <p class="text-sm font-medium" style="color: var(--text);">Content Sections</p>
            </a>
        </div>
    </div>
</div>
@endsection
