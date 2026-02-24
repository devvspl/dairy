@extends('layouts.public')

@section('title', $product->name)
@section('meta_description', $product->short_description ?? $product->meta)

@section('content')

<style>
 

  .apw-pd-wrap{padding:22px 0 60px;background:#fff;color:#1f2a1a;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
  .apw-pd-wrap *{box-sizing:border-box}
  .apw-pd-wrap a{color:inherit;text-decoration:none}
  .apw-pd-wrap img{max-width:100%;display:block}
  .apw-pd-wrap button,
  .apw-pd-wrap input{font-family:inherit}

  .apw-pd-container{width:min(1220px, calc(100% - 32px));margin:0 auto}

  /* Breadcrumb */
  .apw-pd-crumbs{display:flex;flex-wrap:wrap;gap:10px;align-items:center;color:#5c6b55;font-size:14px;margin:6px 0 18px}
  .apw-pd-crumbs a{color:#5c6b55}
  .apw-pd-crumbs .apw-pd-sep{opacity:.6}

  /* Premium gradient (scoped) */
  .apw-pd-boxbg{
    background:
      radial-gradient(circle at 20% 20%, rgba(241, 204, 36, 0.16), transparent 55%),
      linear-gradient(180deg, #ffffff, #f6f8f2);
  }

  
  .apw-pd-banner{
    border:1px solid rgba(31,42,26,.10);
    border-radius:26px;
    overflow:hidden;
    box-shadow:0 14px 30px rgba(20,25,18,.08);
    margin:0 0 18px;
    position:relative;
  }
  .apw-pd-banner__in{
    display:grid;
    grid-template-columns: 1.1fr .9fr;
    gap:18px;
    padding:18px;
    align-items:center;
  }
  .apw-pd-banner__copy small{
    display:inline-flex;gap:8px;align-items:center;
    font-weight:900;
    font-size:12px;
    color:#293e8a;
    background:rgba(41,62,138,.08);
    border:1px solid rgba(41,62,138,.18);
    padding:7px 10px;
    border-radius:999px;
  }
  .apw-pd-banner__copy h2{
    margin:10px 0 8px;
    font-size:26px;
    line-height:1.15;
    letter-spacing:-.3px;
  }
  .apw-pd-banner__copy p{
    margin:0;
    color:#5c6b55;
    line-height:1.7;
    font-size:14px;
  }
  .apw-pd-banner__cta{
    display:flex;gap:10px;flex-wrap:wrap;
    margin-top:12px;
  }
  .apw-pd-banner__img{
    border-radius:20px;
    border:1px solid rgba(31,42,26,.10);
    overflow:hidden;
    background:#fff;
    box-shadow:0 12px 26px rgba(20,25,18,.06);
  }
  .apw-pd-banner__img img{width:100%;height:100%;object-fit:cover;aspect-ratio:16/10}

 
  .apw-pd-grid{
    display:grid;
    grid-template-columns: 1.05fr .95fr;
    gap:22px;
    align-items:start;
  }

  
  .apw-pd-card{
    border-radius:24px;
    border:1px solid rgba(31,42,26,.10);
    box-shadow:0 10px 25px rgba(20,25,18,.08);
    overflow:hidden;
    background:#fff;
  }
  .apw-pd-card.apw-pd-pad{padding:18px}

 
  .apw-pd-gallery{position:sticky;top:18px}
  .apw-pd-gallery__in{padding:16px}
  .apw-pd-mainshot{
    border-radius:18px;
    overflow:hidden;
    background:#fff;
    border:1px solid rgba(31,42,26,.10);
    box-shadow:0 10px 25px rgba(20,25,18,.06);
  }
  .apw-pd-mainshot img{width:100%;aspect-ratio:4/3;object-fit:cover}

  .apw-pd-thumbs{margin-top:14px;display:grid;grid-template-columns:repeat(5,1fr);gap:10px}
  .apw-pd-thumb{
    border-radius:14px;
    overflow:hidden;
    border:1px solid rgba(31,42,26,.10);
    background:#fff;
    cursor:pointer;
    transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    padding:0;
  }
  .apw-pd-thumb img{width:100%;aspect-ratio:1/1;object-fit:cover}
  .apw-pd-thumb:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(20,25,18,.08)}
  .apw-pd-thumb.is-active{border-color:rgba(41,62,138,.45);box-shadow:0 14px 28px rgba(41,62,138,.12)}

  
  .apw-pd-titleRow{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:space-between}
  .apw-pd-h1{font-size:30px;line-height:1.18;margin:0 0 8px;letter-spacing:-.3px}
  .apw-pd-sub{color:#5c6b55;font-size:14px;margin:0}


  .apw-pd-badges{display:flex;flex-wrap:wrap;gap:10px;margin:14px 0 12px}
  .apw-pd-badge{
    display:inline-flex;align-items:center;gap:8px;
    padding:8px 12px;border-radius:999px;
    background:rgba(41,62,138,.08);
    border:1px solid rgba(41,62,138,.18);
    color:#293e8a;
    font-weight:800;font-size:13px;
  }
  .apw-pd-badge.accent{
    background:rgba(241,204,36,.18);
    border:1px solid rgba(241,204,36,.55);
    color:#5b4a00;
  }
  .apw-pd-dot{width:8px;height:8px;border-radius:99px;background:currentColor;opacity:.9}

  
  .apw-pd-priceBox{
    margin-top:14px;
    border:1px solid rgba(31,42,26,.10);
    border-radius:18px;
    padding:14px;
    box-shadow:0 12px 30px rgba(20,25,18,.06);
    background:#fff;
  }
  .apw-pd-priceRow{display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;justify-content:space-between}
  .apw-pd-price{font-size:30px;font-weight:900;letter-spacing:-.3px;color:#1f2a1a}
  .apw-pd-mrp{color:#5c6b55;text-decoration:line-through;font-size:14px}
  .apw-pd-save{
    font-weight:800;font-size:13px;
    color:#2F4A1E;
    background:rgba(47,74,30,.10);
    border:1px solid rgba(47,74,30,.22);
    padding:7px 10px;border-radius:999px;
  }
  .apw-pd-stock{display:flex;align-items:center;gap:8px;color:#5c6b55;font-size:14px;margin-top:10px}
  .apw-pd-pill{
    padding:6px 10px;border-radius:999px;
    background:rgba(41,62,138,.08);
    border:1px solid rgba(41,62,138,.18);
    color:#293e8a;
    font-weight:900;font-size:12px;
  }

  
  .apw-pd-bgBrand{background: rgba(41,62,138,.10);border:1px solid rgba(41,62,138,.18)}
  .apw-pd-bgGreen{background: rgba(47,74,30,.08);border:1px solid rgba(47,74,30,.16)}

  
  .apw-pd-quick{margin-top:12px;display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
  .apw-pd-q{border-radius:16px;padding:12px;background:#fff;border:1px solid rgba(31,42,26,.10)}
  .apw-pd-q b{display:block;font-size:13px}
  .apw-pd-q span{display:block;color:#5c6b55;font-size:12px;margin-top:2px}

  
  .apw-pd-opts{margin-top:14px;display:grid;gap:12px}
  .apw-pd-optRow{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:space-between}
  .apw-pd-label{font-size:15px;color:#5c6b55;font-weight:800}
  .apw-pd-chips{display:flex;gap:8px;flex-wrap:wrap}
  .apw-pd-chip{
    border:1px solid rgba(31,42,26,.10);
    background:#fff;
    padding:9px 12px;
    border-radius:999px;
    cursor:pointer;
    font-weight:700;font-size:14px;
    transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
  }
  .apw-pd-chip:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(20,25,18,.07)}
  .apw-pd-chip.is-active{border-color:rgba(41,62,138,.45);box-shadow:0 14px 26px rgba(41,62,138,.12)}

  
  .apw-pd-ctaRow{margin-top:14px;display:grid;grid-template-columns:140px 1fr;gap:12px}
  .apw-pd-qty{
    display:flex;align-items:center;justify-content:space-between;
    border:1px solid rgba(31,42,26,.10);
    border-radius:14px;
    background:#fff;
    padding:10px;
  }
  .apw-pd-qty button{
    width:38px;height:38px;border-radius:12px;border:1px solid rgba(31,42,26,.10);
    background:#f6f8f2;
    cursor:pointer;font-size:18px;font-weight:900;color:#1f2a1a;
    transition:transform .18s ease;
  }
  .apw-pd-qty button:hover{transform:translateY(-1px)}
  .apw-pd-qty input{width:44px;border:0;outline:0;text-align:center;font-weight:700;background:transparent;color:#1f2a1a;font-size:20px;}

  .apw-pd-btn{
    display:inline-flex;align-items:center;justify-content:center;gap:10px;
    border-radius:14px;
    padding:13px 14px;
    font-weight:900;
    border:1px solid transparent;
    cursor:pointer;
    transition:transform .18s ease, box-shadow .18s ease, background .18s ease;
    user-select:none;
    text-align:center;
  }
  .apw-pd-btn.primary{background:#293e8a;color:#fff;box-shadow:0 16px 35px rgba(41,62,138,.22)}
  .apw-pd-btn.primary:hover{transform:translateY(-2px)}
  .apw-pd-btn.ghost{background:#fff;border-color:rgba(31,42,26,.10);color:#1f2a1a}

  /* Mini actions */
  .apw-pd-miniActions{margin-top:12px;display:flex;gap:10px;flex-wrap:wrap}
  .apw-pd-mini{
    flex:1;min-width:160px;
    display:flex;align-items:center;justify-content:center;gap:10px;
    border:1px solid rgba(31,42,26,.10);
    border-radius:14px;
    padding:12px;
    font-weight:900;
    cursor:pointer;
    transition:transform .18s ease, box-shadow .18s ease;
    background:#fff;
  }
  .apw-pd-mini:hover{transform:translateY(-1px);box-shadow:0 14px 26px rgba(20,25,18,.08)}
  .apw-pd-mini .apw-pd-ic{
    width:34px;height:34px;border-radius:12px;
    background:rgba(241,204,36,.22);
    border:1px solid rgba(241,204,36,.50);
    display:grid;place-items:center;
    color:#5b4a00;font-weight:900;
  }

 
  .apw-pd-details{margin-top:22px;display:grid;grid-template-columns:1fr .7fr;gap:18px;align-items:start}
  .apw-pd-tabs{display:flex;gap:10px;flex-wrap:wrap;border-bottom:1px solid rgba(31,42,26,.10);padding-bottom:12px;margin-bottom:12px}
  .apw-pd-tab{
    padding:10px 12px;border-radius:999px;
    border:1px solid rgba(31,42,26,.10);
    background:#fff;
    font-weight:900;font-size:13px;cursor:pointer;color:#1f2a1a;
  }
  .apw-pd-tab.is-active{background:rgba(41,62,138,.08);border-color:rgba(41,62,138,.22);color:#293e8a}
  .apw-pd-panel{display:none}
  .apw-pd-panel.is-active{display:block}
  .apw-pd-p{color:#5c6b55;line-height:1.75;margin:0 0 12px}

  .apw-pd-points{display:grid;gap:10px;margin:12px 0 0}
  .apw-pd-point{
    display:flex;gap:10px;align-items:flex-start;
    padding:12px;border-radius:16px;
    border:1px solid rgba(31,42,26,.10);
    background:#fff;
  }
  .apw-pd-tick{
    width:28px;height:28px;border-radius:10px;
    background: #2a431c;
    border:1px solid rgba(41,62,138,.18);
    display:grid;place-items:center;
    color: #ffffff;font-weight:900;flex:0 0 auto;
  }
  .apw-pd-point b{display:block;font-size:14px}
  .apw-pd-point span{display:block;color:#5c6b55;font-size:13px;margin-top:2px}

  .apw-pd-spec{
    width:100%;
    border-collapse:separate;border-spacing:0;
    border:1px solid rgba(31,42,26,.10);
    border-radius:16px;
    overflow:hidden;
    background:#fff;
  }
  .apw-pd-spec th,.apw-pd-spec td{padding:12px;border-bottom:1px solid rgba(31,42,26,.10);font-size:14px;text-align:left}
  .apw-pd-spec th{width:42%;color:#5c6b55;font-weight:900;background:#f6f8f2}
  .apw-pd-spec tr:last-child th,.apw-pd-spec tr:last-child td{border-bottom:0}

  
  .apw-pd-sideHead{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:14px 14px 10px;border-bottom:1px solid rgba(31,42,26,.10)}
  .apw-pd-sideHead b{font-size:18px}
  .apw-pd-sideBody{padding:14px}
  .apw-pd-ship{display:grid;gap:6px}
  .apw-pd-shipRow{
    display:flex;gap:10px;align-items:flex-start;
    padding:12px;border:1px solid rgba(31,42,26,.10);
    border-radius:16px;background:#fff;
  }
  .apw-pd-shipRow .apw-pd-shipIc{
    width:32px;height:32px;border-radius:12px;
    background:rgba(241,204,36,.22);
    border:1px solid rgba(241,204,36,.55);
    display:grid;place-items:center;
    font-weight:900;color:#5b4a00;flex:0 0 auto;
  }
  .apw-pd-shipRow b{display:block;font-size:13px}
  .apw-pd-shipRow span{display:block;color:#5c6b55;font-size:12px;margin-top:2px}

 
  .apw-pd-related{margin-top:50px}
  .apw-pd-relHead{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:25px}
  .apw-pd-relHead h2{font-size:28px;margin:0;font-weight:800}
  .apw-pd-relGrid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
  .apw-pd-relCard{
    border:1px solid rgba(31,42,26,.10);
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 12px 24px rgba(20,25,18,.06);
    transition:transform .18s ease, box-shadow .18s ease;
    background:#fff;
  }
  .apw-pd-relCard:hover{transform:translateY(-3px);box-shadow:0 18px 36px rgba(20,25,18,.10)}
  .apw-pd-relImg{aspect-ratio:4/3;background:#fff}
  .apw-pd-relImg img{width:100%;height:100%;object-fit:cover}
  .apw-pd-relInfo{padding:12px}
  .apw-pd-relInfo b{display:block;font-size:14px;margin-bottom:6px}
  .apw-pd-relPr{display:flex;gap:10px;align-items:center}
  .apw-pd-relPr span{font-weight:900}
  .apw-pd-relPr s{color:#5c6b55;font-size:12px}

  
  .apw-pd-card.apw-pd-boxbg,
  .apw-pd-priceBox.apw-pd-boxbg,
  .apw-pd-relCard.apw-pd-boxbg,
  .apw-pd-mini.apw-pd-boxbg,
  .apw-pd-banner.apw-pd-boxbg{background:
      radial-gradient(circle at 20% 20%, rgba(241, 204, 36, 0.16), transparent 55%),
      linear-gradient(180deg, #ffffff, #f6f8f2);
  }

  @media (max-width:980px){
    .apw-pd-grid{grid-template-columns:1fr;gap:16px}
    .apw-pd-gallery{position:relative;top:auto}
    .apw-pd-details{grid-template-columns:1fr;gap:14px}
    .apw-pd-relGrid{grid-template-columns:repeat(2,1fr)}
    .apw-pd-quick{grid-template-columns:1fr}
    .apw-pd-banner__in{grid-template-columns:1fr}
  }
  @media (max-width:520px){
    .apw-pd-h1{font-size:24px}
    .apw-pd-thumbs{grid-template-columns:repeat(4,1fr)}
    .apw-pd-ctaRow{grid-template-columns:1fr}
    .apw-pd-relGrid{grid-template-columns:1fr}
    .apw-pd-banner__copy h2{font-size:22px}
  }
</style>

<main class="apw-pd-wrap">
  <div class="apw-pd-container">

    <nav class="apw-pd-crumbs" aria-label="Breadcrumb">
      <a href="{{ route('home') }}">Home</a> <span class="apw-pd-sep">›</span>
      <a href="{{ route('products') }}">Products</a> <span class="apw-pd-sep">›</span>
      <span>{{ $product->name }}</span>
    </nav>

   
    <section class="apw-pd-banner apw-pd-boxbg" aria-label="Product banner">
      <div class="apw-pd-banner__in">
        <div class="apw-pd-banner__copy">
          <small><span class="apw-pd-dot"></span> Today’s Fresh Dairy Drop</small>
          <h2>Fresh Cow Milk, Curd, Paneer & More — Delivered Fast</h2>
          <p>
            Hygienic packing, cold-chain handling, and same-day slots (area wise).
            Daily essentials ke liye premium dairy range explore karein.
          </p>
          <div class="apw-pd-banner__cta">
            <a class="apw-pd-btn primary" href="#apwPdRelated">Explore Products</a>
            <a class="apw-pd-btn ghost" href="#">Contact Support</a>
          </div>
        </div>

        <div class="apw-pd-banner__img" aria-hidden="true">
          <!-- change banner image here -->
          <img src="https://keywordhike.com/Dairy/images/milk-vans.webp" alt="Fresh dairy banner">
        </div>
      </div>
    </section>

    <section class="apw-pd-grid">

      
      <div class="apw-pd-card apw-pd-boxbg apw-pd-gallery">
        <div class="apw-pd-gallery__in">
          <div class="apw-pd-mainshot">
            <img id="apwPdMainImage" src="{{ asset($product->images[0] ?? $product->image) }}" alt="{{ $product->name }}">
          </div>

          <div class="apw-pd-thumbs" role="list">
            @if($product->images && count($product->images) > 0)
              @foreach($product->images as $index => $img)
              <button class="apw-pd-thumb {{ $index == 0 ? 'is-active' : '' }}" type="button" data-img="{{ asset($img) }}" aria-label="Thumbnail {{ $index + 1 }}">
                <img src="{{ asset($img) }}" alt="Thumbnail {{ $index + 1 }}">
              </button>
              @endforeach
            @else
              <button class="apw-pd-thumb is-active" type="button" data-img="{{ asset($product->image) }}" aria-label="Thumbnail 1">
                <img src="{{ asset($product->image) }}" alt="Thumbnail 1">
              </button>
            @endif
          </div>
        </div>
      </div>

      
      <div class="apw-pd-card apw-pd-pad apw-pd-boxbg">
        <div class="apw-pd-titleRow">
          <div>
            <h1 class="apw-pd-h1">{{ $product->name }}</h1>
            <p class="apw-pd-sub">{{ $product->meta }} @if($product->sku)• SKU: <b>#{{ $product->sku }}</b>@endif</p>
          </div>
          @if($product->badge)
          <div class="apw-pd-badge accent"><span class="apw-pd-dot"></span> {{ $product->badge }}</div>
          @endif
        </div>

        @if($product->features && count($product->features) > 0)
        <div class="apw-pd-badges">
          @foreach(array_slice($product->features, 0, 3) as $feature)
          <span class="apw-pd-badge"><span class="apw-pd-dot"></span> {{ $feature['title'] ?? '' }}</span>
          @endforeach
        </div>
        @endif

        <div class="apw-pd-priceBox apw-pd-boxbg">
          <div class="apw-pd-priceRow">
            <div>
              <div class="apw-pd-price">₹ {{ number_format($product->price, 0) }}</div>
              @if($product->mrp)
              <div class="apw-pd-mrp">MRP: ₹ {{ number_format($product->mrp, 0) }}</div>
              @endif
            </div>
            @if($product->discount_percent > 0)
            <div class="apw-pd-save">Save {{ $product->discount_percent }}%</div>
            @endif
          </div>

          <div class="apw-pd-stock">
            <span class="apw-pd-pill">{{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}</span>
            <span>Delivery in 2–6 hours (area wise)</span>
          </div>

          <div class="apw-pd-quick">
            @if($product->storage_temp)
            <div class="apw-pd-q apw-pd-bgGreen"><b>Storage</b><span>{{ $product->storage_temp }}</span></div>
            @endif
            @if($product->shelf_life)
            <div class="apw-pd-q apw-pd-bgBrand"><b>Shelf Life</b><span>{{ $product->shelf_life }}</span></div>
            @endif
            @if($product->best_for)
            <div class="apw-pd-q apw-pd-bgGreen"><b>Best For</b><span>{{ $product->best_for }}</span></div>
            @endif
          </div>

          @if($product->pack_sizes && count($product->pack_sizes) > 0)
          <div class="apw-pd-opts">
            <div class="apw-pd-optRow">
              <div class="apw-pd-label">Pack Size</div>
              <div class="apw-pd-chips" role="list">
                @foreach($product->pack_sizes as $index => $size)
                <button class="apw-pd-chip {{ $index == 0 ? 'is-active' : '' }}" type="button">{{ $size }}</button>
                @endforeach
              </div>
            </div>
          </div>
          @endif

          @if($product->delivery_slots && count($product->delivery_slots) > 0)
          <div class="apw-pd-opts">
            <div class="apw-pd-optRow">
              <div class="apw-pd-label">Delivery Slot</div>
              <div class="apw-pd-chips" role="list">
                @foreach($product->delivery_slots as $index => $slot)
                <button class="apw-pd-chip {{ $index == 0 ? 'is-active' : '' }}" type="button">{{ $slot }}</button>
                @endforeach
              </div>
            </div>
          </div>
          @endif

          <div class="apw-pd-ctaRow">
            <div class="apw-pd-qty" aria-label="Quantity selector">
              <button type="button" id="apwPdDecQty">−</button>
              <input type="text" id="apwPdQty" value="1" inputmode="numeric" aria-label="Quantity">
              <button type="button" id="apwPdIncQty">+</button>
            </div>
            <button class="apw-pd-btn primary" type="button" id="apwPdAddToCartBtn">Add to Cart</button>
          </div>

          <div class="apw-pd-miniActions">
            <button class="apw-pd-mini apw-pd-boxbg" type="button"><span class="apw-pd-ic">💬</span> WhatsApp Order</button>
            <button class="apw-pd-mini apw-pd-boxbg" type="button"><span class="apw-pd-ic">📞</span> Call for Bulk</button>
          </div>
        </div>
      </div>

    </section>

    
    <section class="apw-pd-details">
      <div class="apw-pd-card apw-pd-pad apw-pd-boxbg">
        <div class="apw-pd-tabs" role="tablist">
          <button class="apw-pd-tab is-active" type="button" data-tab="desc">About</button>
          @if($product->nutrition_info && count($product->nutrition_info) > 0)
          <button class="apw-pd-tab" type="button" data-tab="nutrition">Nutrition</button>
          @endif
          @if($product->storage_instructions && count($product->storage_instructions) > 0)
          <button class="apw-pd-tab" type="button" data-tab="storage">Storage</button>
          @endif
          @if($product->specifications && count($product->specifications) > 0)
          <button class="apw-pd-tab" type="button" data-tab="specs">Specifications</button>
          @endif
        </div>

        <div>
          <div class="apw-pd-panel is-active" id="apwPdPanelDesc">
            @if($product->description)
            <p class="apw-pd-p">{{ $product->description }}</p>
            @endif

            @if($product->features && count($product->features) > 0)
            <div class="apw-pd-points">
              @foreach($product->features as $feature)
              <div class="apw-pd-point">
                <div class="apw-pd-tick">{{ $feature['icon'] ?? '✓' }}</div>
                <div>
                  <b>{{ $feature['title'] ?? '' }}</b>
                  <span>{{ $feature['description'] ?? '' }}</span>
                </div>
              </div>
              @endforeach
            </div>
            @endif
          </div>

          @if($product->nutrition_info && count($product->nutrition_info) > 0)
          <div class="apw-pd-panel" id="apwPdPanelNutrition">
            <table class="apw-pd-spec" aria-label="Nutrition table">
              @foreach($product->nutrition_info as $key => $value)
              <tr><th>{{ $key }}</th><td>{{ $value }}</td></tr>
              @endforeach
            </table>
          </div>
          @endif

          @if($product->storage_instructions && count($product->storage_instructions) > 0)
          <div class="apw-pd-panel" id="apwPdPanelStorage">
            <div class="apw-pd-points">
              @foreach($product->storage_instructions as $instruction)
              <div class="apw-pd-point">
                <div class="apw-pd-tick">{{ $instruction['icon'] ?? '🧊' }}</div>
                <div>
                  <b>{{ $instruction['title'] ?? '' }}</b>
                  <span>{{ $instruction['description'] ?? '' }}</span>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          @if($product->specifications && count($product->specifications) > 0)
          <div class="apw-pd-panel" id="apwPdPanelSpecs">
            <table class="apw-pd-spec" aria-label="Specifications table">
              @foreach($product->specifications as $key => $value)
              <tr><th>{{ $key }}</th><td>{{ $value }}</td></tr>
              @endforeach
            </table>
          </div>
          @endif
        </div>
      </div>

     
      <aside class="apw-pd-card apw-pd-boxbg">
        <div class="apw-pd-sideHead">
          <b>Delivery & Policy</b>
          <span style="color:#5c6b55;font-size:13px">{{ ucfirst($product->category ?? 'Product') }}</span>
        </div>
        <div class="apw-pd-sideBody">
          <div class="apw-pd-ship">
            <div class="apw-pd-shipRow">
              <div class="apw-pd-shipIc">🚚</div>
              <div><b>Same Day Slots</b><span>Area wise slots available.</span></div>
            </div>
            <div class="apw-pd-shipRow">
              <div class="apw-pd-shipIc">🧊</div>
              <div><b>Cold Handling</b><span>Temperature safe delivery.</span></div>
            </div>
            <div class="apw-pd-shipRow">
              <div class="apw-pd-shipIc">↩</div>
              <div><b>Return/Refund</b><span>Perishable terms apply.</span></div>
            </div>
          </div>

          <div style="margin-top:12px;display:grid;gap:10px">
            <a class="apw-pd-btn ghost" href="{{ route('terms-conditions') }}" style="width:100%">View Terms</a>
            <a class="apw-pd-btn ghost" href="{{ route('contact') }}" style="width:100%">Contact Support</a>
          </div>
        </div>
      </aside>
    </section>

    
    <section class="apw-pd-related" id="apwPdRelated">
      <div class="apw-pd-relHead">
        <h2>Related {{ ucfirst($product->category ?? 'Dairy') }} Products</h2>
        <a class="apw-pd-btn ghost" href="{{ route('products') }}" style="padding:10px 12px;border-radius:999px">View All</a>
      </div>

      <div class="apw-pd-relGrid">
        @forelse($relatedProducts as $relProduct)
        <a class="apw-pd-relCard apw-pd-boxbg" href="{{ route('product.detail', $relProduct->slug) }}">
          <div class="apw-pd-relImg"><img src="{{ asset($relProduct->main_image) }}" alt="{{ $relProduct->name }}"></div>
          <div class="apw-pd-relInfo">
            <b>{{ $relProduct->name }}</b>
            <div class="apw-pd-relPr">
              <span>₹ {{ number_format($relProduct->price, 0) }}</span>
              @if($relProduct->mrp)
              <s>₹ {{ number_format($relProduct->mrp, 0) }}</s>
              @endif
            </div>
          </div>
        </a>
        @empty
        <p style="grid-column: 1/-1; text-align:center; color:#5c6b55;">No related products found.</p>
        @endforelse
      </div>
    </section>

  </div>
</main>

<script>
  
  (function(){
    const root = document.querySelector('.apw-pd-wrap');
    if(!root) return;

    const main = document.getElementById('apwPdMainImage');
    const thumbs = root.querySelectorAll('.apw-pd-thumb');

    thumbs.forEach(btn=>{
      btn.addEventListener('click', ()=>{
        thumbs.forEach(t=>t.classList.remove('is-active'));
        btn.classList.add('is-active');
        const src = btn.getAttribute('data-img');
        if(src && main) main.src = src;
      });
    });
  })();

  // Tabs
  (function(){
    const root = document.querySelector('.apw-pd-wrap');
    if(!root) return;

    const tabs = root.querySelectorAll('.apw-pd-tab');
    const panels = {
      desc: document.getElementById('apwPdPanelDesc'),
      nutrition: document.getElementById('apwPdPanelNutrition'),
      storage: document.getElementById('apwPdPanelStorage'),
    };

    tabs.forEach(t=>{
      t.addEventListener('click', ()=>{
        tabs.forEach(x=>x.classList.remove('is-active'));
        t.classList.add('is-active');

        const key = t.getAttribute('data-tab');
        Object.values(panels).forEach(p=>p && p.classList.remove('is-active'));
        if(panels[key]) panels[key].classList.add('is-active');
      });
    });
  })();

  
  (function(){
    const qty = document.getElementById('apwPdQty');
    const dec = document.getElementById('apwPdDecQty');
    const inc = document.getElementById('apwPdIncQty');

    if(!qty || !dec || !inc) return;

    function clamp(val){
      val = parseInt(val, 10);
      if(isNaN(val) || val < 1) val = 1;
      if(val > 99) val = 99;
      return val;
    }

    dec.addEventListener('click', ()=> qty.value = Math.max(1, clamp(qty.value) - 1));
    inc.addEventListener('click', ()=> qty.value = clamp(qty.value) + 1);
    qty.addEventListener('input', ()=> qty.value = clamp(qty.value));
  })();

  
 (function(){
    const btn = document.getElementById('apwPdAddToCartBtn');
    const qtyInput = document.getElementById('apwPdQty');
    if(!btn) return;

    btn.addEventListener('click', ()=>{
      const quantity = parseInt(qtyInput?.value || 1);
      
      // Get product data from page
      const product = {
        id: {{ $product->id }},
        name: "{{ $product->name }}",
        price: {{ $product->price }},
        image: "{{ asset($product->images[0] ?? $product->image) }}",
        slug: "{{ $product->slug }}",
        quantity: quantity
      };

      if (window.DairyCart) {
        window.DairyCart.addToCart(product);
      }

      const old = btn.textContent;
      btn.textContent = 'Added ✓';
      btn.style.transform = 'translateY(-2px)';

      setTimeout(()=>{
        btn.textContent = old;
        btn.style.transform = '';
      }, 1200);
    });
  })();
</script>

@endsection
