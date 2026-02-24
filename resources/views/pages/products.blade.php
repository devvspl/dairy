@extends('layouts.public')

@section('title', 'Products')
@section('meta_description', 'Explore our products')

@section('content')

<style>
/* =========================
   BOTTLE MILK PRODUCT LISTING (Scoped)
   ========================= */
#plpgProductPage{
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

#plpgProductPage *{ box-sizing:border-box; }
#plpgProductPage img{ max-width:100%; display:block; }
#plpgProductPage a{ color:inherit; }

#plpgProductPage .container{
  max-width:1250px;
  margin:0 auto;
  padding:0 24px;
}

#plpgProductPage .plpg-kicker{
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
  background:white !important;
  border:1px solid rgba(38,61,24,.12);
}

#plpgProductPage .plpg-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  padding:12px 16px;
  border-radius:16px;
  font-weight:900;
  text-decoration:none;
  border:1px solid var(--border);
  box-shadow:0 14px 34px rgba(0,0,0,.08);
  transition:220ms ease;
  cursor:pointer;
  white-space:nowrap;
  background:#fff;
}
#plpgProductPage .plpg-btn i{ font-size:15px; }

#plpgProductPage .plpg-btn-primary{
  background:var(--brand);
  color:#fff;
  border-color:rgba(38,61,24,.25);
}
#plpgProductPage .plpg-btn-primary:hover{
  transform:translateY(-2px);
  background:var(--accent);
  color:var(--text);
  border-color:rgba(241,204,36,.9);
  box-shadow:0 18px 44px rgba(0,0,0,.12);
}
#plpgProductPage .plpg-btn-outline:hover{
  transform:translateY(-2px);
  border-color:rgba(241,204,36,.85);
  box-shadow:0 18px 44px rgba(0,0,0,.12);
}

#plpgProductPage .plpg-pill{
  display:inline-flex;
  gap:8px;
  align-items:center;
  padding:8px 10px;
  border-radius:999px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.16);
  color:rgba(255,255,255,.92);
  font-weight:850;
  font-size:13px;
  backdrop-filter: blur(6px);
}


#plpgProductPage .plpg-hero{
  position:relative;
  padding:66px 0 54px;
  overflow:hidden;
  background:#0f130e;
}
#plpgProductPage .plpg-hero-bg{
  position:absolute; inset:0;
  background-image:url("images/milk-products-banner.webp");
  background-size:cover;
  background-position:center;
  transform:scale(1.04);
  filter:saturate(1.03);
}
#plpgProductPage .plpg-hero-overlay{
  position:absolute; inset:0;
  background:
    radial-gradient(circle at 18% 20%, rgba(241,204,36,.22), transparent 55%),
    radial-gradient(circle at 82% 70%, rgba(38,61,24,.35), transparent 60%),
    linear-gradient(180deg, rgba(0,0,0,.62), rgba(0,0,0,.72));
}
#plpgProductPage .plpg-hero-inner{ position:relative; z-index:2; text-align:center; color:#fff; }
#plpgProductPage .plpg-hero-inner h1{
  margin:14px 0 10px;
  font-size:clamp(28px,4vw,40px);
  font-weight:950;
  line-height:1.05;
  letter-spacing:-.8px;
}
#plpgProductPage .plpg-hero-inner p{
  margin:0 auto 16px;
  max-width:820px;
  color:rgba(255,255,255,.86);
  font-weight:650;
  line-height:1.8;
}
#plpgProductPage .plpg-hero-row{
  display:flex;
  gap:12px;
  justify-content:center;
  flex-wrap:wrap;
  margin-top:10px;
}
#plpgProductPage .plpg-hero-badges{
  margin-top:18px;
  display:flex;
  justify-content:center;
  flex-wrap:wrap;
  gap:10px;
}

/* BAR */
#plpgProductPage .plpg-bar{
  padding:18px 0;
  background:linear-gradient(180deg,#fff, var(--soft2));
  border-bottom:1px solid rgba(0,0,0,.06);
}
#plpgProductPage .plpg-bar-inner{
  display:flex;
  gap:12px;
  align-items:center;
  justify-content:space-between;
  flex-wrap:wrap;
}
#plpgProductPage .plpg-breadcrumb{
  color:var(--muted);
  font-weight:750;
  font-size:13px;
}
#plpgProductPage .plpg-breadcrumb a{
  color:var(--brand);
  text-decoration:none;
  font-weight:900;
}
#plpgProductPage .plpg-breadcrumb span{ opacity:.9; }

/* LAYOUT */
#plpgProductPage .plpg-main{
  padding:26px 0 70px;
  background:#fff;
}
#plpgProductPage .plpg-grid{
  display:grid;
  grid-template-columns: 330px 1fr;
  gap:18px;
  align-items:start;
}

/* FILTER */
#plpgProductPage .plpg-filter{
  border-radius:26px;
  border:1px solid rgba(0,0,0,.08);
  box-shadow:var(--shadow);
  background:
    radial-gradient(circle at 20% 20%, rgba(241,204,36,0.14), transparent 55%),
    linear-gradient(180deg,#ffffff,#f6f8f2);
  overflow:hidden;
  position:sticky;
  top:18px;
}
#plpgProductPage .plpg-filter-head{
  padding:16px 16px 12px;
  border-bottom:1px solid rgba(0,0,0,.06);
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
}
#plpgProductPage .plpg-filter-head strong{
  font-weight:950;
  color:var(--brand);
  display:flex;
  align-items:center;
  gap:10px;
}
#plpgProductPage .plpg-clear{
  background:#fff;
  border:1px solid rgba(0,0,0,.10);
  border-radius:14px;
  padding:10px 12px;
  font-weight:900;
  cursor:pointer;
  transition:220ms ease;
}
#plpgProductPage .plpg-clear:hover{
  transform:translateY(-2px);
  border-color:rgba(241,204,36,.65);
  box-shadow:0 18px 44px rgba(0,0,0,.10);
}
#plpgProductPage .plpg-filter-body{
  padding:14px 16px 16px;
  display:grid;
  gap:14px;
}
#plpgProductPage .plpg-block{
  background:#fff;
  border:1px solid rgba(0,0,0,.07);
  border-radius:18px;
  padding:12px;
  box-shadow:0 14px 34px rgba(0,0,0,.06);
}
#plpgProductPage .plpg-block-title{
  font-weight:950;
  color:var(--brand);
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
}
#plpgProductPage .plpg-block-title small{
  color:var(--muted);
  font-weight:850;
}
#plpgProductPage .plpg-block-content{
  margin-top:10px;
  display:grid;
  gap:10px;
}
#plpgProductPage .plpg-search{ position:relative; }
#plpgProductPage .plpg-search i{
  position:absolute; left:12px; top:50%; transform:translateY(-50%);
  color:var(--brand); opacity:.9;
}
#plpgProductPage .plpg-search input{
  width:100%;
  height:46px;
  border-radius:14px;
  border:1px solid rgba(0,0,0,.12);
  padding:0 12px 0 40px;
  outline:none;
  font-weight:700;
  background:#fff;
}
#plpgProductPage .plpg-search input:focus{
  border-color:var(--accent);
  box-shadow:0 0 0 4px rgba(241,204,36,.16);
}
#plpgProductPage .plpg-opt{
  display:flex;
  align-items:center;
  gap:10px;
  font-weight:750;
  color:var(--muted);
  font-size:14px;
}
#plpgProductPage .plpg-opt input{
  width:18px; height:18px;
  accent-color: var(--brand);
}
#plpgProductPage .plpg-priceRange{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:10px;
}
#plpgProductPage .plpg-priceRange input{
  width:100%;
  height:46px;
  border-radius:14px;
  border:1px solid rgba(0,0,0,.12);
  padding:0 12px;
  outline:none;
  font-weight:800;
}
#plpgProductPage .plpg-priceRange input:focus{
  border-color:var(--accent);
  box-shadow:0 0 0 4px rgba(241,204,36,.16);
}
#plpgProductPage .plpg-apply{ width:100%; }

/* RESULTS HEADER */
#plpgProductPage .plpg-toprow{
  border-radius:26px;
  border:1px solid rgba(0,0,0,.08);
  box-shadow:var(--shadow);
  background:linear-gradient(180deg,#ffffff,#f7f9f4);
  padding:14px;
  display:flex;
  gap:12px;
  align-items:center;
  justify-content:space-between;
  flex-wrap:wrap;
}
#plpgProductPage .plpg-top-left strong{ color:var(--brand); font-weight:950; }
#plpgProductPage .plpg-top-left span{ color:var(--muted); font-weight:750; font-size:13px; }
#plpgProductPage .plpg-sort{
  height:44px;
  border-radius:16px;
  border:1px solid rgba(0,0,0,.12);
  padding:0 12px;
  font-weight:850;
  outline:none;
  background:#fff;
}
#plpgProductPage .plpg-sort:focus{
  border-color:var(--accent);
  box-shadow:0 0 0 4px rgba(241,204,36,.14);
}

/* PRODUCT GRID */
#plpgProductPage .plpg-cards{
  margin-top:14px;
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:14px;
}
#plpgProductPage .plpg-card{
  border-radius:22px;
  overflow:hidden;
  border:1px solid rgba(0,0,0,.08);
  background:#fff;
  box-shadow:var(--shadow);
  transition:220ms ease;
  position:relative;
}
#plpgProductPage .plpg-card:hover{
  transform:translateY(-7px);
  border-color:rgba(241,204,36,.65);
  box-shadow:var(--shadow2);
}
#plpgProductPage .plpg-badges{
  position:absolute;
  top:12px; left:12px;
  display:flex;
  gap:8px;
  z-index:2;
}
#plpgProductPage .plpg-tag{
  padding:7px 10px;
  border-radius:999px;
  font-weight:950;
  font-size:12px;
  border:1px solid rgba(0,0,0,.08);
  background:rgba(255,255,255,.92);
}
#plpgProductPage .plpg-tag-hot{ border-color:rgba(241,204,36,.75); }
#plpgProductPage .plpg-tag-new{ border-color:rgba(38,61,24,.25); }

#plpgProductPage .plpg-media{
  height:220px;
  background:#0f130e;
  position:relative;
  overflow:hidden;
}
#plpgProductPage .plpg-media img{
  width:100%;
  height:100%;
  object-fit:cover;
  transform:scale(1.02);
}
#plpgProductPage .plpg-media:after{
  content:"";
  position:absolute; inset:0;
  background:linear-gradient(180deg, transparent 55%, rgba(0,0,0,.55));
  opacity:.95;
}
#plpgProductPage .plpg-quick{
  position:absolute;
  right:12px; bottom:12px;
  z-index:3;
  background:rgba(255,255,255,.92);
  border:1px solid rgba(0,0,0,.10);
  border-radius:14px;
  padding:10px 12px;
  font-weight:950;
  cursor:pointer;
  transition:220ms ease;
  display:inline-flex;
  align-items:center;
  gap:8px;
}
#plpgProductPage .plpg-quick:hover{
  transform:translateY(-2px);
  border-color:rgba(241,204,36,.70);
  box-shadow:0 18px 44px rgba(0,0,0,.12);
}

#plpgProductPage .plpg-info{
  padding:14px;
  display:flex;
  flex-direction:column;
  gap:10px;
}
#plpgProductPage .plpg-title{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:10px;
}
#plpgProductPage .plpg-title strong{
  font-weight:950;
  color:var(--brand);
  line-height:1.2;
}
#plpgProductPage .plpg-title a{
  text-decoration:none;
  color:inherit;
  transition:color 0.2s ease;
}
#plpgProductPage .plpg-title a:hover strong{
  color:#f1cc24;
}
#plpgProductPage .plpg-rating{
  display:inline-flex;
  align-items:center;
  gap:6px;
  font-weight:950;
  font-size:12px;
  color:#7a5b00;
  background:rgba(241,204,36,.18);
  border:1px solid rgba(241,204,36,.45);
  padding:6px 9px;
  border-radius:999px;
  white-space:nowrap;
}
#plpgProductPage .plpg-desc{
  color:var(--muted);
  font-weight:650;
  line-height:1.65;
  font-size:14px;
  margin-top:-2px;
}
#plpgProductPage .plpg-meta{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
  flex-wrap:wrap;
}
#plpgProductPage .plpg-pricebox{
  display:flex;
  align-items:baseline;
  gap:8px;
}
#plpgProductPage .plpg-price{
  font-weight:950;
  color:var(--brand);
  font-size:18px;
}
#plpgProductPage .plpg-mrp{
  color:rgba(92,107,85,.9);
  font-weight:850;
  font-size:13px;
  text-decoration:line-through;
}
#plpgProductPage .plpg-actions{
  display:flex;
  gap:10px;
}
#plpgProductPage .plpg-iconbtn{
  width:44px; height:44px;
  border-radius:16px;
  border:1px solid rgba(0,0,0,.10);
  background:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  transition:220ms ease;
}
#plpgProductPage .plpg-iconbtn:hover{
  transform:translateY(-2px);
  border-color:rgba(241,204,36,.65);
  box-shadow:0 18px 44px rgba(0,0,0,.10);
}
#plpgProductPage .plpg-iconbtn i.fa-solid.fa-heart{
  color:#e74c3c;
}
#plpgProductPage .plpg-iconbtn:hover i.fa-solid.fa-heart{
  color:#c0392b;
}

/* PAGINATION */
#plpgProductPage .plpg-pagination{
  margin-top:18px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  flex-wrap:wrap;
}
#plpgProductPage .plpg-page{
  width:44px; height:44px;
  border-radius:16px;
  border:1px solid rgba(0,0,0,.10);
  background:#fff;
  font-weight:950;
  cursor:pointer;
  transition:220ms ease;
}
#plpgProductPage .plpg-page:hover{
  transform:translateY(-2px);
  border-color:rgba(241,204,36,.65);
  box-shadow:0 18px 44px rgba(0,0,0,.10);
}
#plpgProductPage .plpg-page.active{
  background:var(--brand);
  color:#fff;
  border-color:rgba(38,61,24,.25);
}

/* MOBILE FILTER BAR */
#plpgProductPage .plpg-mbar{
  display:none;
  position:sticky;
  top:0;
  z-index:50;
  padding:10px 0;
  background:rgba(255,255,255,.92);
  border-bottom:1px solid rgba(0,0,0,.06);
  backdrop-filter: blur(10px);
}
#plpgProductPage .plpg-mbar-inner{
  display:flex;
  gap:10px;
  align-items:center;
  justify-content:space-between;
}
#plpgProductPage .plpg-mbar strong{
  color:var(--brand);
  font-weight:950;
}
#plpgProductPage .plpg-mfilter-btn{
  border:1px solid rgba(0,0,0,.12);
  background:#fff;
  border-radius:16px;
  padding:11px 14px;
  font-weight:950;
  display:inline-flex;
  align-items:center;
  gap:10px;
  cursor:pointer;
}

/* FILTER DRAWER */
#plpgProductPage .plpg-drawer{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.48);
  z-index:100;
  display:none;
}
#plpgProductPage .plpg-drawer.open{ display:block; }
#plpgProductPage .plpg-drawer-panel{
  position:absolute;
  right:0; top:0; bottom:0;
  width:min(92vw, 420px);
  background:#fff;
  border-left:1px solid rgba(255,255,255,.12);
  box-shadow:0 26px 90px rgba(0,0,0,.25);
  padding:14px;
  overflow:auto;
  border-top-left-radius:22px;
  border-bottom-left-radius:22px;
}
#plpgProductPage .plpg-drawer-top{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
  padding:6px 2px 12px;
}
#plpgProductPage .plpg-close{
  width:44px; height:44px;
  border-radius:16px;
  border:1px solid rgba(0,0,0,.10);
  background:#fff;
  cursor:pointer;
  font-weight:950;
}

/* MODAL */
#plpgProductPage .plpg-modal{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.55);
  z-index:120;
  display:none;
  padding:16px;
}
#plpgProductPage .plpg-modal.open{ display:flex; align-items:center; justify-content:center; }
#plpgProductPage .plpg-modal-box{
  width:min(980px, 100%);
  background:#fff;
  border-radius:26px;
  border:1px solid rgba(0,0,0,.08);
  box-shadow:0 26px 90px rgba(0,0,0,.25);
  overflow:hidden;
}
#plpgProductPage .plpg-modal-head{
  padding:14px 16px;
  border-bottom:1px solid rgba(0,0,0,.06);
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
}
#plpgProductPage .plpg-modal-head strong{ color:var(--brand); font-weight:950; }
#plpgProductPage .plpg-modal-body{
  display:grid;
  grid-template-columns: 1fr 1.05fr;
}
#plpgProductPage .plpg-modal-media{
  min-height:320px;
  background:#0f130e;
}
#plpgProductPage .plpg-modal-media img{
  width:100%;
  height:100%;
  object-fit:cover;
}
#plpgProductPage .plpg-modal-info{
  padding:16px;
  display:grid;
  gap:10px;
}
#plpgProductPage .plpg-modal-info p{
  margin:0;
  color:var(--muted);
  font-weight:650;
  line-height:1.75;
}
#plpgProductPage .plpg-feats{
  display:grid;
  gap:10px;
  margin-top:2px;
}
#plpgProductPage .plpg-feat{
  display:flex;
  gap:10px;
  align-items:flex-start;
  padding:12px;
  border-radius:18px;
  background:linear-gradient(180deg,#fff, var(--soft2));
  border:1px solid rgba(0,0,0,.06);
}
#plpgProductPage .plpg-feat i{
  width:36px; height:36px;
  border-radius:14px;
  display:flex; align-items:center; justify-content:center;
  background:rgba(241,204,36,.18);
  color:#7a5b00;
  flex:0 0 36px;
}
#plpgProductPage .plpg-feat strong{ display:block; font-weight:950; color:var(--brand); }
#plpgProductPage .plpg-feat span{ display:block; color:var(--muted); font-weight:650; font-size:13px; margin-top:2px; }

/* RESPONSIVE */
@media (max-width:1100px){
  #plpgProductPage .plpg-cards{ grid-template-columns:repeat(2,1fr); }
}
@media (max-width:980px){
  #plpgProductPage .plpg-grid{ grid-template-columns:1fr; }
  #plpgProductPage .plpg-filter{ display:none; position:static; }
  #plpgProductPage .plpg-mbar{ display:block; }
  #plpgProductPage .plpg-modal-body{ grid-template-columns:1fr; }
}
@media (max-width:560px){
  #plpgProductPage .container{ padding:0 16px; }
  #plpgProductPage .plpg-cards{ grid-template-columns:1fr; }
  #plpgProductPage .plpg-hero{ padding:58px 0 46px; }
  #plpgProductPage .plpg-hero-inner p{ font-size:14px; }
}
</style>

<main id="plpgProductPage">

  <!-- HERO -->
  <section class="plpg-hero">
    <div class="plpg-hero-bg"></div>
    <div class="plpg-hero-overlay"></div>

    <div class="container plpg-hero-inner">
      <span class="plpg-kicker"><i class="fa-solid fa-bottle-water"></i> Our Products</span>
      <h1>Fresh Dairy Collection</h1>
      <p>Daily essentials with clean handling, consistent quality, and quick dispatch.</p>

      <div class="plpg-hero-row">
        <a href="#plpgList" class="plpg-btn plpg-btn-primary"><i class="fa-solid fa-store"></i> Browse Products</a>
      </div>

      <div class="plpg-hero-badges">
        <span class="plpg-pill"><i class="fa-solid fa-snowflake"></i> Cold Chain Care</span>
        <span class="plpg-pill"><i class="fa-solid fa-circle-check"></i> Hygienic Handling</span>
        <span class="plpg-pill"><i class="fa-solid fa-truck-fast"></i> Quick Delivery</span>
      </div>
    </div>
  </section>

  <!-- BAR -->
  <section class="plpg-bar">
    <div class="container plpg-bar-inner">
      <div class="plpg-breadcrumb">
        <a href="{{ route('home') }}">Home</a> <span> / </span> <span>Products</span>
      </div>
      <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <span class="plpg-kicker"><i class="fa-solid fa-leaf"></i> Fresh</span>
        <span style="color:var(--muted); font-weight:800; font-size:13px;">Same-day dispatch (selected areas) • COD Available</span>
      </div>
    </div>
  </section>

 
  <section class="plpg-mbar">
    <div class="container plpg-mbar-inner">
      <strong><i class="fa-solid fa-sliders"></i> Filters</strong>
      <button class="plpg-mfilter-btn" type="button" id="plpgOpenFilter">
        <i class="fa-solid fa-filter"></i> Open
      </button>
    </div>
  </section>

  <!-- MAIN -->
  <section class="plpg-main" id="plpgList">
    <div class="container plpg-grid">

      <!-- FILTER SIDEBAR (Desktop) -->
      <aside class="plpg-filter" aria-label="Product Filters">
        <div class="plpg-filter-head">
          <strong><i class="fa-solid fa-sliders"></i> Filters</strong>
          <button type="button" class="plpg-clear" id="plpgClearFilters"><i class="fa-solid fa-rotate-left"></i> Clear</button>
        </div>

        <div class="plpg-filter-body">
          <div class="plpg-block">
            <div class="plpg-block-title">
              <span><i class="fa-solid fa-magnifying-glass"></i> Search</span>
              <small>Type name</small>
            </div>
            <div class="plpg-block-content">
              <div class="plpg-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="plpgSearch" placeholder="Search bottle milk products...">
              </div>
            </div>
          </div>

          <div class="plpg-block">
            <div class="plpg-block-title">
              <span><i class="fa-solid fa-layer-group"></i> Type</span>
              <small>Choose</small>
            </div>
            <div class="plpg-block-content">
              @foreach($types as $type)
              <label class="plpg-opt"><input type="checkbox" class="plpgType" value="{{ $type->slug }}"> {{ $type->name }}</label>
              @endforeach
            </div>
          </div>

          <div class="plpg-block">
            <div class="plpg-block-title">
              <span><i class="fa-solid fa-indian-rupee-sign"></i> Price Range</span>
              <small>Min/Max</small>
            </div>
            <div class="plpg-block-content">
              <div class="plpg-priceRange">
                <input type="number" id="plpgMin" placeholder="Min" min="0">
                <input type="number" id="plpgMax" placeholder="Max" min="0">
              </div>
              <button class="plpg-btn plpg-btn-primary plpg-apply" type="button" id="plpgApply">
                <i class="fa-solid fa-check"></i> Apply Filters
              </button>
            </div>
          </div>

          <div class="plpg-block">
            <div class="plpg-block-title">
              <span><i class="fa-solid fa-star"></i> Rating</span>
              <small>Minimum</small>
            </div>
            <div class="plpg-block-content">
              <label class="plpg-opt"><input type="radio" name="rating" class="plpgRate" value="4" checked> 4.0+ stars</label>
              <label class="plpg-opt"><input type="radio" name="rating" class="plpgRate" value="3"> 3.0+ stars</label>
              <label class="plpg-opt"><input type="radio" name="rating" class="plpgRate" value="0"> Any rating</label>
            </div>
          </div>
        </div>
      </aside>

      <!-- RESULTS -->
      <div class="plpg-results">
        <div class="plpg-toprow">
          <div class="plpg-top-left">
            <strong id="plpgCountText">Showing products</strong>
            <span>Bottle packaging • Fresh handling • Consistent supply</span>
          </div>

          <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <select class="plpg-sort" id="plpgSort">
              <option value="featured">Sort: Featured</option>
              <option value="priceLow">Price: Low to High</option>
              <option value="priceHigh">Price: High to Low</option>
              <option value="ratingHigh">Rating: High to Low</option>
              <option value="nameAZ">Name: A to Z</option>
            </select>
            <button class="plpg-btn plpg-btn-outline" type="button" id="viewCartBtn"><i class="fa-solid fa-bag-shopping"></i> View Cart</button>
          </div>
        </div>

        <div class="plpg-cards" id="plpgCards">

          @forelse($products as $product)
          <article class="plpg-card" 
            data-name="{{ $product->name }}" 
            data-type="{{ $product->type ? $product->type->name : '' }}" 
            data-price="{{ $product->price }}" 
            data-rating="{{ $product->rating }}"
            data-product-id="{{ $product->id }}"
            data-product-name="{{ $product->name }}"
            data-product-price="{{ $product->price }}"
            data-product-image="{{ asset($product->main_image) }}"
            data-product-slug="{{ $product->slug }}">
            @if($product->badge)
            <div class="plpg-badges">
              <span class="plpg-tag plpg-tag-{{ $product->badge_color }}">
                <i class="fa-solid fa-{{ $product->badge_color == 'hot' ? 'fire' : 'sparkles' }}"></i> {{ $product->badge }}
              </span>
            </div>
            @endif
            <div class="plpg-media">
              <img src="{{ asset($product->main_image) }}" alt="{{ $product->name }}">
              <button class="plpg-quick" type="button" data-quick="{{ $product->name }}"><i class="fa-solid fa-eye"></i> Quick View</button>
            </div>
            <div class="plpg-info">
              <div class="plpg-title">
                <a href="{{ route('product.detail', $product->slug) }}" style="text-decoration: none; color: inherit;">
                  <strong>{{ $product->name }}</strong>
                </a>
                <span class="plpg-rating"><i class="fa-solid fa-star"></i> {{ number_format($product->rating, 1) }}</span>
              </div>
              <div class="plpg-desc">{{ $product->short_description ?? $product->meta }}</div>
              <div class="plpg-meta">
                <div class="plpg-pricebox">
                  <span class="plpg-price">₹{{ number_format($product->price, 0) }}</span>
                  @if($product->mrp)
                  <span class="plpg-mrp">₹{{ number_format($product->mrp, 0) }}</span>
                  @endif
                </div>
                <div class="plpg-actions">
                  <button class="plpg-iconbtn wishlist-btn" type="button" title="Wishlist" data-product-id="{{ $product->id }}"><i class="fa-regular fa-heart"></i></button>
                  <a class="plpg-btn plpg-btn-primary" href="{{ route('product.detail', $product->slug) }}"><i class="fa-solid fa-eye"></i> View</a>
                </div>
              </div>
            </div>
          </article>
          @empty
          <div style="grid-column: 1/-1; text-align:center; padding:40px;">
            <p style="color:var(--muted); font-size:18px;">No products found. Try adjusting your filters.</p>
          </div>
          @endforelse

        </div>

        <div class="plpg-pagination" aria-label="Pagination">
          @if($products->hasPages())
            @if(!$products->onFirstPage())
            <a href="{{ $products->previousPageUrl() }}" class="plpg-page"><i class="fa-solid fa-chevron-left"></i></a>
            @endif
            
            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
              <a href="{{ $url }}" class="plpg-page {{ $page == $products->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            
            @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="plpg-page"><i class="fa-solid fa-chevron-right"></i></a>
            @endif
          @endif
        </div>

      </div>
    </div>
  </section>

  <!-- MOBILE FILTER DRAWER -->
  <div class="plpg-drawer" id="plpgDrawer" aria-hidden="true">
    <div class="plpg-drawer-panel">
      <div class="plpg-drawer-top">
        <strong style="color:var(--brand); font-weight:950;"><i class="fa-solid fa-sliders"></i> Filters</strong>
        <button class="plpg-close" type="button" id="plpgCloseFilter"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <div class="plpg-filter-body" style="padding:0;">
        <div class="plpg-block">
          <div class="plpg-block-title"><span><i class="fa-solid fa-magnifying-glass"></i> Search</span><small>Type</small></div>
          <div class="plpg-block-content">
            <div class="plpg-search">
              <i class="fa-solid fa-magnifying-glass"></i>
              <input type="text" id="plpgSearchM" placeholder="Search bottle milk products...">
            </div>
          </div>
        </div>

        <div class="plpg-block">
          <div class="plpg-block-title"><span><i class="fa-solid fa-layer-group"></i> Type</span><small>Choose</small></div>
          <div class="plpg-block-content">
            @foreach($types as $type)
            <label class="plpg-opt"><input type="checkbox" class="plpgTypeM" value="{{ $type->slug }}"> {{ $type->name }}</label>
            @endforeach
          </div>
        </div>

        <div class="plpg-block">
          <div class="plpg-block-title"><span><i class="fa-solid fa-indian-rupee-sign"></i> Price Range</span><small>Min/Max</small></div>
          <div class="plpg-block-content">
            <div class="plpg-priceRange">
              <input type="number" id="plpgMinM" placeholder="Min" min="0">
              <input type="number" id="plpgMaxM" placeholder="Max" min="0">
            </div>
          </div>
        </div>

        <div class="plpg-block">
          <div class="plpg-block-title"><span><i class="fa-solid fa-star"></i> Rating</span><small>Min</small></div>
          <div class="plpg-block-content">
            <label class="plpg-opt"><input type="radio" name="ratingM" class="plpgRateM" value="4" checked> 4.0+ stars</label>
            <label class="plpg-opt"><input type="radio" name="ratingM" class="plpgRateM" value="3"> 3.0+ stars</label>
            <label class="plpg-opt"><input type="radio" name="ratingM" class="plpgRateM" value="0"> Any rating</label>
          </div>
        </div>

        <button class="plpg-btn plpg-btn-primary" type="button" id="plpgApplyM" style="width:100%; height:50px;">
          <i class="fa-solid fa-check"></i> Apply Filters
        </button>

        <button class="plpg-btn" type="button" id="plpgClearFiltersM" style="width:100%; height:50px;">
          <i class="fa-solid fa-rotate-left"></i> Clear
        </button>
      </div>

    </div>
  </div>

  <!-- QUICK VIEW MODAL -->
  <div class="plpg-modal" id="plpgModal" aria-hidden="true">
    <div class="plpg-modal-box" role="dialog" aria-modal="true">
      <div class="plpg-modal-head">
        <strong><i class="fa-solid fa-eye"></i> Quick View</strong>
        <button class="plpg-close" type="button" id="plpgCloseModal"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <div class="plpg-modal-body">
        <div class="plpg-modal-media"><img id="plpgModalImg" src="" alt="Product"></div>

        <div class="plpg-modal-info">
          <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap;">
            <div style="display:flex; flex-direction:column; gap:2px;">
              <strong style="color:var(--brand); font-weight:950; font-size:20px;" id="plpgModalName">Product Name</strong>
              <span style="color:var(--muted); font-weight:750; font-size:13px;" id="plpgModalCat">Type</span>
            </div>
            <span class="plpg-rating" id="plpgModalRating"><i class="fa-solid fa-star"></i> 4.6</span>
          </div>

          <p id="plpgModalDesc">Fresh bottle milk product — hygienic handling and consistent quality.</p>

          <div class="plpg-feats">
            <div class="plpg-feat"><i class="fa-solid fa-circle-check"></i><div><strong>Hygienic Bottling</strong><span>Clean handling and sealed packaging.</span></div></div>
            <div class="plpg-feat"><i class="fa-solid fa-snowflake"></i><div><strong>Cold Chain Care</strong><span>Maintains freshness during delivery.</span></div></div>
            <div class="plpg-feat"><i class="fa-solid fa-truck-fast"></i><div><strong>Quick Delivery</strong><span>Fast dispatch in selected areas.</span></div></div>
          </div>

          <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-top:2px;">
            <div class="plpg-pricebox">
              <span class="plpg-price" id="plpgModalPrice">₹0</span>
              <span class="plpg-mrp" id="plpgModalMrp">₹0</span>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
              <button class="plpg-iconbtn" type="button" title="Wishlist"><i class="fa-regular fa-heart"></i></button>
              <a class="plpg-btn plpg-btn-primary" href="#"><i class="fa-solid fa-cart-shopping"></i> Add to Cart</a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

</main>

<script>
(function(){
  const root = document.getElementById("plpgProductPage");
  if(!root) return;

  const cardsWrap = root.querySelector("#plpgCards");
  const countText = root.querySelector("#plpgCountText");

  // Desktop inputs
  const q = root.querySelector("#plpgSearch");
  const types = () => Array.from(root.querySelectorAll(".plpgType:checked")).map(i=>i.value);
  const minEl = root.querySelector("#plpgMin");
  const maxEl = root.querySelector("#plpgMax");
  const rateEl = () => root.querySelector(".plpgRate:checked")?.value || "0";
  const sortEl = root.querySelector("#plpgSort");

  // Mobile inputs
  const qM = root.querySelector("#plpgSearchM");
  const typesM = () => Array.from(root.querySelectorAll(".plpgTypeM:checked")).map(i=>i.value);
  const minM = root.querySelector("#plpgMinM");
  const maxM = root.querySelector("#plpgMaxM");
  const rateM = () => root.querySelector(".plpgRateM:checked")?.value || "0";

  // Drawer
  const drawer = root.querySelector("#plpgDrawer");
  root.querySelector("#plpgOpenFilter")?.addEventListener("click", ()=> drawer.classList.add("open"));
  root.querySelector("#plpgCloseFilter")?.addEventListener("click", ()=> drawer.classList.remove("open"));
  drawer?.addEventListener("click", (e)=>{ if(e.target === drawer) drawer.classList.remove("open"); });

  // Modal
  const modal = root.querySelector("#plpgModal");
  const closeModal = root.querySelector("#plpgCloseModal");
  const modalName = root.querySelector("#plpgModalName");
  const modalCat = root.querySelector("#plpgModalCat");
  const modalImg = root.querySelector("#plpgModalImg");
  const modalRating = root.querySelector("#plpgModalRating");
  const modalDesc = root.querySelector("#plpgModalDesc");
  const modalPrice = root.querySelector("#plpgModalPrice");
  const modalMrp = root.querySelector("#plpgModalMrp");

  const openQuick = (productData)=>{
    const rating = parseFloat(productData.rating) || 0;
    modalName.textContent = productData.name || "Product";
    modalCat.textContent = "Type: " + (productData.category || "type");
    modalImg.src = productData.image || "";
    modalRating.innerHTML = '<i class="fa-solid fa-star"></i> ' + rating.toFixed(1);
    modalDesc.textContent = productData.short_description || "Fresh bottle milk product — hygienic handling, sealed packaging, and consistent quality.";
    modalPrice.textContent = "₹" + Math.round(productData.price || 0);
    modalMrp.textContent = productData.mrp ? "₹" + Math.round(productData.mrp) : "";

    modal.classList.add("open");
    modal.setAttribute("aria-hidden","false");
  };

  closeModal?.addEventListener("click", ()=>{
    modal.classList.remove("open");
    modal.setAttribute("aria-hidden","true");
  });
  modal?.addEventListener("click", (e)=>{
    if(e.target === modal){
      modal.classList.remove("open");
      modal.setAttribute("aria-hidden","true");
    }
  });

  // Render products from API response
  const renderProducts = (products)=>{
    if(!products || products.length === 0){
      cardsWrap.innerHTML = '<div style="grid-column: 1/-1; text-align:center; padding:40px;"><p style="color:var(--muted); font-size:18px;">No products found. Try adjusting your filters.</p></div>';
      return;
    }

    cardsWrap.innerHTML = products.map(product=>{
      const rating = parseFloat(product.rating) || 0;
      const badgeHtml = product.badge ? `
        <div class="plpg-badges">
          <span class="plpg-tag plpg-tag-${product.badge_color || 'new'}">
            <i class="fa-solid fa-${product.badge_color === 'hot' ? 'fire' : 'sparkles'}"></i> ${product.badge}
          </span>
        </div>
      ` : '';

      const mrpHtml = product.mrp ? `<span class="plpg-mrp">₹${Math.round(product.mrp)}</span>` : '';

      return `
        <article class="plpg-card" data-product='${JSON.stringify(product)}'>
          ${badgeHtml}
          <div class="plpg-media">
            <img src="${product.image || ''}" alt="${product.name || 'Product'}">
            <button class="plpg-quick" type="button" data-quick="true"><i class="fa-solid fa-eye"></i> Quick View</button>
          </div>
          <div class="plpg-info">
            <div class="plpg-title">
              <strong>${product.name || 'Product'}</strong>
              <span class="plpg-rating"><i class="fa-solid fa-star"></i> ${rating.toFixed(1)}</span>
            </div>
            <div class="plpg-desc">${product.short_description || ''}</div>
            <div class="plpg-meta">
              <div class="plpg-pricebox">
                <span class="plpg-price">₹${Math.round(product.price || 0)}</span>
                ${mrpHtml}
              </div>
              <div class="plpg-actions">
                <button class="plpg-iconbtn" type="button" title="Wishlist"><i class="fa-regular fa-heart"></i></button>
                <a class="plpg-btn plpg-btn-primary" href="${product.url || '#'}"><i class="fa-solid fa-eye"></i> View</a>
              </div>
            </div>
          </div>
        </article>
      `;
    }).join('');

    // Attach quick view handlers
    cardsWrap.querySelectorAll("[data-quick]").forEach(btn=>{
      btn.addEventListener("click", ()=>{
        const card = btn.closest(".plpg-card");
        if(card){
          const productData = JSON.parse(card.dataset.product);
          openQuick(productData);
        }
      });
    });
  };

  // AJAX filter function
  let isLoading = false;
  const applyFilters = async (opts)=>{
    if(isLoading) return;
    isLoading = true;

    const query = (opts.query || "").trim();
    const selectedTypes = opts.types || [];
    const min = Number(opts.min || 0);
    const max = Number(opts.max || 0);
    const minRating = Number(opts.rating || 0);
    const sort = opts.sort || "featured";

    // Build URL params
    const params = new URLSearchParams();
    if(query) params.set('search', query);
    if(selectedTypes.length > 0) params.set('type', selectedTypes.join(','));
    if(min > 0) params.set('min_price', min);
    if(max > 0) params.set('max_price', max);
    if(minRating > 0) params.set('min_rating', minRating);
    if(sort !== 'featured') params.set('sort', sort);

    // Update URL without reload
    const newUrl = params.toString() ? `${window.location.pathname}?${params.toString()}` : window.location.pathname;
    window.history.pushState({}, '', newUrl);

    // Show loading state
    cardsWrap.innerHTML = '<div style="grid-column: 1/-1; text-align:center; padding:40px;"><i class="fa-solid fa-spinner fa-spin" style="font-size:32px; color:var(--brand);"></i><p style="color:var(--muted); margin-top:16px;">Loading products...</p></div>';

    try {
      const response = await fetch(`/api/filter-products?${params.toString()}`);
      const data = await response.json();

      renderProducts(data.products);
      countText.textContent = `Showing ${data.total} products`;
    } catch (error) {
      console.error('Filter error:', error);
      cardsWrap.innerHTML = '<div style="grid-column: 1/-1; text-align:center; padding:40px;"><p style="color:var(--muted); font-size:18px;">Error loading products. Please try again.</p></div>';
    } finally {
      isLoading = false;
    }
  };

  // Initialize from URL parameters
  const initFromUrl = ()=>{
    const params = new URLSearchParams(window.location.search);
    
    // Set search
    const search = params.get('search') || '';
    q.value = search;
    qM.value = search;

    // Set types (using slugs)
    const typeSlugs = params.get('type') ? params.get('type').split(',') : [];
    root.querySelectorAll(".plpgType").forEach(i=> i.checked = typeSlugs.includes(i.value));
    root.querySelectorAll(".plpgTypeM").forEach(i=> i.checked = typeSlugs.includes(i.value));

    // Set price range
    const minPrice = params.get('min_price') || '';
    const maxPrice = params.get('max_price') || '';
    minEl.value = minPrice;
    maxEl.value = maxPrice;
    minM.value = minPrice;
    maxM.value = maxPrice;

    // Set rating
    const minRating = params.get('min_rating') || '0';
    root.querySelectorAll(".plpgRate").forEach(i=> i.checked = (i.value === minRating));
    root.querySelectorAll(".plpgRateM").forEach(i=> i.checked = (i.value === minRating));

    // Set sort
    const sort = params.get('sort') || 'featured';
    sortEl.value = sort;

    // Apply filters if any params exist
    if(params.toString()){
      applyFilters({ 
        query: search, 
        types: typeSlugs, 
        min: minPrice, 
        max: maxPrice, 
        rating: minRating,
        sort: sort
      });
    }
  };

  // Desktop Apply
  root.querySelector("#plpgApply")?.addEventListener("click", ()=>{
    applyFilters({ 
      query: q.value, 
      types: types(), 
      min: minEl.value, 
      max: maxEl.value, 
      rating: rateEl(),
      sort: sortEl.value
    });
  });

  // Desktop Clear
  root.querySelector("#plpgClearFilters")?.addEventListener("click", ()=>{
    q.value = "";
    minEl.value = "";
    maxEl.value = "";
    root.querySelectorAll(".plpgType").forEach(i=> i.checked = false);
    root.querySelectorAll(".plpgRate").forEach(i=> i.checked = (i.value==="0"));
    sortEl.value = "featured";
    applyFilters({ query: "", types: [], min: 0, max: 0, rating: 0, sort: "featured" });
  });

  // Sort change
  sortEl?.addEventListener("change", ()=>{
    applyFilters({ 
      query: q.value, 
      types: types(), 
      min: minEl.value, 
      max: maxEl.value, 
      rating: rateEl(),
      sort: sortEl.value
    });
  });

  // Mobile Apply
  root.querySelector("#plpgApplyM")?.addEventListener("click", ()=>{
    applyFilters({ 
      query: qM.value, 
      types: typesM(), 
      min: minM.value, 
      max: maxM.value, 
      rating: rateM(),
      sort: sortEl.value
    });
    drawer.classList.remove("open");
  });

  // Mobile Clear
  root.querySelector("#plpgClearFiltersM")?.addEventListener("click", ()=>{
    qM.value = "";
    minM.value = "";
    maxM.value = "";
    root.querySelectorAll(".plpgTypeM").forEach(i=> i.checked = false);
    root.querySelectorAll(".plpgRateM").forEach(i=> i.checked = (i.value==="0"));
    applyFilters({ query: "", types: [], min: 0, max: 0, rating: 0, sort: sortEl.value });
  });

  // Initialize from URL on page load
  initFromUrl();
})();

// Cart and Wishlist Event Listeners for Products Page
(function() {
  // Wait for DairyCart to be available
  function initCartWishlist() {
    if (!window.DairyCart) {
      console.log('Waiting for DairyCart on products page...');
      setTimeout(initCartWishlist, 100);
      return;
    }

    console.log('DairyCart loaded on products page, initializing wishlist');

    // Wishlist buttons
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        // Find the parent card (article element)
        const card = this.closest('article.plpg-card');
        if (!card) {
          console.error('Card not found for wishlist button:', this);
          return;
        }

        const productId = parseInt(card.getAttribute('data-product-id'));
        const product = {
          id: productId,
          name: card.getAttribute('data-product-name'),
          price: parseFloat(card.getAttribute('data-product-price')),
          image: card.getAttribute('data-product-image'),
          slug: card.getAttribute('data-product-slug')
        };

        const isAdded = window.DairyCart.toggleWishlist(product);
        const icon = this.querySelector('i');
        if (icon) {
          icon.className = isAdded ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
        }
      });
    });

    // Update wishlist button states on page load
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
      const card = btn.closest('article.plpg-card');
      if (!card) return;
      
      const productId = parseInt(card.getAttribute('data-product-id'));
      if (window.DairyCart.isInWishlist(productId)) {
        const icon = btn.querySelector('i');
        if (icon) {
          icon.className = 'fa-solid fa-heart';
        }
      }
    });
  }

  // Start initialization
  initCartWishlist();
})();

// View Cart Button Handler
(function() {
  const viewCartBtn = document.getElementById('viewCartBtn');
  if (viewCartBtn) {
    viewCartBtn.addEventListener('click', function() {
      // Trigger the cart button click in the header
      const cartBtn = document.getElementById('cartBtn');
      if (cartBtn) {
        cartBtn.click();
      }
    });
  }
})();
</script>

@endsection
