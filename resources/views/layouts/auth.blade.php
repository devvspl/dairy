<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Authentication') - {{ config('app.name') }}</title>
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
<body class="bg-gradient-to-br from-green-50 to-emerald-50 min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, #f0f4ed 0%, #e8f0e3 100%);">
    <div class="w-full max-w-md">
        @yield('content')
    </div>
</body>
</html>
