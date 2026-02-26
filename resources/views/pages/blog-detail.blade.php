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
        --brand: #263d18;
        --accent: #f1cc24;
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
        background-image: url("https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&q=80&w=1600");
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
        max-width: 1250px;
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
        margin-top: 40px;
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

    /* Sidebar */
    .sidebar-card {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 14px 36px rgba(0, 0, 0, 0.08);
    }

    .sidebar-card h3 {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--brand);
        margin: 0 0 20px 0;
    }

    /* Related Posts in Sidebar */
    .related-post {
        display: flex;
        gap: 12px;
        text-decoration: none;
        color: inherit;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #eee;
    }

    .related-post:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .related-post-img {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        background-size: cover;
        background-position: center;
        flex-shrink: 0;
    }

    .related-post-content h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--brand);
        margin: 0 0 6px 0;
        line-height: 1.3;
        transition: color 0.3s;
    }

    .related-post:hover h4 {
        color: var(--accent);
    }

    .related-post-meta {
        font-size: 0.8rem;
        color: #999;
        font-weight: 600;
    }

    /* Inquiry Form */
    .inquiry-form {
        display: grid;
        gap: 14px;
    }

    .form-field {
        position: relative;
    }

    .form-field i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--brand);
        opacity: 0.7;
    }

    .form-field input,
    .form-field textarea {
        width: 100%;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.12);
        outline: none;
        font-weight: 600;
        font-family: inherit;
        background: #fff;
        padding-left: 42px;
    }

    .form-field input {
        height: 46px;
        padding-right: 14px;
    }

    .form-field textarea {
        min-height: 100px;
        padding: 12px 14px 12px 42px;
        resize: vertical;
    }

    .form-field input:focus,
    .form-field textarea:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(241, 204, 36, 0.16);
    }

    .submit-btn {
        width: 100%;
        height: 48px;
        border: none;
        border-radius: 12px;
        background: #d85f0f;
        color: #fff;
        font-weight: 900;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .submit-btn:hover {
        background: var(--accent);
        color: var(--brand);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .alert {
        padding: 12px;
        border-radius: 12px;
        font-weight: 650;
        font-size: 0.9rem;
        display: none;
    }

    .alert-success {
        background: #d1fae5;
        border: 1px solid #10b981;
        color: #065f46;
    }

    .alert-error {
        background: #fee2e2;
        border: 1px solid #ef4444;
        color: #991b1b;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .blog-detail-container {
            padding: 40px 20px;
        }

        .article-content {
            font-size: 1rem;
        }

        .blog-detail-hero {
            padding: 64px 0 56px;
        }

        .blog-meta {
            flex-direction: column;
            gap: 10px;
        }

        .col-md-8,
        .col-md-4 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        .sidebar-card {
            margin-top: 30px;
        }
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
    <div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -12px;">
        <!-- Main Content - col-md-8 -->
        <div class="col-md-8" style="flex: 0 0 66.666667%; max-width: 66.666667%; padding: 0 12px;">
            <article>
                <!-- Featured Image -->
                @if($blog->image)
                <div class="featured-image" style="margin-bottom: 30px;">
                    <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" style="width: 100%; height: auto; border-radius: 18px; box-shadow: 0 14px 36px rgba(0, 0, 0, 0.12);">
                </div>
                @endif

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
        </div>

        <!-- Sidebar - col-md-4 -->
        <div class="col-md-4" style="flex: 0 0 33.333333%; max-width: 33.333333%; padding: 0 12px;">
            <!-- Related Posts -->
            @if($relatedBlogs && $relatedBlogs->count() > 0)
            <div class="sidebar-card">
                <h3>Related Posts</h3>
                @foreach($relatedBlogs as $related)
                    <a href="{{ route('blog.detail', $related->slug) }}" class="related-post">
                        <div class="related-post-img" style="background-image: url('{{ $related->image ? asset($related->image) : 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&q=80&w=200' }}');"></div>
                        <div class="related-post-content">
                            <h4>{{ $related->title }}</h4>
                            <div class="related-post-meta">
                                <i class="fa-solid fa-calendar"></i> {{ $related->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @endif

            <!-- Inquiry Form -->
            <div class="sidebar-card">
                <h3>Send Inquiry</h3>
                <form class="inquiry-form" id="inquiryForm" action="{{ route('contact.submit') }}" method="post">
                    @csrf
                    <input type="hidden" name="subject" value="Blog Inquiry: {{ $blog->title }}">
                    
                    <div class="form-field">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="name" placeholder="Your Name" required>
                    </div>

                    <div class="form-field">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>

                    <div class="form-field">
                        <i class="fa-solid fa-phone"></i>
                        <input type="tel" name="phone" placeholder="Phone Number" required>
                    </div>

                    <div class="form-field">
                        <i class="fa-solid fa-message"></i>
                        <textarea name="message" placeholder="Your Message" required></textarea>
                    </div>

                    <div class="alert alert-success" id="successAlert">
                        <i class="fa-solid fa-circle-check"></i> <span id="successText"></span>
                    </div>

                    <div class="alert alert-error" id="errorAlert">
                        <i class="fa-solid fa-circle-xmark"></i> <span id="errorText"></span>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fa-solid fa-paper-plane"></i> <span id="btnText">Submit Inquiry</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const inquiryForm = document.getElementById('inquiryForm');
    if (!inquiryForm) return;

    inquiryForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');
        const successText = document.getElementById('successText');
        const errorText = document.getElementById('errorText');

        // Hide alerts
        successAlert.style.display = 'none';
        errorAlert.style.display = 'none';

        // Disable button
        submitBtn.disabled = true;
        btnText.textContent = 'Sending...';

        // Send request
        fetch(inquiryForm.action, {
            method: 'POST',
            body: new FormData(inquiryForm),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => { throw data; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                successText.textContent = data.message;
                successAlert.style.display = 'block';
                inquiryForm.reset();
                
                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 5000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorText.textContent = error.message || 'Something went wrong. Please try again.';
            errorAlert.style.display = 'block';
        })
        .finally(() => {
            submitBtn.disabled = false;
            btnText.textContent = 'Submit Inquiry';
        });
    });
})();
</script>
@endsection
