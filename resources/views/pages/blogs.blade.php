@extends('layouts.public')

@section('title', 'Farm Life Stories - Nulac')

@section('content')
<style>
    :root {
        --dark-green: #1B3022;
        --soft-cream: #F9F8F3;
        --accent-gold: #C5A059;
        --white: #ffffff;
        --text-body: #4A4A4A;
    }

    /* HERO */
    .blog-hero {
        position: relative;
        padding: 78px 0 66px;
        overflow: hidden;
        background: #0f130e;
    }

    .blog-hero-bg {
        position: absolute;
        inset: 0;
        background-image: url("https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&q=80&w=1600");
        background-size: cover;
        background-position: center;
        transform: scale(1.04);
        filter: saturate(1.03);
    }

    .blog-hero-overlay {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 18% 20%, rgba(241, 204, 36, .22), transparent 55%),
            radial-gradient(circle at 82% 70%, rgba(38, 61, 24, .35), transparent 60%),
            linear-gradient(180deg, rgba(0, 0, 0, .62), rgba(0, 0, 0, .70));
    }

    .blog-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: center;
        max-width: 1250px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .blog-hero-content {
        text-align: center;
        max-width: 940px;
        color: #fff;
    }

    .blog-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 999px;
        font-weight: 900;
        letter-spacing: .9px;
        text-transform: uppercase;
        font-size: 12px;
        background: rgba(241, 204, 36, .14);
        border: 1px solid rgba(241, 204, 36, .28);
        color: #fff;
    }

    .blog-hero-content h1 {
        margin: 14px 0 12px;
        font-size: clamp(28px, 4vw, 48px);
        font-weight: 950;
        line-height: 1.05;
        letter-spacing: -.8px;
    }

    .blog-hero-content p {
        margin: 0 auto 18px;
        max-width: 780px;
        color: rgba(255, 255, 255, .86);
        font-weight: 650;
        line-height: 1.8;
    }

    .blog-container {
        max-width: 1240px;
        margin: 0 auto;
        padding: 80px 20px;
    }

    /* Perfectly Aligned 3-Column Grid */
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
    }

    .post-card {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .img-wrapper {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        border-radius: 24px;
        background-color: var(--soft-cream);
    }

    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .post-card:hover .img-wrapper img {
        transform: scale(1.08);
    }

    /* Floating Info Badge */
    .category-pill {
        position: absolute;
        top: 20px;
        left: 20px;
        background: var(--white);
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        color: var(--dark-green);
    }

    .post-content {
        padding: 25px 5px;
        transition: transform 0.4s ease;
    }

    .post-date {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--accent-gold);
        margin-bottom: 10px;
        display: block;
    }

    .post-title {
        font-size: 1.4rem;
        font-weight: 800;
        line-height: 1.3;
        margin: 0 0 12px 0;
        transition: color 0.3s ease;
        color: var(--dark-green);
    }

    .post-card:hover .post-title {
        color: var(--accent-gold);
    }

    .post-excerpt {
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--text-body);
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .read-link {
        margin-top: 15px;
        display: inline-block;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--dark-green);
    }

    .read-link::after {
        content: ' →';
        transition: margin-left 0.3s ease;
    }

    .post-card:hover .read-link::after {
        margin-left: 8px;
    }

    .no-blogs {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-body);
    }

    .no-blogs h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: var(--dark-green);
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 60px;
        display: flex;
        justify-content: center;
    }

    /* Responsive Fixes */
    @media (max-width: 1024px) {
        .blog-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .blog-grid { grid-template-columns: 1fr; }
        .img-wrapper { aspect-ratio: 4 / 3; }
        .blog-container { padding: 40px 20px; }
        .blog-hero { padding: 64px 0 56px; }
    }
</style>

<!-- HERO -->
<section class="blog-hero">
    <div class="blog-hero-bg"></div>
    <div class="blog-hero-overlay"></div>
    <div class="blog-hero-inner">
        <div class="blog-hero-content">
            <span class="blog-kicker"><i class="fa-solid fa-pen-to-square"></i> Blogs</span>
            <h1>Farm Life Stories</h1>
            <p>A collection of honest thoughts on purity, tradition, and wellness.</p>
        </div>
    </div>
</section>

<div class="blog-container">
    @if($blogs->count() > 0)
        <main class="blog-grid">
            @foreach($blogs as $blog)
                <a href="{{ route('blog.detail', $blog->slug) }}" class="post-card">
                    <div class="img-wrapper">
                        @if($blog->tag)
                            <span class="category-pill">{{ $blog->tag }}</span>
                        @endif
                        <img src="{{ $blog->image ? asset($blog->image) : 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $blog->title }}">
                    </div>
                    <div class="post-content">
                        <span class="post-date">{{ strtoupper($blog->created_at->format('F d, Y')) }}</span>
                        <h3 class="post-title">{{ $blog->title }}</h3>
                        @if($blog->excerpt)
                            <p class="post-excerpt">{{ $blog->excerpt }}</p>
                        @endif
                        <span class="read-link">Read Story</span>
                    </div>
                </a>
            @endforeach
        </main>

        @if($blogs->hasPages())
            <div class="pagination-wrapper">
                {{ $blogs->links() }}
            </div>
        @endif
    @else
        <div class="no-blogs">
            <h3>No Stories Yet</h3>
            <p>Check back soon for inspiring stories from our farm.</p>
        </div>
    @endif
</div>
@endsection
