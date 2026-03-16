<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <!-- SEO Meta Tags -->
  @php
    $currentPath = request()->path() === '/' ? '/' : '/' . request()->path();
    $seoMeta = \App\Models\SeoMeta::getByPageUrl($currentPath);
  @endphp
  
  @if($seoMeta)
    <title>{{ $seoMeta->meta_title }}</title>
    <meta name="description" content="{{ $seoMeta->meta_description }}">
    @if($seoMeta->meta_keywords)
      <meta name="keywords" content="{{ $seoMeta->meta_keywords }}">
    @endif
    @if($seoMeta->canonical_url)
      <link rel="canonical" href="{{ $seoMeta->canonical_url }}">
    @else
      <link rel="canonical" href="{{ url()->current() }}">
    @endif
    <meta name="robots" content="{{ $seoMeta->robots }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seoMeta->meta_title }}">
    <meta property="og:description" content="{{ $seoMeta->meta_description }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $seoMeta->meta_title }}">
    <meta property="twitter:description" content="{{ $seoMeta->meta_description }}">
    <meta property="twitter:image" content="@yield('twitter_image', asset('images/og-image.jpg'))">
  @else
    <!-- Fallback SEO Meta Tags -->
    <title>@yield('title', 'Home') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Welcome to ' . config('app.name'))">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="index,follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'Welcome to ' . config('app.name'))">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('twitter_title', config('app.name'))">
    <meta property="twitter:description" content="@yield('twitter_description', 'Welcome to ' . config('app.name'))">
    <meta property="twitter:image" content="@yield('twitter_image', asset('images/og-image.jpg'))">
  @endif
  
  <meta name="author" content="{{ config('app.name') }}">
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

  <!-- Font Awesome (ICONS CDN) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    :root{
      --green:#2f4a1e;
      --green-dark:#263d18;
      --border:#e7e7e7;
      --text:#1f2a1a;
      --muted:#6a7a63;
      --container:1250px;
      --iconSize:22px;
    }

    *{box-sizing:border-box;}
    body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:var(--text);background:#fff;}

    .container{
      max-width:var(--container);
      margin:0 auto;
      padding:0 16px;
    }

    /* TOP MOVING BAR */
    .topbar{
      background:var(--green);
      color:#fff;
      overflow:hidden;
      white-space:nowrap;
    }
    .marquee{
      display:flex;
      gap:60px;
      align-items:center;
      padding:10px 0;
      will-change:transform;
      animation: marquee 18s linear infinite;
    }
    .marquee span{
      font-size:12px;
      font-weight:600;
      opacity:.95;
    }
    .marquee b{font-weight:800;}
    @keyframes marquee{
      0%{ transform: translateX(0); }
      100%{ transform: translateX(-50%); }
    }
    .marquee-wrap{
      display:flex;
      width:200%;
    }

    /* MAIN HEADER */
    .mainbar{
      border-bottom:1px solid var(--border);
      background:#fff;
    }
    .mainbar-inner{
      display:grid;
      grid-template-columns: 360px 1fr 240px;
      gap:5px;
      align-items:center;
      padding: 5px 0;
    }

    /* Search */
    .search{
      position:relative;
      width:100%;
      max-width:360px;
    }
    .search input{
      width:100%;
      height:44px;
      border:1px solid #cfcfcf;
      border-radius:7px;
      padding:0 88px 0 14px;
      font-size:14px;
      outline:none;
      transition: border-color 0.2s ease;
    }
    .search input:focus{ 
      border-color:var(--green); 
      box-shadow: 0 0 0 3px rgba(47, 74, 30, 0.1);
    }
    .search input::placeholder{
      color: #999;
    }
    .search-submit,
    .search-clear{
      position:absolute;
      top:50%;
      transform:translateY(-50%);
      width:36px;
      height:36px;
      border:0;
      border-radius:8px;
      background:#fff;
      cursor:pointer;
      display:flex;
      align-items:center;
      justify-content:center;
      transition: all 0.2s ease;
    }
    .search-submit{
      right:8px;
      color:var(--green-dark);
    }
    .search-submit:hover{
      background: var(--green);
    }
    .search-submit:hover i{ 
      color: #fff;
    }
    .search-submit i{ 
      font-size:20px; 
      color:var(--green-dark);
      transition: color 0.2s ease;
    }
    .search-submit:disabled{
      opacity: 0.6;
      cursor: not-allowed;
    }
    
    .search-clear{
      right: 48px;
      color: #999;
      display: none;
    }
    .search-clear:hover{
      background: #f5f5f5;
      color: #e74c3c;
    }
    .search-clear i{
      font-size: 18px;
    }
    .search.has-value .search-clear{
      display: flex;
    }
    .search.has-value input{
      padding-right: 88px;
    }
    
    /* Active search indicator */
    .search.has-value input{
      border-color: var(--green);
      background: #f9fdf7;
    }

    /* Search Dropdown */
    .search-dropdown{
      position: absolute;
      top: calc(100% + 8px);
      left: 0;
      right: 0;
      background: #fff;
      border: 1px solid #e0e0e0;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.12);
      max-height: 400px;
      overflow-y: auto;
      z-index: 1000;
      display: none;
    }
    .search-dropdown.show{
      display: block;
    }
    .search-dropdown.loading .search-dropdown-loading{
      display: flex;
    }
    .search-dropdown.loading .search-dropdown-results,
    .search-dropdown.loading .search-dropdown-empty{
      display: none;
    }
    .search-dropdown.empty .search-dropdown-empty{
      display: flex;
    }
    .search-dropdown.empty .search-dropdown-results,
    .search-dropdown.empty .search-dropdown-loading{
      display: none;
    }
    .search-dropdown-loading{
      display: none;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 20px;
      color: var(--muted);
      font-size: 14px;
    }
    .search-dropdown-loading i{
      font-size: 18px;
      color: var(--green);
    }
    .search-dropdown-empty{
      display: none;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 30px 20px;
      color: var(--muted);
    }
    .search-dropdown-empty i{
      font-size: 32px;
      opacity: 0.5;
    }
    .search-dropdown-empty p{
      margin: 0;
      font-size: 14px;
      font-weight: 600;
    }
    .search-dropdown-results{
      padding: 8px;
    }
    .search-result-item{
      display: flex;
      gap: 12px;
      padding: 10px;
      border-radius: 8px;
      text-decoration: none;
      color: var(--text);
      transition: background 0.2s ease;
      cursor: pointer;
    }
    .search-result-item:hover{
      background: #f7f7f7;
    }
    .search-result-img{
      width: 60px;
      height: 60px;
      border-radius: 8px;
      object-fit: cover;
      border: 1px solid #e0e0e0;
      flex-shrink: 0;
    }
    .search-result-info{
      flex: 1;
      min-width: 0;
    }
    .search-result-name{
      font-weight: 700;
      font-size: 14px;
      color: var(--green-dark);
      margin: 0 0 4px 0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .search-result-desc{
      font-size: 12px;
      color: var(--muted);
      margin: 0 0 6px 0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .search-result-price{
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
    }
    .search-result-price-current{
      font-weight: 800;
      color: var(--green);
    }
    .search-result-price-old{
      font-size: 12px;
      color: #999;
      text-decoration: line-through;
    }
    .search-result-badge{
      display: inline-block;
      padding: 2px 8px;
      border-radius: 4px;
      font-size: 11px;
      font-weight: 700;
      background: #fff3cd;
      color: #856404;
    }
    .search-dropdown-footer{
      padding: 10px;
      border-top: 1px solid #e0e0e0;
      text-align: center;
    }
    .search-dropdown-footer a{
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      background: var(--green);
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 700;
      transition: background 0.2s ease;
    }
    .search-dropdown-footer a:hover{
      background: var(--green-dark);
    }
    
    /* Highlight matched text */
    .search-highlight{
      background: #fff3cd;
      color: #856404;
      font-weight: 700;
      padding: 0 2px;
      border-radius: 2px;
    }

    /* Logo */
    .logo{
      text-align:center;
      text-decoration:none;
      color:var(--green-dark);
      display:inline-block;
    }
    .logo-img{
      height:60px;
      width:auto;
      display:block;
      margin: 0 auto;
    }

    /* Icons */
    .icons{
      display:flex;
      justify-content:flex-end;
      align-items:center;
      gap:14px;
    }
    .icon-btn{
      width:44px;
      height:44px;
      border:0;
      background:transparent;
      border-radius:12px;
      cursor:pointer;
      position:relative;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .icon-btn:hover{ background:#f7f7f7; }
    .icon-btn i{
      font-size:var(--iconSize);
      color:var(--green);
    }
    .badge{
      position:absolute;
      top:4px;
      right:4px;
      width:18px;
      height:18px;
      border-radius:999px;
      background:#1f2a1a;
      color:#fff;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:11px;
      font-weight:700;
    }

    /* NAV */
    .navbar{
      border-bottom:1px solid var(--border);
      background:#fff;
    }
    .nav{
      margin:0;
      padding:8px 0;
      list-style:none;
      display:flex;
      justify-content:center;
      align-items:center;
      gap:28px;
      flex-wrap:wrap;
    }
    .nav a{
      text-decoration:none;
      color:var(--green-dark);
      font-weight:800;
      letter-spacing:.2px;
      font-size:14px;
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 2px;
    }
    .nav a i{
      font-size:18px;
      color:var(--green);
    }
    .nav a:hover{ opacity:.85; }

    /* âœ… Hamburger (same style as your old .hamburger) */
    .tb-hamburger{
      display:none;
      width:44px;
      height:44px;
      border:1px solid var(--border);
      border-radius:12px;
      background:#fff;
      cursor:pointer;
      align-items:center;
      justify-content:center;
    }
    .tb-hamburger:hover{ background:#f7f7f7; }
    .tb-hamburger i{ font-size:22px; color:var(--green); }

    @media (max-width: 980px){
      .mainbar-inner{
        grid-template-columns: 1fr 1fr;
        grid-template-areas:
          "logo icons"
          "search search";
      }
      .search{ grid-area:search; max-width:100%; }
      .logo{ grid-area:logo; text-align:left; }
      .logo-img{ margin:0; height:60px; }
      .icons{ grid-area:icons; }
      .tb-hamburger{ display:flex; }

      /* mobile menu panel */
      .navbar{
        display:none;
        border-top: 1px solid var(--border);
        border-bottom:1px solid var(--border);
      }
      .navbar.open{ display:block; }

      .nav{
        flex-direction:column;
        align-items:flex-start;
        gap:0;
        padding:10px 0 14px;
      }
      .nav li{ width:100%; }
      .nav a{
        width:100%;
        padding:12px 6px;
        border-radius:10px;
      }
      .nav a:hover{ background:#f7f7f7; opacity:1; }
      
      /* Search dropdown mobile adjustments */
      .search-dropdown{
        max-height: 300px;
      }
      .search-result-item{
        padding: 8px;
      }
      .search-result-img{
        width: 50px;
        height: 50px;
      }
      .search-result-name{
        font-size: 13px;
      }
      .search-result-desc{
        font-size: 11px;
      }
    }
  </style>
  
  <!-- Toast Notifications -->
  <style>
    .dairy-toast {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: #fff;
      border: 1px solid rgba(0,0,0,0.1);
      border-radius: 12px;
      padding: 14px 18px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      display: flex;
      align-items: center;
      gap: 12px;
      z-index: 9999;
      transform: translateX(400px);
      opacity: 0;
      transition: all 0.3s ease;
      min-width: 280px;
      max-width: 400px;
    }
    .dairy-toast.show {
      transform: translateX(0);
      opacity: 1;
    }
    .dairy-toast i {
      font-size: 20px;
      flex-shrink: 0;
    }
    .dairy-toast-success {
      border-left: 4px solid #2F4A1E;
    }
    .dairy-toast-success i {
      color: #2F4A1E;
    }
    .dairy-toast-error {
      border-left: 4px solid #e74c3c;
    }
    .dairy-toast-error i {
      color: #e74c3c;
    }
    .dairy-toast span {
      font-weight: 700;
      font-size: 14px;
      color: var(--text);
    }

    /* Offcanvas Styles */
    .dairy-offcanvas-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 10000;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }
    .dairy-offcanvas-overlay.show {
      opacity: 1;
      visibility: visible;
    }
    .dairy-offcanvas {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      width: min(450px, 90vw);
      background: #fff;
      box-shadow: -5px 0 25px rgba(0,0,0,0.2);
      z-index: 10001;
      transform: translateX(100%);
      transition: transform 0.3s ease;
      display: flex;
      flex-direction: column;
    }
    .dairy-offcanvas.show {
      transform: translateX(0);
    }
    .dairy-offcanvas-header {
      padding: 20px 24px;
      border-bottom: 1px solid #e0e0e0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: linear-gradient(180deg, #fff, #f9f9f9);
    }
    .dairy-offcanvas-header h3 {
      margin: 0;
      font-size: 20px;
      font-weight: 900;
      color: var(--green-dark);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .dairy-offcanvas-close {
      width: 36px;
      height: 36px;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      background: #fff;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
    }
    .dairy-offcanvas-close:hover {
      background: #f5f5f5;
      border-color: #f1cc24;
    }
    .dairy-offcanvas-body {
      flex: 1 1 auto;
      overflow-y: auto;
      padding: 16px 20px;
      min-height: 0;
    }
    .dairy-offcanvas-empty {
      text-align: center;
      padding: 60px 20px;
      color: var(--muted);
    }
    .dairy-offcanvas-empty i {
      font-size: 64px;
      opacity: 0.3;
      margin-bottom: 16px;
    }
    .dairy-offcanvas-empty p {
      font-size: 16px;
      font-weight: 700;
      margin: 0 0 20px 0;
    }
    .dairy-offcanvas-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 12px;
      border: 1px solid #e0e0e0;
      border-radius: 12px;
      margin-bottom: 10px;
      background: #fff;
      transition: all 0.2s ease;
    }
    .dairy-offcanvas-item:hover {
      border-color: #f1cc24;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .dairy-offcanvas-img {
      width: 60px;
      height: 60px;
      border-radius: 8px;
      object-fit: cover;
      border: 1px solid #e0e0e0;
      flex-shrink: 0;
    }
    .dairy-offcanvas-info {
      flex: 1;
      min-width: 0;
    }
    .dairy-offcanvas-name {
      font-weight: 700;
      font-size: 13px;
      color: var(--green-dark);
      margin: 0 0 4px 0;
      display: block;
      text-decoration: none;
      transition: color 0.2s ease;
      line-height: 1.3;
    }
    .dairy-offcanvas-name:hover {
      color: #f1cc24;
    }
    .dairy-offcanvas-price {
      font-weight: 800;
      font-size: 14px;
      color: #2f4a1e;
      margin-bottom: 6px;
    }
    .dairy-offcanvas-qty {
      display: flex;
      align-items: center;
      gap: 6px;
      margin-top: 6px;
    }
    .dairy-offcanvas-qty button {
      width: 26px;
      height: 26px;
      border: 1px solid #e0e0e0;
      border-radius: 6px;
      background: #fff;
      cursor: pointer;
      font-weight: 900;
      font-size: 14px;
      line-height: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
    }
    .dairy-offcanvas-qty button:hover {
      background: var(--green);
      color: #fff;
      border-color: var(--green);
    }
    .dairy-offcanvas-qty span {
      font-weight: 800;
      min-width: 30px;
      text-align: center;
    }
    .dairy-offcanvas-remove {
      width: 32px;
      height: 32px;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      background: #fff;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #e74c3c;
      transition: all 0.2s ease;
      flex-shrink: 0;
    }
    .dairy-offcanvas-remove:hover {
      background: #fee;
      border-color: #e74c3c;
    }
    .dairy-offcanvas-footer {
      padding: 16px 20px;
      border-top: 1px solid #e0e0e0;
      background: #f9f9f9;
      flex-shrink: 0;
    }
    .dairy-offcanvas-total {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
      font-size: 16px;
      font-weight: 800;
      color: #1f2a1a;
    }
    .dairy-offcanvas-btn {
      width: 100%;
      padding: 13px 16px;
      border: 0;
      border-radius: 10px;
      background: #2f4a1e;
      color: #fff;
      font-weight: 800;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-decoration: none;
      box-sizing: border-box;
    }
    .dairy-offcanvas-btn:hover {
      background: #1e3212;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .dairy-offcanvas-btn.secondary {
      background: #fff;
      color: #2f4a1e;
      border: 1px solid #e0e0e0;
      margin-top: 8px;
    }
    .dairy-offcanvas-btn.secondary:hover {
      background: #f5f5f5;
      border-color: #2f4a1e;
    }

    @media (max-width: 560px) {
      .dairy-toast {
        bottom: 20px;
        right: 20px;
        left: 20px;
        min-width: auto;
      }
      .dairy-offcanvas {
        width: 100%;
      }
    }
  </style>

  @stack('styles')
</head>

<body>

  <!-- TOP MOVING BAR -->
  @php
    $announcements = \App\Models\Announcement::active()->get();
  @endphp
  @if($announcements->count() > 0)
  <div class="topbar">
    <div class="container">
      <div class="marquee-wrap">
        <div class="marquee">
          @foreach($announcements as $announcement)
          <span>
            @if($announcement->icon){{ $announcement->icon }} @endif
            {!! $announcement->message !!}
            @if($announcement->link && $announcement->link_text)
            | <a href="{{ $announcement->link }}" style="color: inherit; text-decoration: underline;">{{ $announcement->link_text }}</a>
            @endif
          </span>
          @endforeach
        </div>
        <div class="marquee" aria-hidden="true">
          @foreach($announcements as $announcement)
          <span>
            @if($announcement->icon){{ $announcement->icon }} @endif
            {!! $announcement->message !!}
            @if($announcement->link && $announcement->link_text)
            | <a href="{{ $announcement->link }}" style="color: inherit; text-decoration: underline;">{{ $announcement->link_text }}</a>
            @endif
          </span>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  @endif


<style>
    /* Sticky Header */
.mainbar{
  position: sticky;
  top: 0;
  z-index: 999;
  background:#fff;
}

/* Sticky Navbar */
.navbar{
  position: sticky;
  top: 70px; /* header height ke according adjust */
  z-index: 998;
  background:#fff;
}
  
</style>

  <!-- MAIN HEADER -->
  <header class="mainbar">
    <div class="container">
      <div class="mainbar-inner">

        <!-- Search -->
        <form action="{{ route('products') }}" method="GET" class="search">
          <input type="text" name="search" id="searchInput" placeholder="Search products..." value="{{ request('search') }}" autocomplete="off" />
          @if(request('search'))
          <button type="button" class="search-clear" aria-label="Clear search" title="Clear">
            <i class="fa-solid fa-xmark"></i>
          </button>
          @endif
          <button type="submit" class="search-submit" aria-label="Search">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
          
          <!-- Search Dropdown -->
          <div class="search-dropdown" id="searchDropdown">
            <div class="search-dropdown-loading">
              <i class="fa-solid fa-spinner fa-spin"></i> Searching...
            </div>
            <div class="search-dropdown-results" id="searchResults"></div>
            <div class="search-dropdown-empty">
              <i class="fa-solid fa-magnifying-glass"></i>
              <p>No products found</p>
            </div>
          </div>
        </form>

        <!-- Logo -->
        <a href="{{ route('home') }}" class="logo" aria-label="{{ config('app.name') }}">
          <img src="{{ asset('images/new.png') }}" alt="{{ config('app.name') }} Logo" class="logo-img">
        </a>

        <!-- Icons + Hamburger -->
        <div class="icons">
          @auth
            @if(auth()->user()->isAdmin())
              <!-- Admin User -->
              <a href="{{ route('admin.dashboard') }}" class="icon-btn" title="Admin Dashboard">
                <i class="fa-solid fa-user-shield"></i>
              </a>
            @else
              <!-- Member User -->
              <a href="{{ route('member.dashboard') }}" class="icon-btn" title="My Dashboard">
                <i class="fa-solid fa-user"></i>
              </a>
            @endif
          @else
            <a href="{{ route('member.login') }}" class="icon-btn" title="Login">
              <i class="fa-solid fa-user"></i>
            </a>
          @endauth

          <button class="icon-btn" type="button" aria-label="Wishlist" id="wishlistBtn">
            <span class="badge" id="wishlistCount">0</span>
            <i class="fa-regular fa-heart"></i>
          </button>

          <button class="icon-btn" type="button" aria-label="Cart" id="cartBtn">
            <span class="badge" id="cartCount">0</span>
            <i class="fa-solid fa-bag-shopping"></i>
          </button>

          <!-- âœ… Hamburger -->
          <button class="tb-hamburger" type="button" id="tbMenuBtn" aria-label="Menu" aria-controls="navbar" aria-expanded="false">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
          </button>
        </div>

      </div>
    </div>
  </header>

  <!-- âœ… NAVBAR (same ID used in JS) -->
  <nav class="navbar" id="navbar" aria-label="Primary">
    <div class="container">
      <ul class="nav">
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fa-solid fa-house"></i> Home</a></li>
        <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}"><i class="fa-solid fa-user"></i> About</a></li>
        <li><a href="{{ route('membership') }}" class="{{ request()->routeIs('membership') ? 'active' : '' }}"><i class="fa-solid fa-id-card-clip"></i> Membership</a></li>
        <li><a href="{{ route('products') }}" class="{{ request()->routeIs('products') ? 'active' : '' }}"><i class="fa-solid fa-box-open"></i> Products</a></li>
        <li><a href="{{ route('blogs') }}" class="{{ request()->routeIs('blogs') ? 'active' : '' }}"><i class="fa-solid fa-pen-to-square"></i> Blogs</a></li>
        <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}"><i class="fa-solid fa-envelope"></i> Contact Us</a></li>
      </ul>
    </div>
  </nav>
    <a href="https://wa.me/919999787973?text=Hello"
   target="_blank"
   style="position:fixed;
          bottom:20px;
          right:20px;
          background:#25D366;
          color:#fff;
          padding:14px 16px;
          border-radius:50%;
          font-size:22px;
          text-decoration:none;
          box-shadow:0 4px 10px rgba(0,0,0,0.3);
          z-index:999;">
    <i class="fa-brands fa-whatsapp"></i>
</a>
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="tb-footer">

      <div class="container tb-footer-grid">

        <!-- Brand -->
        <div class="tb-foot-brand">
          <!--<h3>{{ config('app.name') }}</h3>-->
          <h3 style="font-size:21.28px;">Nulac Dairy Pvt. Ltd.</h3>
          
          <p>Pure, clean, and ethically sourced food products crafted for modern Indian families.</p>

          <!--<div class="tb-foot-social">-->
          <!--  <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>-->
          <!--  <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>-->
          <!--  <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>-->
          <!--  <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>-->
          <!--</div>-->
        </div>

        <!-- Links -->
        <div class="tb-foot-links">
          <h4>Quick Links</h4>
          <a href="{{ route('home') }}">Home</a>
          <a href="{{ route('about') }}">About Us</a>
          <a href="{{ route('contact') }}">Contact Us</a>
          <a href="{{ route('membership') }}">Membership</a>
          <a href="{{ route('blogs') }}">Blogs</a>
        </div>

        <div class="tb-foot-links">
          <h4>Products</h4>
          <a href="{{ route('products') }}">Milk</a>
          <!--<a href="{{ route('products') }}">Ghee</a>-->
          <!--<a href="{{ route('products') }}">Paneer</a>-->
        </div>

        <div class="tb-foot-links">
          <h4>Support</h4>
          <a href="{{ route('terms-conditions') }}">Terms & Conditions</a>
          <a href="{{ route('contact') }}">Contact Us</a>
          <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
        </div>

        <!-- Newsletter -->
        <div class="tb-foot-news">
          <h4>Join Our Community</h4>
          <p>Get exclusive offers, product launches and healthy living tips.</p>

          <form class="tb-foot-form" id="newsletterForm" action="{{ route('contact.submit') }}" method="POST">
            @csrf
            <input type="email" name="email" id="newsletter_email" placeholder="Your email address" required>
            <input type="hidden" name="name" value="Newsletter Subscriber">
            <input type="hidden" name="phone" value="0000000000">
            <input type="hidden" name="subject" value="Newsletter Subscription">
            <input type="hidden" name="message" value="I would like to subscribe to your newsletter for exclusive offers and updates.">
            <button type="submit" id="newsletterBtn"><span id="newsletterBtnText">Subscribe</span></button>
          </form>
          
          <!-- Success/Error Messages -->
          <div id="newsletterSuccess" style="display: none; margin-top: 12px; padding: 10px; background: #d1fae5; border-radius: 8px; color: #065f46; font-size: 13px; font-weight: 600;">
            <i class="fa-solid fa-circle-check"></i> <span id="newsletterSuccessText"></span>
          </div>
          <div id="newsletterError" style="display: none; margin-top: 12px; padding: 10px; background: #fee2e2; border-radius: 8px; color: #991b1b; font-size: 13px; font-weight: 600;">
            <i class="fa-solid fa-circle-xmark"></i> <span id="newsletterErrorText"></span>
          </div>
        </div>

      </div>

      <!-- Bottom bar -->
<!--      <div class="tb-foot-bottom">-->
<!--        <div class="container">-->
<!--          <p>Â© {{ date('Y') }} {{ config('app.name') }}. All rights reserved. Designed and Developed by <a target="_blank" href="https://www.apwebworld.com/"  style="text-decoration: none; color:white;">Ap Web World</a>-->
<!--</p>-->
<!--        </div>-->
<!--      </div>-->

<!-- Bottom bar -->
      <div class="tb-foot-bottom">
        <div class="container">
          <p>Â© {{ date('Y') }} Nulac Dairy Private Limited. All rights reserved. Designed and Developed by <a target="" href="https://www.apwebworld.com/"  style="text-decoration: none; color:white;">Ap Web World</a>
</p>
        </div>
      </div>

    </footer>

    <style>
    /* =============================
       FOOTER BASE
    ============================= */
    .tb-footer{
      position:relative;
      color:#fff;
      padding-top:80px;
      overflow:hidden;

      /* Background image */
      background:url('{{ asset('images/about-banner.webp') }}') center/cover no-repeat;
    }

    /* Overlay for opacity */
    .tb-footer::before{
      content:"";
      position:absolute;
      inset:0;
      background:rgba(0,0,0,0.65);
      z-index:0;
    }

    /* Keep content above overlay */
    .tb-footer > *{
      position:relative;
      z-index:1;
    }

    /* =============================
       GRID
    ============================= */
    .tb-footer-grid{
      display:grid;
      grid-template-columns: 1.5fr 1fr 1fr 1fr 1.5fr;
      gap:40px;
      padding-bottom:60px;
    }

    /* =============================
       BRAND
    ============================= */
    .tb-foot-brand h3{
      font-size:26px;
      font-weight:900;
      color:#fff;
    }
    .tb-foot-brand p{
      margin-top:12px;
      color:rgba(255,255,255,.85);
      line-height:1.6;
    }

    /* =============================
       SOCIAL
    ============================= */
    .tb-foot-social{
      display:flex;
      gap:12px;
      margin-top:20px;
    }
    .tb-foot-social a{
      width:38px;
      height:38px;
      border-radius:50%;
      background:#ffca41;
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight:900;
      color:#fff;
      text-decoration:none;
      transition:.25s;
    }
    .tb-foot-social a:hover{
      background:#f1cc24;
      color:#1f2a1a;
      transform:translateY(-3px);
    }

    /* =============================
       LINKS
    ============================= */
    .tb-foot-links h4{
      font-size:16px;
      font-weight:900;
      margin-bottom:16px;
    }
    .tb-foot-links a{
      display:block;
      color:rgba(255,255,255,.78);
      text-decoration:none;
      margin-bottom:10px;
      font-weight:600;
      transition:.2s;
    }
    .tb-foot-links a:hover{
      color:#de4631;
      padding-left:4px;
    }

    /* =============================
       NEWSLETTER
    ============================= */
    .tb-foot-news h4{
      font-size:18px;
      font-weight:900;
    }
    .tb-foot-news p{
      color:rgba(255,255,255,.78);
      margin:10px 0 16px;
      line-height:1.5;
    }

    .tb-foot-form{
      display:flex;
      background:#fff;
      border-radius:40px;
      overflow:hidden;
    }
    .tb-foot-form input{
      flex:1;
      border:none;
      padding:14px 18px;
      outline:none;
    }
    .tb-foot-form button{
      background:#ffca41;
      border:none;
      padding:0 24px;
      font-weight:900;
      cursor:pointer;
      color:white;
      transition:.25s;
    }
    .tb-foot-form button:hover{
      background:#c34f07;
    }


    .tb-foot-bottom{
      background:black;
      padding:10px 0;
      text-align:center;
      border-top: 1px solid #434343;
    }
    .tb-foot-bottom p{
      margin:0;
      font-size:14px;
      color:rgba(255,255,255,.75);
    }

    /* =============================
       RESPONSIVE
    ============================= */
    @media(max-width:900px){
      .tb-footer-grid{
        grid-template-columns:1fr 1fr;
      }
    }
    @media(max-width:600px){
      .tb-footer-grid{
        gap:30px;
      }
      .tb-footer {
        position: relative;
        color: #fff;
        padding-top: 30px;
        overflow: hidden;
        background: url({{ asset('images/about-banner.webp') }}) center / cover no-repeat;
    }

      .tb-foot-form{
        flex-direction:column;
        border-radius:12px;
      }

      .tb-foot-form button{
        padding:12px;
      }
    }
    
    
    
    
 
    </style>
    
    <!-- Cart Offcanvas (also used for checkout steps) -->
    <div class="dairy-offcanvas-overlay" id="cartOverlay"></div>
    <div class="dairy-offcanvas" id="cartOffcanvas">

      <!-- Cart Header -->
      <div class="dairy-offcanvas-header" id="cartHeader">
        <h3><i class="fa-solid fa-bag-shopping"></i> Shopping Cart</h3>
        <button class="dairy-offcanvas-close" id="closeCart" aria-label="Close cart">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Checkout Header (hidden by default) -->
      <div id="checkoutHeader" style="display:none;align-items:center;justify-content:space-between;padding:16px 20px;background:#2f4a1e;flex-shrink:0;">
        <div style="display:flex;align-items:center;gap:10px;">
          <button id="checkoutBackBtn" style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,.8);font-size:16px;padding:0;line-height:1;">
            <i class="fa-solid fa-arrow-left"></i>
          </button>
          <h3 id="checkoutHeaderTitle" style="margin:0;font-size:16px;font-weight:800;color:#fff;">Checkout</h3>
        </div>
        <button class="dairy-offcanvas-close" onclick="closeCartPanel()" style="color:rgba(255,255,255,.8);">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Step tabs (shown during checkout) -->
      <div id="checkoutTabs" style="display:none;flex-shrink:0;border-bottom:1px solid #e7e7e7;">
        <div style="display:flex;">
          <div id="coTab1" style="flex:1;padding:10px;text-align:center;font-size:12px;font-weight:700;color:#2f4a1e;border-bottom:2px solid #2f4a1e;">
            <i class="fa-solid fa-list" style="margin-right:3px;"></i>Order
          </div>
          <div id="coTab2" style="flex:1;padding:10px;text-align:center;font-size:12px;font-weight:700;color:#9ca3af;border-bottom:2px solid transparent;">
            <i class="fa-solid fa-user" style="margin-right:3px;"></i>Details
          </div>
          <div id="coTab3" style="flex:1;padding:10px;text-align:center;font-size:12px;font-weight:700;color:#9ca3af;border-bottom:2px solid transparent;">
            <i class="fa-solid fa-lock" style="margin-right:3px;"></i>Pay
          </div>
        </div>
      </div>

      <!-- Cart body -->
      <div class="dairy-offcanvas-body" id="cartBody"></div>

      <!-- Checkout Step 1: Order Summary -->
      <div class="dairy-offcanvas-body" id="coStep1" style="display:none;">
        <div id="coSummary"></div>
      </div>

      <!-- Checkout Step 2: Details -->
      <div class="dairy-offcanvas-body" id="coStep2" style="display:none;padding:20px;">
        <p style="font-size:12px;font-weight:700;color:#6a7a63;text-transform:uppercase;letter-spacing:.5px;margin:0 0 14px;">Delivery Details</p>
        <div style="margin-bottom:12px;">
          <label style="display:block;font-size:13px;font-weight:600;color:#1f2a1a;margin-bottom:4px;">Full Name *</label>
          <input type="text" id="co_name" required placeholder="Your name"
                 style="width:100%;padding:10px 12px;border:1px solid #e7e7e7;border-radius:10px;font-size:14px;box-sizing:border-box;">
        </div>
        <div style="margin-bottom:12px;">
          <label style="display:block;font-size:13px;font-weight:600;color:#1f2a1a;margin-bottom:4px;">Phone *</label>
          <input type="tel" id="co_phone" required placeholder="10-digit mobile number"
                 style="width:100%;padding:10px 12px;border:1px solid #e7e7e7;border-radius:10px;font-size:14px;box-sizing:border-box;">
        </div>
        <div style="margin-bottom:12px;">
          <label style="display:block;font-size:13px;font-weight:600;color:#1f2a1a;margin-bottom:4px;">Email</label>
          <input type="email" id="co_email" placeholder="Optional"
                 style="width:100%;padding:10px 12px;border:1px solid #e7e7e7;border-radius:10px;font-size:14px;box-sizing:border-box;">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#1f2a1a;margin-bottom:4px;">Delivery Address *</label>
          <textarea id="co_address" required rows="3" placeholder="Full delivery address"
                    style="width:100%;padding:10px 12px;border:1px solid #e7e7e7;border-radius:10px;font-size:14px;box-sizing:border-box;resize:vertical;"></textarea>
        </div>
      </div>

      <!-- Checkout Step 3: Review & Pay -->
      <div class="dairy-offcanvas-body" id="coStep3" style="display:none;">
        <div id="coReview"></div>
        <div id="checkoutError" style="display:none;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:10px 14px;color:#dc2626;font-size:13px;margin-top:12px;"></div>
      </div>

      <!-- Cart Footer -->
      <div class="dairy-offcanvas-footer" id="cartFooter" style="display:none;">
        <div class="dairy-offcanvas-total">
          <span>Total:</span>
          <span id="cartTotal">&#8377;0</span>
        </div>
        <button onclick="openCheckoutModal()" class="dairy-offcanvas-btn">
          <i class="fa-solid fa-credit-card"></i> Proceed to Payment
        </button>
        <button class="dairy-offcanvas-btn secondary" id="clearCartBtn">
          <i class="fa-solid fa-trash"></i> Clear Cart
        </button>
      </div>

      <!-- Checkout Footer -->
      <div id="checkoutFooter" style="display:none;padding:16px 20px;border-top:1px solid #e7e7e7;flex-shrink:0;">
        <button id="checkoutNextBtn" onclick="checkoutNext()"
                class="dairy-offcanvas-btn" style="margin-bottom:0;">
          Continue <i class="fa-solid fa-arrow-right" style="margin-left:6px;"></i>
        </button>
      </div>

    </div>

    <!-- Wishlist Offcanvas -->
    <div class="dairy-offcanvas-overlay" id="wishlistOverlay"></div>
    <div class="dairy-offcanvas" id="wishlistOffcanvas">
      <div class="dairy-offcanvas-header">
        <h3><i class="fa-solid fa-heart"></i> My Wishlist</h3>
        <button class="dairy-offcanvas-close" id="closeWishlist" aria-label="Close wishlist">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <div class="dairy-offcanvas-body" id="wishlistBody">
        <!-- Wishlist items will be inserted here -->
      </div>
      <div class="dairy-offcanvas-footer" id="wishlistFooter" style="display: none;">
        <button class="dairy-offcanvas-btn secondary" id="clearWishlistBtn">
          <i class="fa-solid fa-trash"></i> Clear Wishlist
        </button>
      </div>
    </div>

    <!-- Scripts -->
    <script>
      (function () {
        const btn = document.getElementById("tbMenuBtn");
        const panel = document.getElementById("navbar");
        if (!btn || !panel) return;

        const icon = btn.querySelector("i");

        function setExpanded(isOpen) {
          btn.setAttribute("aria-expanded", isOpen ? "true" : "false");
          if (icon) icon.className = isOpen ? "fa-solid fa-xmark" : "fa-solid fa-bars";
        }

        function openMenu() {
          panel.classList.add("open");
          setExpanded(true);
        }

        function closeMenu() {
          panel.classList.remove("open");
          setExpanded(false);
        }

        function toggleMenu(e) {
          e.preventDefault();
          e.stopPropagation();
          panel.classList.contains("open") ? closeMenu() : openMenu();
        }

        // click toggle
        btn.addEventListener("click", toggleMenu, { passive: false });

        // close on outside click
        document.addEventListener("click", function (e) {
          if (!panel.classList.contains("open")) return;
          if (!panel.contains(e.target) && !btn.contains(e.target)) closeMenu();
        });

        // close on ESC
        document.addEventListener("keydown", function (e) {
          if (e.key === "Escape") closeMenu();
        });

        // close if desktop view
        window.addEventListener("resize", function () {
          if (window.innerWidth > 980) closeMenu();
        });

        // close when clicking a link (mobile)
        panel.addEventListener("click", function (e) {
          const a = e.target.closest("a");
          if (a && window.innerWidth <= 980) closeMenu();
        });

        // default state
        setExpanded(false);
      })();

      // Search Enhancement with Live Results
      (function() {
        const searchForm = document.querySelector('.search');
        const searchInput = searchForm?.querySelector('input[name="search"]');
        const clearBtn = searchForm?.querySelector('.search-clear');
        const dropdown = document.getElementById('searchDropdown');
        const resultsContainer = document.getElementById('searchResults');
        
        if (!searchInput || !dropdown) return;

        let searchTimeout;
        let currentQuery = '';

        // Update visual state based on input value
        function updateSearchState() {
          if (searchInput.value.trim()) {
            searchForm.classList.add('has-value');
          } else {
            searchForm.classList.remove('has-value');
            hideDropdown();
          }
        }

        // Show dropdown
        function showDropdown() {
          dropdown.classList.add('show');
        }

        // Hide dropdown
        function hideDropdown() {
          dropdown.classList.remove('show');
          dropdown.classList.remove('loading', 'empty');
        }

        // Highlight matching text
        function highlightText(text, query) {
          if (!query || !text) return text;
          const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
          return text.replace(regex, '<span class="search-highlight">$1</span>');
        }

        // Render search results
        function renderResults(products, query) {
          if (products.length === 0) {
            dropdown.classList.add('empty');
            dropdown.classList.remove('loading');
            return;
          }

          dropdown.classList.remove('loading', 'empty');

          let html = '';
          products.forEach(product => {
            html += `
              <a href="${product.url}" class="search-result-item">
                <img src="${product.image}" alt="${product.name}" class="search-result-img" onerror="this.src='{{ asset('images/products-1.png') }}'">
                <div class="search-result-info">
                  <h4 class="search-result-name">${highlightText(product.name, query)}</h4>
                  <p class="search-result-desc">${product.short_description || ''}</p>
                  <div class="search-result-price">
                    <span class="search-result-price-current">â‚¹${Math.round(product.price)}</span>
                    ${product.mrp ? `<span class="search-result-price-old">â‚¹${Math.round(product.mrp)}</span>` : ''}
                    ${product.badge ? `<span class="search-result-badge">${product.badge}</span>` : ''}
                  </div>
                </div>
              </a>
            `;
          });

          html += `
            <div class="search-dropdown-footer">
              <a href="{{ route('products') }}?search=${encodeURIComponent(query)}">
                View all results <i class="fa-solid fa-arrow-right"></i>
              </a>
            </div>
          `;

          resultsContainer.innerHTML = html;
        }

        // Perform search
        async function performSearch(query) {
          if (query.length < 2) {
            hideDropdown();
            return;
          }

          currentQuery = query;
          showDropdown();
          dropdown.classList.add('loading');
          dropdown.classList.remove('empty');

          try {
            const response = await fetch(`{{ route('api.search.products') }}?q=${encodeURIComponent(query)}`);
            const products = await response.json();
            
            // Only render if this is still the current query
            if (query === currentQuery) {
              renderResults(products, query);
            }
          } catch (error) {
            console.error('Search error:', error);
            dropdown.classList.remove('loading');
            dropdown.classList.add('empty');
          }
        }

        // Initial state
        updateSearchState();

        // Handle input with debounce
        searchInput.addEventListener('input', function() {
          updateSearchState();
          
          clearTimeout(searchTimeout);
          const query = searchInput.value.trim();
          
          if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
              performSearch(query);
            }, 300);
          } else {
            hideDropdown();
          }
        });

        // Show dropdown on focus if there's a value
        searchInput.addEventListener('focus', function() {
          const query = searchInput.value.trim();
          if (query.length >= 2) {
            performSearch(query);
          }
        });

        // Clear button functionality
        if (clearBtn) {
          clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            updateSearchState();
            searchInput.focus();
            window.location.href = '{{ route("products") }}';
          });
        }

        // Clear search on ESC
        searchInput.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') {
            searchInput.value = '';
            updateSearchState();
            searchInput.blur();
            hideDropdown();
          }
          // Navigate results with arrow keys
          if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();
            const items = dropdown.querySelectorAll('.search-result-item');
            if (items.length > 0) {
              const focused = dropdown.querySelector('.search-result-item:focus');
              if (!focused) {
                items[0].focus();
              } else {
                const index = Array.from(items).indexOf(focused);
                if (e.key === 'ArrowDown' && index < items.length - 1) {
                  items[index + 1].focus();
                } else if (e.key === 'ArrowUp' && index > 0) {
                  items[index - 1].focus();
                }
              }
            }
          }
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
          if (!searchForm.contains(e.target)) {
            hideDropdown();
          }
        });

        // Trim whitespace on submit
        searchForm.addEventListener('submit', function(e) {
          const value = searchInput.value.trim();
          if (!value) {
            e.preventDefault();
            return;
          }
          searchInput.value = value;
          hideDropdown();
        });

        // Add loading state
        searchForm.addEventListener('submit', function() {
          const btn = searchForm.querySelector('.search-submit');
          if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.6';
            const icon = btn.querySelector('i');
            if (icon) {
              icon.className = 'fa-solid fa-spinner fa-spin';
            }
            setTimeout(() => {
              btn.disabled = false;
              btn.style.opacity = '1';
              if (icon) {
                icon.className = 'fa-solid fa-magnifying-glass';
              }
            }, 2000);
          }
        });

        // Focus search with Ctrl/Cmd + K
        document.addEventListener('keydown', function(e) {
          if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
          }
        });
      })();

      // Newsletter Form AJAX Submission
      (function() {
        const form = document.getElementById('newsletterForm');
        const btn = document.getElementById('newsletterBtn');
        const btnText = document.getElementById('newsletterBtnText');
        const successDiv = document.getElementById('newsletterSuccess');
        const successText = document.getElementById('newsletterSuccessText');
        const errorDiv = document.getElementById('newsletterError');
        const errorText = document.getElementById('newsletterErrorText');
        const emailInput = document.getElementById('newsletter_email');

        if (!form) return;

        form.addEventListener('submit', async function(e) {
          e.preventDefault();

          // Hide previous messages
          successDiv.style.display = 'none';
          errorDiv.style.display = 'none';

          // Show loading state
          btn.disabled = true;
          btnText.textContent = 'Subscribing...';

          try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
              method: 'POST',
              body: formData,
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
              }
            });

            const data = await response.json();

            if (response.ok && data.success) {
              // Show success message
              successText.textContent = data.message || 'Thank you for subscribing!';
              successDiv.style.display = 'block';
              
              // Reset form
              emailInput.value = '';

              // Auto-hide success message after 5 seconds
              setTimeout(() => {
                successDiv.style.display = 'none';
              }, 5000);
            } else {
              // Show error message
              errorText.textContent = data.message || 'Something went wrong. Please try again.';
              errorDiv.style.display = 'block';
            }
          } catch (error) {
            console.error('Newsletter error:', error);
            errorText.textContent = 'Network error. Please check your connection and try again.';
            errorDiv.style.display = 'block';
          } finally {
            // Reset button state
            btn.disabled = false;
            btnText.textContent = 'Subscribe';
          }
        });
      })();
    </script>

    <!-- Cart & Wishlist Management -->
    <script src="{{ asset('js/cart-wishlist.js') }}"></script>

    <!-- Cart & Wishlist Modals -->
    <script>
      // Cart Offcanvas Handler
      (function() {
        const cartBtn = document.getElementById('cartBtn');
        const cartOverlay = document.getElementById('cartOverlay');
        const cartOffcanvas = document.getElementById('cartOffcanvas');
        const closeCart = document.getElementById('closeCart');
        const cartBody = document.getElementById('cartBody');
        const cartFooter = document.getElementById('cartFooter');
        const cartTotal = document.getElementById('cartTotal');
        const clearCartBtn = document.getElementById('clearCartBtn');

        if (!cartBtn || !cartOffcanvas) return;

        function openCart() {
          if (!window.DairyCart) return;

          // Reset to cart view (in case we were in checkout)
          if (typeof _showCartView === 'function') _showCartView();

          const cart = window.DairyCart.getCart();
          const total = window.DairyCart.getCartTotal();

          // Render cart items
          if (cart.length === 0) {
            cartBody.innerHTML = `
              <div class="dairy-offcanvas-empty">
                <i class="fa-solid fa-bag-shopping"></i>
                <p>Your cart is empty</p>
                <a href="{{ route('products') }}" class="dairy-offcanvas-btn">
                  <i class="fa-solid fa-store"></i> Browse Products
                </a>
              </div>
            `;
            cartFooter.style.display = 'none';
          } else {
            let html = '';
            cart.forEach(item => {
              // Ensure all values have defaults
              const itemName = item.name || 'Unknown Product';
              const itemPrice = parseFloat(item.price) || 0;
              const itemQty = parseInt(item.quantity) || 1;
              const itemImage = item.image || '{{ asset('images/products-1.png') }}';
              const itemSlug = item.slug || '';
              const productUrl = itemSlug ? `{{ url('products') }}/${itemSlug}` : '#';
              const itemTotal = itemPrice * itemQty;

              html += `
                <div class="dairy-offcanvas-item" data-cart-item-id="${item.id}">
                  <img src="${itemImage}" alt="${itemName}" class="dairy-offcanvas-img" onerror="this.src='{{ asset('images/products-1.png') }}'">
                  <div class="dairy-offcanvas-info">
                    <a href="${productUrl}" class="dairy-offcanvas-name">${itemName}</a>
                    <div class="dairy-offcanvas-qty">
                      <button onclick="updateCartQty(${item.id}, ${itemQty - 1})" aria-label="Decrease quantity">&#8722;</button>
                      <span>${itemQty}</span>
                      <button onclick="updateCartQty(${item.id}, ${itemQty + 1})" aria-label="Increase quantity">+</button>
                      <span style="color:#9ca3af;font-size:11px;margin-left:2px;">&#215; &#8377;${itemPrice.toFixed(0)}</span>
                    </div>
                  </div>
                  <div style="display:flex;flex-direction:column;align-items:flex-end;justify-content:space-between;flex-shrink:0;gap:6px;">
                    <button class="dairy-offcanvas-remove" onclick="removeFromCart(${item.id})" aria-label="Remove item">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                    <span style="font-weight:800;font-size:14px;color:#2f4a1e;">&#8377;${itemTotal.toFixed(0)}</span>
                  </div>
                </div>
              `;
            });
            cartBody.innerHTML = html;
            cartTotal.textContent = `â‚¹${total.toFixed(0)}`;
            cartFooter.style.display = 'block';
          }

          // Show offcanvas
          cartOverlay.classList.add('show');
          cartOffcanvas.classList.add('show');
          document.body.style.overflow = 'hidden';
        }

        function closeCartPanel() {
          cartOverlay.classList.remove('show');
          cartOffcanvas.classList.remove('show');
          document.body.style.overflow = '';
          // Reset to cart view for next open
          if (typeof _showCartView === 'function') _showCartView();
        }

        // Global functions for cart operations
        window.updateCartQty = function(productId, newQty) {
          if (window.DairyCart) {
            window.DairyCart.updateCartQuantity(productId, newQty);
            openCart(); // Refresh cart display
          }
        };

        window.removeFromCart = function(productId) {
          if (window.DairyCart) {
            window.DairyCart.removeFromCart(productId);
            openCart(); // Refresh cart display
          }
        };

        // Event listeners
        cartBtn.addEventListener('click', openCart);
        closeCart.addEventListener('click', closeCartPanel);
        cartOverlay.addEventListener('click', closeCartPanel);
        
        if (clearCartBtn) {
          clearCartBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your cart?')) {
              window.DairyCart.clearCart();
              closeCartPanel();
            }
          });
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape' && cartOffcanvas.classList.contains('show')) {
            closeCartPanel();
          }
        });
      })();

      // Wishlist Offcanvas Handler
      (function() {
        const wishlistBtn = document.getElementById('wishlistBtn');
        const wishlistOverlay = document.getElementById('wishlistOverlay');
        const wishlistOffcanvas = document.getElementById('wishlistOffcanvas');
        const closeWishlist = document.getElementById('closeWishlist');
        const wishlistBody = document.getElementById('wishlistBody');
        const wishlistFooter = document.getElementById('wishlistFooter');
        const clearWishlistBtn = document.getElementById('clearWishlistBtn');

        if (!wishlistBtn || !wishlistOffcanvas) return;

        function openWishlist() {
          if (!window.DairyCart) {
            console.error('DairyCart not loaded');
            return;
          }

          const wishlist = window.DairyCart.getWishlist();

          console.log('Opening wishlist with items:', wishlist);

          // Render wishlist items
          if (wishlist.length === 0) {
            wishlistBody.innerHTML = `
              <div class="dairy-offcanvas-empty">
                <i class="fa-solid fa-heart"></i>
                <p>Your wishlist is empty</p>
                <a href="{{ route('products') }}" class="dairy-offcanvas-btn">
                  <i class="fa-solid fa-store"></i> Browse Products
                </a>
              </div>
            `;
            wishlistFooter.style.display = 'none';
          } else {
            let html = '';
            wishlist.forEach(item => {
              // Ensure all values have defaults
              const itemName = item.name || 'Unknown Product';
              const itemPrice = parseFloat(item.price) || 0;
              const itemImage = item.image || '{{ asset('images/products-1.png') }}';
              const itemSlug = item.slug || '';
              const productUrl = itemSlug ? `{{ url('products') }}/${itemSlug}` : '#';

              html += `
                <div class="dairy-offcanvas-item" data-wishlist-item-id="${item.id}">
                  <img src="${itemImage}" alt="${itemName}" class="dairy-offcanvas-img" onerror="this.src='{{ asset('images/products-1.png') }}'">
                  <div class="dairy-offcanvas-info">
                    <a href="${productUrl}" class="dairy-offcanvas-name">${itemName}</a>
                    <div class="dairy-offcanvas-price">â‚¹${itemPrice.toFixed(0)}</div>
                    <button class="dairy-offcanvas-btn" onclick="moveToCart(${item.id})" style="margin-top: 8px; padding: 8px 12px; font-size: 13px;">
                      <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                    </button>
                  </div>
                  <button class="dairy-offcanvas-remove" onclick="removeFromWishlist(${item.id})" aria-label="Remove item">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              `;
            });
            wishlistBody.innerHTML = html;
            wishlistFooter.style.display = 'block';
          }

          // Show offcanvas
          wishlistOverlay.classList.add('show');
          wishlistOffcanvas.classList.add('show');
          document.body.style.overflow = 'hidden';
        }

        function closeWishlistPanel() {
          wishlistOverlay.classList.remove('show');
          wishlistOffcanvas.classList.remove('show');
          document.body.style.overflow = '';
        }

        // Global functions for wishlist operations
        window.removeFromWishlist = function(productId) {
          if (window.DairyCart) {
            window.DairyCart.removeFromWishlist(productId);
            openWishlist(); // Refresh wishlist display
          }
        };

        window.moveToCart = function(productId) {
          if (window.DairyCart) {
            const wishlist = window.DairyCart.getWishlist();
            const item = wishlist.find(i => i.id === productId);
            if (item) {
              window.DairyCart.addToCart({ ...item, quantity: 1 });
              window.DairyCart.removeFromWishlist(productId);
              openWishlist(); // Refresh wishlist display
            }
          }
        };

        // Event listeners
        wishlistBtn.addEventListener('click', openWishlist);
        closeWishlist.addEventListener('click', closeWishlistPanel);
        wishlistOverlay.addEventListener('click', closeWishlistPanel);
        
        if (clearWishlistBtn) {
          clearWishlistBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your wishlist?')) {
              window.DairyCart.clearWishlist();
              closeWishlistPanel();
            }
          });
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape' && wishlistOffcanvas.classList.contains('show')) {
            closeWishlistPanel();
          }
        });
      })();
    </script>
    
    @stack('scripts')

    <script>
    const PRODUCT_ORDER_URL   = '{{ route('payment.product.initiate') }}';
    const PRODUCT_CSRF        = '{{ csrf_token() }}';
    const IS_MEMBER_LOGGED_IN = {{ auth()->check() && auth()->user()->isMember() ? 'true' : 'false' }};
    const MEMBER_LOGIN_URL    = '{{ route('member.login') }}';

    // ── helpers to switch cart offcanvas between cart-view and checkout-view ──

    function _showCartView() {
      document.getElementById('cartHeader').style.display    = '';
      document.getElementById('checkoutHeader').style.display = 'none';
      document.getElementById('checkoutTabs').style.display  = 'none';
      document.getElementById('cartBody').style.display      = '';
      document.getElementById('cartFooter').style.display    = ''; // openCart() will correct this
      ['coStep1','coStep2','coStep3'].forEach(id => {
        document.getElementById(id).style.display = 'none';
      });
      document.getElementById('checkoutFooter').style.display = 'none';
    }

    function _showCheckoutStep(step) {
      document.getElementById('cartHeader').style.display    = 'none';
      document.getElementById('checkoutHeader').style.display = 'flex';
      document.getElementById('checkoutTabs').style.display  = 'block';
      document.getElementById('cartBody').style.display      = 'none';
      document.getElementById('cartFooter').style.display    = 'none';
      document.getElementById('checkoutFooter').style.display = 'block';

      ['coStep1','coStep2','coStep3'].forEach((id, i) => {
        document.getElementById(id).style.display = (i + 1 === step) ? '' : 'none';
      });

      // Update tab styles
      [1,2,3].forEach(n => {
        const tab = document.getElementById('coTab' + n);
        if (!tab) return;
        tab.style.color             = n === step ? '#2f4a1e' : '#9ca3af';
        tab.style.borderBottomColor = n === step ? '#2f4a1e' : 'transparent';
        tab.style.borderBottom      = n === step ? '2px solid #2f4a1e' : '2px solid transparent';
      });

      // Update checkout footer button
      const nextBtn = document.getElementById('checkoutNextBtn');
      if (!nextBtn) return;
      if (step === 1) {
        nextBtn.innerHTML = 'Continue <i class="fa-solid fa-arrow-right" style="margin-left:6px;"></i>';
        nextBtn.style.display = '';
      } else if (step === 2) {
        nextBtn.innerHTML = 'Review Order <i class="fa-solid fa-arrow-right" style="margin-left:6px;"></i>';
        nextBtn.style.display = '';
      } else {
        // Step 3: show pay button
        const total = window.DairyCart ? window.DairyCart.getCartTotal() : 0;
        nextBtn.innerHTML = '<i class="fa-solid fa-lock" style="margin-right:6px;"></i>Pay ₹' + total.toFixed(0);
        nextBtn.style.display = '';
      }

      // Update back button behaviour
      const backBtn = document.getElementById('checkoutBackBtn');
      if (backBtn) {
        backBtn.onclick = step > 1
          ? () => _goToCheckoutStep(step - 1)
          : () => { _showCartView(); };
      }

      // Update header title
      const titles = { 1: 'Order Summary', 2: 'Delivery Details', 3: 'Review & Pay' };
      const titleEl = document.getElementById('checkoutHeaderTitle');
      if (titleEl) titleEl.textContent = titles[step] || 'Checkout';
    }

    function _goToCheckoutStep(step) {
      if (step === 3) {
        // Validate step 2 before proceeding
        const name  = (document.getElementById('co_name')?.value || '').trim();
        const phone = (document.getElementById('co_phone')?.value || '').trim();
        const addr  = (document.getElementById('co_address')?.value || '').trim();
        if (!name || !phone || !addr) {
          _showCheckoutStep(2);
          return;
        }
        // Build review
        const cart  = window.DairyCart ? window.DairyCart.getCart() : [];
        const total = window.DairyCart ? window.DairyCart.getCartTotal() : 0;
        let html = `<div style="margin-bottom:10px;padding-bottom:10px;border-bottom:1px solid #e7e7e7;">
          <div style="font-size:12px;color:#6a7a63;margin-bottom:2px;">Deliver to</div>
          <div style="font-weight:700;color:#1f2a1a;font-size:13px;">${name} · ${phone}</div>
          <div style="color:#6a7a63;font-size:12px;">${addr}</div>
        </div>`;
        cart.forEach(item => {
          html += `<div style="display:flex;justify-content:space-between;padding:4px 0;font-size:13px;">
            <span style="color:#1f2a1a;">${item.name} × ${item.quantity}</span>
            <span style="font-weight:700;color:#2f4a1e;">₹${(item.price * item.quantity).toFixed(0)}</span>
          </div>`;
        });
        html += `<div style="display:flex;justify-content:space-between;padding-top:8px;font-weight:800;font-size:15px;border-top:1px solid #e7e7e7;margin-top:6px;">
          <span>Total</span><span style="color:#2f4a1e;">₹${total.toFixed(0)}</span>
        </div>`;
        document.getElementById('coReview').innerHTML = html;
        const errEl = document.getElementById('checkoutError');
        if (errEl) errEl.style.display = 'none';
      }
      _showCheckoutStep(step);
    }

    // Called by "Proceed to Payment" button in cart footer
    function openCheckoutModal() {
      if (!IS_MEMBER_LOGGED_IN) {
        const returnUrl = window.location.origin + window.location.pathname + '?checkout=1';
        window.location.href = MEMBER_LOGIN_URL + '?redirect=' + encodeURIComponent(returnUrl);
        return;
      }

      const cart  = window.DairyCart ? window.DairyCart.getCart() : [];
      const total = window.DairyCart ? window.DairyCart.getCartTotal() : 0;
      if (!cart.length) return;

      // Build step-1 order summary
      let html = '';
      cart.forEach(item => {
        html += `<div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #e7e7e7;">
          <img src="${item.image || ''}" onerror="this.style.display='none'"
               style="width:44px;height:44px;border-radius:8px;object-fit:cover;border:1px solid #e7e7e7;flex-shrink:0;">
          <div style="flex:1;min-width:0;">
            <div style="font-weight:600;color:#1f2a1a;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${item.name}</div>
            <div style="color:#6a7a63;font-size:12px;">Qty: ${item.quantity} × ₹${item.price.toFixed(0)}</div>
          </div>
          <div style="font-weight:800;color:#2f4a1e;font-size:14px;flex-shrink:0;">₹${(item.price * item.quantity).toFixed(0)}</div>
        </div>`;
      });
      html += `<div style="display:flex;justify-content:space-between;padding-top:12px;font-weight:800;font-size:15px;">
        <span style="color:#1f2a1a;">Total</span>
        <span style="color:#2f4a1e;">₹${total.toFixed(0)}</span>
      </div>`;
      document.getElementById('coSummary').innerHTML = html;

      // Pre-fill member details
      @auth
      if (!document.getElementById('co_name').value)  document.getElementById('co_name').value  = '{{ auth()->user()->name ?? '' }}';
      if (!document.getElementById('co_phone').value) document.getElementById('co_phone').value = '{{ auth()->user()->phone ?? '' }}';
      if (!document.getElementById('co_email').value) document.getElementById('co_email').value = '{{ auth()->user()->email ?? '' }}';
      @endauth

      _showCheckoutStep(1);

      // Make sure cart offcanvas is open
      const cartOverlay   = document.getElementById('cartOverlay');
      const cartOffcanvas = document.getElementById('cartOffcanvas');
      if (cartOverlay && !cartOverlay.classList.contains('show')) {
        cartOverlay.classList.add('show');
        cartOffcanvas.classList.add('show');
        document.body.style.overflow = 'hidden';
      }

      // Clean ?checkout=1 from URL
      const url = new URL(window.location.href);
      url.searchParams.delete('checkout');
      window.history.replaceState({}, '', url.toString());
    }

    // Checkout footer "Continue / Review / Pay" button
    function checkoutNext() {
      // Determine current step
      const steps = [1,2,3];
      let current = 1;
      steps.forEach(n => {
        const el = document.getElementById('coStep' + n);
        if (el && el.style.display !== 'none') current = n;
      });

      if (current === 1) {
        _goToCheckoutStep(2);
      } else if (current === 2) {
        const name  = (document.getElementById('co_name')?.value || '').trim();
        const phone = (document.getElementById('co_phone')?.value || '').trim();
        const addr  = (document.getElementById('co_address')?.value || '').trim();
        if (!name || !phone || !addr) {
          alert('Please fill in all required fields.');
          return;
        }
        _goToCheckoutStep(3);
      } else {
        _submitCheckout();
      }
    }

    function _submitCheckout() {
      const cart  = window.DairyCart ? window.DairyCart.getCart() : [];
      const btn   = document.getElementById('checkoutNextBtn');
      const errEl = document.getElementById('checkoutError');
      if (errEl) errEl.style.display = 'none';

      const origHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="margin-right:6px;"></i>Redirecting...';

      fetch(PRODUCT_ORDER_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': PRODUCT_CSRF },
        body: JSON.stringify({
          items:            cart,
          customer_name:    document.getElementById('co_name').value,
          customer_phone:   document.getElementById('co_phone').value,
          customer_email:   document.getElementById('co_email').value,
          delivery_address: document.getElementById('co_address').value,
        }),
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          window.DairyCart.clearCart();
          window.location.href = data.redirect_url;
        } else {
          if (errEl) { errEl.textContent = data.message || 'Payment failed. Please try again.'; errEl.style.display = 'block'; }
          btn.disabled = false;
          btn.innerHTML = origHtml;
        }
      })
      .catch(() => {
        if (errEl) { errEl.textContent = 'Network error. Please try again.'; errEl.style.display = 'block'; }
        btn.disabled = false;
        btn.innerHTML = origHtml;
      });
    }

    // Auto-open checkout after login redirect (?checkout=1)
    document.addEventListener('DOMContentLoaded', function() {
      if (new URLSearchParams(window.location.search).get('checkout') === '1' && IS_MEMBER_LOGGED_IN) {
        setTimeout(function() {
          // Open cart offcanvas first, then switch to checkout
          const cartOverlay   = document.getElementById('cartOverlay');
          const cartOffcanvas = document.getElementById('cartOffcanvas');
          if (cartOverlay && cartOffcanvas) {
            cartOverlay.classList.add('show');
            cartOffcanvas.classList.add('show');
            document.body.style.overflow = 'hidden';
          }
          openCheckoutModal();
        }, 300);
      }
    });
    </script>
</body>
</html>
