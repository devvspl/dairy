<?php include __DIR__ . '/includes/header.php'; ?>

<!-- contact section -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<style>

#cpgContactPage{
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

#cpgContactPage *{ box-sizing:border-box; }
#cpgContactPage img{ max-width:100%; display:block; }

#cpgContactPage .container{
  max-width:1250px;
  margin:0 auto;
  padding:0 24px;
}

#cpgContactPage .cpg-kicker{
  display:inline-flex;
  align-items:center;
  gap:10px;
  padding:8px 12px;
  border-radius:999px;
  font-weight:900;
  letter-spacing:.9px;
  text-transform:uppercase;
  font-size:12px;
  color:#fff;
  background:rgba(241,204,36,.14);
  border:1px solid rgba(241,204,36,.28);
}

#cpgContactPage .cpg-sec-kicker{
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

#cpgContactPage .cpg-sec-head{
  text-align:center;
  max-width:840px;
  margin:0 auto 28px;
}
#cpgContactPage .cpg-sec-head-left{ text-align:left; margin-left:0; }
#cpgContactPage .cpg-sec-head h2{
  margin:12px 0 8px;
  font-size:clamp(26px,3.2vw,42px);
  font-weight:950;
  letter-spacing:-.4px;
  color:var(--brand);
  line-height:1.08;
}
#cpgContactPage .cpg-sec-head p{
  margin:0;
  color:var(--muted);
  font-weight:650;
  line-height:1.75;
}

#cpgContactPage .cpg-btn{
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
#cpgContactPage .cpg-btn i{ font-size:16px; }
#cpgContactPage .cpg-btn-primary{
  background:#d85f0f;
  color:#fff;
  border-color:rgba(38,61,24,.25);
}
#cpgContactPage .cpg-btn-primary:hover{
  transform:translateY(-2px);
  background:var(--accent);
  color:var(--text);
  border-color:rgba(241,204,36,.9);
  box-shadow:0 18px 44px rgba(0,0,0,.12);
}
#cpgContactPage .cpg-btn-outline{
  background:#fff;
  color:var(--brand);
}
#cpgContactPage .cpg-btn-outline:hover{
  transform:translateY(-2px);
  border-color:rgba(241,204,36,.85);
  box-shadow:0 18px 44px rgba(0,0,0,.12);
}

#cpgContactPage .cpg-card{
  background:#fff;
  border:1px solid rgba(0,0,0,.08);
  box-shadow:var(--shadow);
  border-radius:var(--radius);
}

/* HERO */
#cpgContactPage .cpg-hero{
  position:relative;
  padding:78px 0 66px;
  overflow:hidden;
  background:#0f130e;
}
#cpgContactPage .cpg-hero-bg{
  position:absolute; inset:0;
  background-image:url("images/contact-us.webp");
  background-size:cover;
  background-position:center;
  transform:scale(1.04);
}
#cpgContactPage .cpg-hero-overlay{
  position:absolute; inset:0;
  background:
    radial-gradient(circle at 18% 20%, rgba(241,204,36,.22), transparent 55%),
    radial-gradient(circle at 82% 70%, rgba(38,61,24,.35), transparent 60%),
    linear-gradient(180deg, rgba(0,0,0,.62), rgba(0,0,0,.70));
}
#cpgContactPage .cpg-hero-inner{
  position:relative; z-index:2;
  display:flex; justify-content:center;
}
#cpgContactPage .cpg-hero-content{
  text-align:center;
  max-width:920px;
  color:#fff;
}
#cpgContactPage .cpg-hero-content h1{
  margin:14px 0 12px;
  font-size:clamp(28px,4vw,35px);
  font-weight:950;
  line-height:1.05;
  letter-spacing:-.8px;
}
#cpgContactPage .cpg-hero-content p{
  margin:0 auto 18px;
  max-width:760px;
  color:rgba(255,255,255,.86);
  font-weight:650;
  line-height:1.8;
}
#cpgContactPage .cpg-hero-actions{
  display:flex;
  gap:12px;
  justify-content:center;
  flex-wrap:wrap;
  margin-top:6px;
}

/* CONTACT CARDS */
#cpgContactPage .cpg-info{
  padding:70px 0;
  background:
    radial-gradient(circle at 18% 20%, rgba(241,204,36,0.12), transparent 55%),
    radial-gradient(circle at 82% 70%, rgba(38,61,24,0.10), transparent 58%),
    linear-gradient(180deg, #ffffff, #f6f8f2);
}
#cpgContactPage .cpg-info-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:18px;
}
#cpgContactPage .cpg-info-card{
  padding:22px;
  border-radius:22px;
  background:#fff;
  border:1px solid rgba(0,0,0,.08);
  box-shadow:var(--shadow);
  transition:220ms ease;
}
#cpgContactPage .cpg-info-card:hover{
  transform:translateY(-7px);
  border-color:rgba(241,204,36,.65);
  box-shadow:var(--shadow2);
}
#cpgContactPage .cpg-info-ico{
  width:52px; height:52px;
  border-radius:18px;
  display:flex; align-items:center; justify-content:center;
  background:rgba(241,204,36,.18);
  color:#7a5b00;
  margin-bottom:12px;
}
#cpgContactPage .cpg-info-ico i{ font-size:22px; }
#cpgContactPage .cpg-info-card h3{
  margin:0 0 6px;
  font-weight:950;
  color:var(--brand);
}
#cpgContactPage .cpg-info-card p{
  margin:0 0 10px;
  color:var(--muted);
  font-weight:650;
  line-height:1.7;
}
#cpgContactPage .cpg-link{
  display:inline-flex;
  align-items:center;
  gap:8px;
  color:var(--brand);
  font-weight:850;
  text-decoration:none;
}
#cpgContactPage .cpg-link:hover{ color:#7a5b00; }

/* FORM + MAP */
#cpgContactPage .cpg-main{
  padding:72px 0;
  background:#fff;
}
#cpgContactPage .cpg-main-grid{
  display:grid;
  grid-template-columns:1.05fr .95fr;
  gap:18px;
  align-items:start;
}
#cpgContactPage .cpg-form{
  padding:22px;
}
#cpgContactPage .cpg-form h3{
  margin:8px 0 6px;
  color:var(--brand);
  font-weight:950;
}
#cpgContactPage .cpg-form p{
  margin:0 0 14px;
  color:var(--muted);
  font-weight:650;
  line-height:1.7;
}

#cpgContactPage .cpg-form-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:12px;
}
#cpgContactPage .cpg-field{ position:relative; }
#cpgContactPage .cpg-field i{
  position:absolute;
  left:12px; top:50%;
  transform:translateY(-50%);
  color:var(--brand);
  opacity:.9;
}
#cpgContactPage .cpg-field input,
#cpgContactPage .cpg-field textarea{
  width:100%;
  border-radius:14px;
  border:1px solid rgba(0,0,0,.12);
  outline:none;
  font-weight:650;
  font-family:inherit;
  background:#fff;
}
#cpgContactPage .cpg-field input{
  height:48px;
  padding:0 12px 0 40px;
}
#cpgContactPage .cpg-field textarea{
  min-height:110px;
  padding:12px 12px 12px 40px;
  resize:vertical;
}
#cpgContactPage .cpg-field input:focus,
#cpgContactPage .cpg-field textarea:focus{
  border-color:var(--accent);
  box-shadow:0 0 0 4px rgba(241,204,36,.16);
}
#cpgContactPage .cpg-full{ grid-column:1 / -1; }
#cpgContactPage .cpg-submit{
  grid-column:1 / -1;
  height:50px;
  border-radius:14px;
  width:100%;
}

#cpgContactPage .cpg-map{
  padding:22px;
  overflow:hidden;
}
#cpgContactPage .cpg-map-top{
  display:flex;
  justify-content:space-between;
  gap:12px;
  align-items:flex-start;
  margin-bottom:12px;
}
#cpgContactPage .cpg-map-top h3{
  margin:a:0;
  margin:0;
  color:var(--brand);
  font-weight:950;
}
#cpgContactPage .cpg-map-top p{
  margin:6px 0 0;
  color:var(--muted);
  font-weight:650;
  line-height:1.7;
}
#cpgContactPage .cpg-map-frame{
  border-radius:18px;
  border:1px solid rgba(0,0,0,.10);
  overflow:hidden;
  box-shadow:0 16px 46px rgba(0,0,0,.08);
}
#cpgContactPage .cpg-map-frame iframe{
  width:100%;
  height:360px;
  border:0;
  display:block;
}

/* FAQ (Optional) */
#cpgContactPage .cpg-faq{
  padding:72px 0;
  background:
    radial-gradient(circle at 18% 20%, rgba(241,204,36,0.10), transparent 55%),
    linear-gradient(180deg, #ffffff, #f6f8f2);
}
#cpgContactPage .cpg-faq-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:18px;
  align-items:start;
}
#cpgContactPage .cpg-faq-col{ display:grid; gap:12px; }
#cpgContactPage .cpg-faq-item{
  border-radius:18px;
  border:1px solid rgba(0,0,0,.08);
  background:#fff;
  overflow:hidden;
  box-shadow:0 16px 46px rgba(0,0,0,.06);
}
#cpgContactPage .cpg-faq-q{
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
#cpgContactPage .cpg-faq-q:hover{ background:rgba(241,204,36,0.10); }
#cpgContactPage .cpg-faq-ico{
  width:36px; height:36px;
  border-radius:12px;
  display:inline-flex;
  align-items:center; justify-content:center;
  background:rgba(38,61,24,.07);
  color:var(--brand);
  transition:200ms ease;
  flex:0 0 36px;
}
#cpgContactPage .cpg-faq-a{
  display:none;
  padding:0 16px 16px;
  color:var(--muted);
  font-weight:650;
  line-height:1.75;
  border-top:1px solid rgba(0,0,0,.06);
}
#cpgContactPage .cpg-faq-item.cpg-open{
  border-color:rgba(241,204,36,.65);
  box-shadow:0 22px 62px rgba(0,0,0,.10);
}
#cpgContactPage .cpg-faq-item.cpg-open .cpg-faq-a{ display:block; }
#cpgContactPage .cpg-faq-item.cpg-open .cpg-faq-ico{
  transform:rotate(180deg);
  background:rgba(241,204,36,.18);
  color:#7a5b00;
}

/* Responsive */
@media (max-width:980px){
  #cpgContactPage .cpg-info-grid{ grid-template-columns:repeat(2,1fr); }
  #cpgContactPage .cpg-main-grid{ grid-template-columns:1fr; }
  #cpgContactPage .cpg-faq-grid{ grid-template-columns:1fr; }
}
@media (max-width:560px){
  #cpgContactPage .container{ padding:0 16px; }
  #cpgContactPage .cpg-info-grid{ grid-template-columns:1fr; }
  #cpgContactPage .cpg-form-grid{ grid-template-columns:1fr; }
  #cpgContactPage .cpg-hero{ padding:64px 0 56px; }
  #cpgContactPage .cpg-hero-content p{ font-size:14px; }
}
</style>

<main id="cpgContactPage">

  <!-- HERO -->
  <section class="cpg-hero">
    <div class="cpg-hero-bg"></div>
    <div class="cpg-hero-overlay"></div>

    <div class="container cpg-hero-inner">
      <div class="cpg-hero-content">
        <span class="cpg-kicker"><i class="fa-solid fa-phone"></i> Contact Us</span>
        <h1>Let’s talk—quick support, clear answers.</h1>
        <p>Have a question about products, Share your details and we’ll connect shortly.</p>

        <div class="cpg-hero-actions">
          <a class="cpg-btn cpg-btn-primary" href="tel:+911234567890">
            <i class="fa-solid fa-phone"></i> Call Now
          </a>
          <a class="cpg-btn cpg-btn-outline" href="mailto:hello@example.com">
            <i class="fa-solid fa-envelope"></i> Email Us
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- INFO CARDS -->
  <section class="cpg-info">
    <div class="container">
      <div class="cpg-sec-head">
        <span class="cpg-sec-kicker"><i class="fa-solid fa-circle-check"></i> Support</span>
        <h2>Contact options</h2>
        <p>Choose what’s easiest—call, email, or visit us.</p>
      </div>

      <div class="cpg-info-grid">
        <div class="cpg-info-card">
          <div class="cpg-info-ico"><i class="fa-solid fa-phone"></i></div>
          <h3>Call Us</h3>
          <p>Talk to our support team for quick help and guidance.</p>
          <a class="cpg-link" href="tel:+911234567890"><i class="fa-solid fa-arrow-right"></i> +91 12345 67890</a>
        </div>

        <div class="cpg-info-card">
          <div class="cpg-info-ico"><i class="fa-solid fa-envelope"></i></div>
          <h3>Email</h3>
          <p>Send your query and we’ll reply with clear details.</p>
          <a class="cpg-link" href="mailto:hello@example.com"><i class="fa-solid fa-arrow-right"></i> hello@example.com</a>
        </div>

        <div class="cpg-info-card">
          <div class="cpg-info-ico"><i class="fa-solid fa-location-dot"></i></div>
          <h3>Visit</h3>
          <p>Office / Store address line will come here for users.</p>
          <a class="cpg-link" href="#cpgMap"><i class="fa-solid fa-arrow-right"></i> Get Directions</a>
        </div>
      </div>
    </div>
  </section>

  <!-- FORM + MAP -->
  <section class="cpg-main">
    <div class="container">
      <div class="cpg-main-grid">
        <!-- FORM -->
        <div class="cpg-card cpg-form">
          <span class="cpg-sec-kicker"><i class="fa-solid fa-paper-plane"></i> Send message</span>
          <h3>We’ll get back shortly</h3>
          <p>Fill the form and our team will connect with you.</p>

          <form class="cpg-form-grid" action="#" method="post">
            <div class="cpg-field">
              <i class="fa-solid fa-user"></i>
              <input type="text" name="name" placeholder="Your Name" required>
            </div>

            <div class="cpg-field">
              <i class="fa-solid fa-envelope"></i>
              <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="cpg-field">
              <i class="fa-solid fa-phone"></i>
              <input type="tel" name="phone" placeholder="Phone Number" required pattern="[0-9]{10}" maxlength="10">
            </div>

            <div class="cpg-field">
              <i class="fa-solid fa-tag"></i>
              <input type="text" name="subject" placeholder="Subject (Optional)">
            </div>

            <div class="cpg-field cpg-full">
              <i class="fa-solid fa-message"></i>
              <textarea name="message" placeholder="Your Message" required></textarea>
            </div>

            <button class="cpg-btn cpg-btn-primary cpg-submit" type="submit">
              <i class="fa-solid fa-paper-plane"></i> Submit
            </button>
          </form>
        </div>

        <!-- MAP -->
        <div class="cpg-card cpg-map" id="cpgMap">
          <div class="cpg-map-top">
            <div>
              <span class="cpg-sec-kicker"><i class="fa-solid fa-map-location-dot"></i> Location</span>
              <h3 class="pt-2">Find us on map</h3>
              
            </div>
            <a class="cpg-btn cpg-btn-outline" href="#" target="_blank" rel="noopener">
              <i class="fa-solid fa-location-arrow"></i> Open in Maps
            </a>
          </div>

          <div class="cpg-map-frame">
            
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224345.83945573586!2d77.0688980770208!3d28.527582004050263!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce2f8a6c5f0ff%3A0x2b3b0a5e3d2b8a1a!2sDelhi!5e0!3m2!1sen!2sin!4v1700000000000"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- OPTIONAL FAQ -->
  <section class="cpg-faq" id="cpgFaq">
    <div class="container">
      <div class="cpg-sec-head">
        <span class="cpg-sec-kicker"><i class="fa-solid fa-circle-question"></i> FAQ</span>
        <h2>Quick answers</h2>
        <p>Most common questions users ask before contacting.</p>
      </div>

      <div class="cpg-faq-grid">
        <div class="cpg-faq-col">
          <div class="cpg-faq-item">
            <button class="cpg-faq-q" type="button">
              How soon do you respond?
              <span class="cpg-faq-ico"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="cpg-faq-a">We usually respond within working hours. For urgent queries, calling is faster.</div>
          </div>

          <div class="cpg-faq-item">
            <button class="cpg-faq-q" type="button">
              Can I get product guidance?
              <span class="cpg-faq-ico"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="cpg-faq-a">Yes—share your need and we’ll suggest the right options.</div>
          </div>
        </div>

        <div class="cpg-faq-col">
          <div class="cpg-faq-item">
            <button class="cpg-faq-q" type="button">
              Do you provide order support?
              <span class="cpg-faq-ico"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="cpg-faq-a">Yes—delivery, tracking, and post-purchase support is available.</div>
          </div>

          <div class="cpg-faq-item">
            <button class="cpg-faq-q" type="button">
              What details should I share in message?
              <span class="cpg-faq-ico"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="cpg-faq-a">Mention your product/issue, preferred contact method, and best time to call.</div>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<script>
 
  (function(){
    const root = document.getElementById("cpgContactPage");
    if(!root) return;

    root.querySelectorAll(".cpg-faq-item .cpg-faq-q").forEach((btn) => {
      btn.addEventListener("click", () => {
        const item = btn.closest(".cpg-faq-item");
        const wrap = item.parentElement.parentElement; // grid

        wrap.querySelectorAll(".cpg-faq-item").forEach(i => {
          if(i !== item) i.classList.remove("cpg-open");
        });

        item.classList.toggle("cpg-open");
      });
    });
  })();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

