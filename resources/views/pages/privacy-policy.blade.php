@extends('layouts.public')

@section('title', $legalPage->title ?? 'Privacy Policy')
@section('meta_description', 'Privacy Policy')

@section('content')

<style>
  #legalPage{
    --bg:#ffffff;
    --soft:#f6f8f2;
    --text:#1f2a1a;
    --muted:#5c6b55;
    --brand:#263d18;
    --accent:#f1cc24;
    --border:rgba(0,0,0,.10);
    color:var(--text);
    background:var(--bg);
    font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;
  }

  #legalPage *{ box-sizing:border-box; }

  #legalPage .hero{
    position:relative;
    padding:78px 0 66px;
    overflow:hidden;
    background:#0f130e;
  }
  #legalPage .hero-bg{
    position:absolute; inset:0;
    background-image:url("{{ asset('images/about-banner.webp') }}");
    background-size:cover;
    background-position:center;
    transform:scale(1.04);
  }
  #legalPage .hero-overlay{
    position:absolute; inset:0;
    background:linear-gradient(180deg, rgba(0,0,0,.62), rgba(0,0,0,.70));
  }
  #legalPage .hero-inner{
    position:relative; z-index:2;
    max-width:940px;
    margin:0 auto;
    padding:0 24px;
    text-align:center;
    color:#fff;
  }
  #legalPage .hero h1{
    font-size:clamp(28px,4vw,35px);
    font-weight:950;
    margin-bottom:12px;
  }
  #legalPage .hero p{
    max-width:780px;
    margin:0 auto 18px;
    color:rgba(255,255,255,.86);
    line-height:1.8;
  }

  .content-wrap{
    max-width:1250px;
    margin:60px auto;
    padding:0 24px;
  }

  .content-card{
    background:#ffffff;
    border-radius:16px;
    padding:34px 32px;
    box-shadow:0 20px 50px rgba(0,0,0,0.08);
    border:1px solid rgba(0,0,0,0.06);
  }

  .content-card h1{
    font-size:32px;
    margin-bottom:6px;
    color:#263d18;
  }

  .updated{
    font-size:14px;
    color:#6b7a64;
    margin-bottom:22px;
  }

  .content-card h2{
    font-size:20px;
    margin:26px 0 8px;
    color:#263d18;
  }

  .content-card p{
    font-size:15.5px;
    line-height:1.85;
    color:#4f5e49;
    margin:0 0 16px;
  }

  .section{
    padding:18px 20px;
    margin-top:18px;
    border-radius:12px;
    background:#fbfcf8;
    box-shadow:0 10px 24px rgba(0,0,0,0.05);
    border:1px solid rgba(0,0,0,0.05);
  }

  .contact-section{
    background:#f6f8f2;
    border-left:4px solid #263d18;
  }
</style>

<main id="legalPage">
  <!-- HERO -->
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-inner">
      <h1>{{ $legalPage->title ?? 'Privacy Policy' }}</h1>
      <p>{{ $legalPage->hero_description ?? 'We are committed to protecting your privacy.' }}</p>
    </div>
  </section>

  <!-- CONTENT -->
  <div class="content-wrap">
    <div class="content-card">
      <h1>{{ $legalPage->title ?? 'Privacy Policy' }}</h1>
      <div class="updated">Last updated: {{ $legalPage->last_updated ?? date('F j, Y') }}</div>

      <div class="content">
        {!! $legalPage->content ?? '<p>Content not available.</p>' !!}
      </div>

      @if($legalPage && ($legalPage->contact_email || $legalPage->contact_phone || $legalPage->contact_address))
      <div class="section contact-section" style="margin-top: 30px;">
        <h2>Contact Us</h2>
        <p>
          If you have any questions, please contact us:
          <br><br>
          @if($legalPage->contact_email)
          <strong>Email:</strong> {{ $legalPage->contact_email }} <br>
          @endif
          @if($legalPage->contact_phone)
          <strong>Phone:</strong> {{ $legalPage->contact_phone }} <br>
          @endif
          @if($legalPage->contact_address)
          <strong>Address:</strong> {{ $legalPage->contact_address }}
          @endif
        </p>
      </div>
      @endif
    </div>
  </div>
</main>

@endsection
