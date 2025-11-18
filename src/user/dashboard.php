<?php
require_once __DIR__ . '/../../includes/auth/auth_user.php';

$user = Auth::user();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FitSphere — Full Page with Hero + Collections</title>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Stack+Sans+Text:wght@200..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* ----------------- Reset & fonts ----------------- */
    *{box-sizing:border-box;margin:0;padding:0;font-family:"Stack Sans Text",sans-serif}
    p{font-family:"Roboto",sans-serif}

    /* ==================================================================================
       🚀 NEW: CIRCULAR MAP STYLES
       ================================================================================== */
    .circle-map-container{
      width:100%;
      display:flex;
      justify-content:center;
      padding:40px 0;
      background:#fff;
    }
    .circle-map{
      width:340px;
      height:340px;
      border-radius:50%;
      overflow:hidden;
      box-shadow:0 8px 30px rgba(0,0,0,0.15);
    }
    .circle-map iframe{
      width:100%;
      height:100%;
      border:0;
    }

    /* ----------------- Header ----------------- */
header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  padding: 10px 60px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: rgba(0,0,0,0.6);
  backdrop-filter: blur(8px);
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  z-index: 1000;
  transition: all .4s ease;

  /* Fix white gaps while keeping same colors */
  background-clip: padding-box;
  border: 1px solid transparent;
}

header.scrolled {
  background: rgba(15, 15, 15, 0.15);
  backdrop-filter: blur(15px);
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);

  /* Fix white gaps while keeping same style */
  background-clip: padding-box;
  border: 1px solid transparent;
}

/* Invisible background layer to stop white bleed-through */
header::before {
  content: "";
  position: absolute;
  inset: 0;
  background: inherit;
  z-index: -1;
}

.logo {
  font-size: 25px;
  color: #fff;
  text-decoration: none;
  font-weight: 700;
  margin-right: auto;
  position: relative;
  transition: color .3s;
}

.logo:hover { color: #D4AF37; }

.logo_main { margin-right: 12px; }

.logo-image1 {
  height: 50px;
  display: inline-block;
  vertical-align: middle;
  transition: transform .4s ease, filter .4s ease;
}

.logo-image1:hover {
  transform: rotate(-5deg) scale(1.05);
  filter: brightness(1.2) drop-shadow(0 0 8px #D4AF37);
}

nav a {
  color: #fff;
  font-family: 'Franklin Gothic Medium','Arial Narrow',Arial,sans-serif;
  text-decoration: none;
  font-weight: 500;
  margin-left: 40px;
  transition: color .3s;
}

nav a:hover { color: #D4AF37; }

.user-auth { margin-left: 40px; }

/* Login Button */
.Login-btn-model {
  height: 40px;
  padding: 0 20px;
  background: transparent;
  border: 2px solid #fff;
  border-radius: 40px;
  color: #fff;
  font-weight: 500;
  cursor: pointer;
  transition: .3s;
}
.Login-btn-model:hover {
  background:#fff;
  color:#222;
}

/* Profile Box */
.profile-box { display: flex; align-items: center; gap: 10px; }
.avatar-circle {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: #D4AF37; color: #000;
  display: flex; align-items: center; justify-content: center;
  font-weight: 700;
}
.dropdown {
  display: flex; flex-direction: column;
  background: rgba(255,255,255,0.05);
  padding: 6px;
  border-radius: 6px;
}
.dropdown a {
  color:#fff; text-decoration:none;
  padding:6px 10px; font-size:14px;
}
.dropdown a:hover {
  background:rgba(255,255,255,0.06);
  color:#D4AF37;
}

/* Mobile nav */
#menu-toggle { display:none; }
.menu-icon {
  display:none;
  font-size:25px;
  color:#fff;
  cursor:pointer;
}

@media (max-width:900px){
  header { padding:15px 30px; }

  nav {
    position:fixed;
    top:70px;
    right:-100%;
    background:rgba(0,0,0,0.95);
    width:250px;
    height:100vh;
    display:flex;
    flex-direction:column;
    padding-top:40px;
    gap:20px;
    align-items:center;
    transition:.4s;
  }

  nav a { margin-left:0; font-size:18px; }
  #menu-toggle:checked ~ nav { right:0; }
  .menu-icon { display:block; margin-left:auto; }
  .user-auth { display:none; }
}


    /* user auth */
    .user-auth{margin-left:40px}
    .Login-btn-model{height:40px;padding:0 20px;background:transparent;border:2px solid #fff;border-radius:40px;color:#fff;font-weight:500;cursor:pointer;transition:.3s}
    .Login-btn-model:hover{background:#fff;color:#222}

    /* simple profile box */
    .profile-box{display:flex;align-items:center;gap:10px}
    .avatar-circle{width:40px;height:40px;border-radius:50%;background:#D4AF37;color:#000;display:flex;align-items:center;justify-content:center;font-weight:700}
    .dropdown{display:flex;flex-direction:column;background:rgba(255,255,255,0.05);padding:6px;border-radius:6px}
    .dropdown a{color:#fff;text-decoration:none;padding:6px 10px;font-size:14px}
    .dropdown a:hover{background:rgba(255,255,255,0.06);color:#D4AF37}

    /* mobile nav */
    #menu-toggle{display:none}
    .menu-icon{display:none;font-size:25px;color:#fff;cursor:pointer}

    @media (max-width:900px){
      header{padding:15px 30px}
      nav{position:fixed;top:70px;right:-100%;background:rgba(0,0,0,0.95);width:250px;height:100vh;display:flex;flex-direction:column;padding-top:40px;gap:20px;align-items:center;transition:.4s}
      nav a{margin-left:0;font-size:18px}
      #menu-toggle:checked ~ nav{right:0}
      .menu-icon{display:block;margin-left:auto}
      .user-auth{display:none}
    }

    main{margin-top:0}

    /* ----------------- HERO ----------------- */
    :root { --hero-img: url('assets/images/suits.webp'); }

    .hero {
      width:100%;min-height:90vh;display:flex;align-items:center;justify-content:center;
      position:relative;background-image:var(--hero-img);background-size:cover;background-position:center;
      background-repeat:no-repeat;color:#fff;overflow:hidden;
    }
    .hero::before{
      content:'';position:absolute;inset:0;
      background:linear-gradient(180deg,rgba(0,0,0,0.45),rgba(0,0,0,0.45));z-index:1;
    }
    .hero-inner{position:relative;z-index:2;text-align:center;padding:20px;max-width:1200px;width:100%;}
    .hero-title{font-family:"Josefin Sans";font-size:72px;letter-spacing:2px;margin-bottom:8px;text-shadow:0 6px 18px rgba(0,0,0,0.6);}

    /* ----------------- Brands + Bookings ----------------- */
    :root{
      --gold: #D4AF37;
      --gold-dark: #b66c0f;
      --muted: #f6f6f6;
      --text: #222;
    }

    .brands-bookings{width:100%;background:#fff;padding:30px 20px;box-sizing:border-box}
    .brand-logos{display:flex;align-items:center;justify-content:center;gap:36px;padding:18px 10px;border-bottom:1px solid #eee;overflow-x:auto}
    .brand-logos img{height:36px;object-fit:contain;filter:grayscale(100%);opacity:.9;transition:filter .25s ease,transform .25s ease,opacity .25s ease}
    .brand-logos img:hover{filter:none;opacity:1;transform:translateY(-2px) scale(1.03)}

    .booking-wrap{max-width:1100px;margin:28px auto 0;padding:0 12px}
    .booking-table{width:100%;border-collapse:collapse;text-align:left;font-family:"Josefin Sans"}
    .booking-table thead th{background:var(--gold);color:#000;padding:12px 16px;font-weight:700;font-size:14px}
    .booking-table tbody td{padding:18px 16px;color:var(--text);font-size:15px;border-bottom:1px solid #efefef}
    .view-link{color:var(--text);text-decoration:none;font-weight:600}
    .view-link:hover{color:var(--gold-dark)}
    .status.active{color:#0a6d24}
    .status.upcoming{color:#b07a00}
    .see-more-wrap{text-align:center;margin-top:18px}
    .see-more{text-decoration:none;color:var(--text);font-size:18px;padding:10px 6px;border-radius:6px}
    .see-more:hover{color:var(--gold);transform:translateX(4px)}

    /* ----------------- MAIN04 SUIT CATEGORIES ----------------- */
    .main04{padding:40px 20px 60px;background:#fff;max-width:1200px;margin:0 auto 40px}
    .main04 h2{text-align:center;font-size:28px;margin-bottom:18px; font-family:"Josefin Sans";font-weight:700}
    .cat001{display:grid;grid-template-columns:repeat(3,1fr);gap:28px}
    .cat01{background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.06);text-align:center;padding-bottom:8px}
    .cat01 img {
  width: 100%;       
  height: auto;      
  max-height: 500px; 
  object-fit: cover; 
  border-radius: 8px;
  margin: 14px auto 8px;
  transition: transform .28s ease;
}

    .cat01 img:hover{transform:translateY(-6px) scale(1.02)}
    .cat01 .cat{background:#d9b03a;color:#000;padding:10px 8px;border-radius:6px;font-weight:700;display:inline-block;width:calc(100% - 32px);margin-top:6px;text-decoration:none; margin-bottom:12px ;}
    .collectionNav{display:block;width:260px;margin:28px auto 0;background:#111;color:#a58226;padding:12px 18px;border-radius:6px;text-align:center;font-weight:700;text-decoration: none;}
    .collectionNav:hover{background:#333}

    /* ----------------- Responsive adjustments ----------------- */
    @media (max-width:1100px){.cat001{grid-template-columns:repeat(2,1fr)}.cat01 img{height:280px}}
    @media (max-width:700px){.cat001{grid-template-columns:1fr}.cat01 img{height:320px}.main04{padding:26px 14px}}
    @media (max-width:520px){.hero-title{font-size:28px}header{padding:12px 18px}}

    .cta-header-section {
  text-align: center;                      
  margin: 40px 0 24px 0;                      
}

.cta-header-section h2 {
  font-family: "Josefin Sans"; 
  font-size: 32px;                             
  font-weight: 700;
  color: #222;                            
}
  </style>
</head>
<body>

  <!-- ============================ HEADER ============================ -->
  <header id="site-header">
    <div class="logo_main">
      <a href="index.html" class="logopng1">
        <img src="assests/images/White Logo.png" alt="FitSphere logo" class="logo-image1">
      </a>
    </div>
    <a href="index.html" class="logo">FitSphere</a>
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon"><i class="fa fa-bars"></i></label>
    <nav id="main-nav">
      <a href="HowItWorks.html">How It Works</a>
      <a href="collection.html">View Clothing</a>
      <a href="offers.html">Offers</a>
      <a href="about.html">About</a>
      <a href="cart.html">Cart</a>
    </nav>
    <div class="user-auth" id="user-auth"></div>
  </header>

  <main>
    <!-- HERO -->
    <section class="hero">
      <div class="hero-inner">
        <h1 class="hero-title">Welcome (<span id="hero-username">Username</span>)!</h1>
      </div>
    </section>

    <!-- BRAND + BOOKINGS -->
    <section class="brands-bookings">
      <div class="brand-logos">
        <img src="assests/images/brands/Brioni_logo_PNG_(1).png" alt="Brand 1 logo">
        <img src="assests/images/brands/Giorgio_Armani_(2).png" alt="Brand 2 logo">
        <img src="assests/images/brands/output-onlinepngtools.png" alt="Brand 3 logo">
        <img src="assests/images/brands/Ermenegildo_Zegna_(1).png" alt="Brand 4 logo">
        <img src="assests/images/brands/NB-Logo-Black_80-1.png" alt="Brand 5 logo">
      </div>
      <div class="booking-wrap">
        <table class="booking-table">
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Suit</th>
              <th>Start</th>
              <th>End</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>111</td>
              <td>Black Tuxedo</td>
              <td>Oct 12</td>
              <td>Oct 17</td>
              <td class="status active">Active</td>
              <td><a class="view-link" href="#">View</a></td>
            </tr>
            <tr>
              <td>112</td>
              <td>Navy Suit</td>
              <td>Oct 13</td>
              <td>Oct 15</td>
              <td class="status upcoming">Upcoming</td>
              <td><a class="view-link" href="#">View</a></td>
            </tr>
            <tr>
              <td>113</td>
              <td>Nilame Suit</td>
              <td>Oct 13</td>
              <td>Oct 12</td>
              <td class="status active">Active</td>
              <td><a class="view-link" href="#">View</a></td>
            </tr>
          </tbody>
        </table>
        <div class="see-more-wrap">
          <a class="see-more" href="#">See more &rarr;</a>
        </div>
      </div>
    </section>

    <!-- SUIT CATEGORIES -->
    <div class="main04">
      <h2>Rent a Suit for Every Occasion</h2>
      <div class="cat001">
        <div class="cat01">
          <img src="assets/images/suits/Polished Business Look.jpg" alt="Business_suit">
          <a href="collection.php#Business_suit" class="cat">Business Suits</a>
          <p>Formal and professional suits ideal for office, meetings, and corporate events.</p>
        </div>
        <div class="cat01">
          <img src="assests/images/suits/Black Slim-Fit Tuxedo 3-Piece.jpg" alt="Dinner_suit">
          <a href="collection.php#Dinner_suit" class="cat">Dinner Suits</a>
          <p>Elegant evening wear with satin details, perfect for black-tie events and formal dinners.</p>
        </div>
        <div class="cat01">
          <img src="assests/images/suits/Venue decorations wedding trends for a….jpg" alt="Wedding_Suits">
          <a href="collection.php#Wedding_Suits" class="cat">Wedding Suits</a>
          <p>Stylish and special suits designed for grooms and wedding ceremonies.</p>
        </div>
        <div class="cat01">
          <img src="assests/images/suits/hon-1.jpghon-1.jpg" alt="Nilame_Suits">
          <a href="collection.php#Nilame_Suits" class="cat">Nilame Suits</a>
          <p>Traditional Sri Lankan ceremonial attire inspired by Kandyan royalty.</p>
        </div>
        <div class="cat01">
          <img src="assets/images/suits/Indian suit.webp" alt="Indian_Suits">
          <a href="collection.php#Indian_Suits" class="cat">Indian Suits</a>
          <p>Opulent, embroidered Sherwanis and Bandhgalas for stunning wedding and celebratory looks</p>
        </div>
        <div class="cat01">
          <img src="assets/images/suits/blazer.jpg" alt="Blazers">
          <a href="collection.php#Blazers" class="cat">Blazers</a>
          <p>Formal and professional Blazers ideal for office, meetings, and corporate events.</p>
        </div>
      </div>
      <a href="collection.php" class="collectionNav">EXPLORE COLLECTION</a>
    </div>

    


    <!-- CIRCULAR MAP -->
     <!-- ====================== CTA HEADER ====================== -->
    <section class="cta-header-section">
     <h2>Grab Your Suit Here</h2>
    </section>

    <section class="circle-map-container">
      <div class="circle-map">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15851.788513060738!2d79.925327740625!3d6.840342755316914!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25936571d9d0d%3A0xbb13f7f5b1f5cddc!2sVENEE%20(Pvt.)%20Ltd!5e0!3m2!1sen!2slk!4v1697719749069!5m2!1sen!2slk"
          allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </section>

  </main>

  <script>
    /* ------------------------- Configuration ------------------------- */
    const userName = '';

    const userAuthContainer = document.getElementById('user-auth');

    function renderAuth(name) {
      userAuthContainer.innerHTML = '';
      if (name && name.trim().length > 0) {
        const profileBox = document.createElement('div');
        profileBox.className = 'profile-box';

        const avatar = document.createElement('div');
        avatar.className = 'avatar-circle';
        avatar.textContent = name.trim().charAt(0).toUpperCase();

        const dropdown = document.createElement('div');
        dropdown.className = 'dropdown';

        const acct = document.createElement('a');
        acct.href = '#';
        acct.textContent = 'My Account';

        const logout = document.createElement('a');
        logout.href = '#';
        logout.textContent = 'Logout';

        dropdown.appendChild(acct);
        dropdown.appendChild(logout);

        profileBox.appendChild(avatar);
        profileBox.appendChild(dropdown);
        userAuthContainer.appendChild(profileBox);
      } else {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'Login-btn-model';
        btn.textContent = 'Login';
        btn.addEventListener('click', () => {
          alert('Open your login modal here');
        });
        userAuthContainer.appendChild(btn);
      }
    }

    renderAuth(userName);

    const header = document.getElementById('site-header');
    function onScroll() { if (window.scrollY > 20) header.classList.add('scrolled'); else header.classList.remove('scrolled'); }
    window.addEventListener('scroll', onScroll, { passive: true });

    const menuToggle = document.getElementById('menu-toggle');
    document.querySelectorAll('#main-nav a').forEach(a => {
      a.addEventListener('click', () => { if (window.matchMedia('(max-width:900px)').matches) menuToggle.checked = false; });
    });

    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') menuToggle.checked = false; });

    (function setHeroUsername() {
      const heroUsername = document.getElementById('hero-username');
      if (heroUsername) { heroUsername.textContent = userName && userName.trim().length > 0 ? userName.trim() : 'Username'; }
    })();
  </script>
</body>
</html>
