@extends('layouts.public')

@section('title', 'Page Not Found')
@section('meta_description', 'The page you are looking for could not be found.')

@section('content')
<div style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; background: linear-gradient(135deg, #f9fdf7 0%, #ffffff 100%);">
    <div style="max-width: 600px; text-align: center;">
        
        <!-- 404 Illustration -->
        <div style="margin-bottom: 30px;">
            <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto;">
                <!-- Milk Bottle -->
                <path d="M70 60 L70 40 C70 35 75 30 80 30 L120 30 C125 30 130 35 130 40 L130 60 L125 170 C125 175 120 180 115 180 L85 180 C80 180 75 175 75 170 Z" fill="#2f4a1e" opacity="0.1"/>
                <path d="M75 65 L75 170 C75 173 78 176 81 176 L119 176 C122 176 125 173 125 170 L125 65 Z" fill="#ffffff" stroke="#2f4a1e" stroke-width="2"/>
                <ellipse cx="100" cy="65" rx="25" ry="8" fill="#2f4a1e" opacity="0.2"/>
                <path d="M85 40 L85 60 L115 60 L115 40 C115 37 112 35 110 35 L90 35 C88 35 85 37 85 40 Z" fill="#2f4a1e" opacity="0.3"/>
                
                <!-- Sad Face on Bottle -->
                <circle cx="90" cy="100" r="4" fill="#2f4a1e"/>
                <circle cx="110" cy="100" r="4" fill="#2f4a1e"/>
                <path d="M85 120 Q100 115 115 120" stroke="#2f4a1e" stroke-width="2" fill="none" stroke-linecap="round"/>
                
                <!-- Question Mark -->
                <text x="100" y="50" font-size="24" font-weight="bold" fill="#2f4a1e" text-anchor="middle">?</text>
            </svg>
        </div>

        <!-- Error Code -->
        <h1 style="font-size: 72px; font-weight: 900; color: #2f4a1e; margin: 0 0 10px 0; line-height: 1;">
            404
        </h1>

        <!-- Error Message -->
        <h2 style="font-size: 28px; font-weight: 800; color: #1f2a1a; margin: 0 0 16px 0;">
            Oops! Page Not Found
        </h2>

        <p style="font-size: 16px; color: #6a7a63; margin: 0 0 40px 0; line-height: 1.6;">
            The page you're looking for seems to have wandered off like a curious cow.<br>
            Don't worry, we'll help you find your way back!
        </p>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; margin-bottom: 40px;">
            <a href="{{ route('home') }}" 
               style="display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: #2f4a1e; color: #fff; text-decoration: none; border-radius: 12px; font-weight: 800; font-size: 15px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(47, 74, 30, 0.2);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Go to Homepage
            </a>

            <a href="{{ route('products') }}" 
               style="display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: #fff; color: #2f4a1e; text-decoration: none; border-radius: 12px; font-weight: 800; font-size: 15px; border: 2px solid #2f4a1e; transition: all 0.3s ease;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Browse Products
            </a>
        </div>

        <!-- Quick Links -->
        <div style="padding-top: 30px; border-top: 1px solid #e7e7e7;">
            <p style="font-size: 14px; color: #6a7a63; margin: 0 0 16px 0; font-weight: 600;">
                Quick Links
            </p>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('about') }}" style="color: #2f4a1e; text-decoration: none; font-size: 14px; font-weight: 700; transition: opacity 0.2s;">
                    About Us
                </a>
                <a href="{{ route('membership') }}" style="color: #2f4a1e; text-decoration: none; font-size: 14px; font-weight: 700; transition: opacity 0.2s;">
                    Membership
                </a>
                <a href="{{ route('blogs') }}" style="color: #2f4a1e; text-decoration: none; font-size: 14px; font-weight: 700; transition: opacity 0.2s;">
                    Blogs
                </a>
                <a href="{{ route('contact') }}" style="color: #2f4a1e; text-decoration: none; font-size: 14px; font-weight: 700; transition: opacity 0.2s;">
                    Contact Us
                </a>
            </div>
        </div>

    </div>
</div>

<style>
    /* Hover Effects */
    a[href="{{ route('home') }}"]:hover {
        background: #263d18 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(47, 74, 30, 0.3) !important;
    }

    a[href="{{ route('products') }}"]:hover {
        background: #f0f4ed !important;
        transform: translateY(-2px);
    }

    div[style*="Quick Links"] a:hover {
        opacity: 0.7;
    }

    /* Animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    svg[width="200"] {
        animation: float 3s ease-in-out infinite;
    }

    /* Responsive */
    @media (max-width: 640px) {
        h1 {
            font-size: 56px !important;
        }
        h2 {
            font-size: 22px !important;
        }
        p {
            font-size: 14px !important;
        }
        a[style*="padding: 14px 28px"] {
            padding: 12px 20px !important;
            font-size: 14px !important;
        }
    }
</style>
@endsection
