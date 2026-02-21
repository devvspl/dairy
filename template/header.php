<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>All Pages</title>

  <!-- Font Awesome (ICONS CDN) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    :root{
      --green:#2f4a1e;
      --green-dark:#263d18;
      --border:#e7e7e7;
      --text:#1f2a1a;
      --muted:#6a7a63;
      --container:1250px;
      --iconSize:22px;
    }

    *{box-sizing:border-box;}
    body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:var(--text);background:#fff;}

    .container{
      max-width:var(--container);
      margin:0 auto;
      padding:0 16px;
    }

    /* TOP MOVING BAR */
    .topbar{
      background:var(--green);
      color:#fff;
      overflow:hidden;
      white-space:nowrap;
    }
    .marquee{
      display:flex;
      gap:60px;
      align-items:center;
      padding:10px 0;
      will-change:transform;
      animation: marquee 18s linear infinite;
    }
    .marquee span{
      font-size:12px;
      font-weight:600;
      opacity:.95;
    }
    .marquee b{font-weight:800;}
    @keyframes marquee{
      0%{ transform: translateX(0); }
      100%{ transform: translateX(-50%); }
    }
    .marquee-wrap{
      display:flex;
      width:200%;
    }

    /* MAIN HEADER */
    .mainbar{
      border-bottom:1px solid var(--border);
      background:#fff;
    }
    .mainbar-inner{
      display:grid;
      grid-template-columns: 360px 1fr 240px;
      gap:5px;
      align-items:center;
      padding: 5px 0;
    }

    /* Search */
    .search{
      position:relative;
      width:100%;
      max-width:360px;
    }
    .search input{
      width:100%;
      height:44px;
      border:1px solid #cfcfcf;
      border-radius:7px;
      padding:0 48px 0 14px;
      font-size:14px;
      outline:none;
    }
    .search input:focus{ border-color:var(--green); }
    .search button{
      position:absolute;
      right:8px;
      top:50%;
      transform:translateY(-50%);
      width:36px;
      height:36px;
      border:0;
      border-radius:8px;
      background:#fff;
      cursor:pointer;
      color:var(--green-dark);
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .search button i{ font-size:20px; color:var(--green-dark); }

    /* Logo */
    .logo{
      text-align:center;
      text-decoration:none;
      color:var(--green-dark);
      display:inline-block;
    }
    .logo-img{
      height:60px;
      width:auto;
      display:block;
      margin: 0 auto;
    }

    /* Icons */
    .icons{
      display:flex;
      justify-content:flex-end;
      align-items:center;
      gap:14px;
    }
    .icon-btn{
      width:44px;
      height:44px;
      border:0;
      background:transparent;
      border-radius:12px;
      cursor:pointer;
      position:relative;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .icon-btn:hover{ background:#f7f7f7; }
    .icon-btn i{
      font-size:var(--iconSize);
      color:var(--green);
    }
    .badge{
      position:absolute;
      top:4px;
      right:4px;
      width:18px;
      height:18px;
      border-radius:999px;
      background:#1f2a1a;
      color:#fff;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:11px;
      font-weight:700;
    }

    /* NAV */
    .navbar{
      border-bottom:1px solid var(--border);
      background:#fff;
    }
    .nav{
      margin:0;
      padding:8px 0;
      list-style:none;
      display:flex;
      justify-content:center;
      align-items:center;
      gap:28px;
      flex-wrap:wrap;
    }
    .nav a{
      text-decoration:none;
      color:var(--green-dark);
      font-weight:800;
      letter-spacing:.2px;
      font-size:14px;
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 2px;
    }
    .nav a i{
      font-size:18px;
      color:var(--green);
    }
    .nav a:hover{ opacity:.85; }

    /* ✅ Hamburger (same style as your old .hamburger) */
    .tb-hamburger{
      display:none;
      width:44px;
      height:44px;
      border:1px solid var(--border);
      border-radius:12px;
      background:#fff;
      cursor:pointer;
      align-items:center;
      justify-content:center;
    }
    .tb-hamburger:hover{ background:#f7f7f7; }
    .tb-hamburger i{ font-size:22px; color:var(--green); }

    @media (max-width: 980px){
      .mainbar-inner{
        grid-template-columns: 1fr 1fr;
        grid-template-areas:
          "logo icons"
          "search search";
      }
      .search{ grid-area:search; max-width:100%; }
      .logo{ grid-area:logo; text-align:left; }
      .logo-img{ margin:0; height:60px; }
      .icons{ grid-area:icons; }
      .tb-hamburger{ display:flex; }

      /* mobile menu panel */
      .navbar{
        display:none;
        border-top: 1px solid var(--border);
        border-bottom:1px solid var(--border);
      }
      .navbar.open{ display:block; }

      .nav{
        flex-direction:column;
        align-items:flex-start;
        gap:0;
        padding:10px 0 14px;
      }
      .nav li{ width:100%; }
      .nav a{
        width:100%;
        padding:12px 6px;
        border-radius:10px;
      }
      .nav a:hover{ background:#f7f7f7; opacity:1; }
    }
  </style>
</head>

<body>

  <!-- TOP MOVING BAR -->
  <div class="topbar">
    <div class="container">
      <div class="marquee-wrap">
        <div class="marquee">
          <span>🎉 Big Savings Alert! Get <b>10% OFF</b> on orders above <b>₹3000</b> | Use code - <b>TBOF10</b></span>
          <span>⭐ Salary Day: Get <b>12% OFF</b> for Your Loved Ones | Code: <b>SAL12</b> | <b>ONLY ON APP</b></span>
          <span>🎉 Big Savings Alert! Get <b>10% OFF</b> on orders above <b>₹3000</b> | Use code - <b>TBOF10</b></span>
          <span>⭐ Salary Day: Get <b>12% OFF</b> for Your Loved Ones | Code: <b>SAL12</b> | <b>ONLY ON APP</b></span>
        </div>
        <div class="marquee" aria-hidden="true">
          <span>🎉 Big Savings Alert! Get <b>10% OFF</b> on orders above <b>₹3000</b> | Use code - <b>TBOF10</b></span>
          <span>⭐ Salary Day: Get <b>12% OFF</b> for Your Loved Ones | Code: <b>SAL12</b> | <b>ONLY ON APP</b></span>
          <span>🎉 Big Savings Alert! Get <b>10% OFF</b> on orders above <b>₹3000</b> | Use code - <b>TBOF10</b></span>
          <span>⭐ Salary Day: Get <b>12% OFF</b> for Your Loved Ones | Code: <b>SAL12</b> | <b>ONLY ON APP</b></span>
        </div>
      </div>
    </div>
  </div>

  <!-- MAIN HEADER -->
  <header class="mainbar">
    <div class="container">
      <div class="mainbar-inner">

        <!-- Search -->
        <div class="search">
          <input type="text" placeholder="Search..." />
          <button type="button" aria-label="Search">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </div>

        <!-- Logo -->
        <a href="#" class="logo" aria-label="Two Brothers Organic Farms">
          <img src="images/new.png" alt="Two Brothers Organic Farms Logo" class="logo-img">
        </a>

        <!-- Icons + Hamburger -->
        <div class="icons">
          <button class="icon-btn" type="button" aria-label="Wishlist">
            <span class="badge">0</span>
            <i class="fa-regular fa-heart"></i>
          </button>

          <button class="icon-btn" type="button" aria-label="Cart">
            <span class="badge">0</span>
            <i class="fa-solid fa-bag-shopping"></i>
          </button>

          <!-- ✅ Hamburger -->
          <button class="tb-hamburger" type="button" id="tbMenuBtn" aria-label="Menu" aria-controls="navbar" aria-expanded="false">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
          </button>
        </div>

      </div>
    </div>
  </header>

  <!-- ✅ NAVBAR (same ID used in JS) -->
  <nav class="navbar" id="navbar" aria-label="Primary">
    <div class="container">
      <ul class="nav">
        <li><a href="#"><i class="fa-solid fa-house"></i> Home</a></li>
        <li><a href="#"><i class="fa-solid fa-user"></i> About</a></li>
        <li><a href="#"><i class="fa-solid fa-id-card-clip"></i> Membership</a></li>
        <li><a href="#"><i class="fa-solid fa-box-open"></i> Products</a></li>
        <li><a href="#"><i class="fa-solid fa-pen-to-square"></i> Blogs</a></li>
        <li><a href="#"><i class="fa-solid fa-envelope"></i> Contact Us</a></li>
      </ul>
    </div>
  </nav>

  <script>
    (function () {
      const btn = document.getElementById("tbMenuBtn");
      const panel = document.getElementById("navbar");
      if (!btn || !panel) return;

      const icon = btn.querySelector("i");

      function setExpanded(isOpen) {
        btn.setAttribute("aria-expanded", isOpen ? "true" : "false");
        if (icon) icon.className = isOpen ? "fa-solid fa-xmark" : "fa-solid fa-bars";
      }

      function openMenu() {
        panel.classList.add("open");
        setExpanded(true);
      }

      function closeMenu() {
        panel.classList.remove("open");
        setExpanded(false);
      }

      function toggleMenu(e) {
        e.preventDefault();
        e.stopPropagation();
        panel.classList.contains("open") ? closeMenu() : openMenu();
      }

      // click toggle
      btn.addEventListener("click", toggleMenu, { passive: false });

      // close on outside click
      document.addEventListener("click", function (e) {
        if (!panel.classList.contains("open")) return;
        if (!panel.contains(e.target) && !btn.contains(e.target)) closeMenu();
      });

      // close on ESC
      document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeMenu();
      });

      // close if desktop view
      window.addEventListener("resize", function () {
        if (window.innerWidth > 980) closeMenu();
      });

      // close when clicking a link (mobile)
      panel.addEventListener("click", function (e) {
        const a = e.target.closest("a");
        if (a && window.innerWidth <= 980) closeMenu();
      });

      // default state
      setExpanded(false);
    })();
  </script>

</body>
</html>
