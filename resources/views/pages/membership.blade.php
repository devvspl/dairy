@extends('layouts.public')

@section('title', 'Membership')
@section('meta_description', 'Join our membership program')

@section('content')

<!-- contact section -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<style>

#mpgMembershipPage{
  --bg:#ffffff;
  --soft:#f6f8f2;
  --soft2:#fbfcf8;
  --text:#1f2a1a;
  --muted:#5c6b55;
  --brand:#263d18;
  --accent:#f1cc24;
  --border:rgba(0,0,0,.10);
  --shadow:0 18px 60px rgba(0,0,0,.08);
  --shadow2:0 26px 80px rgba(0,0,0,.12);
  --radius:22px;

  color:var(--text);
  background:var(--bg);
  font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;
}

#mpgMembershipPage *{ box-sizing:border-box; }
#mpgMembershipPage img{ max-width:100%; display:block; }

#mpgMembershipPage .container{
  max-width:1250px;
  margin:0 auto;
  padding:0 24px;
}

/* Head */
#mpgMembershipPage .mpg-sec-head{
  text-align:center;
  max-width:860px;
  margin:0 auto 28px;
}
#mpgMembershipPage .mpg-sec-head-left{ text-align:left; margin-left:0; }

#mpgMembershipPage .mpg-kicker{
  display:inline-flex;
  align-items:center;
  gap:10px;
  padding:8px 12px;
  border-radius:999px;
  font-weight:900;
  letter-spacing:.9px;
  text-transform:uppercase;
  font-size:12px;
  color:var(--brand);
  background:rgba(38,61,24,.07);
  border:1px solid rgba(38,61,24,.12);
}
#mpgMembershipPage .mpg-sec-head h2{
  margin:12px 0 8px;
  font-size:clamp(26px,3.2vw,42px);
  font-weight:950;
  letter-spacing:-.4px;
  color:var(--brand);
  line-height:1.08;
}
#mpgMembershipPage .mpg-sec-head p{
  margin:0;
  color:var(--muted);
  font-weight:650;
  line-height:1.75;
}

/* Buttons */
#mpgMembershipPage .mpg-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  padding:14px 18px;
  border-radius:16px;
  font-weight:900;
  text-decoration:none;
  border:1px solid var(--border);
  box-shadow:0 14px 34px rgba(0,0,0,.08);
  transition:220ms ease;
  cursor:pointer;
  white-space:nowrap;
}
#mpgMembershipPage .mpg-btn i{ font-size:16px; }

#mpgMembershipPage .mpg-btn-primary{
  background:var(--brand);
  color:#fff;
  border-color:rgba(38,61,24,.25);
}
#mpgMembershipPage .mpg-btn-primary:hover{
  transform:translateY(-2px);
  background:var(--accent);
  color:var(--text);
  border-color:rgba(241,204,36,.9);
  box-shadow:0 18px 44px rgba(0,0,0,.12);
}
#mpgMembershipPage .mpg-btn-outline{
  background:#fff;
  color:var(--brand);
}
#mpgMembershipPage .mpg-btn-outline:hover{
  transform:translateY(-2px);
  border-color:rgba(241,204,36,.85);
  box-shadow:0 18px 44px rgba(0,0,0,.12);
}
#mpgMembershipPage .mpg-btn-block{ width:100%; }

#mpgMembershipPage .mpg-card{
  background:#fff;
  border:1px solid rgba(0,0,0,.08);
  box-shadow:var(--shadow);
  border-radius:var(--radius);
}

/* HERO */
#mpgMembershipPage .mpg-hero{
  position:relative;
  padding:78px 0 66px;
  overflow:hidden;
  background:#0f130e;
}
#mpgMembershipPage .mpg-hero-bg{
  position:absolute; inset:0;
  background-image:url("images/membership-banner.webp");
  background-size:cover;
  background-position:center;
  transform:scale(1.04);
}
#mpgMembershipPage .mpg-hero-overlay{
  position:absolute; inset:0;
  background:
    radial-gradient(circle at 18% 20%, rgba(241,204,36,.22), transparent 55%),
    radial-gradient(circle at 82% 70%, rgba(38,61,24,.35), transparent 60%),
    linear-gradient(180deg, rgba(0,0,0,.62), rgba(0,0,0,.70));
}
#mpgMembershipPage .mpg-hero-inner{
  position:relative; z-index:2;
  display:flex; justify-content:center;
}
#mpgMembershipPage .mpg-hero-content{
  text-align:center;
  max-width:980px;
  color:#fff;
}
#mpgMembershipPage .mpg-hero-content .mpg-kicker{
  color:#fff;
  background:rgba(241,204,36,.14);
  border:1px solid rgba(241,204,36,.28);
}
#mpgMembershipPage .mpg-hero-content h1{
  margin:14px 0 12px;
  font-size:clamp(28px,4vw,35px);
  font-weight:950;
  line-height:1.05;
  letter-spacing:-.8px;
}
#mpgMembershipPage .mpg-hero-content p{
  margin:0 auto 18px;
  max-width:780px;
  color:rgba(255,255,255,.86);
  font-weight:650;
  line-height:1.8;
}
#mpgMembershipPage .mpg-hero-actions{
  display:flex;
  gap:12px;
  justify-content:center;
  flex-wrap:wrap;
  margin-top:6px;
}
#mpgMembershipPage .mpg-hero-badges{
  margin-top:22px;
  display:flex;
  flex-wrap:wrap;
  gap:10px;
  justify-content:center;
}
#mpgMembershipPage .mpg-badge{
  display:inline-flex;
  align-items:center;
  gap:10px;
  padding:10px 12px;
  border-radius:999px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.16);
  color:rgba(255,255,255,.92);
  font-weight:800;
  font-size:13px;
  backdrop-filter: blur(6px);
}

/* PLANS */
#mpgMembershipPage .mpg-plans{
  padding:72px 0;
  background:
    radial-gradient(circle at 18% 20%, rgba(241,204,36,0.12), transparent 55%),
    radial-gradient(circle at 82% 70%, rgba(38,61,24,0.10), transparent 58%),
    linear-gradient(180deg, #ffffff, #f6f8f2);
}
#mpgMembershipPage .mpg-plans-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:18px;
  align-items:stretch;
}
#mpgMembershipPage .mpg-plan{
  padding:22px;
  border-radius:22px;
  background:#fff;
  border:1px solid rgba(0,0,0,.08);
  box-shadow:var(--shadow);
  transition:220ms ease;
  position:relative;
  overflow:hidden;
}
#mpgMembershipPage .mpg-plan:hover{
  transform:translateY(-7px);
  border-color:rgba(241,204,36,.65);
  box-shadow:var(--shadow2);
}
#mpgMembershipPage .mpg-plan-top{
  display:flex;
  justify-content:space-between;
  gap:12px;
  align-items:flex-start;
}
#mpgMembershipPage .mpg-plan-badge{
  display:inline-flex;
  gap:8px;
  align-items:center;
  padding:8px 10px;
  border-radius:999px;
  font-weight:900;
  font-size:12px;
  background:rgba(38,61,24,.07);
  color:var(--brand);
  border:1px solid rgba(38,61,24,.12);
}
#mpgMembershipPage .mpg-plan h3{
  margin:12px 0 6px;
  font-weight:950;
  color:var(--brand);
}
#mpgMembershipPage .mpg-plan p{
  margin:0 0 14px;
  color:var(--muted);
  font-weight:650;
  line-height:1.7;
}
#mpgMembershipPage .mpg-price{
  display:flex;
  align-items:flex-end;
  gap:8px;
  margin:10px 0 14px;
}
#mpgMembershipPage .mpg-price strong{
  font-size:34px;
  color:var(--brand);
  font-weight:950;
  line-height:1;
}
#mpgMembershipPage .mpg-price span{
  color:var(--muted);
  font-weight:750;
  font-size:13px;
  padding-bottom:3px;
}
#mpgMembershipPage .mpg-divider{
  height:1px;
  background:rgba(0,0,0,.08);
  margin:14px 0;
}
#mpgMembershipPage .mpg-list{
  display:grid;
  gap:10px;
  margin:0 0 16px;
  padding:0;
  list-style:none;
}
#mpgMembershipPage .mpg-list li{
  display:flex;
  align-items:flex-start;
  gap:10px;
  color:var(--text);
  font-weight:700;
  line-height:1.55;
}
#mpgMembershipPage .mpg-list i{
  margin-top:3px;
  color:#7a5b00;
  background:rgba(241,204,36,.18);
  width:28px; height:28px;
  border-radius:10px;
  display:flex;
  align-items:center;
  justify-content:center;
  flex:0 0 28px;
}

/* Featured plan */
#mpgMembershipPage .mpg-plan-featured{
  border-color:rgba(241,204,36,.55);
  box-shadow:var(--shadow2);
  transform:translateY(-6px);
}
#mpgMembershipPage .mpg-plan-featured:before{
  content:"";
  position:absolute; inset:-2px;
  background:linear-gradient(120deg, rgba(241,204,36,.20), transparent 45%, rgba(38,61,24,.14));
  opacity:1;
}
#mpgMembershipPage .mpg-plan-featured > *{ position:relative; z-index:1; }

/* BENEFITS */
#mpgMembershipPage .mpg-benefits{
  padding:72px 0;
  background:#fff;
}
#mpgMembershipPage .mpg-ben-grid{
  display:grid;
  grid-template-columns:1.05fr .95fr;
  gap:18px;
  align-items:start;
}
#mpgMembershipPage .mpg-ben-left{
  padding:22px;
}
#mpgMembershipPage .mpg-ben-left h3{
  margin:10px 0 8px;
  color:var(--brand);
  font-weight:950;
}
#mpgMembershipPage .mpg-ben-left p{
  margin:0 0 14px;
  color:var(--muted);
  font-weight:650;
  line-height:1.75;
}

#mpgMembershipPage .mpg-checkgrid{ display:grid; gap:12px; margin-top:12px; }
#mpgMembershipPage .mpg-check{
  display:flex;
  gap:12px;
  align-items:flex-start;
  padding:14px;
  border-radius:18px;
  background:linear-gradient(180deg,#fff,var(--soft2));
  border:1px solid rgba(0,0,0,.06);
  transition:220ms ease;
}
#mpgMembershipPage .mpg-check:hover{
  transform:translateY(-2px);
  border-color:rgba(241,204,36,.55);
  box-shadow:0 18px 44px rgba(0,0,0,.08);
}
#mpgMembershipPage .mpg-check-ico{
  width:40px; height:40px;
  border-radius:14px;
  display:flex; align-items:center; justify-content:center;
  background:rgba(241,204,36,.18);
  color:#7a5b00;
  flex:0 0 40px;
}
#mpgMembershipPage .mpg-check strong{
  display:block;
  font-weight:900;
  margin-bottom:2px;
}
#mpgMembershipPage .mpg-check span{
  display:block;
  color:var(--muted);
  font-weight:650;
  line-height:1.6;
  font-size:14px;
}

/* Side box */
#mpgMembershipPage .mpg-ben-side{
  padding:22px;
  background:
    radial-gradient(circle at 20% 20%, rgba(241,204,36,0.16), transparent 55%),
    linear-gradient(180deg,#ffffff,#f6f8f2);
}
#mpgMembershipPage .mpg-ben-side h3{
  margin:10px 0 8px;
  font-weight:950;
  color:var(--brand);
}
#mpgMembershipPage .mpg-ben-side p{
  margin:0 0 14px;
  color:var(--muted);
  font-weight:650;
  line-height:1.75;
}
#mpgMembershipPage .mpg-mini{
  display:grid;
  gap:10px;
  margin:14px 0 16px;
}
#mpgMembershipPage .mpg-mini .mpg-mini-row{
  display:flex;
  gap:10px;
  align-items:center;
  padding:12px 12px;
  border-radius:16px;
  background:#fff;
  border:1px solid rgba(0,0,0,.06);
}
#mpgMembershipPage .mpg-mini .mpg-mini-row i{
  width:34px; height:34px;
  border-radius:12px;
  display:flex; align-items:center; justify-content:center;
  background:rgba(38,61,24,.08);
  color:var(--brand);
}

/* STEPS */
#mpgMembershipPage .mpg-steps{
  padding:72px 0;
  background:
    radial-gradient(circle at 18% 20%, rgba(241,204,36,0.10), transparent 55%),
    linear-gradient(180deg, #ffffff, #f6f8f2);
}
#mpgMembershipPage .mpg-steps-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:18px;
}
#mpgMembershipPage .mpg-step{
  padding:20px;
  border-radius:22px;
  background:#fff;
  border:1px solid rgba(0,0,0,.08);
  box-shadow:var(--shadow);
  transition:220ms ease;
}
#mpgMembershipPage .mpg-step:hover{
  transform:translateY(-6px);
  border-color:rgba(241,204,36,.65);
  box-shadow:var(--shadow2);
}
#mpgMembershipPage .mpg-step .mpg-step-no{
  width:44px; height:44px;
  border-radius:18px;
  display:flex; align-items:center; justify-content:center;
  background:rgba(241,204,36,.18);
  color:#7a5b00;
  font-weight:950;
  margin-bottom:10px;
}
#mpgMembershipPage .mpg-step h4{
  margin:0 0 6px;
  font-weight:950;
  color:var(--brand);
}
#mpgMembershipPage .mpg-step p{
  margin:0;
  color:var(--muted);
  font-weight:650;
  line-height:1.7;
}

/* FAQ */
#mpgMembershipPage .mpg-faq{
  padding:72px 0;
  background:#fff;
}
#mpgMembershipPage .mpg-faq-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:18px;
  align-items:start;
}
#mpgMembershipPage .mpg-faq-col{ display:grid; gap:12px; }
#mpgMembershipPage .mpg-faq-item{
  border-radius:18px;
  border:1px solid rgba(0,0,0,.08);
  background:#fff;
  overflow:hidden;
  box-shadow:0 16px 46px rgba(0,0,0,.06);
}
#mpgMembershipPage .mpg-faq-q{
  width:100%;
  text-align:left;
  padding:16px;
  border:0;
  background:linear-gradient(180deg,#fff,#fbfcf8);
  cursor:pointer;
  font-weight:900;
  color:var(--brand);
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:14px;
}
#mpgMembershipPage .mpg-faq-q:hover{ background:rgba(241,204,36,0.10); }
#mpgMembershipPage .mpg-faq-ico{
  width:36px; height:36px;
  border-radius:12px;
  display:inline-flex;
  align-items:center; justify-content:center;
  background:rgba(38,61,24,.07);
  color:var(--brand);
  transition:200ms ease;
  flex:0 0 36px;
}
#mpgMembershipPage .mpg-faq-a{
  display:none;
  padding:0 16px 16px;
  color:var(--muted);
  font-weight:650;
  line-height:1.75;
  border-top:1px solid rgba(0,0,0,.06);
}
#mpgMembershipPage .mpg-faq-item.mpg-open{
  border-color:rgba(241,204,36,.65);
  box-shadow:0 22px 62px rgba(0,0,0,.10);
}
#mpgMembershipPage .mpg-faq-item.mpg-open .mpg-faq-a{ display:block; }
#mpgMembershipPage .mpg-faq-item.mpg-open .mpg-faq-ico{
  transform:rotate(180deg);
  background:rgba(241,204,36,.18);
  color:#7a5b00;
}

/* CTA form */
#mpgMembershipPage .mpg-cta{
  padding:72px 0 84px;
  background:
    radial-gradient(circle at 18% 20%, rgba(241,204,36,0.14), transparent 55%),
    radial-gradient(circle at 82% 70%, rgba(38,61,24,0.10), transparent 58%),
    linear-gradient(180deg, #ffffff, #f6f8f2);
}
#mpgMembershipPage .mpg-cta-box{
  border-radius:26px;
  border:1px solid rgba(0,0,0,.08);
  box-shadow:0 22px 70px rgba(0,0,0,.10);
  background:linear-gradient(180deg,#ffffff,#f7f9f4);
  padding:26px;
  display:grid;
  grid-template-columns:1.05fr .95fr;
  gap:18px;
  align-items:center;
}
#mpgMembershipPage .mpg-cta-left h3{
  margin:10px 0 6px;
  font-weight:950;
  color:var(--brand);
  font-size:clamp(22px,2.8vw,34px);
}
#mpgMembershipPage .mpg-cta-left p{
  margin:0;
  color:var(--muted);
  font-weight:650;
  line-height:1.75;
}
#mpgMembershipPage .mpg-form{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:12px;
}
#mpgMembershipPage .mpg-field{ position:relative; }
#mpgMembershipPage .mpg-field i{
  position:absolute;
  left:12px; top:50%;
  transform:translateY(-50%);
  color:var(--brand);
  opacity:.9;
}
#mpgMembershipPage .mpg-field input,
#mpgMembershipPage .mpg-field select{
  width:100%;
  height:48px;
  border-radius:14px;
  border:1px solid rgba(0,0,0,.12);
  padding:0 12px 0 40px;
  outline:none;
  font-weight:650;
  font-family:inherit;
  background:#fff;
}
#mpgMembershipPage .mpg-field input:focus,
#mpgMembershipPage .mpg-field select:focus{
  border-color:var(--accent);
  box-shadow:0 0 0 4px rgba(241,204,36,.16);
}
#mpgMembershipPage .mpg-submit{
  grid-column:1/-1;
  height:50px;
  border-radius:14px;
  width:100%;
}

/* Responsive */
@media (max-width:980px){
  #mpgMembershipPage .mpg-plans-grid{ grid-template-columns:1fr; }
  #mpgMembershipPage .mpg-plan-featured{ transform:none; }
  #mpgMembershipPage .mpg-ben-grid{ grid-template-columns:1fr; }
  #mpgMembershipPage .mpg-steps-grid{ grid-template-columns:repeat(2,1fr); }
  #mpgMembershipPage .mpg-faq-grid{ grid-template-columns:1fr; }
  #mpgMembershipPage .mpg-cta-box{ grid-template-columns:1fr; }
}
@media (max-width:560px){
  #mpgMembershipPage .container{ padding:0 16px; }
  #mpgMembershipPage .mpg-steps-grid{ grid-template-columns:1fr; }
  #mpgMembershipPage .mpg-form{ grid-template-columns:1fr; }
  #mpgMembershipPage .mpg-hero{ padding:64px 0 56px; }
  #mpgMembershipPage .mpg-hero-content p{ font-size:14px; }
}
</style>

<main id="mpgMembershipPage">

  <!-- HERO -->
  <section class="mpg-hero">
    <div class="mpg-hero-bg"></div>
    <div class="mpg-hero-overlay"></div>

    <div class="container mpg-hero-inner">
      <div class="mpg-hero-content">
        <span class="mpg-kicker"><i class="fa-solid fa-crown"></i> Membership</span>
        <h1>Join membership for premium benefits.</h1>
        <p>Get priority support, member-only offers experience—simple, clean, and reliable.</p>

        <div class="mpg-hero-actions">
          <a class="mpg-btn mpg-btn-primary" href="#mpgPlans"><i class="fa-solid fa-bolt"></i> View Plans</a>
          <a class="mpg-btn mpg-btn-outline" href="#mpgCta"><i class="fa-solid fa-paper-plane"></i> Enquire</a>
        </div>

        <div class="mpg-hero-badges">
          <span class="mpg-badge"><i class="fa-solid fa-tag"></i> Member Prices</span>
          <span class="mpg-badge"><i class="fa-solid fa-headset"></i> Priority Support</span>
          <span class="mpg-badge"><i class="fa-solid fa-gift"></i> Exclusive Offers</span>
        </div>
      </div>
    </div>
  </section>

  <!-- PLANS -->
  <section class="mpg-plans" id="mpgPlans">
    <div class="container">
      <div class="mpg-sec-head">
        <span class="mpg-kicker"><i class="fa-solid fa-layer-group"></i> Plans</span>
        <h2>Choose the right membership</h2>
        <p>Simple plans with clear benefits. Update prices as per your business.</p>
      </div>

      <div class="mpg-plans-grid">
        @foreach($plans as $plan)
        <div class="mpg-plan {{ $plan->is_featured ? 'mpg-plan-featured' : '' }}">
          <div class="mpg-plan-top">
            @if($plan->badge)
            <span class="mpg-plan-badge"><i class="fa-solid {{ $plan->icon ?? 'fa-star' }}"></i> {{ $plan->badge }}</span>
            @endif
          </div>
          <h3>{{ $plan->name }}</h3>
          <p>{{ $plan->description }}</p>

          <div class="mpg-price">
            <strong>₹{{ number_format($plan->price, 0) }}</strong><span>/ {{ $plan->duration }}</span>
          </div>

          <div class="mpg-divider"></div>

          <ul class="mpg-list">
            @if($plan->features)
              @foreach($plan->features as $feature)
              <li><i class="fa-solid fa-check"></i> {{ $feature }}</li>
              @endforeach
            @endif
          </ul>

          <a href="#mpgCta" class="mpg-btn {{ $plan->is_featured ? 'mpg-btn-primary' : 'mpg-btn-outline' }} mpg-btn-block">
            <i class="fa-solid {{ $plan->is_featured ? 'fa-crown' : 'fa-arrow-right' }}"></i> Join {{ $plan->badge ?? $plan->name }}
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- BENEFITS -->
  <section class="mpg-benefits" id="mpgBenefits">
    <div class="container">
      <div class="mpg-ben-grid">
        <div class="mpg-card mpg-ben-left">
          <span class="mpg-kicker"><i class="fa-solid fa-circle-check"></i> Benefits</span>
          <h3>What you get as a member</h3>
          <p>Premium membership improves your overall experience—faster, cleaner, and more rewarding.</p>

          <div class="mpg-checkgrid">
            @foreach($benefits as $benefit)
            <div class="mpg-check">
              <div class="mpg-check-ico"><i class="fa-solid {{ $benefit->icon ?? 'fa-check' }}"></i></div>
              <div>
                <strong>{{ $benefit->title }}</strong>
                <span>{{ $benefit->description }}</span>
              </div>
            </div>
            @endforeach
          </div>
        </div>

        <div class="mpg-card mpg-ben-side">
          <span class="mpg-kicker"><i class="fa-solid fa-shield"></i> Member promise</span>
          <h3>Clean experience, every time</h3>
          <p>We keep the membership benefits simple and transparent so you can decide quickly.</p>

          <div class="mpg-mini">
            <div class="mpg-mini-row">
              <i class="fa-solid fa-lock"></i>
              <div><strong>Secure</strong><div style="color:var(--muted);font-weight:650;font-size:13px;">Safe & trusted checkout</div></div>
            </div>
            <div class="mpg-mini-row">
              <i class="fa-solid fa-clock"></i>
              <div><strong>Quick</strong><div style="color:var(--muted);font-weight:650;font-size:13px;">Fast support response</div></div>
            </div>
            <div class="mpg-mini-row">
              <i class="fa-solid fa-star"></i>
              <div><strong>Premium</strong><div style="color:var(--muted);font-weight:650;font-size:13px;">Better overall experience</div></div>
            </div>
          </div>

          <a href="#mpgCta" class="mpg-btn mpg-btn-primary mpg-btn-block">
            <i class="fa-solid fa-paper-plane"></i> Request Membership
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- STEPS -->
  <section class="mpg-steps" id="mpgSteps">
    <div class="container">
      <div class="mpg-sec-head">
        <span class="mpg-kicker"><i class="fa-solid fa-shoe-prints"></i> Steps</span>
        <h2>How Membership Works</h2>
        <p>Simple steps—no confusion.</p>
      </div>

      <div class="mpg-steps-grid">
        @foreach($steps as $step)
        <div class="mpg-step">
          <div class="mpg-step-no">{{ $step->step_number }}</div>
          <h4>{{ $step->title }}</h4>
          <p>{{ $step->description }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="mpg-faq" id="mpgFaq">
    <div class="container">
      <div class="mpg-sec-head">
        <span class="mpg-kicker"><i class="fa-solid fa-circle-question"></i> FAQ</span>
        <h2>Membership FAQs</h2>
        <p>Quick answers before you join.</p>
      </div>

      <div class="mpg-faq-grid">
        <div class="mpg-faq-col">
          @foreach($faqs->take(ceil($faqs->count() / 2)) as $faq)
          <div class="mpg-faq-item">
            <button class="mpg-faq-q" type="button">
              {{ $faq->question }}
              <span class="mpg-faq-ico"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="mpg-faq-a">{{ $faq->answer }}</div>
          </div>
          @endforeach
        </div>

        <div class="mpg-faq-col">
          @foreach($faqs->skip(ceil($faqs->count() / 2)) as $faq)
          <div class="mpg-faq-item">
            <button class="mpg-faq-q" type="button">
              {{ $faq->question }}
              <span class="mpg-faq-ico"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="mpg-faq-a">{{ $faq->answer }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <!-- CTA FORM -->
  <section class="mpg-cta" id="mpgCta">
    <div class="container">
      <div class="mpg-cta-box">
        <div class="mpg-cta-left">
          <span class="mpg-kicker"><i class="fa-solid fa-message"></i> Enquiry</span>
          <h3>Want membership help?</h3>
          <p>Share details and our team will guide you for the best plan.</p>
        </div>

        <form class="mpg-form" id="membershipForm" action="{{ route('contact.submit') }}" method="post">
          @csrf
          <div class="mpg-field">
            <i class="fa-solid fa-user"></i>
            <input type="text" name="name" id="membership_name" placeholder="Your Name" required>
            <p class="text-red-600 text-xs mt-1" id="membership-error-name" style="display: none;"></p>
          </div>

          <div class="mpg-field">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" name="email" id="membership_email" placeholder="Email Address" required>
            <p class="text-red-600 text-xs mt-1" id="membership-error-email" style="display: none;"></p>
          </div>

          <div class="mpg-field">
            <i class="fa-solid fa-phone"></i>
            <input type="tel" name="phone" id="membership_phone" placeholder="Phone Number" required>
            <p class="text-red-600 text-xs mt-1" id="membership-error-phone" style="display: none;"></p>
          </div>

          <div class="mpg-field">
            <i class="fa-solid fa-layer-group"></i>
            <select name="plan_id" id="membership_plan" required>
              <option value="" selected disabled>Select Plan</option>
              @foreach($plans as $plan)
              <option value="{{ $plan->id }}" data-plan-name="{{ $plan->name }} - ₹{{ number_format($plan->price, 0) }}/{{ $plan->duration }}">{{ $plan->name }} - ₹{{ number_format($plan->price, 0) }}/{{ $plan->duration }}</option>
              @endforeach
            </select>
            <p class="text-red-600 text-xs mt-1" id="membership-error-plan_id" style="display: none;"></p>
          </div>

          <!-- Hidden fields for subject and message -->
          <input type="hidden" name="subject" id="membership_subject" value="">
          <input type="hidden" name="message" id="membership_message" value="Membership inquiry">

          <!-- Success Message -->
          <div style="display: none; padding: 12px; background: #d1fae5; border: 1px solid #10b981; border-radius: 12px; color: #065f46; font-weight: 650; margin-bottom: 12px;" id="membershipSuccessMessage">
            <i class="fa-solid fa-circle-check"></i> <span id="membershipSuccessText"></span>
          </div>

          <!-- Error Message -->
          <div style="display: none; padding: 12px; background: #fee2e2; border: 1px solid #ef4444; border-radius: 12px; color: #991b1b; font-weight: 650; margin-bottom: 12px;" id="membershipErrorMessage">
            <i class="fa-solid fa-circle-xmark"></i> <span id="membershipErrorText"></span>
          </div>

          <button class="mpg-btn mpg-btn-primary mpg-submit" type="submit" id="membershipSubmitBtn">
            <i class="fa-solid fa-paper-plane"></i> <span id="membershipBtnText">Submit</span>
          </button>
        </form>
      </div>
    </div>
  </section>

</main>

<script>

(function(){
  const root = document.getElementById("mpgMembershipPage");
  if(!root) return;

  root.querySelectorAll(".mpg-faq-item .mpg-faq-q").forEach((btn) => {
    btn.addEventListener("click", () => {
      const item = btn.closest(".mpg-faq-item");
      const grid = item.closest(".mpg-faq-grid");
      if(!grid) return;

      grid.querySelectorAll(".mpg-faq-item").forEach(i => {
        if(i !== item) i.classList.remove("mpg-open");
      });

      item.classList.toggle("mpg-open");
    });
  });

  // AJAX Form Submission for Membership Page
  const membershipForm = document.getElementById('membershipForm');
  if (membershipForm) {
    // Update subject when plan is selected
    const planSelect = document.getElementById('membership_plan');
    const subjectInput = document.getElementById('membership_subject');
    const messageInput = document.getElementById('membership_message');

    planSelect.addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const planName = selectedOption.getAttribute('data-plan-name');
      subjectInput.value = 'Membership Inquiry: ' + planName;
      messageInput.value = 'I am interested in the ' + planName + ' membership plan. Please contact me with more details.';
    });

    membershipForm.addEventListener('submit', function(e) {
      e.preventDefault();

      // Get form elements
      const submitBtn = document.getElementById('membershipSubmitBtn');
      const btnText = document.getElementById('membershipBtnText');
      const successMessage = document.getElementById('membershipSuccessMessage');
      const errorMessage = document.getElementById('membershipErrorMessage');
      const successText = document.getElementById('membershipSuccessText');
      const errorText = document.getElementById('membershipErrorText');

      // Hide previous messages
      successMessage.style.display = 'none';
      errorMessage.style.display = 'none';

      // Clear previous error messages
      document.querySelectorAll('[id^="membership-error-"]').forEach(el => {
        el.style.display = 'none';
        el.textContent = '';
      });

      // Disable submit button
      submitBtn.disabled = true;
      btnText.textContent = 'Sending...';

      // Get form data
      const formData = new FormData(membershipForm);

      // Send AJAX request
      fetch(membershipForm.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      })
      .then(response => {
        if (!response.ok) {
          return response.json().then(data => {
            throw data;
          });
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          // Show success message
          successText.textContent = data.message;
          successMessage.style.display = 'block';

          // Reset form
          membershipForm.reset();

          // Scroll to success message
          successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });

          // Hide success message after 5 seconds
          setTimeout(() => {
            successMessage.style.display = 'none';
          }, 5000);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        
        // Handle validation errors
        if (error.errors) {
          // Display field-specific errors
          Object.keys(error.errors).forEach(field => {
            const errorElement = document.getElementById('membership-error-' + field);
            if (errorElement) {
              errorElement.textContent = error.errors[field][0];
              errorElement.style.display = 'block';
            }
          });

          // Show general error message
          errorText.textContent = error.message || 'Please fix the errors below.';
          errorMessage.style.display = 'block';
          errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
          // Show generic error message
          errorText.textContent = error.message || 'Something went wrong. Please try again.';
          errorMessage.style.display = 'block';
          errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      })
      .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        btnText.textContent = 'Submit';
      });
    });
  }
})();
</script>






@endsection
