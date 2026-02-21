<!--- footer -->

<footer class="tb-footer">

  <div class="container tb-footer-grid">

    <!-- Brand -->
    <div class="tb-foot-brand">
      <h3>New Website</h3>
      <p>Pure, clean, and ethically sourced food products crafted for modern Indian families.</p>

      <div class="tb-foot-social">
        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
      </div>
    </div>

    <!-- Links -->
    <div class="tb-foot-links">
      <h4>Quick Links</h4>
      <a href="#">Home</a>
      <a href="#">About Us</a>
      <a href="#">Contact Us</a>
      <a href="#">Membership</a>
      <a href="#">Blogs</a>
    </div>

    <div class="tb-foot-links">
      <h4>Products</h4>
      <a href="#">Milks</a>
      <a href="#">Curd</a>
      <a href="#">Paneer</a>
    </div>

    <div class="tb-foot-links">
      <h4>Support</h4>
      <a href="#">Terms & Conditions</a>
      <a href="#">FAQs</a>
      <a href="#">Privacy Policy</a>
    </div>

    <!-- Newsletter -->
    <div class="tb-foot-news">
      <h4>Join Our Community</h4>
      <p>Get exclusive offers, product launches and healthy living tips.</p>

      <form class="tb-foot-form">
        <input type="email" placeholder="Your email address">
        <button type="submit">Subscribe</button>
      </form>
    </div>

  </div>

  <!-- Bottom bar -->
  <div class="tb-foot-bottom">
    <div class="container">
      <p>© 2026 New Website. All rights reserved.</p>
    </div>
  </div>

</footer>

<style>
/* =============================
   FOOTER BASE
============================= */
.tb-footer{
  position:relative;
  color:#fff;
  padding-top:80px;
  overflow:hidden;

  /* Background image */
  background:url('https://keywordhike.com/Dairy/images/about-banner.webp') center/cover no-repeat;
}

/* Overlay for opacity */
.tb-footer::before{
  content:"";
  position:absolute;
  inset:0;
  background:rgba(0,0,0,0.65);
  z-index:0;
}

/* Keep content above overlay */
.tb-footer > *{
  position:relative;
  z-index:1;
}

/* =============================
   GRID
============================= */
.tb-footer-grid{
  display:grid;
  grid-template-columns: 1.5fr 1fr 1fr 1fr 1.5fr;
  gap:40px;
  padding-bottom:60px;
}

/* =============================
   BRAND
============================= */
.tb-foot-brand h3{
  font-size:26px;
  font-weight:900;
  color:#fff;
}
.tb-foot-brand p{
  margin-top:12px;
  color:rgba(255,255,255,.85);
  line-height:1.6;
}

/* =============================
   SOCIAL
============================= */
.tb-foot-social{
  display:flex;
  gap:12px;
  margin-top:20px;
}
.tb-foot-social a{
  width:38px;
  height:38px;
  border-radius:50%;
  background:#ffca41;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:900;
  color:#fff;
  text-decoration:none;
  transition:.25s;
}
.tb-foot-social a:hover{
  background:#f1cc24;
  color:#1f2a1a;
  transform:translateY(-3px);
}

/* =============================
   LINKS
============================= */
.tb-foot-links h4{
  font-size:16px;
  font-weight:900;
  margin-bottom:16px;
}
.tb-foot-links a{
  display:block;
  color:rgba(255,255,255,.78);
  text-decoration:none;
  margin-bottom:10px;
  font-weight:600;
  transition:.2s;
}
.tb-foot-links a:hover{
  color:#de4631;
  padding-left:4px;
}

/* =============================
   NEWSLETTER
============================= */
.tb-foot-news h4{
  font-size:18px;
  font-weight:900;
}
.tb-foot-news p{
  color:rgba(255,255,255,.78);
  margin:10px 0 16px;
  line-height:1.5;
}

.tb-foot-form{
  display:flex;
  background:#fff;
  border-radius:40px;
  overflow:hidden;
}
.tb-foot-form input{
  flex:1;
  border:none;
  padding:14px 18px;
  outline:none;
}
.tb-foot-form button{
  background:#ffca41;
  border:none;
  padding:0 24px;
  font-weight:900;
  cursor:pointer;
  color:white;
  transition:.25s;
}
.tb-foot-form button:hover{
  background:#c34f07;
}


.tb-foot-bottom{
  background:black;
  padding:10px 0;
  text-align:center;
  border-top: 1px solid #434343;
}
.tb-foot-bottom p{
  margin:0;
  font-size:14px;
  color:rgba(255,255,255,.75);
}

/* =============================
   RESPONSIVE
============================= */
@media(max-width:900px){
  .tb-footer-grid{
    grid-template-columns:1fr 1fr;
  }
}
@media(max-width:600px){
  .tb-footer-grid{
    /*grid-template-columns:1fr;*/
    gap:30px;
  }
  .tb-footer {
    position: relative;
    color: #fff;
    padding-top: 30px;
    overflow: hidden;
    background: url(https://keywordhike.com/Dairy/images/about-banner.webp) center / cover no-repeat;
}

  .tb-foot-form{
    flex-direction:column;
    border-radius:12px;
  }

  .tb-foot-form button{
    padding:12px;
  }
}
</style>

<script>
/* Mobile menu toggle (your old snippet) */
(function(){
  var btn = document.getElementById('menuBtn');
  var nav = document.getElementById('navbar');
  if(!btn || !nav) return;

  btn.addEventListener('click', function(){
    nav.classList.toggle('open');
  });
})();
</script>


<!--- whatsapp -->



<!-- ended -->

</body>
</html>
