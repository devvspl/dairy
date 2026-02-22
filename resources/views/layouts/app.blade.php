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
        button, .btn {
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
        <aside class="fixed inset-y-0 left-0 z-50 bg-white border-r transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
               :class="[
                   sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64',
                   'lg:' + (sidebarCollapsed ? 'w-20' : 'w-64')
               ]"
               style="border-color: var(--border);">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 border-b" style="background-color: var(--green); border-color: var(--border);">
                    <h1 class="text-lg font-bold text-white" x-show="!sidebarCollapsed">{{ config('app.name') }}</h1>
                    <h1 class="text-lg font-bold text-white" x-show="sidebarCollapsed" style="display: none;">L</h1>
                    <button @click="sidebarOpen = false" class="absolute right-4 lg:hidden text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 py-6 space-y-1 overflow-y-auto" :class="sidebarCollapsed ? 'px-2' : 'px-4'">
                    <a href="{{ route('dashboard') }}" 
                       class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center rounded-lg transition-all text-sm"
                       :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                       style="{{ request()->routeIs('dashboard') ? '' : 'color: var(--muted);' }}" 
                       :title="sidebarCollapsed ? 'Dashboard' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                       :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                       style="{{ request()->routeIs('users.*') ? '' : 'color: var(--muted);' }}" 
                       :title="sidebarCollapsed ? 'Users' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Users</span>
                    </a>

                    <!-- Home Page Management -->
                    <div x-show="!sidebarCollapsed" class="px-3 pt-4 pb-2">
                        <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Home Page</p>
                    </div>

                    <a href="{{ route('admin.sliders.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                       :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                       style="{{ request()->routeIs('admin.sliders.*') ? '' : 'color: var(--muted);' }}" 
                       :title="sidebarCollapsed ? 'Sliders' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Sliders</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                       :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                       style="{{ request()->routeIs('admin.categories.*') ? '' : 'color: var(--muted);' }}" 
                       :title="sidebarCollapsed ? 'Categories' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Categories</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                       :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                       style="{{ request()->routeIs('admin.products.*') ? '' : 'color: var(--muted);' }}" 
                       :title="sidebarCollapsed ? 'Products' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Products</span>
                    </a>

                    <a href="{{ route('admin.why-choose-us.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.why-choose-us.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                       :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                       style="{{ request()->routeIs('admin.why-choose-us.*') ? '' : 'color: var(--muted);' }}" 
                       :title="sidebarCollapsed ? 'Why Choose Us' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Why Choose Us</span>
                    </a>

                    <a href="{{ route('admin.usps.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.usps.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                       :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                       style="{{ request()->routeIs('admin.usps.*') ? '' : 'color: var(--muted);' }}" 
                       :title="sidebarCollapsed ? 'USPs' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">USPs</span>
                    </a>

                    <a href="{{ route('admin.content-sections.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.content-sections.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                       :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                       style="{{ request()->routeIs('admin.content-sections.*') ? '' : 'color: var(--muted);' }}" 
                       :title="sidebarCollapsed ? 'Content Sections' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Content Sections</span>
                    </a>

                    <a href="{{ route('admin.testimonials.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                       :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                       style="{{ request()->routeIs('admin.testimonials.*') ? '' : 'color: var(--muted);' }}" 
                       :title="sidebarCollapsed ? 'Testimonials' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Testimonials</span>
                    </a>

                    <a href="{{ route('admin.blogs.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }} flex items-center rounded-lg transition-all hover:bg-gray-50 text-sm"
                       :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3 py-2'"
                       style="{{ request()->routeIs('admin.blogs.*') ? '' : 'color: var(--muted);' }}" 
                       :title="sidebarCollapsed ? 'Blogs' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        <span class="ml-3 font-medium" x-show="!sidebarCollapsed">Blogs</span>
                    </a>
                </nav>

                <!-- Collapse Toggle Button (Desktop Only) -->
                <div class="border-t hidden lg:block" style="border-color: var(--border);" :class="sidebarCollapsed ? 'p-2' : 'p-4'">
                    <button @click="sidebarCollapsed = !sidebarCollapsed" 
                            class="w-full flex items-center rounded-lg hover:bg-gray-100 transition-colors"
                            :class="sidebarCollapsed ? 'justify-center p-3' : 'justify-center px-3 py-2'"
                            style="color: var(--muted);" 
                            :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
                        <svg class="w-5 h-5 transition-transform duration-300 flex-shrink-0" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                        </svg>
                        <span class="ml-2 text-sm font-medium" x-show="!sidebarCollapsed">Collapse</span>
                    </button>
                </div>

                <!-- User Section -->
                <div class="border-t" style="border-color: var(--border);" :class="sidebarCollapsed ? 'p-2' : 'p-4'">
                    <div x-show="!sidebarCollapsed" class="flex items-center space-x-3 px-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0" style="background-color: var(--green);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color: var(--text);">{{ auth()->user()->name }}</p>
                            <p class="text-xs truncate" style="color: var(--muted);">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    
                    <div x-show="sidebarCollapsed" class="flex justify-center" style="display: none;">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold" style="background-color: var(--green);" :title="'{{ auth()->user()->name }}'">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
             style="display: none;"></div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navbar -->
            <header class="flex items-center justify-between h-16 px-4 lg:px-6 bg-white border-b" style="border-color: var(--border);">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = true" class="lg:hidden hover:bg-gray-100 p-2 rounded-lg" style="color: var(--text);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h2 class="text-lg font-semibold hidden sm:block" style="color: var(--text);">@yield('page-title', 'Dashboard')</h2>
                </div>
                
                <div class="flex items-center space-x-3 lg:space-x-4">
                    <!-- Notifications -->
                    <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors" style="color: var(--muted);">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 rounded-full" style="background-color: var(--green);"></span>
                    </button>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 lg:space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm" style="background-color: var(--green);">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium" style="color: var(--text);">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 hidden md:block" style="color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border"
                             style="border-color: var(--border); display: none;">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 transition-colors" style="color: var(--text);">
                                Profile Settings
                            </a>
                            <a href="{{ route('account') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 transition-colors" style="color: var(--text);">
                                Account
                            </a>
                            <hr class="my-2" style="border-color: var(--border);">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors" style="color: var(--text);">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                @if(session('success'))
                    <div class="mb-4 lg:mb-6 p-4 rounded-lg border" style="background-color: #f0f9f4; border-color: var(--green); color: var(--green-dark);">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
