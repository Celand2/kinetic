<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('home.title') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Rajdhani:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<style>
/* ── RESET & BASE ─────────────────────────────────────────────────── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --gold:    #c9a227;
  --gold-d:  #a07d1a;
  --gold-l:  #e8c050;
  --bg:      #060b14;
  --bg2:     #0a1220;
  --bg3:     #0d1a2e;
  --navy:    #0f2040;
  --green:   #81c784;
  --blue:    #7a9cc6;
  --red:     #ef5350;
  --text:    #e8e8e8;
  --muted:   #b0bfd9;
  --dim:     #6b7a9a;
  --border:  rgba(201,162,39,0.18);
  --glow-sm: 0 0 14px rgba(201,162,39,0.3);
  --glow-md: 0 0 30px rgba(201,162,39,0.45), 0 0 70px rgba(201,162,39,0.15);
}
html{scroll-behavior:smooth;}
body{
  font-family:'Rajdhani',sans-serif;
  background:var(--bg);
  color:var(--text);
  overflow-x:hidden;
  min-height:100vh;
}

/* ── MESH BACKGROUND ──────────────────────────────────────────────── */
body::before{
  content:'';position:fixed;inset:0;
  background-image:
    linear-gradient(rgba(201,162,39,0.03) 1px,transparent 1px),
    linear-gradient(90deg,rgba(201,162,39,0.03) 1px,transparent 1px);
  background-size:64px 64px;
  pointer-events:none;z-index:0;
}

/* ── NAV ──────────────────────────────────────────────────────────── */
.kts-nav{
  position:fixed;top:0;left:0;right:0;z-index:500;
  height:62px;
  display:flex;align-items:center;justify-content:space-between;
  padding:0 5%;
  background:rgba(6,11,20,0.88);
  backdrop-filter:blur(18px);
  border-bottom:1px solid var(--border);
}
.nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none;}
.nav-logo-icon{
  width:38px;height:38px;
  background:linear-gradient(135deg,var(--gold),var(--gold-d));
  clip-path:polygon(20% 0%,80% 0%,100% 20%,100% 80%,80% 100%,20% 100%,0% 80%,0% 20%);
  display:flex;align-items:center;justify-content:center;
  font-family:'Orbitron',sans-serif;font-weight:900;font-size:17px;
  color:#060b14;
  animation:pulse-logo 3s ease-in-out infinite;
}
@keyframes pulse-logo{0%,100%{box-shadow:var(--glow-sm);}50%{box-shadow:var(--glow-md);}}
.nav-brand{
  font-family:'Orbitron',sans-serif;font-size:12px;font-weight:700;
  letter-spacing:2px;color:var(--gold);line-height:1.15;
}
.nav-brand span{display:block;font-size:8px;letter-spacing:4px;color:var(--dim);font-weight:400;}
.nav-links{display:flex;gap:28px;}
.nav-links a{
  font-family:'Rajdhani',sans-serif;font-size:13px;font-weight:600;
  letter-spacing:1.5px;text-transform:uppercase;
  color:var(--muted);text-decoration:none;
  transition:color .25s;position:relative;
}
.nav-links a::after{
  content:'';position:absolute;bottom:-3px;left:0;right:0;
  height:1px;background:var(--gold);
  transform:scaleX(0);transition:transform .25s;
}
.nav-links a:hover{color:var(--gold);}
.nav-links a:hover::after{transform:scaleX(1);}
.nav-cta{display:flex;gap:10px;}
.btn-outline{
  padding:8px 20px;
  border:1px solid var(--border);
  background:transparent;
  color:var(--muted);
  font-family:'Rajdhani',sans-serif;font-size:12px;font-weight:600;
  letter-spacing:1.5px;text-transform:uppercase;
  border-radius:4px;text-decoration:none;
  transition:all .25s;
}
.btn-outline:hover{border-color:var(--gold);color:var(--gold);background:rgba(201,162,39,0.07);}
.btn-gold{
  padding:8px 20px;
  background:linear-gradient(135deg,var(--gold),var(--gold-d));
  border:none;color:#060b14;
  font-family:'Rajdhani',sans-serif;font-size:12px;
  font-weight:700;letter-spacing:1.5px;text-transform:uppercase;
  border-radius:4px;text-decoration:none;
  transition:all .25s;
  box-shadow:0 4px 16px rgba(201,162,39,0.25);
}
.btn-gold:hover{transform:translateY(-2px);box-shadow:var(--glow-md);}
.btn-gold-xl{
  padding:14px 36px;font-size:14px;letter-spacing:2px;
  background:linear-gradient(135deg,var(--gold),var(--gold-d));
  border:none;color:#060b14;
  font-family:'Orbitron',sans-serif;font-weight:700;
  border-radius:6px;text-decoration:none;
  display:inline-block;transition:all .25s;
  box-shadow:0 6px 24px rgba(201,162,39,0.3);
}
.btn-gold-xl:hover{transform:translateY(-3px);box-shadow:var(--glow-md);}
.btn-ghost-xl{
  padding:14px 36px;font-size:13px;letter-spacing:2px;
  border:1px solid rgba(201,162,39,0.35);background:rgba(201,162,39,0.06);
  color:var(--gold);
  font-family:'Orbitron',sans-serif;font-weight:600;
  border-radius:6px;text-decoration:none;
  display:inline-block;transition:all .25s;
}
.btn-ghost-xl:hover{background:rgba(201,162,39,0.12);border-color:var(--gold);}

/* ── HERO SLIDESHOW ───────────────────────────────────────────────── */
.hero{
  position:relative;
  height:100vh;min-height:600px;
  display:flex;align-items:center;
  overflow:hidden;
  margin-top:0;
}

/* Slides en arrière-plan */
.hero-slides{position:absolute;inset:0;z-index:0;}
.hero-slide{
  position:absolute;inset:0;
  background-size:cover;
  background-position:center center;
  opacity:0;
  transition:opacity 1.6s ease;
  transform:scale(1.06);
  animation:kenburns 14s ease-in-out infinite alternate;
}
.hero-slide.active{opacity:1;}
@keyframes kenburns{
  0%  {transform:scale(1.06) translate(0,0);}
  100%{transform:scale(1.0) translate(-1%,-0.5%);}
}

/* Dégradé sombre sur les slides */
.hero-overlay{
  position:absolute;inset:0;z-index:1;
  background:linear-gradient(
    135deg,
    rgba(6,11,20,0.82) 0%,
    rgba(6,11,20,0.55) 50%,
    rgba(6,11,20,0.72) 100%
  );
}
/* Bande dorée bas */
.hero-overlay::after{
  content:'';position:absolute;bottom:0;left:0;right:0;
  height:30%;
  background:linear-gradient(to bottom,transparent,var(--bg));
  pointer-events:none;
}

/* Contenu hero */
.hero-content{
  position:relative;z-index:2;
  max-width:660px;padding:0 5%;
  margin-top:62px;
}
.hero-badge{
  display:inline-flex;align-items:center;gap:8px;
  padding:6px 16px;
  border:1px solid rgba(201,162,39,0.35);
  background:rgba(201,162,39,0.08);
  border-radius:2px;margin-bottom:28px;
  font-size:10px;letter-spacing:4px;text-transform:uppercase;
  color:var(--gold);font-family:'Space Mono',monospace;
}
.hero-badge::before{
  content:'';width:6px;height:6px;border-radius:50%;
  background:var(--gold);animation:blink 1.5s ease-in-out infinite;
  flex-shrink:0;
}
@keyframes blink{0%,100%{opacity:1;}50%{opacity:0.2;}}
.hero h1{
  font-family:'Orbitron',sans-serif;font-weight:900;
  font-size:clamp(32px,5vw,68px);line-height:1.05;
  letter-spacing:-1px;margin-bottom:20px;
}
.hero h1 .line1{display:block;color:var(--text);}
.hero h1 .line2{
  display:block;
  background:linear-gradient(90deg,var(--gold),var(--gold-l),var(--gold));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  background-clip:text;
  filter:drop-shadow(0 0 20px rgba(201,162,39,0.45));
}
.hero-desc{
  font-size:17px;font-weight:400;line-height:1.75;
  color:rgba(232,232,232,0.78);
  margin-bottom:36px;max-width:520px;
}
.hero-actions{display:flex;gap:14px;flex-wrap:wrap;align-items:center;}
.hero-stats{
  display:flex;gap:36px;
  margin-top:56px;padding-top:32px;
  border-top:1px solid rgba(201,162,39,0.15);
  flex-wrap:wrap;
}
.hero-stat .val{
  font-family:'Orbitron',sans-serif;font-size:24px;font-weight:700;
  color:var(--gold);display:block;
}
.hero-stat .lbl{
  font-size:10px;letter-spacing:3px;text-transform:uppercase;
  color:var(--dim);margin-top:3px;display:block;
}

/* Dots navigation slideshow */
.hero-dots{
  position:absolute;bottom:100px;left:5%;z-index:3;
  display:flex;gap:8px;align-items:center;
}
.hero-dot{
  width:8px;height:8px;border-radius:50%;
  background:rgba(201,162,39,0.25);
  border:1px solid rgba(201,162,39,0.4);
  cursor:pointer;transition:all .3s;
}
.hero-dot.active{
  background:var(--gold);width:24px;border-radius:4px;
  box-shadow:0 0 8px rgba(201,162,39,0.5);
}

/* Flèches navigation */
.hero-arrows{
  position:absolute;bottom:85px;right:5%;z-index:3;
  display:flex;gap:8px;
}
.hero-arrow{
  width:38px;height:38px;border-radius:6px;
  border:1px solid rgba(201,162,39,0.3);
  background:rgba(201,162,39,0.06);
  color:var(--gold);font-size:16px;
  display:flex;align-items:center;justify-content:center;
  cursor:pointer;transition:all .2s;
}
.hero-arrow:hover{background:rgba(201,162,39,0.15);border-color:var(--gold);}

/* Numéro slide courant */
.hero-slide-count{
  position:absolute;top:50%;right:5%;transform:translateY(-50%);
  z-index:3;
  font-family:'Space Mono',monospace;font-size:11px;
  color:rgba(201,162,39,0.5);letter-spacing:2px;
}

/* ── TICKER ───────────────────────────────────────────────────────── */
.ticker{
  background:rgba(10,18,32,0.95);
  border-top:1px solid var(--border);
  border-bottom:1px solid var(--border);
  overflow:hidden;height:38px;position:relative;z-index:10;
}
.ticker-inner{
  display:flex;gap:60px;align-items:center;height:100%;
  white-space:nowrap;
  animation:ticker-move 30s linear infinite;
}
@keyframes ticker-move{0%{transform:translateX(0);}100%{transform:translateX(-50%);}}
.ticker-item{
  font-family:'Space Mono',monospace;font-size:11px;
  color:var(--muted);display:flex;gap:10px;align-items:center;
}
.ticker-item .t-name{color:var(--gold);font-weight:700;}
.ticker-item .t-rate{color:var(--green);}
.ticker-item .t-sep{color:var(--border);}

/* ── SECTION COMMUNES ─────────────────────────────────────────────── */
.section{position:relative;z-index:1;padding:90px 5%;}
.s-header{text-align:center;margin-bottom:56px;}
.s-eyebrow{
  font-family:'Space Mono',monospace;font-size:10px;
  letter-spacing:5px;text-transform:uppercase;
  color:var(--gold);margin-bottom:14px;
}
.s-title{
  font-family:'Orbitron',sans-serif;font-size:clamp(22px,3vw,40px);
  font-weight:700;color:var(--text);line-height:1.2;
}
.s-title span{
  background:linear-gradient(90deg,var(--gold),var(--gold-l));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  background-clip:text;
}
.s-sub{font-size:15px;color:var(--muted);margin-top:12px;max-width:560px;margin-left:auto;margin-right:auto;line-height:1.6;}

/* ── CYCLES ───────────────────────────────────────────────────────── */
.cycles-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
  gap:18px;max-width:1100px;margin:0 auto;
}
.cycle-card{
  background:linear-gradient(155deg,rgba(13,26,46,0.9),rgba(10,18,32,0.95));
  border:1px solid var(--border);
  border-radius:14px;padding:28px 24px;
  position:relative;overflow:hidden;
  transition:transform .3s,border-color .3s,box-shadow .3s;
  text-decoration:none;color:inherit;display:block;
}
.cycle-card:hover{
  transform:translateY(-6px);
  border-color:var(--gold);
  box-shadow:0 12px 40px rgba(201,162,39,0.18);
}
.cycle-card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--gold),var(--gold-l),var(--gold));
}
.cycle-card::after{
  content:'';position:absolute;top:-60px;right:-60px;
  width:150px;height:150px;border-radius:50%;
  background:radial-gradient(circle,rgba(201,162,39,0.06) 0%,transparent 70%);
  pointer-events:none;
}
.c-index{
  font-family:'Space Mono',monospace;font-size:9px;
  letter-spacing:4px;text-transform:uppercase;
  color:var(--dim);margin-bottom:10px;
}
.c-name{
  font-family:'Orbitron',sans-serif;font-size:13px;font-weight:700;
  letter-spacing:1.5px;color:var(--text);margin-bottom:6px;
}
.c-days{
  font-size:11px;letter-spacing:2px;text-transform:uppercase;
  color:var(--dim);margin-bottom:22px;
  font-family:'Space Mono',monospace;
}
.c-rate{
  font-family:'Orbitron',sans-serif;font-size:44px;font-weight:900;
  line-height:1;color:var(--gold);
  text-shadow:0 0 20px rgba(201,162,39,0.35);
  margin-bottom:4px;
}
.c-unit{font-size:13px;color:var(--dim);margin-bottom:20px;letter-spacing:1px;}
.c-return{
  display:flex;justify-content:space-between;align-items:center;
  padding:10px 0;
  border-top:1px solid rgba(201,162,39,0.12);
  font-size:12px;letter-spacing:1px;
  margin-bottom:20px;
}
.c-return .k{color:var(--dim);}
.c-return .v{font-family:'Orbitron',sans-serif;font-weight:700;color:var(--green);}
.c-btn{
  display:block;width:100%;text-align:center;
  background:linear-gradient(135deg,var(--gold),var(--gold-d));
  color:#060b14;font-family:'Orbitron',sans-serif;
  font-size:12px;font-weight:700;letter-spacing:1.5px;
  padding:10px;border-radius:7px;text-decoration:none;
  transition:opacity .2s;
}
.c-btn:hover{opacity:.88;}
.c-empty{
  grid-column:1/-1;text-align:center;padding:3rem;color:var(--dim);
  font-family:'Space Mono',monospace;font-size:13px;
}

/* ── ABOUT ────────────────────────────────────────────────────────── */
.about-section{background:linear-gradient(180deg,var(--bg),var(--bg2),var(--bg));}
.about-grid{
  display:grid;grid-template-columns:1fr 1fr;
  gap:56px;align-items:center;max-width:1100px;margin:0 auto;
}
.about-left h2{
  font-family:'Orbitron',sans-serif;
  font-size:clamp(20px,2.5vw,34px);
  font-weight:700;color:var(--text);line-height:1.25;margin-bottom:16px;
}
.about-left h2 span{
  background:linear-gradient(90deg,var(--gold),var(--gold-l));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.about-left p{
  font-size:15px;line-height:1.8;color:var(--muted);margin-bottom:28px;
}
.about-tags{display:flex;flex-wrap:wrap;gap:8px;}
.about-tag{
  display:inline-flex;align-items:center;gap:5px;
  padding:5px 12px;
  border:1px solid rgba(201,162,39,0.22);
  background:rgba(201,162,39,0.05);
  border-radius:4px;
  font-size:11px;letter-spacing:1.5px;text-transform:uppercase;
  color:var(--gold);font-family:'Space Mono',monospace;
  transition:all .2s;
}
.about-tag:hover{background:rgba(201,162,39,0.1);border-color:rgba(201,162,39,0.45);}
.about-tag::before{content:'';width:4px;height:4px;border-radius:50%;background:var(--gold);flex-shrink:0;}

/* Stats grid droite */
.stats-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.stat-card{
  padding:22px 18px;
  background:linear-gradient(135deg,rgba(13,26,46,0.7),rgba(10,18,32,0.85));
  border:1px solid var(--border);
  border-radius:12px;transition:all .3s;
}
.stat-card:hover{border-color:var(--gold);box-shadow:0 8px 28px rgba(201,162,39,0.1);transform:translateY(-3px);}
.stat-icon{font-size:22px;margin-bottom:10px;}
.stat-val{
  font-family:'Orbitron',sans-serif;font-size:22px;font-weight:700;
  color:var(--gold);line-height:1;
}
.stat-lbl{font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:var(--dim);margin-top:5px;display:block;}

/* ── AUTH CTA (guest only) ────────────────────────────────────────── */
.auth-section{
  position:relative;z-index:1;
  padding:100px 5%;text-align:center;
}
.auth-box{
  max-width:680px;margin:0 auto;
  padding:60px 40px;
  background:linear-gradient(135deg,rgba(13,26,46,0.9),rgba(201,162,39,0.04));
  border:1px solid rgba(201,162,39,0.22);
  border-radius:18px;
  position:relative;overflow:hidden;
  box-shadow:0 40px 100px rgba(0,0,0,0.5);
}
.auth-box::before{
  content:'';position:absolute;top:0;left:0;right:0;height:2px;
  background:linear-gradient(90deg,transparent,var(--gold),var(--gold-l),var(--gold),transparent);
}
.auth-box::after{
  content:'';position:absolute;
  bottom:-80px;right:-80px;width:220px;height:220px;border-radius:50%;
  background:radial-gradient(circle,rgba(201,162,39,0.07) 0%,transparent 70%);
  pointer-events:none;
}
.auth-box h2{
  font-family:'Orbitron',sans-serif;font-size:clamp(20px,3vw,34px);
  font-weight:900;color:var(--text);margin-bottom:16px;
}
.auth-box h2 span{
  background:linear-gradient(90deg,var(--gold),var(--gold-l));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.auth-box p{font-size:16px;color:var(--muted);margin-bottom:36px;line-height:1.7;}
.auth-actions{display:flex;justify-content:center;gap:14px;flex-wrap:wrap;}

/* ── FOOTER ───────────────────────────────────────────────────────── */
footer{
  position:relative;z-index:1;
  border-top:1px solid var(--border);
  padding:32px 5%;
  display:flex;justify-content:space-between;align-items:center;
  flex-wrap:wrap;gap:16px;
}
.footer-brand{font-family:'Orbitron',sans-serif;font-size:11px;letter-spacing:3px;color:var(--gold);}
.footer-copy{font-size:12px;color:var(--dim);letter-spacing:1px;}

/* ── RESPONSIVE ───────────────────────────────────────────────────── */
@media(min-width:901px){
  .nav-cta .mobile-menu-btn{display:none;}
}
@media(max-width:900px){
  .nav-links{display:none;}
  .nav-cta .text{display:none;}
  .nav-cta .btn-outline,
  .nav-cta .btn-gold{padding:8px 12px;min-width:42px;}
  .nav-cta .icon{font-size:1rem;}
  .mobile-menu-btn{display:flex;align-items:center;justify-content:center;width:42px;height:42px;border:1px solid var(--border);background:rgba(201,162,39,0.08);color:var(--gold);border-radius:8px;cursor:pointer;margin-left:8px;}
  .mobile-menu{display:none;position:absolute;top:62px;left:0;right:0;background:rgba(6,11,20,0.96);border-bottom:1px solid var(--border);padding:14px 5%;z-index:400;}
  .mobile-menu.active{display:block;}
  .mobile-menu a{display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:10px;color:var(--muted);text-decoration:none;border:1px solid transparent;transition:all .2s;}
  .mobile-menu a:hover{border-color:rgba(201,162,39,0.22);color:var(--gold);background:rgba(201,162,39,0.06);}
  .about-grid{grid-template-columns:1fr;gap:40px;}
  .hero-slide-count{display:none;}
}
@media(max-width:600px){
  .hero-stats{gap:20px;}
  .auth-box{padding:40px 24px;}
  .stats-grid{grid-template-columns:1fr 1fr;}
  .auth-actions{flex-direction:column;align-items:center;}
  .hero-dots{bottom:70px;}
  .hero-arrows{bottom:55px;}
}
@media(max-width:400px){
  .nav-cta .btn-outline:not(.lang-btn){display:none;}
  .hero h1{font-size:clamp(28px,8vw,42px);}
  .hero-badge{font-size:9px;letter-spacing:3px;}
}

/* ── ANIMATIONS ENTRÉE ────────────────────────────────────────────── */
.fade-up{opacity:0;transform:translateY(28px);transition:opacity .7s ease,transform .7s ease;}
.fade-up.visible{opacity:1;transform:translateY(0);}
</style>
</head>
<body>

<!-- NAV -->
<nav class="kts-nav">
  <a href="{{ route('home') }}" class="nav-logo">
    <div class="nav-logo-icon">K</div>
    <div class="nav-brand">{{ __('home.brand.name') }}<span>{{ __('home.brand.tagline') }}</span></div>
  </a>
  <div class="nav-links">
    <a href="#cycles">{{ __('home.nav.cycles') }}</a>
    <a href="#about">{{ __('home.nav.about') }}</a>
    @guest
    <a href="{{ route('login') }}">{{ __('home.nav.login') }}</a>
    @endguest
  </div>
  <div class="nav-cta">
    <a href="{{ route('locale.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" class="btn-outline lang-btn"><span class="icon">🌐</span><span class="text">{{ strtoupper(app()->getLocale()) }}</span></a>
    @guest
      <a href="{{ route('login') }}" class="btn-outline login-link"><span class="icon">🔐</span><span class="text">{{ __('home.nav.login') }}</span></a>
      <a href="{{ route('register') }}" class="btn-gold register-only"><span class="icon">🚀</span><span class="text">{{ __('home.nav.register') }}</span></a>
    @else
      <a href="{{ route('dashboard') }}" class="btn-gold dashboard-btn"><span class="icon">📊</span><span class="text">{{ __('dashboard.title') }}</span></a>
    @endguest
    <button type="button" class="mobile-menu-btn" aria-label="{{ __('home.nav.menu') }}" onclick="toggleMobileMenu()">☰</button>
  </div>
</nav>
<div class="mobile-menu" id="mobileMenu">
  <a href="#cycles">{{ __('home.nav.cycles') }}</a>
  <a href="#about">{{ __('home.nav.about') }}</a>
  @guest
    <a href="{{ route('login') }}">🔐 {{ __('home.nav.login') }}</a>
    <a href="{{ route('register') }}">🚀 {{ __('home.nav.register') }}</a>
  @else
    <a href="{{ route('dashboard') }}">📊 {{ __('dashboard.title') }}</a>
  @endguest
</div>

<!-- ══ HERO SLIDESHOW ══════════════════════════════════════════════ -->
<section class="hero" id="home">
  <!-- Slides photos -->
  <div class="hero-slides" id="heroSlides">
    @foreach($slides as $i => $slide)
      <div class="hero-slide {{ $i === 0 ? 'active' : '' }}"
           style="background-image:url('{{ asset($slide) }}')"></div>
    @endforeach
  </div>

  <!-- Overlay -->
  <div class="hero-overlay"></div>

  <!-- Contenu -->
  <div class="hero-content">
    <div class="hero-badge">{{ __('home.hero.badge') }}</div>
    <h1>
      <span class="line1">{{ __('home.hero.line1') }}</span>
      <span class="line2">{{ __('home.hero.line2') }}</span>
    </h1>
    <p class="hero-desc">{{ __('home.hero.desc') }}</p>
    <div class="hero-actions">
      @auth
        <a href="{{ route('dashboard') }}" class="btn-gold-xl">{{ __('home.hero.dashboard') }}</a>
      @else
        <a href="{{ route('register') }}" class="btn-gold-xl">{{ __('home.hero.open_account') }}</a>
        <a href="#cycles" class="btn-ghost-xl">{{ __('home.hero.view_cycles') }}</a>
      @endauth
    </div>
    <div class="hero-stats">
      <div class="hero-stat">
        <span class="val">{{ $cycles->count() ?: '—' }}</span>
        <span class="lbl">{{ __('home.hero.stats.cycles_active') }}</span>
      </div>
      @if($cycles->count())
        <div class="hero-stat">
          <span class="val">{{ number_format($cycles->max('daily_profit_percent'), 0) }}%/jr</span>
          <span class="lbl">{{ __('home.hero.stats.max_profit') }}</span>
        </div>
      @endif
      <div class="hero-stat">
        <span class="val">3 Niv.</span>
        <span class="lbl">{{ __('home.hero.stats.referral_levels') }}</span>
      </div>
    </div>
  </div>

  <!-- Dots -->
  <div class="hero-dots" id="heroDots"></div>

  <!-- Flèches -->
  @if(count($slides) > 1)
  <div class="hero-arrows">
    <button class="hero-arrow" onclick="heroSlide(-1)">‹</button>
    <button class="hero-arrow" onclick="heroSlide(1)">›</button>
  </div>
  <!-- Compteur -->
  <div class="hero-slide-count" id="heroCount">01 / {{ str_pad(count($slides), 2, '0', STR_PAD_LEFT) }}</div>
  @endif
</section>

<!-- TICKER -->
<div class="ticker">
  <div class="ticker-inner" id="tickerInner">
    @php $tickItems = $cycles->count() ? $cycles : collect(); @endphp
    @forelse($tickItems as $c)
      <div class="ticker-item">
        <span class="t-name">{{ strtoupper($c->name) }}</span>
        <span class="t-sep">|</span>
        <span class="t-rate">+{{ $c->daily_profit_percent }}%/jr</span>
        <span class="t-sep">·</span>
        <span>{{ $c->duration_days }}J</span>
      </div>
    @empty
      <div class="ticker-item"><span class="t-name">{{ __('home.brand.full') }}</span><span class="t-sep">|</span><span class="t-rate">{{ __('home.ticker.platform_active') }}</span></div>
    @endforelse
    {{-- Dupliquer pour boucle infinie --}}
    @forelse($tickItems as $c)
      <div class="ticker-item">
        <span class="t-name">{{ strtoupper($c->name) }}</span>
        <span class="t-sep">|</span>
        <span class="t-rate">+{{ $c->daily_profit_percent }}%/jr</span>
        <span class="t-sep">·</span>
        <span>{{ $c->duration_days }}J</span>
      </div>
    @empty
      <div class="ticker-item"><span class="t-name">{{ __('home.brand.full') }}</span><span class="t-sep">|</span><span class="t-rate">{{ __('home.ticker.platform_active') }}</span></div>
    @endforelse
  </div>
</div>

<!-- ══ CYCLES DYNAMIQUES ══════════════════════════════════════════ -->
<section class="section" id="cycles">
  <div class="s-header">
    <div class="s-eyebrow">// {{ __('home.cycles.eyebrow') }}</div>
    <h2 class="s-title">{!! __('home.cycles.title') !!}</h2>
    <p class="s-sub">{{ __('home.cycles.subtitle') }}</p>
  </div>

  <div class="cycles-grid">
    @forelse($cycles as $i => $cycle)
      <div class="cycle-card fade-up">
        <div class="c-index">// Cycle #{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
        <div class="c-name">{{ strtoupper($cycle->name) }}</div>
        <div class="c-days">{{ $cycle->duration_days }} {{ __('home.cycles.days') }} · {{ $cycle->description ?? __('home.cycles.default_description') }}</div>
        <div class="c-rate">{{ $cycle->daily_profit_percent }}%</div>
        <div class="c-unit">{{ __('home.cycles.profit_unit') }}</div>
        <div class="c-return">
          <span class="k">{{ __('home.cycles.total_return') }}</span>
          <span class="v">{{ $cycle->total_return_percent }}%</span>
        </div>
        @auth
          <a href="{{ route('investments.cycle.tranches', $cycle) }}" class="c-btn">{{ __('home.cycles.invest') }}</a>
        @else
          <a href="{{ route('register') }}" class="c-btn">{{ __('home.cycles.start') }}</a>
        @endauth
      </div>
    @empty
      <div class="c-empty">{{ __('home.cycles.none') }}</div>
    @endforelse
  </div>
</section>

<!-- ══ ABOUT ═══════════════════════════════════════════════════════ -->
<section class="section about-section" id="about">
  <div class="about-grid">
    <!-- Gauche : texte -->
    <div class="about-left fade-up">
      <div class="s-eyebrow" style="text-align:left;margin-bottom:14px;">// {{ __('home.about.eyebrow') }}</div>
      <h2>{!! __('home.about.title') !!}</h2>
      <p>{{ __('home.about.text') }}</p>
      <div class="about-tags">
        <span class="about-tag">{{ __('home.about.tags.automatic_profits') }}</span>
        <span class="about-tag">{{ __('home.about.tags.transparent') }}</span>
        <span class="about-tag">{{ __('home.about.tags.secure') }}</span>
        <span class="about-tag">{{ __('home.about.tags.multi_level') }}</span>
        <span class="about-tag">{{ __('home.about.tags.fast_withdrawals') }}</span>
        <span class="about-tag">{{ __('home.about.tags.responsive_support') }}</span>
        <span class="about-tag">{{ __('home.about.tags.repeatable_cycles') }}</span>
        <span class="about-tag">{{ __('home.about.tags.always_on') }}</span>
      </div>
    </div>

    <!-- Droite : stats -->
    <div class="stats-grid fade-up">
      <div class="stat-card">
        <div class="stat-icon">⚡</div>
        <div class="stat-val">{{ $cycles->count() ?: '—' }}</div>
        <span class="stat-lbl">{{ __('home.stats.trading_cycles') }}</span>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📈</div>
        <div class="stat-val">{{ $cycles->count() ? number_format($cycles->max('daily_profit_percent'), 0) . '%' : '—' }}</div>
        <span class="stat-lbl">{{ __('home.stats.max_profit_day') }}</span>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🤝</div>
        <div class="stat-val">3 Niv.</div>
        <span class="stat-lbl">{{ __('home.stats.referral_commissions') }}</span>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🔒</div>
        <div class="stat-val">98%</div>
        <span class="stat-lbl">{{ __('home.stats.withdrawals_processed') }}</span>
      </div>
      <div class="stat-card">
        <div class="stat-icon">💳</div>
        <div class="stat-val">10%</div>
        <span class="stat-lbl">{{ __('home.stats.level_one_commission') }}</span>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🌍</div>
        <div class="stat-val">24/7</div>
        <span class="stat-lbl">{{ __('home.stats.availability') }}</span>
      </div>
    </div>
  </div>
</section>

<!-- ══ AUTH CTA (guests uniquement) ═══════════════════════════════ -->
@guest
<section class="auth-section">
  <div class="auth-box fade-up">
    <h2>{!! __('home.auth.heading') !!}</h2>
    <p>{{ __('home.auth.desc') }}</p>
    <div class="auth-actions">
      <a href="{{ route('register') }}" class="btn-gold-xl">{{ __('home.auth.create_account') }}</a>
      <a href="{{ route('login') }}" class="btn-ghost-xl">{{ __('home.auth.login') }}</a>
    </div>
  </div>
</section>
@endguest

<!-- FOOTER -->
<footer>
  <div class="footer-brand">{{ __('home.footer.brand') }}</div>
  <div class="footer-copy">{{ __('home.footer.copy', ['year' => date('Y')]) }}</div>
</footer>

<script>
/* ══ HERO SLIDESHOW ════════════════════════════════════════════════ */
(function(){
  var slides  = document.querySelectorAll('#heroSlides .hero-slide');
  var dotsWrap= document.getElementById('heroDots');
  var countEl = document.getElementById('heroCount');
  if(!slides.length) return;

  var total   = slides.length;
  var current = 0;
  var timer;

  // Créer dots
  for(var i=0;i<total;i++){
    var d=document.createElement('span');
    d.className='hero-dot'+(i===0?' active':'');
    (function(idx){d.addEventListener('click',function(){go(idx);restart();});})(i);
    dotsWrap.appendChild(d);
  }
  var dots=dotsWrap.querySelectorAll('.hero-dot');

  function go(n){
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');
    current=(n+total)%total;
    slides[current].classList.add('active');
    dots[current].classList.add('active');
    if(countEl) countEl.textContent=String(current+1).padStart(2,'0')+' / '+String(total).padStart(2,'0');
  }
  function restart(){clearInterval(timer);timer=setInterval(function(){go(current+1);},6000);}

  restart();

  // Pause au survol
  document.querySelector('.hero').addEventListener('mouseenter',function(){clearInterval(timer);});
  document.querySelector('.hero').addEventListener('mouseleave',restart);

  // Touch swipe mobile
  var startX=0;
  document.querySelector('.hero').addEventListener('touchstart',function(e){startX=e.touches[0].clientX;},{passive:true});
  document.querySelector('.hero').addEventListener('touchend',function(e){
    var dx=e.changedTouches[0].clientX-startX;
    if(Math.abs(dx)>50){go(current+(dx<0?1:-1));restart();}
  });

  // Exposer pour les flèches
  window.heroSlide=function(dir){go(current+dir);restart();};
})();

/* ══ INTERSECTION OBSERVER fade-up ════════════════════════════════ */
var obs=new IntersectionObserver(function(entries){
  entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('visible');}});
},{threshold:0.12});
document.querySelectorAll('.fade-up').forEach(function(el){obs.observe(el);});
function toggleMobileMenu(){
  document.getElementById('mobileMenu').classList.toggle('active');
}</script>
</body>
</html>
