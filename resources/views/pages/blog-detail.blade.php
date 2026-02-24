@extends('layouts.public')

@section('title', $blog->title . ' - Nulac')

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
    .blog-detail-hero {
        position: relative;
        padding: 78px 0 66px;
        overflow: hidden;
        background: #0f130e;
    }

    .blog-detail-hero-bg {
        position: absolute;
        inset: 0;
        background-image: url("{{ $blog->image ? asset($blog->image) : 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&q=80&w=1600' }}");
        background-size: cover;
        background-position: center;
        transform: scale(1.04);
        filter: saturate(1.03);
    }

    .blog-detail-hero-overlay {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 18% 20%, rgba(241, 204, 36, .22), transparent 55%),
            radial-gradient(circle at 82% 70%, rgba(38, 61, 24, .35), transparent 60%),
            linear-gradient(180deg, rgba(0, 0, 0, .62), rgba(0, 0, 0, .70));
    }

    .blog-detail-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: center;
        max-width: 1250px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .blog-detail-hero-content {
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

    .blog-detail-hero-content h1 {
        margin: 14px 0 12px;
        font-size: clamp(28px, 4vw, 42px);
        font-weight: 950;
        line-height: 1.05;
        letter-spacing: -.8px;
    }

    .blog-detail-hero-content p {
        margin: 0 auto 18px;
        max-width: 780px;
        color: rgba(255, 255, 255, .86);
        font-weight: 650;
        line-height: 1.8;
        font-size: 1.1rem;
    }

    .blog-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        justify-content: center;
        margin-top: 20px;
    }

    .blog-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .10);
        border: 1px solid rgba(255, 255, 255, .16);
        color: rgba(255, 255, 255, .92);
        font-weight: 800;
        font-size: 13px;
        backdrop-filter: blur(6px);
    }

    .blog-detail-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--dark-green);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 40px;
        transition: color 0.3s;
    }

    .back-link:hover {
        color: var(--accent-gold);
    }

    .back-link::before {
        content: '←';
        font-size: 1.2rem;
    }

    /* Article Content */
    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--text-body);
        margin-bottom: 60px;
    }

    .article-content p {
        margin-bottom: 1.5rem;
    }

    .article-content h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-green);
        margin: 2.5rem 0 1rem 0;
    }

    .article-content h3 {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--dark-green);
        margin: 2rem 0 1rem 0;
    }

    .article-content ul,
    .article-content ol {
        margin: 1.5rem 0;
        padding-left: 2rem;
    }

    .article-content li {
        margin-bottom: 0.5rem;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 16px;
        margin: 2rem 0;
    }

    .article-content blockquote {
        border-left: 4px solid var(--accent-gold);
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: var(--dark-green);
    }

    /* Share Section */
    .share-section {
        padding: 30px 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        margin-bottom: 60px;
    }

    .share-section h4 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dark-green);
        margin: 0 0 15px 0;
    }

    .share-buttons {
        display: flex;
        gap: 10px;
    }

    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--soft-cream);
        color: var(--dark-green);
        text-decoration: none;
        transition: all 0.3s;
    }

    .share-btn:hover {
        background: var(--accent-gold);
        color: var(--white);
        transform: translateY(-2px);
    }

    /* Related Posts */
    .related-section {
        margin-top: 80px;
    }

    .related-section h3 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark-green);
        margin-bottom: 40px;
        text-align: center;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .related-card {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .related-img {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        border-radius: 16px;
        background-color: var(--soft-cream);
        margin-bottom: 15px;
    }

    .related-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .related-card:hover .related-img img {
        transform: scale(1.08);
    }

    .related-tag {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--white);
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .related-title {
        font-size: 1.1rem;
        font-weight: 700;
        line-height: 1.3;
        color: var(--dark-green);
        margin: 0;
        transition: color 0.3s;
    }

    .related-card:hover .related-title {
        color: var(--accent-gold);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .related-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .blog-detail-container { padding: 40px 20px; }
        .article-content { font-size: 1rem; }
        .related-grid { grid-template-columns: 1fr; }
        .related-img { aspect-ratio: 4 / 3; }
        .blog-detail-hero { padding: 64px 0 56px; }
        .blog-meta { flex-direction: column; gap: 10px; }
    }
</style>

<!-- HERO -->
<section class="blog-detail-hero">
    <div class="blog-detail-hero-bg"></div>
    <div class="blog-detail-hero-overlay"></div>
    <div class="blog-detail-hero-inner">
        <div class="blog-detail-hero-content">
            <span class="blog-kicker">
                @if($blog->tag)
                    <i class="fa-solid fa-tag"></i> {{ $blog->tag }}
                @else
                    <i class="fa-solid fa-pen-to-square"></i> Blog
                @endif
            </span>
            <h1>{{ $blog->title }}</h1>
            @if($blog->excerpt)
                <p>{{ $blog->excerpt }}</p>
            @endif
            <div class="blog-meta">
                <span class="blog-meta-item">
                    <i class="fa-solid fa-calendar"></i>
                    {{ $blog->created_at->format('F d, Y') }}
                </span>
                <span class="blog-meta-item">
                    <i class="fa-solid fa-clock"></i>
                    {{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read
                </span>
            </div>
        </div>
    </div>
</section>

<div class="blog-detail-container">
    <a href="{{ route('blogs') }}" class="back-link">Back to Stories</a>

    <article>
        <div class="article-content">
            {!! $blog->content !!}
        </div>

        <div class="share-section">
            <h4>Share this story</h4>
            <div class="share-buttons">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.detail', $blog->slug)) }}" target="_blank" class="share-btn" title="Share on Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.detail', $blog->slug)) }}&text={{ urlencode($blog->title) }}" target="_blank" class="share-btn" title="Share on Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.detail', $blog->slug)) }}&title={{ urlencode($blog->title) }}" target="_blank" class="share-btn" title="Share on LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . route('blog.detail', $blog->slug)) }}" target="_blank" class="share-btn" title="Share on WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </article>

    @if($relatedBlogs->count() > 0)
        <section class="related-section">
            <h3>More Stories</h3>
            <div class="related-grid">
                @foreach($relatedBlogs as $related)
                    <a href="{{ route('blog.detail', $related->slug) }}" class="related-card">
                        <div class="related-img">
                            @if($related->tag)
                                <span class="related-tag">{{ $related->tag }}</span>
                            @endif
                            <img src="{{ $related->image ? asset($related->image) : 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $related->title }}">
                        </div>
                        <h4 class="related-title">{{ $related->title }}</h4>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
