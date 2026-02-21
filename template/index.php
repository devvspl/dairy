<?php include __DIR__ . '/includes/header.php'; ?>

<!---Banner start -->


<style>
  *{ box-sizing:border-box; }

  .tb-hero-wrap{
    width: 99%;
    max-width: 1600px;
    margin: 0 auto;
  }
  @media (max-width: 768px){
    .tb-hero-wrap{ width: 94%; }
  }

  .tb-hero{
    background:#fff;
    padding: 5px 0 26px;
  }

  .tb-slider{
    position:relative;
    border-radius:18px;
    overflow:hidden;
    border:1px solid var(--border);
    background: #fff;
  }

  .tb-slides{
    display:flex;
    transition: transform 500ms ease;
    will-change: transform;
  }

  .tb-slide{
    min-width:100%;
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    padding:60px 80px;
    align-items:center;
  }

  /* slide backgrounds */
  .tb-slide:nth-child(1){
    background: linear-gradient(120deg, rgba(47,74,30,0.10), rgba(241,204,36,0.12));
  }
  .tb-slide:nth-child(2){
    background: linear-gradient(120deg, rgba(38,61,24,0.10), rgba(241,204,36,0.10));
  }
  .tb-slide:nth-child(3){
    background: linear-gradient(120deg, rgba(47,74,30,0.08), rgba(241,204,36,0.14));
  }

  .tb-kicker{
    margin:0 0 8px 0;
    color: var(--green-dark);
    font-weight:800;
    letter-spacing:.3px;
    opacity:.9;
    font-size:14px;
  }

  .tb-title{
    margin:0 0 10px 0;
    color: var(--green-dark);
    font-weight:900;
    font-size:42px;
    line-height:1.05;
    letter-spacing:.2px;
  }

  .tb-subtitle{
    margin:0 0 18px 0;
    color: var(--muted);
    font-weight:600;
    font-size:16px;
    line-height:1.5;
    max-width: 520px;
  }

  .tb-cta{
    display:flex;
    align-items:center;
    gap:14px;
    flex-wrap:wrap;
  }

  .tb-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding: 10px 15px;
    border-radius: 12px;
    text-decoration:none;
    background: var(--green);
    color:#fff;
    font-weight:900;
    border:1px solid transparent;
    transition: 200ms ease;
    white-space: nowrap;
  }
  .tb-btn:hover{
    background:#f1cc24;
    color: #1f2a1a;
  }

  .tb-link{
    text-decoration:none;
    font-weight:900;
    color: var(--green-dark);
    transition: 200ms ease;
    padding: 10px 4px;
    white-space: nowrap;
  }
  .tb-link:hover{ color:#f1cc24; }

  
  .tb-slide-media{
    display:flex;
    justify-content:flex-end;
    width:100%;
  }

  .tb-media-card{
    width: 100%;
    max-width: 100%;
    height: auto;
    border-radius: 18px;
    overflow:hidden; 
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
  }

  
  .tb-media-card img{
    width:100%;
    height:100%;
    display:block;
    object-fit: contain;
    border-radius: 18px;
  }

  
  .tb-arrow{
    position:absolute;
    top:50%;
    transform: translateY(-50%);
    width:44px;
    height:44px;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.10);
    background: rgba(255,255,255,0.92);
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    transition: 200ms ease;
    z-index:3;
  }
  .tb-arrow span{
    font-size:28px;
    line-height:1;
    color: var(--green-dark);
    margin-top:-2px;
  }
  .tb-arrow:hover{
    border-color:#f1cc24;
    box-shadow: 0 10px 24px rgba(0,0,0,0.10);
  }
  .tb-arrow:hover span{ color:#f1cc24; }

  .tb-prev{ left: 14px; }
  .tb-next{ right: 14px; }

  
  .tb-dots{
    position:absolute;
    left:50%;
    bottom: 14px;
    transform: translateX(-50%);
    display:flex;
    gap:10px;
    z-index:3;
  }
  .tb-dot{
    width:10px;
    height:10px;
    border-radius:999px;
    border: 2px solid rgba(38,61,24,0.45);
    background: rgba(255,255,255,0.9);
    cursor:pointer;
    transition: 200ms ease;
  }
  .tb-dot.is-active{
    width:26px;
    border-color:#f1cc24;
    background:#f1cc24;
  }
  .tb-dot:hover{ border-color:#f1cc24; }

  
  @media (max-width: 980px){
    .tb-slide{
      grid-template-columns: 1fr;
      padding: 26px 22px 58px; 
      gap: 16px;
    }

    .tb-slide-content{ order: 1; }
    .tb-slide-media{
      order: 2;
      justify-content:flex-start;
    }

    .tb-media-card{
      max-width: 520px;
      height: 240px;
    }

    .tb-title{ font-size:34px; }
    .tb-subtitle{ max-width: 100%; }
  }

  @media (max-width: 560px){
    .tb-hero{ padding: 12px 0 18px; }

    
    .tb-slide{
      padding: 18px 16px 62px;
    }

    .tb-title{ font-size:20px; }
    .tb-subtitle{ font-size:15px; }

    .tb-media-card{
      height: 210px;
    }

  
    .tb-arrow{ width:38px; height:38px; }
    .tb-prev{ left:8px; }
    .tb-next{ right:8px; }

    
    .tb-dots{ bottom: 12px; }
  }
</style>

<section class="tb-hero">
  <div class="tb-hero-wrap">
    <div class="tb-slider" id="tbSlider" aria-label="Homepage banner slider">
      <div class="tb-slides" id="tbSlides">

        <article class="tb-slide is-active">
          <div class="tb-slide-content">
            <p class="tb-kicker">Organic • Fresh • Farm Direct</p>
            <h1 class="tb-title">Pure Taste, Real Ingredients</h1>
            <p class="tb-subtitle">Premium essentials delivering purity, quality, and trust daily.</p>
            <div class="tb-cta">
              <a class="tb-btn" href="#">Shop Now</a>
              <a class="tb-link" href="#">Explore Products</a>
            </div>
          </div>
          <div class="tb-slide-media" aria-hidden="true">
            <div class="tb-media-card">
              <img src="images/cow.png" alt="Banner 1">
            </div>
          </div>
        </article>

        <article class="tb-slide">
          <div class="tb-slide-content">
            <p class="tb-kicker">Membership Benefits</p>
            <h2 class="tb-title">Join & Save on Every Order</h2>
            <p class="tb-subtitle">Be first to explore new launches, exclusive deals, and seasonal bundles.</p>
            <div class="tb-cta">
              <a class="tb-btn" href="#">Join Membership</a>
              <a class="tb-link" href="#">Know More</a>
            </div>
          </div>
          <div class="tb-slide-media" aria-hidden="true">
            <div class="tb-media-card">
              <img src="images/Banners-2.png" alt="Banner 2">
            </div>
          </div>
        </article>

        <article class="tb-slide">
          <div class="tb-slide-content">
            <p class="tb-kicker">Farm Life Stories</p>
            <h2 class="tb-title">From Soil to Shelf—Honestly</h2>
            <p class="tb-subtitle">Discover purity crafted with care and tradition.</p>
            <div class="tb-cta">
              <a class="tb-btn" href="#">Read Blogs</a>
              <a class="tb-link" href="#">About Us</a>
            </div>
          </div>
          <div class="tb-slide-media" aria-hidden="true">
            <div class="tb-media-card">
              <img src="images/Banners-3.png" alt="Banner 3">
            </div>
          </div>
        </article>

      </div>

      <button class="tb-arrow tb-prev" type="button" aria-label="Previous slide">
        <span aria-hidden="true">‹</span>
      </button>
      <button class="tb-arrow tb-next" type="button" aria-label="Next slide">
        <span aria-hidden="true">›</span>
      </button>

      <div class="tb-dots" role="tablist" aria-label="Slider pagination">
        <button class="tb-dot is-active" type="button" aria-label="Go to slide 1"></button>
        <button class="tb-dot" type="button" aria-label="Go to slide 2"></button>
        <button class="tb-dot" type="button" aria-label="Go to slide 3"></button>
      </div>
    </div>
  </div>
</section>

<script>
  (function(){
    var slider = document.getElementById('tbSlider');
    var track  = document.getElementById('tbSlides');
    if(!slider || !track) return;

    var slides = track.querySelectorAll('.tb-slide');
    var dots   = slider.querySelectorAll('.tb-dot');
    var prev   = slider.querySelector('.tb-prev');
    var next   = slider.querySelector('.tb-next');

    var index = 0;
    var total = slides.length;
    var timer = null;
    var interval = 4500;

    function goTo(i){
      index = (i + total) % total;
      track.style.transform = 'translateX(' + (-index * 100) + '%)';
      dots.forEach(function(d){ d.classList.remove('is-active'); });
      if(dots[index]) dots[index].classList.add('is-active');
    }

    function start(){
      stop();
      timer = setInterval(function(){ goTo(index + 1); }, interval);
    }

    function stop(){
      if(timer){ clearInterval(timer); timer = null; }
    }

    prev.addEventListener('click', function(){ goTo(index - 1); start(); });
    next.addEventListener('click', function(){ goTo(index + 1); start(); });

    dots.forEach(function(dot, i){
      dot.addEventListener('click', function(){ goTo(i); start(); });
    });

    slider.addEventListener('mouseenter', stop);
    slider.addEventListener('mouseleave', start);

    var startX = 0, endX = 0;
    slider.addEventListener('touchstart', function(e){
      startX = e.touches[0].clientX;
      stop();
    }, {passive:true});

    slider.addEventListener('touchend', function(e){
      endX = e.changedTouches[0].clientX;
      var diff = endX - startX;
      if(Math.abs(diff) > 40){
        if(diff > 0) goTo(index - 1);
        else goTo(index + 1);
      }
      start();
    });

    goTo(0);
    start();
  })();
</script>


<!--- our features -->

<style>

.tb-cats{
  background:#fff;
  padding: 18px 0 8px;
}

/* grid */
.tb-cats-grid{
  display:grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 16px;
}

/* card */
.tb-cat{
  text-decoration:none;
  color: var(--green-dark);
  background: #fff;
  border: 1px solid rgba(0,0,0,0.08);
  border-radius: 18px;
  padding: 16px 14px;
  display:flex;
  flex-direction:column;
  align-items:center;
  gap: 12px;
  position:relative;
  transition: 220ms ease;
  box-shadow: 0 10px 26px rgba(0,0,0,0.06);
  overflow:hidden;
}

/* subtle premium top glow inside card */
.tb-cat::before{
  content:"";
  position:absolute;
  inset:-1px;
  background: radial-gradient(circle at 30% 10%, rgba(241,204,36,0.18), transparent 60%);
  opacity:.65;
  pointer-events:none;
}

/* circle icon */
.tb-cat-ico{
  width: 76px;
  height: 76px;
  border-radius: 999px;
  display:grid;
  place-items:center;
  box-shadow: 0 14px 30px rgba(0,0,0,0.12);
  transition: 220ms ease;
  position:relative;
  z-index:1;
}

.bg-green{ background: var(--green); }
.bg-brown{ background:#4a2a10; } /* same pattern */

/* svg icon */
.tb-cat-svg{
  width: 34px;
  height: 34px;
  stroke: #f6eed4;
  stroke-width: 3.2;
  fill:none;
  stroke-linecap:round;
  stroke-linejoin:round;
  transition: 220ms ease;
}

/* text */
.tb-cat-title{
  font-weight: 900;
  font-size: 14px;
  letter-spacing: .2px;
  text-align:center;
  position:relative;
  z-index:1;
}

/* Price chip inside circle */
.tb-price-chip{
  color:#f6eed4;
  font-weight: 900;
  font-size: 14px;
  line-height: 1.05;
  text-align:center;
  letter-spacing:.2px;
}

/* PREMIUM HOVER */
.tb-cat:hover{
  transform: translateY(-6px);
  border-color: rgba(241,204,36,0.70);
  box-shadow: 0 18px 44px rgba(0,0,0,0.12);
}

/* gold glow ring */
.tb-cat:hover .tb-cat-ico{
  box-shadow:
    0 18px 40px rgba(0,0,0,0.16),
    0 0 0 6px rgba(241,204,36,0.18);
}

/* icon + text highlight */
.tb-cat:hover .tb-cat-title{
  color:#f1cc24;
}

/* svg stroke becomes gold */
.tb-cat:hover .tb-cat-svg{
  stroke:#f1cc24;
}

/* nice underline grow effect */
.tb-cat-title::after{
  content:"";
  display:block;
  width:0;
  height:3px;
  border-radius:99px;
  background:#f1cc24;
  margin:10px auto 0;
  transition: 220ms ease;
}
.tb-cat:hover .tb-cat-title::after{
  width:34px;
}

/* Responsive */
@media (max-width: 1100px){
  .tb-cats-grid{ grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 640px){
  .tb-cats-grid{ grid-template-columns: repeat(3, 1fr); }
  .tb-cat{ padding: 14px 12px; border-radius: 16px; }
  .tb-cat-ico{ width: 50px; height: 50px; }
  .tb-cats-grid {
    display: grid;
    
    gap: 8px;
}
}




</style>
<section class="tb-cats">
  <div class="container">
    <div class="tb-cats-grid">

      <a href="#" class="tb-cat">
        <span class="tb-cat-ico bg-green">
          <!-- box + spark -->
          <svg class="tb-cat-svg" viewBox="0 0 64 64" aria-hidden="true">
            <path d="M10 26l22-12 22 12-22 12-22-12Z"/>
            <path d="M10 26v22l22 12 22-12V26"/>
            <path d="M44 10l4-4 4 4-4 4-4-4Z"/>
          </svg>
        </span>
        <span class="tb-cat-title">New Launches</span>
      </a>

      <a href="#" class="tb-cat">
        <span class="tb-cat-ico bg-brown">
          <!-- gift -->
          <svg class="tb-cat-svg" viewBox="0 0 64 64" aria-hidden="true">
            <path d="M14 28h36v26H14V28Z"/>
            <path d="M14 28h36v-8H14v8Z"/>
            <path d="M32 20v34"/>
            <path d="M14 36h36"/>
            <path d="M26 20c-4 0-6-5-1-7 4-2 7 3 7 7"/>
            <path d="M38 20c4 0 6-5 1-7-4-2-7 3-7 7"/>
          </svg>
        </span>
        <span class="tb-cat-title">Membership Deals</span>
      </a>

      <a href="#" class="tb-cat">
        <span class="tb-cat-ico bg-green">
          <!-- heart -->
          <svg class="tb-cat-svg" viewBox="0 0 64 64" aria-hidden="true">
            <path d="M32 54s-18-10-22-24c-2-7 3-14 11-14 5 0 9 3 11 7 2-4 6-7 11-7 8 0 13 7 11 14-4 14-22 24-22 24Z"/>
          </svg>
        </span>
        <span class="tb-cat-title">Shop By Concern</span>
      </a>

      <a href="#" class="tb-cat">
        <span class="tb-cat-ico bg-brown">
          <span class="tb-price-chip">Under<br>₹499</span>
        </span>
        <span class="tb-cat-title">Under ₹499</span>
      </a>

      <a href="#" class="tb-cat">
        <span class="tb-cat-ico bg-green">
          <!-- jar -->
          <svg class="tb-cat-svg" viewBox="0 0 64 64" aria-hidden="true">
            <path d="M22 10h20M22 14h20"/>
            <path d="M20 14h24v40a6 6 0 0 1-6 6H26a6 6 0 0 1-6-6V14Z"/>
            <path d="M26 28h12"/>
          </svg>
        </span>
        <span class="tb-cat-title">All Products</span>
      </a>

      <a href="#" class="tb-cat">
        <span class="tb-cat-ico bg-brown">
          <span class="tb-price-chip">Under<br>₹999</span>
        </span>
        <span class="tb-cat-title">Under ₹999</span>
      </a>

    </div>
  </div>
</section>



<!--- about section -->


<section class="tb-abintro-sec" id="tbAbIntroSec">
  <div class="container">
    <div class="tb-abintro-wrap">

      <!-- LEFT CONTENT -->
      <div class="tb-abintro-left">
        <span class="tb-abintro-kicker">About Us</span>
        <h2>Clean, Honest Essentials — Made to Feel Premium</h2>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt 
          ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco 
          laboris nisi ut aliquip ex ea commodo consequat.
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        </p>

        <a href="#" class="tb-abintro-btn">
          
          Know More
        </a>

        <div class="tb-abintro-mini">
          <div class="tb-abintro-mini-item">
            <strong>Clean Standards</strong>
            <span>Transparent sourcing & processes</span>
          </div>
          <div class="tb-abintro-mini-item">
            <strong>Packaging</strong>
            <span>Refined look & better protection</span>
          </div>
        </div>
      </div>

      <!-- RIGHT IMAGE -->
      <div class="tb-abintro-right">
        <div class="tb-abintro-img" aria-label="About image"></div>
        <div class="tb-abintro-badge">
          <strong><i class="fi fi-rr-star"></i> 4.8/5</strong>
          <span>Average customer rating</span>
        </div>
      </div>

    </div>
  </div>
</section>
<style>
    .tb-abintro-sec{
  padding: 40px 0;
  background:#fff;
}

.tb-abintro-wrap{
  display:grid;
  grid-template-columns: 1.08fr 0.92fr;
  gap: 22px;
  align-items:center;
}


.tb-abintro-kicker{
  display:inline-block;
  font-weight:950;
  letter-spacing:1px;
  text-transform:uppercase;
  font-size:12px;
  color:white;
  background:#2a431c;
  padding: 8px 12px;
  border-radius: 999px;
  margin-bottom: 12px;
}

.tb-abintro-left h2{
  margin:0 0 10px 0;
  font-size: clamp(28px, 3.2vw, 42px);
  font-weight: 950;
  color:#2a431c;
  line-height: 1.12;
}

.tb-abintro-left p{
  margin:0 0 16px 0;
  color:#424242;
  font-weight: 700;
  line-height: 1.75;
  max-width: 640px;
}


.tb-abintro-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  padding: 11px 18px;
  border-radius: 14px;
  font-weight: 950;
  text-decoration:none;
  background:#183f79;
  color:#fff;
  border:1px solid rgba(0,0,0,0.10);
  box-shadow:0 14px 34px rgba(0,0,0,0.10);
  transition: .2s ease;
}
.tb-abintro-btn i{ font-size:18px; }
.tb-abintro-btn:hover{
  background:#293879;
  color:white;
  border-color:#f1cc24;
  transform: translateY(-3px);
  box-shadow:0 22px 60px rgba(0,0,0,0.14);
}


.tb-abintro-mini{
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap:12px;
  margin-top: 16px;
}
.tb-abintro-mini-item{
  padding: 12px 14px;
  border-radius: 18px;
  background: rgba(255,255,255,0.92);
  border: 1px solid rgba(0,0,0,0.08);
  box-shadow: 0 16px 44px rgba(0,0,0,0.08);
}
.tb-abintro-mini-item strong{
  display:flex;
  align-items:center;
  gap:8px;
  color:#263d18;
  font-weight: 950;
}
.tb-abintro-mini-item strong i{ color:#2f4a1e; }
.tb-abintro-mini-item span{
  display:block;
  margin-top: 4px;
  color:#5c6b55;
  font-weight: 750;
  font-size: 13px;
}


.tb-abintro-right{
  position:relative;
}

.tb-abintro-img{
  height: 420px;
  border-radius: 26px;
  border: 1px solid rgba(0,0,0,0.10);
  box-shadow: 0 26px 80px rgba(0,0,0,0.14);
  background:
    radial-gradient(circle at 30% 30%, rgba(241,204,36,0.18), transparent 60%),
    radial-gradient(circle at 70% 70%, rgba(47,74,30,0.12), transparent 62%),
    url("images/transport.png");
  background-size: cover;
  background-position: center;
}


.tb-abintro-badge{
  position:absolute;
  left:16px;
  bottom:16px;
  background: rgba(255,255,255,0.92);
  border: 1px solid rgba(0,0,0,0.08);
  border-radius: 16px;
  padding: 12px 14px;
  box-shadow: 0 16px 44px rgba(0,0,0,0.14);
}
.tb-abintro-badge strong{
  display:flex;
  align-items:center;
  gap:8px;
  color:#263d18;
  font-weight: 950;
}
.tb-abintro-badge span{
  display:block;
  margin-top: 3px;
  color:#5c6b55;
  font-weight: 750;
  font-size: 13px;
}


@media(max-width:980px){
  .tb-abintro-wrap{ grid-template-columns: 1fr; }
  .tb-abintro-img{ height: 340px; }
  /*.tb-abintro-mini{ grid-template-columns:1fr; }*/
}
@media(max-width:560px){
  .tb-abintro-sec{ padding: 56px 0; }
  .tb-abintro-img{ height: 300px; }
}

</style>

 
 
<!--- product section now -->

<section class="tb-products">
  <div class="container">
    <div class="tb-prod-head">
      <div class="tb-prod-title">
        <h2>Most Loved</h2>
        <p>Top picks people keep coming back for</p>
      </div>

      <a href="#" class="tb-shopmore">Shop More</a>
    </div>

    <div class="tb-prod-slider" id="tbProdSlider">
      <button class="tb-prod-arrow left" type="button" aria-label="Previous products">‹</button>

      <div class="tb-prod-viewport" id="tbProdViewport">
        <div class="tb-prod-track" id="tbProdTrack">

          <!-- Card 1 -->
          <article class="tb-card">
            <div class="tb-card-media">
              <span class="tb-badge green">Best Seller</span>
              <button class="tb-wish" type="button" aria-label="Add to wishlist">♡</button>

              <!-- Cow Milk Image -->
              <div class="tb-img ph1"></div>
            </div>

            <div class="tb-card-body">
              <div class="tb-name-price">
                <h3>A2 Gir Cow Milk</h3>
                <span class="tb-price">₹95</span>
              </div>

              <p class="tb-meta">Fresh • Farm sourced • A2</p>

              <div class="tb-rating">
                <span class="tb-stars">★★★★★</span>
                <span class="tb-rev">4.9 | 1455 Reviews</span>
              </div>

              <div class="tb-variant">
                <select aria-label="Select variant">
                  <option>1 L</option>
                  <option>500 ml</option>
                </select>
              </div>

              <button class="tb-add" type="button">ADD TO CART</button>
            </div>
          </article>

          <!-- Card 2 -->
          <article class="tb-card">
            <div class="tb-card-media">
              <span class="tb-badge dark">Popular</span>
              <button class="tb-wish" type="button" aria-label="Add to wishlist">♡</button>

              <div class="tb-img ph2"></div>
            </div>

            <div class="tb-card-body">
              <div class="tb-name-price">
                <h3>Farm Fresh Cow Milk</h3>
                <span class="tb-price">₹80</span>
              </div>

              <p class="tb-meta">Daily delivery • Pure & clean</p>

              <div class="tb-rating">
                <span class="tb-stars">★★★★★</span>
                <span class="tb-rev">4.8 | 2217 Reviews</span>
              </div>

              <div class="tb-variant">
                <select aria-label="Select variant">
                  <option>1 L</option>
                  <option>500 ml</option>
                </select>
              </div>

              <button class="tb-add" type="button">ADD TO CART</button>
            </div>
          </article>

          <!-- Card 3 -->
          <article class="tb-card">
            <div class="tb-card-media">
              <span class="tb-badge orange">Trending</span>
              <button class="tb-wish" type="button" aria-label="Add to wishlist">♡</button>

              <div class="tb-img ph3"></div>
            </div>

            <div class="tb-card-body">
              <div class="tb-name-price">
                <h3>Cow Milk Curd (Dahi)</h3>
                <span class="tb-price">₹65</span>
              </div>

              <p class="tb-meta">Thick set • No preservatives</p>

              <div class="tb-rating">
                <span class="tb-stars">★★★★★</span>
                <span class="tb-rev">4.8 | 258 Reviews</span>
              </div>

              <div class="tb-variant">
                <select aria-label="Select variant">
                  <option>500 g</option>
                  <option>1 kg</option>
                </select>
              </div>

              <button class="tb-add" type="button">ADD TO CART</button>
            </div>
          </article>

          <!-- Card 4 -->
          <article class="tb-card">
            <div class="tb-card-media">
              <span class="tb-badge purple">Must Try</span>
              <button class="tb-wish" type="button" aria-label="Add to wishlist">♡</button>

              <div class="tb-img ph4"></div>
            </div>

            <div class="tb-card-body">
              <div class="tb-name-price">
                <h3>Cow Milk Paneer</h3>
                <span class="tb-price">₹120</span>
              </div>

              <p class="tb-meta">Soft • High protein • Fresh</p>

              <div class="tb-rating">
                <span class="tb-stars">★★★★★</span>
                <span class="tb-rev">4.9 | 167 Reviews</span>
              </div>

              <div class="tb-variant">
                <select aria-label="Select variant">
                  <option>200 g</option>
                  <option>500 g</option>
                </select>
              </div>

              <button class="tb-add" type="button">ADD TO CART</button>
            </div>
          </article>

        </div>
      </div>

      <button class="tb-prod-arrow right" type="button" aria-label="Next products">›</button>
    </div>
  </div>
</section>

<style>
  .tb-products{
    padding: 40px 0 50px;
    background:
      radial-gradient(circle at 15% 15%, rgba(241,204,36,0.10), transparent 55%),
      radial-gradient(circle at 85% 75%, rgba(47,74,30,0.08), transparent 60%),
      #fff;
    margin:20px 0px;
  }

  .tb-prod-head{
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:16px;
    margin-bottom: 16px;
  }

  .tb-prod-title h2{
    margin:0;
    font-size:44px;
    color: var(--green-dark);
    font-weight: 950;
    letter-spacing:.2px;
  }

  .tb-prod-title p{
    margin:8px 0 0 0;
    color: var(--muted);
    font-weight:700;
  }

  .tb-shopmore{
    text-decoration:none;
    background:#183f79;
    color:#fff;
    padding: 11px 20px;
    border-radius: 10px;
    font-weight:900;
    transition: 200ms ease;
    border: 1px solid rgba(0,0,0,0.06);
  }
  .tb-shopmore:hover{
    background:#293879;
    color:white;
  }

  /* Slider shell */
  .tb-prod-slider{
    position:relative;
  }

  .tb-prod-viewport{
    overflow:hidden;
    border-radius: 18px;
  }

  .tb-prod-track{
    display:flex;
    gap: 16px;
    transition: transform 520ms ease;
    will-change: transform;
    padding: 6px 2px 18px;
  }

  
  .tb-card{
    background:#fff;
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 18px;
    overflow:hidden;
    box-shadow: 0 14px 36px rgba(0,0,0,0.08);
    flex: 0 0 calc((100% - 48px)/4);
    min-width: 260px;
    transition: 220ms ease;
  }

  .tb-card:hover{
    transform: translateY(-6px);
    border-color: rgba(241,204,36,0.55);
    box-shadow: 0 18px 46px rgba(0,0,0,0.12);
  }

  .tb-card-media{
    position:relative;
    height: 260px;
    background: #f6f3ea;
    overflow:hidden;
  }

 
  .tb-img{
    height:100%;
    width:100%;
    background-size: cover;
    background-position:center;
    transform: scale(1);
    transition: 300ms ease;
  }
  .tb-card:hover .tb-img{ transform: scale(1.03); }

 
  .ph1{ background-image: url("images/products-1.png"); }
  .ph2{ background-image: url("images/products-2.png"); }
  .ph3{ background-image: url("images/products-3.png"); }
  .ph4{ background-image: url("images/products-4.png"); }

 
  .tb-badge{
    position:absolute;
    top: 10px;
    left: 10px;
    padding: 6px 10px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 900;
    color:#fff;
    z-index:2;
  }
  .tb-badge.green,
  .tb-badge.dark,
  .tb-badge.orange,
  .tb-badge.purple{ background:#f1cc24; }

  .tb-wish{
    position:absolute;
    top: 10px;
    right: 10px;
    width: 34px;
    height: 34px;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.10);
    background: rgba(255,255,255,0.92);
    cursor:pointer;
    font-size:18px;
    color: var(--green-dark);
    display:flex;
    align-items:center;
    justify-content:center;
    transition: 200ms ease;
    z-index:2;
  }
  .tb-wish:hover{
    border-color:#f1cc24;
    color:#f1cc24;
  }

  
  .tb-card-body{ padding: 14px 14px 16px; }

  .tb-name-price{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
  }

  .tb-name-price h3{
    margin:0;
    font-size:16px;
    font-weight:800;
    color: var(--green-dark);
    line-height:1.25;
  }

  .tb-price{
    font-weight:950;
    color:#1f2a1a;
    white-space:nowrap;
  }

  .tb-meta{
    margin: 8px 0 10px;
    color: var(--muted);
    font-weight:700;
    font-size:13px;
  }

  .tb-rating{
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom: 12px;
  }
  .tb-stars{ color:#f1cc24; letter-spacing:1px; font-size:14px; }
  .tb-rev{ color: #6d6d6d; font-size:12px; font-weight:700; }

  .tb-variant select{
    width:100%;
    height: 42px;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.14);
    padding: 0 12px;
    font-weight:700;
    color:#1f2a1a;
    outline:none;
    background:#fff;
    font-size:14px;
  }
  .tb-variant select:focus{
    border-color:#f1cc24;
    box-shadow: 0 0 0 4px rgba(241,204,36,0.18);
  }

  .tb-add{
    width:100%;
    height: 46px;
    border:0;
    margin-top: 12px;
    border-radius: 12px;
    background: #2a431c;
    color:#fff;
    font-weight:950;
    letter-spacing:.6px;
    cursor:pointer;
    transition: 200ms ease;
  }
  .tb-add:hover{
    background:#293879;
    color:white;
  }

  
  .tb-prod-arrow{
    position:absolute;
    top: 45%;
    transform: translateY(-50%);
    width:46px;
    height:46px;
    border-radius: 14px;
    border: 1px solid rgba(0,0,0,0.10);
    background: rgba(255,255,255,0.92);
    cursor:pointer;
    font-size:30px;
    color: var(--green-dark);
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:3;
    transition: 200ms ease;
  }
  .tb-prod-arrow:hover{
    border-color:#f1cc24;
    color:#f1cc24;
    box-shadow: 0 12px 26px rgba(0,0,0,0.12);
  }
  .tb-prod-arrow.left{ left: -6px; }
  .tb-prod-arrow.right{ right: -6px; }

  
  @media (max-width: 1100px){
    .tb-card{ flex: 0 0 calc((100% - 16px)/2); }
    .tb-prod-title h2{ font-size:36px; }
  }

 
  @media (max-width: 700px){
    .tb-prod-slider{ padding-bottom: 62px; } 

    .tb-prod-track{
      gap: 0;               
      padding: 6px 0 18px;  
    }

    .tb-card{
      flex: 0 0 100%;       
      min-width: 0;         
    }

    .tb-prod-arrow{
      display:flex;
      top: auto;
      bottom: 6px;          
      transform: none;
      width: 42px;
      height: 42px;
      font-size: 26px;
      z-index: 2;
    }

    .tb-prod-arrow.left{ left: calc(50% - 56px); }
    .tb-prod-arrow.right{ right: calc(50% - 56px); }

    .tb-prod-title h2{ font-size:30px; }
    .tb-shopmore{ padding: 10px 14px; }
  }
</style>

<script>
  (function(){
    var slider = document.getElementById('tbProdSlider');
    var viewport = document.getElementById('tbProdViewport');
    var track = document.getElementById('tbProdTrack');
    var prog = document.getElementById('tbProgFill');
    if(!slider || !viewport || !track) return;

    var btnPrev = slider.querySelector('.tb-prod-arrow.left');
    var btnNext = slider.querySelector('.tb-prod-arrow.right');

    var index = 0;

    function cardWidth(){
      var first = track.querySelector('.tb-card');
      if(!first) return 0;
      var styles = window.getComputedStyle(track);
      var gap = parseFloat(styles.columnGap || styles.gap || 0);
      return first.getBoundingClientRect().width + gap;
    }

    function maxIndex(){
      var vw = viewport.getBoundingClientRect().width;
      var cw = cardWidth();
      if(!cw) return 0;
      var visible = Math.max(1, Math.round(vw / cw));
      var total = track.querySelectorAll('.tb-card').length;
      return Math.max(0, total - visible);
    }

    function update(){
      var cw = cardWidth();
      var max = maxIndex();
      if(index > max) index = max;
      if(index < 0) index = 0;

      track.style.transform = 'translateX(' + (-index * cw) + 'px)';

      if(prog){
        var total = track.querySelectorAll('.tb-card').length;
        var maxSteps = Math.max(1, total);
        var pct = ((index + 1) / (max + 1)) * 100;
        prog.style.width = Math.max(18, Math.min(100, pct)) + '%';
      }
    }

    btnPrev && btnPrev.addEventListener('click', function(){ index--; update(); });
    btnNext && btnNext.addEventListener('click', function(){ index++; update(); });

    
    var startX = 0, endX = 0;
    viewport.addEventListener('touchstart', function(e){
      startX = e.touches[0].clientX;
    }, {passive:true});

    viewport.addEventListener('touchend', function(e){
      endX = e.changedTouches[0].clientX;
      var diff = endX - startX;
      if(Math.abs(diff) > 40){
        if(diff < 0) index++;
        else index--;
        update();
      }
    });

    window.addEventListener('resize', update);
    update();
  })();
</script>


<!-- ===========================
   WHY CHOOSE US (WHITE PREMIUM)
=========================== -->
<section class="tb-why tb-why-white">
  <div class="container">
    <div class="tb-why-head">
      <div>
        <h2>Why People Choose Us</h2>
        <p>Clean ingredients, honest sourcing, and quality you can trust.</p>
      </div>

      <a href="#" class="tb-why-cta">Learn More</a>
    </div>

    <div class="tb-why-grid">

      <article class="tb-why-card">
        <div class="tb-why-ico">
          <!-- Shield check -->
          <svg class="tb-why-svg" viewBox="0 0 64 64" aria-hidden="true">
            <path d="M32 10l18 8v14c0 12-8 20-18 22C22 52 14 44 14 32V18l18-8Z"/>
            <path d="M22 32l6 6 14-14"/>
          </svg>
        </div>
        <h3>Quality Checked</h3>
        <p>Every batch goes through strict checks for freshness and consistency.</p>
      </article>

      <article class="tb-why-card">
        <div class="tb-why-ico">
          <!-- Leaf -->
          <svg class="tb-why-svg" viewBox="0 0 64 64" aria-hidden="true">
            <path d="M50 14C24 16 14 32 14 44c0 6 4 10 10 10 12 0 30-10 26-40Z"/>
            <path d="M22 44c10-2 18-10 22-22"/>
          </svg>
        </div>
        <h3>Cleanness</h3>
        <p>No unnecessary additives—only what’s needed for real taste and purity.</p>
      </article>

      <article class="tb-why-card">
        <div class="tb-why-ico">
          <!-- Farm / house -->
          <svg class="tb-why-svg" viewBox="0 0 64 64" aria-hidden="true">
            <path d="M10 30L32 12l22 18"/>
            <path d="M18 28v24h28V28"/>
            <path d="M26 52V38h12v14"/>
          </svg>
        </div>
        <h3>Farm to Home</h3>
        <p>Sourced responsibly and delivered with care for everyday use.</p>
      </article>

      <article class="tb-why-card">
        <div class="tb-why-ico">
          <!-- Transparency -->
          <svg class="tb-why-svg" viewBox="0 0 64 64" aria-hidden="true">
            <path d="M14 16h36v26H26l-10 10V16Z"/>
            <path d="M22 26h20"/>
            <path d="M22 34h14"/>
          </svg>
        </div>
        <h3>Transparent Process</h3>
        <p>Clear product info, real sourcing, and honest communication.</p>
      </article>

    </div>
  </div>
</section>

<style>
 
  .tb-why-white{
    background:#fff;
    padding: 34px 0 34px;
  }

  .tb-why-head{
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:18px;
    margin-bottom: 18px;
    flex-wrap:wrap;
  }

  .tb-why-head h2{
    margin:0;
    font-size:38px;
    font-weight:950;
    color:#2a431c;
    letter-spacing:.2px;
  }

  .tb-why-head p{
    margin:8px 0 0 0;
    color: var(--muted);
    font-weight:700;
    max-width: 560px;
  }

  .tb-why-cta{
    text-decoration:none;
    padding: 12px 16px;
    border-radius: 12px;
    font-weight:900;
    color: var(--green-dark);
    border: 1px solid rgba(0,0,0,0.10);
    background:#fff;
    transition: 200ms ease;
    white-space:nowrap;
    box-shadow: 0 10px 26px rgba(0,0,0,0.06);
  }
  .tb-why-cta:hover{
    border-color:#f1cc24;
    color:#1f2a1a;
    box-shadow: 0 14px 34px rgba(0,0,0,0.10);
  }

  .tb-why-grid{
    display:grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }

  .tb-why-card{
    background:#fff;
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 18px;
    padding: 18px 16px;
    box-shadow: 0 14px 36px rgba(0,0,0,0.08);
    transition: 220ms ease;
    position:relative;
    overflow:hidden;
    min-height: 210px;
  }

  /* premium top highlight (still white, very subtle) */
  .tb-why-card::before{
    content:"";
    position:absolute;
    top:-40px;
    left:-40px;
    width:140px;
    height:140px;
    background: rgba(241,204,36,0.14);
    border-radius: 999px;
    filter: blur(0px);
    opacity: .6;
    pointer-events:none;
  }

  .tb-why-card:hover{
    transform: translateY(-6px);
    border-color: rgba(241,204,36,0.65);
    box-shadow: 0 18px 46px rgba(0,0,0,0.12);
  }

  .tb-why-ico{
    width: 58px;
    height: 58px;
    border-radius: 16px;
    background: rgba(47,74,30,0.10);
    display:grid;
    place-items:center;
    position:relative;
    z-index:1;
    transition: 220ms ease;
  }

  .tb-why-svg{
    width: 30px;
    height: 30px;
    stroke: var(--green-dark);
    stroke-width: 3.2;
    fill:none;
    stroke-linecap:round;
    stroke-linejoin:round;
    transition: 220ms ease;
  }

  .tb-why-card h3{
    margin: 12px 0 8px 0;
    font-size:16px;
    font-weight:950;
    color: var(--green-dark);
    position:relative;
    z-index:1;
  }

  .tb-why-card p{
    margin:0;
    color: #6d6d6d;
    font-weight:650;
    line-height:1.55;
    font-size:14px;
    position:relative;
    z-index:1;
  }

  
  .tb-why-card:hover .tb-why-ico{
    background: rgba(241,204,36,0.18);
    box-shadow: 0 0 0 6px rgba(241,204,36,0.14);
  }
  .tb-why-card:hover .tb-why-svg{
    stroke:#f1cc24;
  }

  @media (max-width: 1100px){
    .tb-why-grid{ grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 640px){
    .tb-why-white{ padding: 26px 0; }
    .tb-why-head h2{ font-size:28px; }
    .tb-why-grid{ grid-template-columns: 1fr; }
  }
</style>


<!-- content section -->
<section class="tb-splitcoll-sec" id="tbSplitCollSec">
  <div class="container">
    <div class="tb-splitcoll-wrap">

      <!-- LEFT CONTENT -->
      <div class="tb-splitcoll-left">
        <span class="tb-splitcoll-kicker">Why It Works</span>
        <h2>Focused Range & Premium Experience</h2>
        <p>
          When choices are curated, decisions become easier. We keep standards high so customers
          get consistent quality, clean processes, and a premium feel—every single time.
        </p>

       <div class="tb-splitcoll-points tb-checklist">
  <div class="tb-splitcoll-point">
    <span class="tb-check-ico" aria-hidden="true">✓</span>
    <span style="color:black;">Farm-fresh sourcing with clean, traceable handling</span>
  </div>

  <div class="tb-splitcoll-point">
    <span class="tb-check-ico" aria-hidden="true">✓</span>
    <span style="color:black;">Purity-first process with hygiene at every stage</span>
  </div>

  <div class="tb-splitcoll-point">
    <span class="tb-check-ico" aria-hidden="true">✓</span>
    <span style="color:black;">Consistent quality across batches—every single time</span>
  </div>

  <div class="tb-splitcoll-point">
    <span class="tb-check-ico" aria-hidden="true">✓</span>
    <span style="color:black;">Premium packing that keeps freshness locked in</span>
  </div>
</div>


        <div class="tb-splitcoll-actions">
          <a href="#" class="tb-splitcoll-btn primary">
        Explore Membership
          </a>
          <a href="#" class="tb-splitcoll-btn outline">
        Contact Us
          </a>
        </div>
      </div>

      <!-- RIGHT COLLAGE -->
      <div class="tb-splitcoll-right">
        <div class="tb-splitcoll-collage">
          <div class="tb-splitcoll-img big"></div>
          

          <div class="tb-splitcoll-float">
            <strong><i class="fi fi-rr-star"></i> 4.8★ Rating</strong>
            <span>Trusted by customers</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<style>
    .tb-splitcoll-sec{
  padding:70px 0;
  background:
    radial-gradient(circle at 18% 20%, rgba(241,204,36,0.12), transparent 55%),
    radial-gradient(circle at 82% 70%, rgba(47,74,30,0.10), transparent 58%),
    linear-gradient(180deg, #ffffff, #f6f8f2);
}

.tb-splitcoll-wrap{
  display:grid;
  grid-template-columns: 1.05fr 0.95fr;
  gap:22px;
  align-items:center;
}


.tb-splitcoll-kicker{
  display:inline-block;
  font-weight:950;
  letter-spacing:1px;
  text-transform:uppercase;
  font-size:12px;
  color:#263d18;
  background:rgba(47,74,30,0.08);
  padding:8px 12px;
  border-radius:999px;
  margin-bottom:12px;
}

.tb-splitcoll-left h2{
  margin:0 0 10px;
  font-size:clamp(28px,3.2vw,42px);
  font-weight:950;
  color:#000000;
  line-height:1.12;
}

.tb-splitcoll-left p{
  margin:0 0 16px;
  color:#424242;
  font-weight:700;
  line-height:1.75;
  max-width:650px;
}

.tb-checklist{
  display:grid;
  gap:12px;
  margin-top:10px;
}

.tb-checklist .tb-splitcoll-point{
  display:flex;
  align-items:flex-start;
  gap:12px;
  padding:10px 14px;
  border-radius:18px;
  background:
    linear-gradient(180deg, rgba(255,255,255,0.96), rgba(255,255,255,0.90));
  border: 1px solid rgba(0,0,0,0.08);
  box-shadow: 0 14px 34px rgba(0,0,0,0.08);
  transition: .22s ease;
  position:relative;
  overflow:hidden;
}



.tb-check-ico{
  flex:0 0 auto;
  width:34px;
  height:34px;
  border-radius:999px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  font-weight:950;
  color:#1f2a1a;
  background:#2a431c;
  box-shadow: 0 10px 22px rgba(0,0,0,0.10);
  margin-top:1px;
}

.tb-checklist span{
  font-weight:750;
  color:white;
  line-height:1.55;
  font-size:16px;
}
.tb-splitcoll-point span{
  font-weight:750;
  color:white;
  line-height:1.55;
  font-size:16px;
}
}


/* premium hover */
.tb-checklist .tb-splitcoll-point:hover{
  transform: translateY(-4px);
  border-color: rgba(241,204,36,0.70);
  box-shadow: 0 22px 60px rgba(0,0,0,0.12);
}


@media(max-width:560px){
  .tb-checklist .tb-splitcoll-point{
    padding:12px 12px;
    border-radius:16px;
  }
  .tb-check-ico{
    width:32px;
    height:32px;
  }
  .tb-checklist .tb-splitcoll-point span{
    font-size:14px;
  }
}

.tb-splitcoll-actions{
  display:flex;
  gap:12px;
  flex-wrap:wrap;
  margin-top:16px;
}
.tb-splitcoll-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  padding:11px 18px;
  border-radius:14px;
  font-weight:950;
  text-decoration:none;
  border:1px solid rgba(0,0,0,0.10);
  box-shadow:0 14px 34px rgba(0,0,0,0.10);
  transition:.2s ease;
}
.tb-splitcoll-btn i{ font-size:18px; }

.tb-splitcoll-btn.primary{
  background:#183f79;
  color:#fff;
}
.tb-splitcoll-btn.primary:hover{
  background:#293879;
  color:white;
  border-color:#f1cc24;
  transform:translateY(-3px);
  box-shadow:0 22px 60px rgba(0,0,0,0.14);
}

.tb-splitcoll-btn.outline{
  background:#fff;
  color:#263d18;
}
.tb-splitcoll-btn.outline:hover{
  border-color:#f1cc24;
  color:#1f2a1a;
  box-shadow:0 18px 46px rgba(0,0,0,0.12);
}


.tb-splitcoll-collage{
  position:relative;
  border-radius:26px;
  border:1px solid rgba(0,0,0,0.10);
  box-shadow:0 26px 80px rgba(0,0,0,0.14);
  background:linear-gradient(180deg,#ffffff,#f7f9f4);
  padding:16px;
  overflow:hidden;
}

.tb-splitcoll-img{
  border-radius:22px;
  border:1px solid rgba(0,0,0,0.10);
  background-size:cover;
  background-position:center;
  box-shadow:0 18px 56px rgba(0,0,0,0.12);
}


.tb-splitcoll-img.big{
  height:380px;
  background-image:url("images/milk-vans.webp");
}
.tb-splitcoll-img.sm{
  position:absolute;
  width:44%;
  height:150px;
  bottom:16px;
}

.tb-splitcoll-float{
  position:absolute;
  top:16px;
  left:16px;
  background:rgba(255,255,255,0.92);
  border:1px solid rgba(0,0,0,0.08);
  border-radius:16px;
  padding:12px 14px;
  box-shadow:0 16px 44px rgba(0,0,0,0.14);
}
.tb-splitcoll-float strong{
  display:flex;
  gap:8px;
  align-items:center;
  color:#263d18;
  font-weight:950;
}
.tb-splitcoll-float span{
  display:block;
  margin-top:3px;
  color:#5c6b55;
  font-weight:750;
  font-size:13px;
}


@media(max-width:980px){
  .tb-splitcoll-wrap{ grid-template-columns:1fr; }
  .tb-splitcoll-img.big{ height:320px; }
  .tb-splitcoll-img.sm{ width:42%; height:135px; }
}
@media(max-width:560px){
  .tb-splitcoll-sec{ padding:56px 0; }
  .tb-splitcoll-img.big{ height:280px; }
  .tb-splitcoll-img.sm{ display:none; } /* clean mobile */
}

</style>


<!-- ===========================
   CLIENT LOGOS (TRUST SECTION)
=========================== -->
<section class="tb-usps" id="tbUsps">
  <div class="container">

    <div class="tb-usps-head">
      <h2>Our USPs</h2>
      <p>Driven by farm-fresh quality, purity standards, and ethical practices, we deliver a clean, premium dairy experience you can trust.</p>
    </div>

    <div class="tb-usps-grid">

      <!-- 1 -->
      <div class="tb-usp-card">
        <div class="tb-usp-icon" aria-hidden="true">
          <!-- Milk Bottle -->
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M9 3h6v3l1.5 2V21H7.5V8L9 6V3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M9 6h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M8 11h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" opacity=".75"/>
          </svg>
        </div>
        <h3>Farm-Fresh Daily Collection</h3>
        <p>Freshly sourced every day for a naturally rich and pure taste.</p>
      </div>

      <!-- 2 -->
      <div class="tb-usp-card">
        <div class="tb-usp-icon" aria-hidden="true">
          <!-- Shield -->
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 3l8 4v6c0 5-3.5 8-8 8s-8-3-8-8V7l8-4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M9.5 12l1.7 1.7L14.8 10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h3>100% Pure & Adulteration-Free</h3>
        <p>No preservatives, no additives—only clean, honest dairy.</p>
      </div>

      <!-- 3 -->
      <div class="tb-usp-card">
        <div class="tb-usp-icon" aria-hidden="true">
          <!-- Cow Head -->
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M7 10c0-3 2-5 5-5h0c3 0 5 2 5 5v7c0 2-1.5 3-3.5 3h-3C8.5 20 7 19 7 17v-7Z"
              stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M7 11c-1.2-.2-2.5-1.2-2.8-2.6C4 7 5 6 6.3 6.3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M17 11c1.2-.2 2.5-1.2 2.8-2.6C20 7 19 6 17.7 6.3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M10 13h0M14 13h0" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            <path d="M10 16c.8.8 3.2.8 4 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </div>
        <h3>Ethical Cow Care</h3>
        <p>Healthy, well-cared cows with clean shelters and balanced feed.</p>
      </div>

      <!-- 4 -->
      <div class="tb-usp-card">
        <div class="tb-usp-icon" aria-hidden="true">
          <!-- Sparkle / Hygiene -->
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 3l1.2 4.2L17 8.5l-3.8 1.3L12 14l-1.2-4.2L7 8.5l3.8-1.3L12 3Z"
              stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M6 14l.6 2.2L9 17l-2.4.8L6 20l-.6-2.2L3 17l2.4-.8L6 14Z"
              stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" opacity=".8"/>
            <path d="M18 14l.6 2.2L21 17l-2.4.8L18 20l-.6-2.2L15 17l2.4-.8L18 14Z"
              stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" opacity=".8"/>
          </svg>
        </div>
        <h3>Hygienic Traditional Process</h3>
        <p>Time-tested methods with strict hygiene at every stage.</p>
      </div>

      <!-- 5 -->
      <div class="tb-usp-card">
        <div class="tb-usp-icon" aria-hidden="true">
          <!-- Checklist -->
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M8 6h13M8 12h13M8 18h13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M3.5 6.2l1.2 1.2L6.8 5.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M3.5 12.2l1.2 1.2 2.1-2.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M3.5 18.2l1.2 1.2 2.1-2.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h3>Quality Checks at Every Stage</h3>
        <p>From milking to packing—purity and quality are verified.</p>
      </div>

      <!-- 6 -->
      <div class="tb-usp-card">
        <div class="tb-usp-icon" aria-hidden="true">
          <!-- Leaf / Sustainable -->
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M20 4c-8 1-13 6-14 14 8-1 13-6 14-14Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M6 18c2-4 6-8 10-10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </div>
        <h3>Sustainable Farm Practices</h3>
        <p>Responsible farming that respects nature and community.</p>
      </div>

    </div>
  </div>
</section>

<style>
  /* ===========================
     OUR USPs - WHITE PREMIUM
  =========================== */

  .tb-usps{
    background:#fff;
    padding: 34px 0 34px;
  }

  .tb-usps-head{
    margin-bottom: 18px;
  }

  .tb-usps-head h2{
    margin:0;
    font-size:38px;
    font-weight:950;
    color:#2a431c;
    letter-spacing:.2px;
  }

  .tb-usps-head p{
    margin:8px 0 0 0;
    color: var(--muted);
    font-weight:700;
    max-width: 720px;
  }

  .tb-usps-grid{
    display:grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-top: 14px;
  }

  .tb-usp-card{
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 18px;
    background:#fff;
    box-shadow: 0 14px 36px rgba(0,0,0,0.07);
    padding: 18px;
    transition: 220ms ease;
    position:relative;
    overflow:hidden;
  }

  .tb-usp-card::before{
    content:"";
    position:absolute;
    top:-40px;
    right:-40px;
    width:120px;
    height:120px;
    border-radius:999px;
    background: rgba(241,204,36,0.16);
    opacity:.75;
  }

  .tb-usp-icon{
    width: 50px;
    height: 50px;
    border-radius: 14px;
    display:flex;
    align-items:center;
    justify-content:center;
    border: 1px solid rgba(0,0,0,0.08);
    box-shadow: 0 10px 22px rgba(0,0,0,0.05);
    background: #fff;
    margin-bottom: 12px;
    color: var(--green-dark);
  }

  .tb-usp-icon svg{
    width: 26px;
    height: 26px;
  }

  .tb-usp-card h3{
    margin:0;
    font-size: 16px;
    font-weight: 900;
    color: var(--green-dark);
    letter-spacing: .2px;
  }

  .tb-usp-card p{
    margin:8px 0 0 0;
    color: var(--muted);
    font-weight: 700;
    line-height: 1.5;
    font-size: 14px;
  }

  .tb-usp-card:hover{
    transform: translateY(-5px);
    border-color: rgba(241,204,36,0.65);
    box-shadow: 0 18px 46px rgba(0,0,0,0.12);
  }

  /* Responsive */
  @media (max-width: 992px){
    .tb-usps-grid{ grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 640px){
    .tb-usps-head h2{ font-size: 28px; }
    .tb-usps-grid{ grid-template-columns: 1fr; }
    .tb-usp-card{ padding: 16px; }
  }
</style>


<!-- you tube section -->


<section class="tb-media-split" id="tbMediaSplit">
  <div class="container">

    <div class="tb-ms-head">
      <h2>See Purity in Action</h2>
      <p>From farm to kitchen—watch how every product is crafted with care and transparency.</p>
    </div>

    <div class="tb-ms-grid">

      <!-- LEFT IMAGES -->
      <div class="tb-ms-left">
        <div class="tb-ms-img img1"></div>
        <div class="tb-ms-img img2"></div>
      </div>

      <!-- RIGHT VIDEO -->
      <div class="tb-ms-right">
        <div class="tb-ms-video" role="button" tabindex="0" aria-label="Play video" data-youtube="YOUTUBE_ID">
          <div class="tb-ms-overlay"></div>

          <!-- ghost text -->
          <div class="tb-ms-ghost">OUR STORY</div>

          <!-- play -->
          <div class="tb-ms-play">
            <span class="tb-ms-ring"></span>
            <span class="tb-ms-core"><i class="tb-ms-tri"></i></span>
            <span class="tb-ms-cta">
              <b>Watch</b>
              <small>2 min video</small>
            </span>
          </div>

          <div class="tb-ms-cap">
            <span class="tb-ms-pill">Clean • Simple • Trusted</span>
            <h3>Crafted with care, built on trust.</h3>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Modal -->
  <div class="tb-ms-modal" aria-hidden="true">
    <div class="tb-ms-modalbox">
      <button class="tb-ms-close" type="button">✕</button>
      <div class="tb-ms-frame" id="tbMsFrame"></div>
    </div>
  </div>
</section>

<style>
:root{
  --tb-green:#2f4a1e;
  --tb-green-dark:#000000;
  --tb-gold:#f1cc24;
}

.tb-ms-head h2{
  margin: 0 0 6px 0;   
  line-height: 1.08;  
}

.tb-ms-head p{
  margin-top: 0;     
}
.tb-media-split{
  background:
    radial-gradient(circle at 20% 20%, rgba(241,204,36,0.20), transparent 55%),
    radial-gradient(circle at 80% 70%, rgba(47,74,30,0.16), transparent 58%),
    linear-gradient(180deg,#fbfaf6,#ffffff);
  padding:50px 0 50px;
  margin-top:50px;
}

.tb-ms-head{
  text-align:center;
  max-width:800px;
  margin:0 auto 28px;
}
.tb-ms-head h2{
  font-size:clamp(34px,4vw,52px);
  font-weight:950;
  color:var(--tb-green-dark);
}
.tb-ms-head p{
  margin-top:10px;
  color:#424242;
  font-weight:700;
}

/* Grid */
.tb-ms-grid{
  display:grid;
  grid-template-columns: 0.5fr 1.2fr;
  gap:24px;
  align-items:stretch;
}

/* LEFT IMAGES */
.tb-ms-left{
  display:grid;
  grid-template-rows:1fr 1fr;
  gap:20px;
}
.tb-ms-img{
  border-radius:22px;
  background-size:cover;
  background-position:center;
  box-shadow:0 22px 60px rgba(0,0,0,.14);
  transition:.25s ease;
  border:1px solid rgba(0,0,0,.1);
}
.tb-ms-img:hover{
  transform:translateY(-6px);
  box-shadow:0 28px 70px rgba(0,0,0,.18);
  border-color:rgba(241,204,36,.6);
}

/* replace images */
.img1{background-image:url("images/galleries-1.png");}
.img2{background-image:url("images/galleries-3.png");}


.tb-ms-video{
  position:relative;
  height:100%;
  min-height:520px;
  border-radius:28px;
  overflow:hidden;
  cursor:pointer;
  border:1px solid rgba(0,0,0,.12);
  box-shadow:0 32px 90px rgba(0,0,0,.2),0 0 0 8px rgba(241,204,36,.08);
  background:
    radial-gradient(circle at 30% 30%, rgba(241,204,36,.28), transparent 60%),
    radial-gradient(circle at 70% 70%, rgba(47,74,30,.28), transparent 60%),
    url("images/ourgallery.webp");
  background-size:cover;
  background-position:center;
}

.tb-ms-video:hover{
  box-shadow:0 36px 100px rgba(0,0,0,.24),0 0 0 10px rgba(241,204,36,.1);
}

.tb-ms-overlay{
  position:absolute;
  inset:0;
  background:linear-gradient(180deg,rgba(0,0,0,.2),rgba(0,0,0,.6));
}

/* ghost text */
.tb-ms-ghost{
  position:absolute;
  top:20px;
  left:50%;
  transform:translateX(-50%);
  font-size:60px;
  font-weight:900;
  color:#fff;
  opacity:.08;
  letter-spacing:8px;
  pointer-events:none;
}

/* play */
.tb-ms-play{
  position:absolute;
  top:50%;
  left:50%;
  transform:translate(-50%,-50%);
  display:flex;
  align-items:center;
  gap:16px;
  z-index:2;
}
.tb-ms-ring{
  position:absolute;
  width:90px;
  height:90px;
  border-radius:50%;
  background:rgba(241,204,36,.25);
  animation:pulse 2s infinite;
}
@keyframes pulse{
  0%{transform:scale(1);opacity:.9}
  70%{transform:scale(1.15);opacity:.3}
  100%{transform:scale(1);opacity:.9}
}
.tb-ms-core{
  width:76px;
  height:76px;
  border-radius:50%;
  background:#fff;
  display:grid;
  place-items:center;
  z-index:2;
}
.tb-ms-tri{
  width:0;height:0;
  border-top:10px solid transparent;
  border-bottom:10px solid transparent;
  border-left:16px solid var(--tb-green-dark);
  margin-left:4px;
}
.tb-ms-cta{
  color:#fff;
  font-weight:900;
}

/* caption */
.tb-ms-cap{
  position:absolute;
  left:22px;
  bottom:22px;
  color:#fff;
}
.tb-ms-pill{
  background:rgba(255,255,255,.15);
  padding:8px 12px;
  border-radius:20px;
  font-weight:800;
  font-size:13px;
}
.tb-ms-cap h3{
  margin-top:10px;
  font-size:28px;
  font-weight:900;
}

/* Modal */
.tb-ms-modal{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.75);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:9999;
}
.tb-ms-modal.is-open{display:flex;}
.tb-ms-modalbox{
  width:min(960px,100%);
  background:#000;
  border-radius:18px;
  overflow:hidden;
}
.tb-ms-frame iframe{width:100%;height:100%;aspect-ratio:16/9;}
.tb-ms-close{
  position:absolute;
  top:10px;right:10px;
  background:#fff;border:none;
  padding:10px;border-radius:50%;
  cursor:pointer;
}

/* Responsive */
@media(max-width:900px){
  .tb-ms-grid{grid-template-columns:1fr;}
  .tb-ms-left{grid-template-columns:1fr 1fr;grid-template-rows:auto;}
  .tb-ms-video{min-height:420px;}
}
</style>

<script>
(function(){
  var sec=document.getElementById('tbMediaSplit');
  var video=sec.querySelector('.tb-ms-video');
  var modal=sec.querySelector('.tb-ms-modal');
  var frame=document.getElementById('tbMsFrame');
  var close=sec.querySelector('.tb-ms-close');

  video.onclick=function(){
    var id=video.getAttribute('data-youtube');
    frame.innerHTML='<iframe src="https://www.youtube.com/embed/'+id+'?autoplay=1" allow="autoplay" allowfullscreen></iframe>';
    modal.classList.add('is-open');
  }
  close.onclick=function(){
    modal.classList.remove('is-open');
    frame.innerHTML='';
  }
})();
</script>


<!--- testimonails section -->

<section class="tb-testimonials" id="tbTestimonials">
  <div class="container">

    <div class="tb-testi-head">
      <h2>What Our Customers Say</h2>
      <p>Real stories from people who trust our products every day.</p>
    </div>

    <div class="tb-testi-grid">

      <!-- Card 1 -->
      <div class="tb-testi-card">
        <div class="tb-testi-quote">“</div>
        <p class="tb-testi-text">
          The quality is exceptional. You can actually feel the difference in taste and freshness. It feels honest and clean.
        </p>
        <div class="tb-testi-user">
          <div class="tb-testi-avatar">A</div>
          <div>
            <strong>Anita Sharma</strong>
            <span>Delhi</span>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="tb-testi-card featured">
        <div class="tb-testi-quote">“</div>
        <p class="tb-testi-text">
          We switched completely to these products for our home. The purity and consistency is what makes them stand out.
        </p>
        <div class="tb-testi-user">
          <div class="tb-testi-avatar">R</div>
          <div>
            <strong>Rohit Mehta</strong>
            <span>Mumbai</span>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="tb-testi-card">
        <div class="tb-testi-quote">“</div>
        <p class="tb-testi-text">
          Packaging, quality, and delivery — everything feels premium. You know you are buying something genuinely good.
        </p>
        <div class="tb-testi-user">
          <div class="tb-testi-avatar">P</div>
          <div>
            <strong>Pooja Verma</strong>
            <span>Bangalore</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<style>
:root{
  --tb-green:#e24431;
  --tb-green-dark:#000000;
  --tb-gold:#f1cc24;
}

.tb-testimonials{
  background:#fff;
  padding:10px 0 30px;
}

/* Heading */
.tb-testi-head{
  text-align:center;
  max-width:700px;
  margin:0 auto 50px;
}
.tb-testi-head h2{
  font-size:clamp(32px,4vw,48px);
  font-weight:950;
  color:#2a431c;
  margin-bottom:6px;
}
.tb-testi-head p{
  color:#424242;
  font-weight:700;
}

/* Grid */
.tb-testi-grid{
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap:26px;
}

/* Cards */
.tb-testi-card{
  background:#fff;
  border-radius:26px;
  padding:34px 32px 32px;
  position:relative;
  border:1px solid rgba(0,0,0,0.08);
  box-shadow:0 18px 60px rgba(0,0,0,0.08);
  transition:.35s ease;
}

.tb-testi-card:hover{
  transform:translateY(-8px);
  box-shadow:0 28px 80px rgba(0,0,0,0.14);
  border-color:#de4631;
}

/* Highlight middle card */
.tb-testi-card.featured{
  box-shadow:0 28px 90px rgba(0,0,0,0.18);
  border-color:#293879;
}

/* Quote */
.tb-testi-quote{
  font-size:72px;
  font-weight:900;
  color:#293879;
  line-height:1;
  position:absolute;
  top:14px;
  left:22px;
  
}

/* Text */
.tb-testi-text{
  font-size:17px;
  line-height:1.7;
  font-weight:600;
  color:#333;
  margin-top:26px;
}


.tb-testi-user{
  display:flex;
  align-items:center;
  gap:14px;
  margin-top:26px;
}

.tb-testi-avatar{
  width:46px;
  height:46px;
  border-radius:50%;
  background:#2a431c;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#1f2a1a;
  font-weight:900;
  font-size:18px;
  color:white;
}

.tb-testi-user strong{
  display:block;
  color:var(--tb-green-dark);
  font-size:15px;
}
.tb-testi-user span{
  font-size:13px;
  color:#777;
}

/* Responsive */
@media(max-width:900px){
  .tb-testi-grid{
    grid-template-columns:1fr;
  }
}
</style>

<!-- cta -->
<section class="tb-prefooter-cta" id="tbPreFooterCta">
  <div class="container">

    <div class="tb-pcta-box">

      <div class="tb-pcta-left">
        <span class="tb-pcta-kicker">Ready to switch to clean food?</span>
        <h2>Bring Purity to Your Everyday Kitchen</h2>
        <p>
          Carefully sourced essentials that feel premium from the first use—trusted by families who value quality and honesty.
        </p>

        <div class="tb-pcta-points">
          <div class="tb-point">
            <span class="tb-dot"></span>
            <span>Clean ingredients & transparent sourcing</span>
          </div>
          <div class="tb-point">
            <span class="tb-dot"></span>
            <span>Fresh batches, premium packaging</span>
          </div>
          <div class="tb-point">
            <span class="tb-dot"></span>
            <span>Fast support & reliable delivery</span>
          </div>
        </div>
      </div>

      <div class="tb-pcta-right">
        <a href="#" class="tb-pcta-btn primary">Explore Products</a>
        <a href="#" class="tb-pcta-btn secondary">Membership</a>

        <div class="tb-pcta-mini">
          <span class="tb-mini-badge">★</span>
          <span><b>4.8/5</b> average customer rating</span>
        </div>
      </div>

    </div>

  </div>
</section>
<style>
.tb-prefooter-cta{
  padding: 60px 0 70px;
  background:
    radial-gradient(circle at 18% 20%, rgba(241,204,36,0.18), transparent 55%),
    radial-gradient(circle at 82% 70%, rgba(47,74,30,0.14), transparent 58%),
    linear-gradient(180deg, #ffffff, #f6f8f2);
}

.tb-pcta-box{
  background: linear-gradient(180deg, #ffffff, #f7f9f4);
  border: 1px solid rgba(0,0,0,0.08);
  border-radius: 26px;
  padding: 44px 48px;
  box-shadow: 0 22px 70px rgba(0,0,0,0.08);
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap: 44px;
  position:relative;
  overflow:hidden;
}

/* subtle premium accent corner */
.tb-pcta-box::before{
  content:"";
  position:absolute;
  top:-60px;
  right:-60px;
  width: 180px;
  height: 180px;
  border-radius: 999px;
  background: rgba(241,204,36,0.14);
  pointer-events:none;
}

.tb-pcta-kicker{
  display:inline-block;
  font-weight: 900;
  letter-spacing: 1px;
  text-transform: uppercase;
  font-size: 12px;
  color: var(--tb-green-dark);
  background: rgba(47,74,30,0.08);
  padding: 8px 12px;
  border-radius: 999px;
  margin-bottom: 14px;
}

.tb-pcta-left h2{
  margin: 0 0 10px 0;
  font-size: clamp(28px, 3.2vw, 40px);
  font-weight: 950;
  color:#2a431c;
  line-height: 1.12;
}

.tb-pcta-left p{
  margin: 0 0 18px 0;
  color: #424242;
  font-weight: 650;
  line-height: 1.65;
  max-width: 560px;
}

.tb-pcta-points{
  display:grid;
  gap: 10px;
}

.tb-point{
  display:flex;
  align-items:flex-start;
  gap: 10px;
  color:#2b2b2b;
  font-weight: 700;
}

.tb-dot{
  width: 10px;
  height: 10px;
  margin-top: 7px;
  border-radius: 999px;
  background: rgba(47,74,30,0.25);
  box-shadow: 0 0 0 4px rgba(241,204,36,0.12);
}

/* Right side */
.tb-pcta-right{
  display:flex;
  flex-direction:column;
  gap: 12px;
  min-width: 240px;
  align-items:flex-end;
}

.tb-pcta-btn{
  text-decoration:none;
  font-weight: 950;
  border-radius: 14px;
  padding: 14px 18px;
  width: 240px;
  text-align:center;
  transition: 200ms ease;
  border: 1px solid rgba(0,0,0,0.10);
  box-shadow: 0 14px 34px rgba(0,0,0,0.08);
}

.tb-pcta-btn.primary{
  background:#183f79;
  color:#fff;
}

.tb-pcta-btn.primary:hover{
  background: #293879;
  color:white;
  border-color:#f1cc24;
}

.tb-pcta-btn.secondary{
  background:#fff;
  color: var(--tb-green-dark);
}

.tb-pcta-btn.secondary:hover{
  border-color:#f1cc24;
  color:#1f2a1a;
  box-shadow: 0 18px 46px rgba(0,0,0,0.12);
}

.tb-pcta-mini{
  display:flex;
  gap: 10px;
  align-items:center;
  color:#5c6b55;
  font-weight: 750;
  margin-top: 6px;
}

.tb-mini-badge{
  width: 26px;
  height: 26px;
  border-radius: 9px;
  display:grid;
  place-items:center;
  background: rgba(241,204,36,0.20);
  color:#7a5b00;
  font-weight: 950;
}

/* Responsive */
@media (max-width: 980px){
  .tb-pcta-box{
    flex-direction: column;
    align-items:flex-start;
    padding: 34px 28px;
  }
  .tb-pcta-right{
    width:100%;
    align-items:stretch;
  }
  .tb-pcta-btn{
    width:100%;
  }
}

    
</style>

<!-- ended -->

<!-- blogs -->

<section class="tb-blog-sec" id="tbBlogSec">
  <div class="container">

    <div class="tb-blog-head">
      <div>
        <span class="tb-blog-kicker">From Our Journal</span>
        <h2>Stories, Insights & Clean Living</h2>
        <p>Explore tips, sourcing stories, and everyday inspiration for a healthier lifestyle.</p>
      </div>

      <a href="/blogs" class="tb-blog-all-btn">
        
        Read All Blogs
      </a>
    </div>

    <div class="tb-blog-grid">

      <!-- Card 1 -->
      <article class="tb-blog-card">
        <div class="tb-blog-img b1"></div>
        <div class="tb-blog-body">
          <span class="tb-blog-tag">Clean Living</span>
          <h3>Why choosing organic daily essentials matters</h3>
          <p>
            Understand how clean sourcing and simple ingredients impact everyday health.
          </p>
          <a href="#" class="tb-blog-read">
            Read More <i class="fi fi-rr-arrow-right"></i>
          </a>
        </div>
      </article>

      <!-- Card 2 -->
      <article class="tb-blog-card">
        <div class="tb-blog-img b2"></div>
        <div class="tb-blog-body">
          <span class="tb-blog-tag">Sourcing</span>
          <h3>How transparent sourcing builds customer trust</h3>
          <p>
            Discover why clear supply chains help families choose better with confidence.
          </p>
          <a href="#" class="tb-blog-read">
            Read More <i class="fi fi-rr-arrow-right"></i>
          </a>
        </div>
      </article>

      <!-- Card 3 -->
      <article class="tb-blog-card">
        <div class="tb-blog-img b3"></div>
        <div class="tb-blog-body">
          <span class="tb-blog-tag">Wellness</span>
          <h3>Premium packaging: More than just good looks</h3>
          <p>
            Learn how better packaging protects freshness and quality.
          </p>
          <a href="#" class="tb-blog-read">
            Read More <i class="fi fi-rr-arrow-right"></i>
          </a>
        </div>
      </article>

    </div>
  </div>
</section>
<style>
    .tb-blog-sec{
  padding:70px 0;
  background:#fff;
}

.tb-blog-head{
  display:flex;
  align-items:flex-end;
  justify-content:space-between;
  gap:18px;
  margin-bottom:28px;
}

.tb-blog-kicker{
  display:inline-block;
  font-weight:950;
  letter-spacing:1px;
  text-transform:uppercase;
  font-size:12px;
  color:white;
  background:#2a431c;
  padding:8px 12px;
  border-radius:999px;
  margin-bottom:10px;
}

.tb-blog-head h2{
  margin:0 0 6px;
  font-size:clamp(28px,3.2vw,42px);
  font-weight:950;
  color:#2a431c;
}

.tb-blog-head p{
  margin:0;
  color:#424242;
  font-weight:700;
  line-height:1.65;
  max-width:520px;
}

.tb-blog-all-btn{
  display:inline-flex;
  align-items:center;
  gap:10px;
  padding:12px 18px;
  border-radius:14px;
  background:#183f79;
  color:#fff;
  text-decoration:none;
  font-weight:950;
  border:1px solid rgba(0,0,0,0.10);
  box-shadow:0 14px 34px rgba(0,0,0,0.10);
  transition:.2s ease;
  white-space:nowrap;
}
.tb-blog-all-btn i{ font-size:18px; }
.tb-blog-all-btn:hover{
  background:#293879;
  color:white;
  border-color:#f1cc24;
  transform:translateY(-3px);
  box-shadow:0 22px 60px rgba(0,0,0,0.14);
}

/* Grid */
.tb-blog-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:18px;
}

/* Card */
.tb-blog-card{
  background:#fff;
  border-radius:22px;
  overflow:hidden;
  border:1px solid rgba(0,0,0,0.08);
  box-shadow:0 18px 56px rgba(0,0,0,0.08);
  transition:.22s ease;
  display:flex;
  flex-direction:column;
}
.tb-blog-card:hover{
  transform:translateY(-7px);
  border-color:rgba(241,204,36,0.65);
  box-shadow:0 26px 76px rgba(0,0,0,0.12);
}


.tb-blog-img{
  height:250px;
  background-size:cover;
  background-position:center;
}

/* Replace with your real blog images */
.tb-blog-img.b1{ background-image:url("https://keywordhike.com/Dairy/images/blog-1.png"); }
.tb-blog-img.b2{ background-image:url("https://keywordhike.com/Dairy/images/blog-2.png"); }
.tb-blog-img.b3{ background-image:url("https://keywordhike.com/Dairy/images/blog-3.png"); }

/* Body */
.tb-blog-body{
  padding:18px;
  display:flex;
  flex-direction:column;
  height:auto;
}

.tb-blog-tag{
  display:inline-block;
  margin-bottom:8px;
  padding:7px 10px;
  border-radius:999px;
  background:rgba(47,74,30,0.08);
  color:#263d18;
  font-weight:950;
  font-size:12px;
  letter-spacing:1px;
  text-transform:uppercase;
}

.tb-blog-body h3{
  margin:0 0 8px;
  font-weight:950;
  color:#263d18;
  font-size:18px;
  line-height:1.35;
}

.tb-blog-body p{
  margin:0 0 auto;
  color:#5c6b55;
  font-weight:700;
  line-height:1.65;
  font-size:14px;
}

.tb-blog-read{
  margin-top:14px;
  display:inline-flex;
  align-items:center;
  gap:8px;
  text-decoration:none;
  font-weight:950;
  color:#263d18;
  transition:.2s ease;
}
.tb-blog-read i{ font-size:16px; }
.tb-blog-read:hover{
  color:#f1cc24;
}

/* Responsive */
@media(max-width:980px){
  .tb-blog-grid{ grid-template-columns:repeat(2,1fr); }
  .tb-blog-head{ flex-direction:column; align-items:flex-start; gap:14px; }
}

@media(max-width:560px){
  .tb-blog-grid{ grid-template-columns:1fr; }
}

</style>


<?php include __DIR__ . '/includes/footer.php'; ?>
