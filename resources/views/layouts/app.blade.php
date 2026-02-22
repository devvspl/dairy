<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --green: #2f4a1e;
            --green-dark: #263d18;
            --border: #e7e7e7;
            --text: #1f2a1a;
            --muted: #6a7a63;
        }

        .sidebar-link.active {
            background-color: #f0f4ed;
            color: #2f4a1e;
            border-left: 3px solid #2f4a1e;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="search"],
        textarea,
        select {
            font-size: 0.875rem !important;
            padding: 0.5rem 0.75rem !important;
        }

        button,
        .btn {
            font-size: 0.875rem !important;
            padding: 0.5rem 1rem !important;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#2f4a1e',
                        'primary-dark': '#263d18',
                        'border-color': '#e7e7e7',
                        'text-main': '#1f2a1a',
                        'text-muted': '#6a7a63',
                    },
                    fontSize: {
                        'xs': '0.75rem',
                        'sm': '0.875rem',
                        'base': '0.875rem',
                        'lg': '1rem',
                        'xl': '1.125rem',
                        '2xl': '1.25rem',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 bg-white border-r transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
            :class="[
                sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64',
                'lg:' + (sidebarCollapsed ? 'w-20' : 'w-64')
            ]"
            style="border-color: var(--border);">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 border-b px-4"
                    style="background-color: #ffffff; border-color: var(--border);">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center" x-show="!sidebarCollapsed">
                        <img src="{{ asset('images/new.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center" x-show="sidebarCollapsed"
                        style="display: none;">
                        <img src="{{ asset('images/new.png') }}" alt="{{ config('app.name') }}" class="h-8 w-auto">
                    </a>
                    <button @click="sidebarOpen = false" class="absolute right-4 lg:hidden"
                        style="color: var(--green);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 py-6 space-y-1 overflow-y-auto" :class="sidebarCollapsed ? 'px-2' : 'px-4'">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center rounded-lg transition-all text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('dashboard') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Dashboard' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Dashboard</span>
                    </a>

                    <!-- Users -->
                    <a href="{{ route('admin.users.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('users.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Users' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Users</span>
                    </a>

                    <!-- Products & Catalog Section -->
                    <div x-show="!sidebarCollapsed" class="px-3 pt-4 pb-2">
                        <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Products
                            & Catalog</p>
                    </div>

                    <a href="{{ route('admin.types.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.types.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.types.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Types' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Types</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.products.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Products' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Products</span>
                    </a>

                    <!-- Home Page Content Section -->
                    <div x-show="!sidebarCollapsed" class="px-3 pt-4 pb-2">
                        <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Home
                            Page Content</p>
                    </div>

                    <a href="{{ route('admin.announcements.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.announcements.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Announcements' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Announcements</span>
                    </a>

                    <a href="{{ route('admin.sliders.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.sliders.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Sliders' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Sliders</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.categories.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Categories' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Categories</span>
                    </a>

                    <a href="{{ route('admin.why-choose-us.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.why-choose-us.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.why-choose-us.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Why Choose Us' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Why Choose Us</span>
                    </a>

                    <a href="{{ route('admin.usps.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.usps.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.usps.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'USPs' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">USPs</span>
                    </a>

                    <a href="{{ route('admin.content-sections.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.content-sections.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.content-sections.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Content Sections' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Content Sections</span>
                    </a>

                    <a href="{{ route('admin.about-sections.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.about-sections.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.about-sections.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'About Sections' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">About Sections</span>
                    </a>

                    <a href="{{ route('admin.testimonials.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.testimonials.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Testimonials' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Testimonials</span>
                    </a>

                    <a href="{{ route('admin.blogs.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.blogs.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Blogs' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Blogs</span>
                    </a>

                    <!-- Pages Section -->
                    <div x-show="!sidebarCollapsed" class="px-3 pt-4 pb-2">
                        <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Pages
                        </p>
                    </div>

                    <a href="{{ route('admin.contact-page.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.contact-page.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.contact-page.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Contact Page' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Contact Page</span>
                    </a>

                    <a href="{{ route('admin.contact-inquiries.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.contact-inquiries.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.contact-inquiries.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Contact Inquiries' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Contact Inquiries</span>
                    </a>

                    <a href="{{ route('admin.about-page.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.about-page.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                        style="{{ request()->routeIs('admin.about-page.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'About Page' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">About Page</span>
                    </a>

                    <!-- Membership Subsection -->
                    <div x-show="!sidebarCollapsed" class="px-3 pt-3 pb-1">
                        <p class="text-xs font-medium" style="color: var(--muted);">Membership</p>
                    </div>

                    <a href="{{ route('admin.membership-plans.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.membership-plans.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2 pl-6'"
                        style="{{ request()->routeIs('admin.membership-plans.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Plans' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Plans</span>
                    </a>

                    <a href="{{ route('admin.membership-benefits.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.membership-benefits.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2 pl-6'"
                        style="{{ request()->routeIs('admin.membership-benefits.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Benefits' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Benefits</span>
                    </a>

                    <a href="{{ route('admin.membership-steps.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.membership-steps.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2 pl-6'"
                        style="{{ request()->routeIs('admin.membership-steps.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Steps' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Steps</span>
                    </a>

                    <a href="{{ route('admin.membership-faqs.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.membership-faqs.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2 pl-6'"
                        style="{{ request()->routeIs('admin.membership-faqs.*') ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'FAQs' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">FAQs</span>
                    </a>

                    <!-- Legal Pages Subsection -->
                    <div x-show="!sidebarCollapsed" class="px-3 pt-3 pb-1">
                        <p class="text-xs font-medium" style="color: var(--muted);">Legal</p>
                    </div>

                    <a href="{{ route('admin.legal-pages.index', 'privacy-policy') }}"
                        class="sidebar-link {{ request()->routeIs('admin.legal-pages.*') && request()->route('pageKey') === 'privacy-policy' ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2 pl-6'"
                        style="{{ request()->routeIs('admin.legal-pages.*') && request()->route('pageKey') === 'privacy-policy' ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Privacy Policy' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Privacy Policy</span>
                    </a>

                    <a href="{{ route('admin.legal-pages.index', 'terms-conditions') }}"
                        class="sidebar-link {{ request()->routeIs('admin.legal-pages.*') && request()->route('pageKey') === 'terms-conditions' ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2 pl-6'"
                        style="{{ request()->routeIs('admin.legal-pages.*') && request()->route('pageKey') === 'terms-conditions' ? '' : 'color: var(--muted);' }}"
                        :title="sidebarCollapsed ? 'Terms & Conditions' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Terms & Conditions</span>
                    </a>
                </nav>

                <!-- Collapse Toggle Button (Desktop Only) -->
                <div class="border-t hidden lg:block" style="border-color: var(--border);"
                    :class="sidebarCollapsed ? 'p-2' : 'p-4'">
                    <button @click="sidebarCollapsed = !sidebarCollapsed"
                        class="w-full flex items-center rounded-lg hover:bg-gray-100 transition-colors"
                        :class="sidebarCollapsed ? 'justify-center p-3' : 'justify-center px-3 py-2'"
                        style="color: var(--muted);"
                        :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
                        <svg class="w-5 h-5 transition-transform duration-300 flex-shrink-0"
                            :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                        </svg>
                        <span class="ml-2 text-sm font-medium" x-show="!sidebarCollapsed">Collapse</span>
                    </button>
                </div>

            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" style="display: none;"></div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navbar -->
            <header class="flex items-center justify-between h-16 px-4 lg:px-6 bg-white border-b"
                style="border-color: var(--border);">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = true" class="lg:hidden hover:bg-gray-100 p-2 rounded-lg"
                        style="color: var(--text);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h2 class="text-lg font-semibold hidden sm:block" style="color: var(--text);">@yield('page-title', 'Dashboard')
                    </h2>
                </div>

                <div class="flex items-center space-x-3 lg:space-x-4">
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center space-x-2 lg:space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm"
                                style="background-color: var(--green);">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium"
                                style="color: var(--text);">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 hidden md:block" style="color: var(--muted);" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border"
                            style="border-color: var(--border); display: none;">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-50 transition-colors"
                                style="color: var(--text);">
                                Profile Settings
                            </a>
                            <a href="{{ route('account') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-50 transition-colors"
                                style="color: var(--text);">
                                Account
                            </a>
                            <hr class="my-2" style="border-color: var(--border);">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors"
                                    style="color: var(--text);">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
