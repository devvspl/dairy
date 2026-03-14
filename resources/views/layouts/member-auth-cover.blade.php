<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Nulac') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-green: #2f4a1e;
            --primary-green-dark: #1f2a1a;
            --primary-green-light: #3d6b2e;
            --bg-light: #f6f8f6;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .cover-section {
            background: linear-gradient(135deg, rgba(47, 74, 30, 0.95) 0%, rgba(31, 42, 26, 0.9) 100%),
                        url('https://images.unsplash.com/photo-1628088062854-d1870b4553da?w=1200&q=80') center/cover;
            position: relative;
        }

        .cover-overlay {
            background: linear-gradient(135deg, rgba(47, 74, 30, 0.95) 0%, rgba(31, 42, 26, 0.85) 100%);
            position: absolute;
            inset: 0;
        }

        .form-section {
            background-color: var(--bg-light);
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: var(--primary-green) !important;
            box-shadow: 0 0 0 3px rgba(47, 74, 30, 0.1) !important;
            outline: none;
        }

        .btn-primary {
            background-color: var(--primary-green);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-green-light);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(47, 74, 30, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .logo-text {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-green);
            letter-spacing: -0.02em;
        }

        @media (max-width: 768px) {
            .cover-section {
                display: none;
            }
            
            .form-section {
                padding: 1rem !important;
            }
            
            .bg-white.rounded-2xl {
                padding: 1.25rem !important;
            }
        }
        
        @media (max-width: 480px) {
            .form-section {
                padding: 0.75rem !important;
            }
            
            .bg-white.rounded-2xl {
                padding: 1rem !important;
                border-radius: 1rem !important;
            }
            
            h2 {
                font-size: 1.5rem !important;
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#2f4a1e',
                        'primary-dark': '#1f2a1a',
                        'primary-light': '#3d6b2e',
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased">
    <div class="min-h-screen flex">
        <!-- Left Section - Cover Area (Hidden on mobile) -->
        <div class="cover-section hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <div class="cover-overlay"></div>
            
            <div class="relative z-10 flex flex-col justify-between p-12 text-white w-full">
                <!-- Logo -->
                <div class="animate-fade-in">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center">
                            <img src="{{ asset('images/new.png') }}" alt="Nulac" class="w-10 h-10 object-contain">
                        </div>
                        <span class="text-3xl font-bold text-white">Nulac</span>
                    </a>
                </div>

                <!-- Main Content -->
                <div class="space-y-8 animate-fade-in" style="animation-delay: 0.2s;">
                    <div>
                        <h1 class="text-5xl font-bold leading-tight mb-4">
                            Fresh Dairy<br>Delivered Daily
                        </h1>
                        <p class="text-xl text-gray-200 leading-relaxed">
                            Pure Milk. Farm Fresh. Trusted Quality.
                        </p>
                    </div>

                    <!-- Features -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="feature-icon">
                                <i class="fas fa-check text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">100% Pure & Fresh</h3>
                                <p class="text-gray-300 text-sm">Directly from farm to your doorstep</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="feature-icon">
                                <i class="fas fa-truck text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Daily Delivery</h3>
                                <p class="text-gray-300 text-sm">Fresh milk delivered every morning</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Quality Assured</h3>
                                <p class="text-gray-300 text-sm">Tested and certified for purity</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-sm text-gray-300 animate-fade-in" style="animation-delay: 0.4s;">
                    <p>© {{ date('Y') }} Nulac. All rights reserved.</p>
                </div>
            </div>
        </div>

        <!-- Right Section - Form Area -->
        <div class="form-section w-full lg:w-1/2 flex items-center justify-center p-3 sm:p-4 lg:p-8 overflow-y-auto">
            <div class="w-full max-w-md my-auto py-4 sm:py-6">
                <!-- Mobile Logo (Visible only on mobile) -->
                <div class="lg:hidden text-center mb-4 sm:mb-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 sm:gap-3 justify-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-lg sm:rounded-xl flex items-center justify-center shadow-md">
                            <img src="{{ asset('images/new.png') }}" alt="Nulac" class="w-6 h-6 sm:w-8 sm:h-8 object-contain">
                        </div>
                        <span class="text-xl sm:text-2xl font-bold" style="color: var(--primary-green);">Nulac</span>
                    </a>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 animate-fade-in">
                    @yield('content')
                </div>

                <!-- Footer Links -->
                <div class="mt-3 sm:mt-4 mb-3 sm:mb-4 text-center text-xs text-gray-600">
                    <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
                    <span class="mx-1 sm:mx-2">•</span>
                    <a href="{{ route('privacy-policy') }}" class="hover:text-primary">Privacy</a>
                    <span class="mx-1 sm:mx-2">•</span>
                    <a href="{{ route('terms-conditions') }}" class="hover:text-primary">Terms</a>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
