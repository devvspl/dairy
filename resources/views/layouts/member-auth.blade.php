<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Dairy Management') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --green: #2f4a1e;
            --green-dark: #1f2a1a;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #e5e7eb;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        textarea,
        select {
            font-size: 0.875rem !important;
        }
        
        button, .btn {
            font-size: 0.875rem !important;
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#2f4a1e',
                        'primary-dark': '#1f2a1a',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <!-- Member Badge -->
        <div class="mb-6">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full" style="background-color: rgba(47, 74, 30, 0.1);">
                <i class="fa-solid fa-mobile-screen-button" style="color: var(--green);"></i>
                <span class="text-sm font-bold" style="color: var(--green);">MEMBER PORTAL</span>
            </div>
        </div>

        <div class="w-full sm:max-w-md">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm" style="color: var(--muted);">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            <div class="mt-2 flex items-center justify-center gap-4 text-xs">
                <a href="{{ route('home') }}" class="hover:underline" style="color: var(--green);">Home</a>
                <span style="color: var(--muted);">•</span>
                <a href="{{ route('privacy-policy') }}" class="hover:underline" style="color: var(--green);">Privacy</a>
                <span style="color: var(--muted);">•</span>
                <a href="{{ route('terms-conditions') }}" class="hover:underline" style="color: var(--green);">Terms</a>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
