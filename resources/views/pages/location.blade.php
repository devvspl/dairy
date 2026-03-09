@extends('layouts.public')
@section('title', 'Location Us')
@section('meta_description', 'Learn more about our company')
@section('content')
<style>
   #locSinglePage {
   --bg: #ffffff;
   --soft: #f6f8f2;
   --soft2: #fbfcf8;
   --text: #1f2a1a;
   --muted: #5c6b55;
   --brand: #263d18;
   --accent: #f1cc24;
   --border: rgba(0, 0, 0, .10);
   --shadow: 0 18px 60px rgba(0, 0, 0, .08);
   --shadow2: 0 26px 80px rgba(0, 0, 0, .12);
   --radius: 22px;
   color: var(--text);
   background: var(--bg);
   font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
   }
   #locSinglePage * {
   box-sizing: border-box;
   }
   #locSinglePage img {
   max-width: 100%;
   display: block;
   }
   #locSinglePage .loc-container {
   max-width: 1250px;
   margin: 0 auto;
   padding: 0 24px;
   }
   /* badge */
   #locSinglePage .loc-badge {
   display: inline-flex;
   align-items: center;
   gap: 10px;
   padding: 8px 12px;
   border-radius: 999px;
   font-weight: 900;
   letter-spacing: .9px;
   text-transform: uppercase;
   font-size: 12px;
   color: var(--brand);
   background: rgba(38, 61, 24, .07);
   border: 1px solid rgba(38, 61, 24, .12);
   }
   /* buttons */
   #locSinglePage .loc-btn {
   display: inline-flex;
   align-items: center;
   justify-content: center;
   gap: 10px;
   padding: 14px 18px;
   border-radius: 16px;
   font-weight: 900;
   text-decoration: none;
   border: 1px solid var(--border);
   box-shadow: 0 14px 34px rgba(0, 0, 0, .08);
   transition: 220ms ease;
   cursor: pointer;
   white-space: nowrap;
   background: #fff;
   color: var(--brand);
   }
   #locSinglePage .loc-btn:hover {
   transform: translateY(-2px);
   border-color: rgba(241, 204, 36, .85);
   box-shadow: 0 18px 44px rgba(0, 0, 0, .12);
   }
   #locSinglePage .loc-btn-primary {
   background: var(--brand);
   color: #fff;
   border-color: rgba(38, 61, 24, .25);
   }
   #locSinglePage .loc-btn-primary:hover {
   background: var(--accent);
   color: var(--text);
   border-color: rgba(241, 204, 36, .9);
   }
   /* ================= HERO (SAME AS YOUR OLD) ================= */
   #locSinglePage .loc-hero {
   position: relative;
   padding: 78px 0 56px;
   overflow: hidden;
   background: #0f130e;
   }
   #locSinglePage .loc-hero-bg {
   position: absolute;
   inset: 0;
   background-image: url("images/rohini-banner.webp");
   background-size: cover;
   background-position: center;
   transform: scale(1.05);
   filter: saturate(1.03);
   }
   #locSinglePage .loc-hero-overlay {
   position: absolute;
   inset: 0;
   background:
   radial-gradient(circle at 18% 20%, rgba(241, 204, 36, .22), transparent 55%),
   radial-gradient(circle at 82% 70%, rgba(38, 61, 24, .35), transparent 60%),
   linear-gradient(180deg, rgba(0, 0, 0, .62), rgba(0, 0, 0, .78));
   }
   #locSinglePage .loc-hero-inner {
   position: relative;
   z-index: 2;
   display: flex;
   justify-content: center;
   }
   #locSinglePage .loc-hero-content {
   text-align: center;
   max-width: 980px;
   color: #fff;
   }
   #locSinglePage .loc-hero-content .loc-badge {
   background: rgba(241, 204, 36, .14);
   border-color: rgba(241, 204, 36, .28);
   color: #fff;
   }
   #locSinglePage .loc-hero-content h1 {
   margin: 14px 0 10px;
   font-size: clamp(28px, 4vw, 35px);
   font-weight: 950;
   line-height: 1.05;
   letter-spacing: -.8px;
   }
   #locSinglePage .loc-hero-content p {
   margin: 0 auto 16px;
   max-width: 860px;
   color: rgba(255, 255, 255, .86);
   font-weight: 650;
   line-height: 1.85;
   }
   #locSinglePage .loc-hero-actions {
   display: flex;
   gap: 12px;
   justify-content: center;
   flex-wrap: wrap;
   margin-top: 10px;
   }
   #locSinglePage .loc-hero-tags {
   margin-top: 18px;
   display: flex;
   flex-wrap: wrap;
   gap: 10px;
   justify-content: center;
   }
   #locSinglePage .loc-tag {
   display: inline-flex;
   align-items: center;
   gap: 10px;
   padding: 10px 12px;
   border-radius: 999px;
   background: rgba(255, 255, 255, .10);
   border: 1px solid rgba(255, 255, 255, .16);
   color: rgba(255, 255, 255, .92);
   font-weight: 800;
   font-size: 13px;
   backdrop-filter: blur(6px);
   }
   /* ================= SECTIONS ================= */
   #locSinglePage .loc-section {
   padding: 72px 0;
   background: #fff;
   }
   #locSinglePage .loc-section.loc-alt {
   background:
   radial-gradient(circle at 18% 20%, rgba(241, 204, 36, 0.12), transparent 55%),
   radial-gradient(circle at 82% 70%, rgba(38, 61, 24, 0.10), transparent 58%),
   linear-gradient(180deg, #ffffff, #f6f8f2);
   }
   #locSinglePage .loc-head {
   text-align: center;
   max-width: 880px;
   margin: 0 auto 28px;
   }
   #locSinglePage .loc-head h2 {
   margin: 12px 0 8px;
   font-size: clamp(26px, 3.2vw, 42px);
   font-weight: 950;
   color: var(--brand);
   line-height: 1.12;
   }
   #locSinglePage .loc-head p {
   margin: 0;
   color: var(--muted);
   font-weight: 650;
   line-height: 1.8;
   }
   /* ================= NEW: BUILDING + AREA (CLEAN) ================= */
   #locSinglePage .loc-building-grid {
   display: grid;
   grid-template-columns: 1.05fr .95fr;
   gap: 18px;
   align-items: stretch;
   }
   #locSinglePage .loc-card {
   border-radius: 26px;
   border: 1px solid rgba(0, 0, 0, .08);
   box-shadow: var(--shadow);
   background: linear-gradient(180deg, #ffffff, #f7f9f4);
   overflow: hidden;
   }
   #locSinglePage .loc-card-top {
   padding: 18px 18px 14px;
   display: flex;
   align-items: flex-start;
   justify-content: space-between;
   gap: 12px;
   border-bottom: 1px solid rgba(0, 0, 0, .06);
   background:
   radial-gradient(circle at 14% 18%, rgba(241, 204, 36, .16), transparent 55%),
   linear-gradient(180deg, #ffffff, #fbfcf8);
   }
   #locSinglePage .loc-titleline {
   display: flex;
   gap: 12px;
   align-items: flex-start;
   }
   #locSinglePage .loc-icoBox {
   width: 46px;
   height: 46px;
   border-radius: 16px;
   display: flex;
   align-items: center;
   justify-content: center;
   background: rgba(241, 204, 36, .18);
   color: #7a5b00;
   flex: 0 0 46px;
   }
   #locSinglePage .loc-card-top h3 {
   margin: 0 0 4px;
   font-weight: 950;
   color: var(--brand);
   letter-spacing: -.2px;
   }
   #locSinglePage .loc-sub {
   margin: 0;
   color: var(--muted);
   font-weight: 700;
   font-size: 13px;
   line-height: 1.45;
   }
   #locSinglePage .loc-chip {
   display: inline-flex;
   align-items: center;
   gap: 8px;
   padding: 10px 12px;
   border-radius: 999px;
   font-weight: 900;
   font-size: 13px;
   color: var(--brand);
   background: rgba(38, 61, 24, .06);
   border: 1px solid rgba(38, 61, 24, .12);
   white-space: nowrap;
   }
   #locSinglePage .loc-card-body {
   padding: 16px 18px 18px;
   }
   #locSinglePage .loc-desc {
   margin: 0 0 14px;
   color: var(--muted);
   font-weight: 650;
   line-height: 1.9;
   }
   #locSinglePage .loc-flow {
   display: flex;
   align-items: center;
   gap: 12px;
   padding: 14px;
   border-radius: 18px;
   border: 1px solid rgba(0, 0, 0, .06);
   background: #fff;
   margin-bottom: 14px;
   }
   #locSinglePage .loc-flow-ico {
   width: 42px;
   height: 42px;
   border-radius: 14px;
   display: flex;
   align-items: center;
   justify-content: center;
   background: rgba(38, 61, 24, .07);
   color: var(--brand);
   flex: 0 0 42px;
   }
   #locSinglePage .loc-flow-text {
   color: var(--muted);
   font-weight: 750;
   line-height: 1.6;
   }
   #locSinglePage .loc-flow-text b {
   color: var(--brand);
   font-weight: 950;
   }
   #locSinglePage .loc-mini {
   display: grid;
   grid-template-columns: repeat(3, 1fr);
   gap: 10px;
   }
   #locSinglePage .loc-miniItem {
   padding: 14px;
   border-radius: 18px;
   background: linear-gradient(180deg, #fff, var(--soft2));
   border: 1px solid rgba(0, 0, 0, .06);
   transition: 220ms ease;
   }
   #locSinglePage .loc-miniItem:hover {
   transform: translateY(-2px);
   border-color: rgba(241, 204, 36, .55);
   box-shadow: 0 18px 44px rgba(0, 0, 0, .08);
   }
   #locSinglePage .loc-miniItem strong {
   display: block;
   font-weight: 950;
   color: var(--brand);
   }
   #locSinglePage .loc-miniItem span {
   display: block;
   margin-top: 4px;
   color: var(--muted);
   font-weight: 650;
   line-height: 1.55;
   font-size: 14px;
   }
   #locSinglePage .loc-mapWrap {
   background: #fff;
   }
   #locSinglePage .loc-addressRow {
   display: flex;
   gap: 10px;
   align-items: flex-start;
   padding: 14px 18px;
   border-bottom: 1px solid rgba(0, 0, 0, .06);
   }
   #locSinglePage .loc-addressRow i {
   margin-top: 4px;
   color: #7a5b00;
   }
   #locSinglePage .loc-addressRow b {
   display: block;
   color: var(--brand);
   font-weight: 950;
   }
   #locSinglePage .loc-addressRow span {
   display: block;
   color: var(--muted);
   font-weight: 650;
   line-height: 1.55;
   }
   #locSinglePage .loc-mapFrame iframe {
   width: 100%;
   height: 290px;
   border: 0;
   display: block;
   }
   #locSinglePage .loc-card-actions {
   padding: 16px 18px 18px;
   display: flex;
   gap: 12px;
   flex-wrap: wrap;
   }
   #locSinglePage .loc-steps {
   display: grid;
   grid-template-columns: repeat(4, 1fr);
   gap: 18px;
   }
   #locSinglePage .loc-step {
   border-radius: 22px;
   background: #fff;
   border: 1px solid rgba(0, 0, 0, .08);
   box-shadow: var(--shadow);
   padding: 20px;
   transition: 220ms ease;
   position: relative;
   overflow: hidden;
   }
   #locSinglePage .loc-step:hover {
   transform: translateY(-6px);
   border-color: rgba(241, 204, 36, .65);
   box-shadow: var(--shadow2);
   }
   #locSinglePage .loc-stepNo {
   width: 44px;
   height: 44px;
   border-radius: 16px;
   display: flex;
   align-items: center;
   justify-content: center;
   background: rgba(241, 204, 36, .18);
   color: #7a5b00;
   font-weight: 950;
   margin-bottom: 10px;
   }
   #locSinglePage .loc-step h3 {
   margin: 0 0 6px;
   font-weight: 950;
   color: var(--brand);
   }
   #locSinglePage .loc-step p {
   margin: 0;
   color: var(--muted);
   font-weight: 650;
   line-height: 1.7;
   }
   #locSinglePage .loc-usps {
   display: grid;
   grid-template-columns: repeat(4, 1fr);
   gap: 18px;
   }
   #locSinglePage .loc-usp {
   padding: 22px;
   border-radius: 22px;
   background: #fff;
   border: 1px solid rgba(0, 0, 0, .08);
   box-shadow: var(--shadow);
   transition: 220ms ease;
   position: relative;
   overflow: hidden;
   }
   #locSinglePage .loc-usp:hover {
   transform: translateY(-7px);
   border-color: rgba(241, 204, 36, .65);
   box-shadow: var(--shadow2);
   }
   #locSinglePage .loc-usp-ico {
   width: 52px;
   height: 52px;
   border-radius: 18px;
   display: flex;
   align-items: center;
   justify-content: center;
   background: rgba(241, 204, 36, .18);
   color: #7a5b00;
   margin-bottom: 12px;
   }
   #locSinglePage .loc-usp-ico i {
   font-size: 22px;
   }
   #locSinglePage .loc-usp h3 {
   margin: 0 0 6px;
   font-weight: 950;
   color: var(--brand);
   }
   #locSinglePage .loc-usp p {
   margin: 0;
   color: var(--muted);
   font-weight: 650;
   line-height: 1.7;
   }
   /* ================= COVERAGE + GUIDELINES ================= */
   #locSinglePage .loc-two-col {
   display: grid;
   grid-template-columns: 1.05fr .95fr;
   gap: 18px;
   align-items: start;
   }
   #locSinglePage .loc-box {
   border-radius: 26px;
   padding: 18px;
   border: 1px solid rgba(0, 0, 0, .08);
   box-shadow: var(--shadow);
   background: linear-gradient(180deg, #ffffff, #f7f9f4);
   }
   #locSinglePage .loc-rule {
   display: flex;
   gap: 12px;
   padding: 16px 14px;
   border-radius: 18px;
   background: #fff;
   border: 1px solid rgba(0, 0, 0, .06);
   margin-bottom: 12px;
   transition: 220ms ease;
   }
   #locSinglePage .loc-rule:hover {
   transform: translateY(-2px);
   border-color: rgba(241, 204, 36, .55);
   box-shadow: 0 18px 44px rgba(0, 0, 0, .08);
   }
   #locSinglePage .loc-rule:last-child {
   margin-bottom: 0;
   }
   #locSinglePage .loc-rule-ico {
   width: 40px;
   height: 40px;
   border-radius: 14px;
   display: flex;
   align-items: center;
   justify-content: center;
   background: rgba(241, 204, 36, .18);
   color: #7a5b00;
   flex: 0 0 40px;
   }
   #locSinglePage .loc-rule h3 {
   margin: 0 0 4px;
   font-weight: 950;
   color: var(--brand);
   }
   #locSinglePage .loc-rule p {
   margin: 0;
   color: var(--muted);
   font-weight: 650;
   line-height: 1.65;
   }
   #locSinglePage .loc-side {
   border-radius: 26px;
   padding: 22px;
   border: 1px solid rgba(0, 0, 0, .08);
   box-shadow: var(--shadow);
   background:
   radial-gradient(circle at 20% 20%, rgba(241, 204, 36, 0.16), transparent 55%),
   linear-gradient(180deg, #ffffff, #f6f8f2);
   }
   #locSinglePage .loc-side h3 {
   margin: 12px 0 8px;
   font-weight: 950;
   color: var(--brand);
   }
   #locSinglePage .loc-side p {
   margin: 0 0 14px;
   color: var(--muted);
   font-weight: 650;
   line-height: 1.75;
   }
   #locSinglePage .loc-map {
   margin-top: 12px;
   border-radius: 18px;
   overflow: hidden;
   border: 1px solid rgba(0, 0, 0, .10);
   box-shadow: 0 18px 50px rgba(0, 0, 0, .10);
   background: #fff;
   }
   #locSinglePage .loc-map iframe {
   width: 100%;
   height: 260px;
   border: 0;
   display: block;
   }
   #locSinglePage .loc-area-list {
   display: grid;
   gap: 10px;
   margin: 12px 0 0;
   }
   #locSinglePage .loc-area {
   display: flex;
   align-items: center;
   justify-content: space-between;
   gap: 10px;
   padding: 12px 14px;
   border-radius: 16px;
   background: #fff;
   border: 1px solid rgba(0, 0, 0, .06);
   font-weight: 850;
   color: var(--brand);
   }
   #locSinglePage .loc-area span {
   font-weight: 750;
   color: var(--muted);
   font-size: 13px;
   }
   /* ================= FAQ ================= */
   #locSinglePage .loc-faq-grid {
   display: grid;
   grid-template-columns: 1fr 1fr;
   gap: 18px;
   align-items: start;
   }
   #locSinglePage .loc-faq-col {
   display: grid;
   gap: 12px;
   }
   #locSinglePage .loc-faq-item {
   border-radius: 18px;
   border: 1px solid rgba(0, 0, 0, .08);
   background: #fff;
   overflow: hidden;
   box-shadow: 0 16px 46px rgba(0, 0, 0, .06);
   transition: 220ms ease;
   }
   #locSinglePage .loc-faq-q {
   width: 100%;
   text-align: left;
   padding: 16px 16px;
   border: 0;
   background: linear-gradient(180deg, #ffffff, #fbfcf8);
   cursor: pointer;
   font-weight: 900;
   color: var(--brand);
   display: flex;
   align-items: center;
   justify-content: space-between;
   gap: 14px;
   }
   #locSinglePage .loc-faq-q:hover {
   background: rgba(241, 204, 36, 0.10);
   }
   #locSinglePage .loc-faq-ico {
   width: 36px;
   height: 36px;
   border-radius: 12px;
   display: inline-flex;
   align-items: center;
   justify-content: center;
   background: rgba(38, 61, 24, .07);
   color: var(--brand);
   transition: 200ms ease;
   flex: 0 0 36px;
   }
   #locSinglePage .loc-faq-a {
   display: none;
   padding: 0 16px 16px;
   color: var(--muted);
   font-weight: 650;
   line-height: 1.75;
   border-top: 1px solid rgba(0, 0, 0, .06);
   }
   #locSinglePage .loc-faq-item.loc-open {
   border-color: rgba(241, 204, 36, .65);
   box-shadow: 0 22px 62px rgba(0, 0, 0, .10);
   }
   #locSinglePage .loc-faq-item.loc-open .loc-faq-a {
   display: block;
   }
   #locSinglePage .loc-faq-item.loc-open .loc-faq-ico {
   transform: rotate(180deg);
   background: rgba(241, 204, 36, .18);
   color: #7a5b00;
   }
   /* ================= CONTACT ================= */
   #locSinglePage .loc-contact {
   padding: 50px 0 84px;
   background:
   radial-gradient(circle at 18% 20%, rgba(241, 204, 36, 0.14), transparent 55%),
   radial-gradient(circle at 82% 70%, rgba(38, 61, 24, 0.10), transparent 58%),
   linear-gradient(180deg, #ffffff, #f6f8f2);
   }
   #locSinglePage .loc-contact-box {
   border-radius: 26px;
   border: 1px solid rgba(0, 0, 0, .08);
   box-shadow: 0 22px 70px rgba(0, 0, 0, .10);
   background: linear-gradient(180deg, #ffffff, #f7f9f4);
   padding: 26px;
   display: grid;
   grid-template-columns: 1.1fr .9fr;
   gap: 18px;
   align-items: center;
   }
   #locSinglePage .loc-contact-box h2 {
   margin: 10px 0 6px;
   font-weight: 950;
   color: var(--brand);
   font-size: clamp(22px, 2.8vw, 34px);
   line-height: 1.12;
   }
   #locSinglePage .loc-contact-box p {
   margin: 0;
   color: var(--muted);
   font-weight: 650;
   line-height: 1.75;
   }
   #locSinglePage .loc-contact-actions {
   display: flex;
   gap: 12px;
   justify-content: flex-end;
   flex-wrap: wrap;
   }
   /* ================= RESPONSIVE ================= */
   @media (max-width:980px) {
   #locSinglePage .loc-building-grid {
   grid-template-columns: 1fr;
   }
   #locSinglePage .loc-mini {
   grid-template-columns: 1fr;
   }
   #locSinglePage .loc-steps {
   grid-template-columns: repeat(2, 1fr);
   }
   #locSinglePage .loc-usps {
   grid-template-columns: repeat(2, 1fr);
   }
   #locSinglePage .loc-two-col {
   grid-template-columns: 1fr;
   }
   #locSinglePage .loc-faq-grid {
   grid-template-columns: 1fr;
   }
   #locSinglePage .loc-contact-box {
   grid-template-columns: 1fr;
   }
   #locSinglePage .loc-contact-actions {
   justify-content: flex-start;
   }
   }
   @media (max-width:560px) {
   #locSinglePage .loc-container {
   padding: 0 16px;
   }
   #locSinglePage .loc-hero {
   padding: 64px 0 52px;
   }
   #locSinglePage .loc-hero-content p {
   font-size: 14px;
   }
   #locSinglePage .loc-steps {
   grid-template-columns: 1fr;
   }
   #locSinglePage .loc-usps {
   grid-template-columns: 1fr;
   }
   #locSinglePage .loc-map iframe {
   height: 220px;
   }
   #locSinglePage .loc-mapFrame iframe {
   height: 240px;
   }
   }
</style>
<main id="locSinglePage">
   <!-- HERO SECTION -->
   <section class="loc-hero">
      @if($location->banner_image)
      <div class="loc-hero-bg" style="background-image: url('{{ asset($location->banner_image) }}');"></div>
      @else
      <div class="loc-hero-bg"></div>
      @endif
      <div class="loc-hero-overlay"></div>
      <div class="loc-container loc-hero-inner">
         <div class="loc-hero-content">
            <span class="loc-badge"><i class="fa-solid fa-location-dot"></i> Location Service Page</span>
            <h1>{{ $location->title ?? 'Bottle Milk Delivery in ' . $location->name }}</h1>
            <p>{{ $location->description ?? $location->building_name . ' located in ' . ($location->sector ? 'Sector ' . $location->sector . ', ' : '') . $location->area }}</p>
            
            <div class="loc-hero-actions">
               <a href="#locBuilding" class="loc-btn loc-btn-primary"><i class="fa-solid fa-building"></i> {{ $location->building_name ?? 'Building' }} Info</a>
               <a href="#locCoverage" class="loc-btn"><i class="fa-solid fa-map-location-dot"></i> Coverage</a>
               <a href="#locContact" class="loc-btn"><i class="fa-solid fa-phone"></i> Contact</a>
            </div>
            
            @if($location->hero_badges && count($location->hero_badges) > 0)
            <div class="loc-hero-tags">
               @foreach($location->hero_badges as $badge)
               <span class="loc-tag"><i class="{{ $badge['icon'] ?? 'fa-solid fa-check' }}"></i> {{ $badge['text'] }}</span>
               @endforeach
            </div>
            @endif
         </div>
      </div>
   </section>
   <!-- BUILDING & AREA SECTION -->
   <section class="loc-section" id="locBuilding">
      <div class="loc-container">
         <div class="loc-head">
            <span class="loc-badge"><i class="fa-solid fa-building"></i> Location Structure</span>
            <h2>{{ $location->building_name ?? $location->name }} (Building) → {{ $location->sector ? 'Sector ' . $location->sector : '' }} (Area) → {{ $location->area }}</h2>
            <p>Clean representation so visitors instantly understand where {{ $location->building_name ?? $location->name }} is located.</p>
         </div>
         <div class="loc-building-grid">
            <article class="loc-card">
               <div class="loc-card-top">
                  <div class="loc-titleline">
                     <div class="loc-icoBox"><i class="fa-solid fa-building"></i></div>
                     <div>
                        <h3>{{ $location->building_name ?? $location->name }}</h3>
                        <p class="loc-sub">{{ $location->building_type ?? 'Building / Society' }} (primary delivery point)</p>
                     </div>
                  </div>
                  <div class="loc-chip"><i class="fa-solid fa-route"></i> Route-Based</div>
               </div>
               <div class="loc-card-body">
                  <div class="loc-flow">
                     <div class="loc-flow-ico"><i class="fa-solid fa-arrow-right"></i></div>
                     <div class="loc-flow-text">
                        <b>Building:</b> {{ $location->building_name ?? $location->name }} &nbsp;→&nbsp; 
                        @if($location->sector)<b>Sector:</b> {{ $location->sector }} &nbsp;→&nbsp; @endif
                        <b>Area:</b> {{ $location->area }}
                     </div>
                  </div>
                  <p class="loc-desc">
                     {{ $location->handling_info ?? 'Milk delivery here works on a society-first route. That means delivery is planned around entry rules (gate/guard) and then executed in a fixed morning sequence.' }}
                  </p>
                  
                  @if($location->mini_items && count($location->mini_items) > 0)
                  <div class="loc-mini">
                     @foreach($location->mini_items as $item)
                     <div class="loc-miniItem">
                        <strong>{{ $item['title'] }}</strong>
                        <span>{{ $item['description'] }}</span>
                     </div>
                     @endforeach
                  </div>
                  @else
                  <div class="loc-mini">
                     <div class="loc-miniItem">
                        <strong>Timing</strong>
                        <span>{{ $location->delivery_timing ?? 'Typical: 5:30 AM – 8:30 AM' }}</span>
                     </div>
                     <div class="loc-miniItem">
                        <strong>Delivery point</strong>
                        <span>{{ $location->delivery_point ?? 'Flat / gate / guard as per rule' }}</span>
                     </div>
                     <div class="loc-miniItem">
                        <strong>Handling</strong>
                        <span>Sealed bottles, hygienic delivery</span>
                     </div>
                  </div>
                  @endif
               </div>
            </article>
            
            <aside class="loc-card">
               <div class="loc-card-top">
                  <div class="loc-titleline">
                     <div class="loc-icoBox"><i class="fa-solid fa-map-location-dot"></i></div>
                     <div>
                        <h3>{{ $location->sector ? 'Sector ' . $location->sector . ', ' : '' }}{{ $location->area }}</h3>
                        <p class="loc-sub">Area context for {{ $location->building_name ?? $location->name }}</p>
                     </div>
                  </div>
                  <div class="loc-chip"><i class="fa-solid fa-location-dot"></i> Exact Location</div>
               </div>
               <div class="loc-mapWrap">
                  @if($location->address)
                  <div class="loc-addressRow">
                     <i class="fa-solid fa-location-dot"></i>
                     <div>
                        <b>Address</b>
                        <span>{{ $location->address }}</span>
                     </div>
                  </div>
                  @endif
                  
                  @if($location->map_embed_url)
                  <div class="loc-mapFrame">
                     <iframe title="{{ $location->name }} Map"
                        src="{{ $location->map_embed_url }}"
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen>
                     </iframe>
                  </div>
                  @endif
                  
                  <div class="loc-card-actions">
                     <a class="loc-btn loc-btn-primary" href="#locCoverage"><i class="fa-solid fa-map"></i> See Coverage</a>
                     <a class="loc-btn" href="#locContact"><i class="fa-solid fa-phone"></i> Confirm on Call</a>
                  </div>
               </div>
            </aside>
         </div>
      </div>
   </section>
   <!-- ROUTE STEPS SECTION -->
   @if($location->route_steps && count($location->route_steps) > 0)
   <section class="loc-section loc-alt" id="locRoute">
      <div class="loc-container">
         <div class="loc-head">
            <span class="loc-badge"><i class="fa-solid fa-route"></i> How Delivery Works</span>
            <h2>Simple route process for {{ $location->building_name ?? $location->name }}</h2>
            <p>Informative steps so user knows exactly what happens on delivery day.</p>
         </div>
         <div class="loc-steps">
            @foreach($location->route_steps as $step)
            <div class="loc-step">
               <div class="loc-stepNo">{{ $step['number'] }}</div>
               <h3>{{ $step['title'] }}</h3>
               <p>{{ $step['description'] }}</p>
            </div>
            @endforeach
         </div>
      </div>
   </section>
   @endif
   <!-- HIGHLIGHTS SECTION -->
   @if($location->highlights && count($location->highlights) > 0)
   <section class="loc-section" id="locHighlights">
      <div class="loc-container">
         <div class="loc-head">
            <span class="loc-badge"><i class="fa-solid fa-gem"></i> Highlights</span>
            <h2>Service points for {{ $location->building_name ?? $location->name }} delivery</h2>
            <p>Short, clean, and informational—only what matters for this building.</p>
         </div>
         <div class="loc-usps">
            @foreach($location->highlights as $highlight)
            <div class="loc-usp">
               <div class="loc-usp-ico"><i class="{{ $highlight['icon'] ?? 'fa-solid fa-check' }}"></i></div>
               <h3>{{ $highlight['title'] }}</h3>
               <p>{{ $highlight['description'] }}</p>
            </div>
            @endforeach
         </div>
      </div>
   </section>
   @endif
   <!-- COVERAGE & GUIDELINES SECTION -->
   <section class="loc-section loc-alt" id="locCoverage">
      <div class="loc-container">
         <div class="loc-head">
            <span class="loc-badge"><i class="fa-solid fa-map-location-dot"></i> Coverage</span>
            <h2>Covered building & delivery guidelines</h2>
            <p>Single building page: coverage clearly defined for {{ $location->building_name ?? $location->name }} only.</p>
         </div>
         <div class="loc-two-col">
            <div class="loc-box">
               @if($location->guidelines && count($location->guidelines) > 0)
                  @foreach($location->guidelines as $guideline)
                  <div class="loc-rule">
                     <span class="loc-rule-ico"><i class="{{ $guideline['icon'] ?? 'fa-solid fa-check' }}"></i></span>
                     <div>
                        <h3>{{ $guideline['title'] }}</h3>
                        <p>{{ $guideline['description'] }}</p>
                     </div>
                  </div>
                  @endforeach
               @else
                  <div class="loc-rule">
                     <span class="loc-rule-ico"><i class="fa-solid fa-clock"></i></span>
                     <div>
                        <h3>Delivery timing</h3>
                        <p>Typical window: <b>{{ $location->delivery_timing ?? '5:30 AM – 8:30 AM' }}</b>. Exact time depends on route sequence & entry.</p>
                     </div>
                  </div>
               @endif
            </div>
            
            <aside class="loc-side">
               <span class="loc-badge"><i class="fa-solid fa-location-dot"></i> {{ $location->building_name ?? $location->name }} Coverage</span>
               <h3>Service map ({{ $location->building_name ?? $location->name }})</h3>
               <p>Map is shown for this single building/society coverage point.</p>
               
               @if($location->map_embed_url)
               <div class="loc-map">
                  <iframe title="{{ $location->name }} Map"
                     src="{{ $location->map_embed_url }}"
                     loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen>
                  </iframe>
               </div>
               @endif
               
               @if($location->coverage_areas && count($location->coverage_areas) > 0)
               <div class="loc-area-list">
                  @foreach($location->coverage_areas as $area)
                  <div class="loc-area">{{ $area['name'] }} <span>{{ $area['details'] }}</span></div>
                  @endforeach
               </div>
               @else
               <div class="loc-area-list">
                  <div class="loc-area">{{ $location->building_name ?? $location->name }} <span>{{ $location->sector ? 'Sector ' . $location->sector . ' • ' : '' }}{{ $location->area }}</span></div>
               </div>
               @endif
            </aside>
         </div>
      </div>
   </section>
   <!-- FAQ SECTION -->
   @if($location->faqs && count($location->faqs) > 0)
   <section class="loc-section" id="locFaq">
      <div class="loc-container">
         <div class="loc-head">
            <span class="loc-badge"><i class="fa-solid fa-circle-question"></i> FAQ</span>
            <h2>{{ $location->building_name ?? $location->name }} milk delivery: quick answers</h2>
            <p>Short and clear answers for this specific building location.</p>
         </div>
         <div class="loc-faq-grid">
            @php
               $halfCount = ceil(count($location->faqs) / 2);
               $firstColumn = array_slice($location->faqs, 0, $halfCount);
               $secondColumn = array_slice($location->faqs, $halfCount);
            @endphp
            
            <div class="loc-faq-col">
               @foreach($firstColumn as $faq)
               <div class="loc-faq-item">
                  <button class="loc-faq-q" type="button">
                     {{ $faq['question'] }}
                     <span class="loc-faq-ico"><i class="fa-solid fa-chevron-down"></i></span>
                  </button>
                  <div class="loc-faq-a">
                     {{ $faq['answer'] }}
                  </div>
               </div>
               @endforeach
            </div>
            
            <div class="loc-faq-col">
               @foreach($secondColumn as $faq)
               <div class="loc-faq-item">
                  <button class="loc-faq-q" type="button">
                     {{ $faq['question'] }}
                     <span class="loc-faq-ico"><i class="fa-solid fa-chevron-down"></i></span>
                  </button>
                  <div class="loc-faq-a">
                     {{ $faq['answer'] }}
                  </div>
               </div>
               @endforeach
            </div>
         </div>
      </div>
   </section>
   @endif
   <!-- CONTACT SECTION -->
   <section class="loc-contact" id="locContact">
      <div class="loc-container">
         <div class="loc-contact-box">
            <div>
               <span class="loc-badge"><i class="fa-solid fa-phone"></i> Contact</span>
               <h2>Confirm coverage for {{ $location->building_name ?? $location->name }}</h2>
               <p>Coverage confirmation ya instructions ke liye call/WhatsApp kar sakte hain.</p>
            </div>
            <div class="loc-contact-actions">
               @if($location->contact_phone)
               <a class="loc-btn loc-btn-primary" href="tel:{{ $location->contact_phone }}"><i class="fa-solid fa-phone"></i> Call</a>
               @endif
               
               @if($location->contact_whatsapp)
               <a class="loc-btn" href="https://wa.me/{{ $location->contact_whatsapp }}" target="_blank" rel="noopener">
                  <i class="fa-brands fa-whatsapp"></i> WhatsApp
               </a>
               @endif
            </div>
         </div>
      </div>
   </section>
</main>
<script>
   (function() {
       const root = document.getElementById("locSinglePage");
       if (!root) return;
       root.querySelectorAll(".loc-faq-item .loc-faq-q").forEach((btn) => {
           btn.addEventListener("click", () => {
               const item = btn.closest(".loc-faq-item");
               const col = item.parentElement;
   
               col.querySelectorAll(".loc-faq-item").forEach(i => {
                   if (i !== item) i.classList.remove("loc-open");
               });
   
               item.classList.toggle("loc-open");
           });
       });
   })();
</script>
@endsection