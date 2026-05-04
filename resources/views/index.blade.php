<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>home</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Rajdhani:wght@300;400;500;600;700&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<style>
  :root {
    --void:        #010F1E;
    --deep-ocean:  #032950;
    --cyan:        #1CE7ED;
    --teal:        #1F9AA5;
    --aqua:        #1DA7DB;
    --royal:       #1A71E0;
    --ice:         #EAFBFF;
    --mid:         #0A2540;
    --glass:       rgba(28,231,237,0.07);
    --glass-border:rgba(28,231,237,0.22);
    --glow-sm:     0 0 12px rgba(28,231,237,0.35);
    --glow-md:     0 0 28px rgba(28,231,237,0.45), 0 0 60px rgba(29,167,219,0.2);
    --glow-lg:     0 0 50px rgba(28,231,237,0.55), 0 0 120px rgba(26,113,224,0.3);
  }
 
  * { margin:0; padding:0; box-sizing:border-box; }
 
  body {
    font-family:'Rajdhani', sans-serif;
    background: linear-gradient(135deg, #021830 0%, #032950 35%, #041E38 70%, #021830 100%);
    color: var(--ice);
    overflow-x: hidden;
    min-height: 100vh;
  }
 
  /* ── GRID MESH BACKGROUND ── */
  body::before {
    content:'';
    position:fixed; inset:0;
    background-image:
      linear-gradient(rgba(28,231,237,0.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(28,231,237,0.04) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events:none; z-index:0;
  }
 
  /* ── ANIMATED GRADIENT ORBS ── */
  .orb {
    position:fixed; border-radius:50%;
    filter:blur(90px); pointer-events:none; z-index:0;
    animation: float 12s ease-in-out infinite;
  }
  .orb1 { width:500px; height:500px; background:rgba(28,231,237,0.08); top:-100px; right:-100px; animation-delay:0s; }
  .orb2 { width:400px; height:400px; background:rgba(26,113,224,0.1); bottom:-80px; left:-80px; animation-delay:-5s; }
  .orb3 { width:300px; height:300px; background:rgba(31,154,165,0.08); top:40%; left:30%; animation-delay:-3s; }
 
  @keyframes float {
    0%,100% { transform:translateY(0) scale(1); }
    50%      { transform:translateY(-30px) scale(1.06); }
  }
 
  /* ── NAV ── */
  nav {
    position:fixed; top:0; left:0; right:0; z-index:100;
    backdrop-filter:blur(20px);
    background:rgba(3,41,80,0.55);
    border-bottom:1px solid var(--glass-border);
    padding:0 5%;
    display:flex; align-items:center; justify-content:space-between;
    height:72px;
  }
 
  .nav-logo {
    display:flex; align-items:center; gap:12px;
  }
  .logo-k {
    width:44px; height:44px;
    background: linear-gradient(135deg, var(--cyan), var(--royal));
    clip-path: polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%);
    display:flex; align-items:center; justify-content:center;
    font-family:'Orbitron',sans-serif; font-weight:900; font-size:20px;
    color:#010F1E; box-shadow:var(--glow-sm);
    animation: pulse-logo 3s ease-in-out infinite;
  }
  @keyframes pulse-logo {
    0%,100% { box-shadow:var(--glow-sm); }
    50%      { box-shadow:var(--glow-md); }
  }
  .nav-brand {
    font-family:'Orbitron',sans-serif; font-size:13px; font-weight:700;
    letter-spacing:2px; color:var(--cyan);
    line-height:1.2;
  }
  .nav-brand span { display:block; font-size:9px; letter-spacing:4px; color:var(--teal); font-weight:400; }
 
  .nav-links { display:flex; gap:32px; }
  .nav-links a {
    font-family:'Rajdhani',sans-serif; font-size:14px; font-weight:600;
    letter-spacing:2px; text-transform:uppercase;
    color:rgba(234,251,255,0.7); text-decoration:none;
    transition:color .3s; position:relative;
  }
  .nav-links a::after {
    content:''; position:absolute; bottom:-4px; left:0; right:0;
    height:1px; background:var(--cyan);
    transform:scaleX(0); transition:transform .3s;
  }
  .nav-links a:hover { color:var(--cyan); }
  .nav-links a:hover::after { transform:scaleX(1); }
 
  .nav-cta {
    display:flex; gap:12px;
  }
  .btn-glass {
    padding:9px 22px;
    border:1px solid var(--glass-border);
    background:var(--glass);
    backdrop-filter:blur(10px);
    color:var(--ice); font-family:'Rajdhani',sans-serif;
    font-size:13px; font-weight:600; letter-spacing:2px; text-transform:uppercase;
    border-radius:4px; cursor:pointer; text-decoration:none;
    transition:all .3s;
  }
  .btn-glass:hover {
    border-color:var(--cyan); color:var(--cyan);
    background:rgba(28,231,237,0.12);
    box-shadow:var(--glow-sm);
  }
  .btn-primary {
    padding:9px 22px;
    background: linear-gradient(135deg, var(--cyan), var(--royal));
    border:none; color:#010F1E;
    font-family:'Rajdhani',sans-serif; font-size:13px;
    font-weight:700; letter-spacing:2px; text-transform:uppercase;
    border-radius:4px; cursor:pointer; text-decoration:none;
    transition:all .3s;
    box-shadow: 0 4px 20px rgba(28,231,237,0.3);
  }
  .btn-primary:hover {
    transform:translateY(-2px);
    box-shadow:var(--glow-md);
  }
 
  /* ── HERO ── */
  .hero {
    position:relative; z-index:1;
    min-height:100vh;
    display:flex; align-items:center;
    padding:120px 5% 80px;
  }
 
  .hero-content {
    max-width:620px;
  }
 
  .hero-badge {
    display:inline-flex; align-items:center; gap:8px;
    padding:6px 16px;
    border:1px solid rgba(28,231,237,0.3);
    background:rgba(28,231,237,0.08);
    border-radius:2px; margin-bottom:32px;
    font-size:11px; letter-spacing:4px; text-transform:uppercase;
    color:var(--cyan); font-family:'Space Mono',monospace;
  }
  .hero-badge::before {
    content:''; width:6px; height:6px; border-radius:50%;
    background:var(--cyan);
    animation:blink 1.5s ease-in-out infinite;
  }
  @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.2} }
 
  .hero h1 {
    font-family:'Orbitron',sans-serif; font-weight:900;
    font-size:clamp(36px,5vw,72px); line-height:1.05;
    letter-spacing:-1px; margin-bottom:24px;
  }
  .hero h1 .line1 { display:block; color:var(--ice); }
  .hero h1 .line2 {
    display:block;
    background: linear-gradient(90deg, var(--cyan), var(--aqua), var(--royal));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    background-clip:text;
    text-shadow:none;
    filter:drop-shadow(0 0 20px rgba(28,231,237,0.4));
  }
 
  .hero-desc {
    font-size:18px; font-weight:400; line-height:1.7;
    color:rgba(234,251,255,0.72); margin-bottom:40px;
    max-width:520px;
  }
 
  .hero-actions { display:flex; gap:16px; align-items:center; flex-wrap:wrap; }
 
  .btn-xl {
    padding:16px 40px;
    font-size:14px; letter-spacing:3px;
  }
 
  .hero-stat-row {
    display:flex; gap:40px; margin-top:64px;
    padding-top:40px; border-top:1px solid rgba(28,231,237,0.15);
  }
  .hero-stat { }
  .hero-stat .val {
    font-family:'Orbitron',sans-serif; font-size:28px; font-weight:700;
    color:var(--cyan); display:block;
    text-shadow:0 0 20px rgba(28,231,237,0.5);
  }
  .hero-stat .lbl {
    font-size:11px; letter-spacing:3px; text-transform:uppercase;
    color:rgba(234,251,255,0.5); margin-top:4px; display:block;
  }
 
  /* ── HERO VISUAL ── */
  .hero-visual {
    position:absolute; right:5%; top:50%;
    transform:translateY(-50%);
    width:min(500px, 42vw);
    pointer-events:none;
  }
 
  .kpi-card {
    background: linear-gradient(135deg, rgba(3,41,80,0.85), rgba(10,37,64,0.9));
    border:1px solid var(--glass-border);
    backdrop-filter:blur(20px);
    border-radius:12px;
    padding:20px 24px;
    box-shadow:var(--glow-sm);
    position:relative;
    overflow:hidden;
  }
  .kpi-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:2px;
    background: linear-gradient(90deg, var(--cyan), var(--royal));
  }
 
  .kpi-main {
    width:100%; margin-bottom:16px;
    animation: slideUp .8s ease both;
  }
  .kpi-row { display:flex; gap:12px; margin-bottom:12px; }
  .kpi-small { flex:1; animation: slideUp .8s ease both; }
  .kpi-small:nth-child(2) { animation-delay:.1s; }
  .kpi-small:nth-child(3) { animation-delay:.2s; }
 
  @keyframes slideUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
  }
 
  .kpi-label {
    font-size:10px; letter-spacing:3px; text-transform:uppercase;
    color:rgba(234,251,255,0.5); margin-bottom:8px;
    font-family:'Space Mono',monospace;
  }
  .kpi-value {
    font-family:'Orbitron',sans-serif; font-size:26px; font-weight:700;
    color:var(--cyan);
  }
  .kpi-small .kpi-value { font-size:18px; }
  .kpi-change {
    display:inline-flex; align-items:center; gap:4px;
    font-size:12px; font-weight:600; letter-spacing:1px;
    color:#1aed7a; margin-top:4px;
  }
 
  /* Cycle cards */
  .cycles-section {
    position:relative; z-index:1;
    padding:100px 5%;
  }
  .section-header { text-align:center; margin-bottom:64px; }
  .section-eyebrow {
    font-family:'Space Mono',monospace; font-size:11px;
    letter-spacing:5px; text-transform:uppercase;
    color:var(--teal); margin-bottom:16px;
  }
  .section-title {
    font-family:'Orbitron',sans-serif; font-size:clamp(24px,3vw,42px);
    font-weight:700; color:var(--ice);
  }
  .section-title span {
    background: linear-gradient(90deg, var(--cyan), var(--aqua));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    background-clip:text;
  }
 
  .cycles-grid {
    display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:20px;
  }
 
  .cycle-card {
    background: linear-gradient(160deg, rgba(3,41,80,0.7), rgba(10,37,64,0.85));
    border:1px solid var(--glass-border);
    border-radius:12px; padding:32px 28px;
    backdrop-filter:blur(16px);
    position:relative; overflow:hidden;
    transition:all .4s ease; cursor:pointer;
  }
  .cycle-card:hover {
    transform:translateY(-6px);
    border-color:var(--cyan);
    box-shadow:var(--glow-md);
  }
  .cycle-card::before {
    content:''; position:absolute;
    top:0; left:0; right:0; height:3px;
  }
  .cycle-card.flash::before  { background:linear-gradient(90deg,#ff6b35,#ffd700); }
  .cycle-card.boost::before  { background:linear-gradient(90deg,var(--cyan),var(--teal)); }
  .cycle-card.pro::before    { background:linear-gradient(90deg,var(--royal),var(--cyan)); }
  .cycle-card.infinity::before{ background:linear-gradient(90deg,#9b59b6,var(--royal)); }
 
  .cycle-name {
    font-family:'Orbitron',sans-serif; font-size:13px; font-weight:700;
    letter-spacing:2px; color:var(--ice); margin-bottom:8px;
  }
  .cycle-days {
    font-size:11px; letter-spacing:3px; text-transform:uppercase;
    color:rgba(234,251,255,0.5); margin-bottom:24px;
    font-family:'Space Mono',monospace;
  }
  .cycle-rate {
    font-family:'Orbitron',sans-serif; font-size:40px; font-weight:900;
    line-height:1; margin-bottom:4px;
  }
  .cycle-card.flash .cycle-rate  { color:#ffd700; text-shadow:0 0 20px rgba(255,215,0,0.4); }
  .cycle-card.boost .cycle-rate  { color:var(--cyan); text-shadow:var(--glow-sm); }
  .cycle-card.pro .cycle-rate    { color:var(--aqua); text-shadow:0 0 20px rgba(29,167,219,0.4); }
  .cycle-card.infinity .cycle-rate{ color:#c77dff; text-shadow:0 0 20px rgba(199,125,255,0.4); }
 
  .cycle-unit { font-size:14px; color:rgba(234,251,255,0.5); margin-bottom:20px; }
  .cycle-return {
    display:flex; justify-content:space-between; align-items:center;
    padding:12px 0; border-top:1px solid rgba(28,231,237,0.15);
    font-size:13px; letter-spacing:1px;
  }
  .cycle-return .key { color:rgba(234,251,255,0.6); }
  .cycle-return .val { font-family:'Orbitron',sans-serif; font-weight:700; color:var(--cyan); }
 
  /* ── REFERRAL SECTION ── */
  .referral-section {
    position:relative; z-index:1;
    padding:100px 5%;
    background: linear-gradient(180deg, transparent, rgba(3,41,80,0.3), transparent);
  }
 
  .referral-visual {
    display:grid; grid-template-columns:1fr 1fr; gap:60px;
    align-items:center; max-width:1100px; margin:0 auto;
  }
 
  .referral-tree {
    display:flex; flex-direction:column; gap:16px;
  }
 
  .ref-level {
    display:flex; align-items:center; gap:16px;
    padding:18px 24px;
    background: linear-gradient(135deg, rgba(3,41,80,0.7), rgba(10,37,64,0.8));
    border:1px solid var(--glass-border);
    border-radius:8px; backdrop-filter:blur(16px);
    transition:all .3s;
  }
  .ref-level:hover {
    border-color:var(--cyan); transform:translateX(8px);
    box-shadow:var(--glow-sm);
  }
  .ref-level-badge {
    width:40px; height:40px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-family:'Orbitron',sans-serif; font-weight:700; font-size:13px;
    flex-shrink:0;
  }
  .ref-level:nth-child(1) .ref-level-badge { background:linear-gradient(135deg,var(--cyan),var(--teal)); color:#010F1E; }
  .ref-level:nth-child(2) .ref-level-badge { background:linear-gradient(135deg,var(--royal),var(--aqua)); color:#fff; }
  .ref-level:nth-child(3) .ref-level-badge { background:linear-gradient(135deg,#1F9AA5,#032950); color:var(--ice); border:1px solid var(--teal); }
 
  .ref-level-info .name { font-weight:700; font-size:16px; color:var(--ice); }
  .ref-level-info .desc { font-size:13px; color:rgba(234,251,255,0.6); margin-top:2px; }
  .ref-pct {
    margin-left:auto; font-family:'Orbitron',sans-serif;
    font-size:22px; font-weight:700; color:var(--cyan);
    text-shadow:var(--glow-sm);
  }
 
  .referral-info { }
  .referral-info h2 {
    font-family:'Orbitron',sans-serif; font-size:clamp(22px,2.5vw,36px);
    font-weight:700; color:var(--ice); margin-bottom:20px; line-height:1.2;
  }
  .referral-info h2 span {
    background:linear-gradient(90deg,var(--cyan),var(--aqua));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
  }
  .referral-info p { font-size:16px; line-height:1.7; color:rgba(234,251,255,0.7); margin-bottom:32px; }
 
  /* ── FEATURES GRID ── */
  .features-section {
    position:relative; z-index:1;
    padding:100px 5%;
  }
 
  .features-grid {
    display:grid; grid-template-columns:repeat(3,1fr); gap:20px;
    max-width:1100px; margin:0 auto;
  }
 
  .feature-card {
    padding:32px;
    background: linear-gradient(135deg, rgba(3,41,80,0.6), rgba(10,37,64,0.75));
    border:1px solid var(--glass-border);
    border-radius:12px; backdrop-filter:blur(16px);
    transition:all .4s; position:relative; overflow:hidden;
  }
  .feature-card:hover {
    transform:translateY(-4px);
    border-color:rgba(28,231,237,0.4);
    box-shadow:0 20px 60px rgba(28,231,237,0.12);
  }
  .feature-icon {
    width:52px; height:52px; border-radius:10px;
    background: linear-gradient(135deg, rgba(28,231,237,0.15), rgba(26,113,224,0.15));
    border:1px solid rgba(28,231,237,0.25);
    display:flex; align-items:center; justify-content:center;
    font-size:24px; margin-bottom:20px;
  }
  .feature-title {
    font-family:'Orbitron',sans-serif; font-size:14px; font-weight:700;
    letter-spacing:1px; color:var(--ice); margin-bottom:10px;
  }
  .feature-desc { font-size:14px; line-height:1.6; color:rgba(234,251,255,0.65); }
 
  /* ── DEPOSIT METHODS ── */
  .deposit-section {
    position:relative; z-index:1;
    padding:80px 5%;
    text-align:center;
  }
  .deposit-cards {
    display:flex; justify-content:center; gap:24px;
    margin-top:48px; flex-wrap:wrap;
  }
  .deposit-card {
    padding:32px 48px;
    background: linear-gradient(135deg, rgba(3,41,80,0.8), rgba(10,37,64,0.9));
    border:1px solid var(--glass-border);
    border-radius:12px; backdrop-filter:blur(20px);
    transition:all .3s;
  }
  .deposit-card:hover {
    border-color:var(--cyan); box-shadow:var(--glow-sm);
    transform:translateY(-4px);
  }
  .deposit-card .icon { font-size:40px; margin-bottom:12px; }
  .deposit-card .name {
    font-family:'Orbitron',sans-serif; font-size:15px;
    font-weight:700; color:var(--cyan); letter-spacing:2px;
  }
  .deposit-card .sub { font-size:12px; color:rgba(234,251,255,0.5); margin-top:4px; letter-spacing:2px; }
 
  /* ── CTA SECTION ── */
  .cta-section {
    position:relative; z-index:1;
    padding:120px 5%; text-align:center;
  }
  .cta-box {
    max-width:700px; margin:0 auto;
    padding:64px;
    background: linear-gradient(135deg, rgba(3,41,80,0.85), rgba(26,113,224,0.15));
    border:1px solid rgba(28,231,237,0.25);
    border-radius:16px; backdrop-filter:blur(24px);
    box-shadow:0 40px 100px rgba(28,231,237,0.08);
    position:relative; overflow:hidden;
  }
  .cta-box::before {
    content:''; position:absolute; top:0; left:0; right:0; height:2px;
    background: linear-gradient(90deg, transparent, var(--cyan), var(--royal), transparent);
  }
  .cta-box h2 {
    font-family:'Orbitron',sans-serif; font-size:clamp(24px,3vw,42px);
    font-weight:900; color:var(--ice); margin-bottom:20px;
  }
  .cta-box p { font-size:17px; color:rgba(234,251,255,0.7); margin-bottom:40px; line-height:1.7; }
  .cta-actions { display:flex; justify-content:center; gap:16px; flex-wrap:wrap; }
 
  /* ── FOOTER ── */
  footer {
    position:relative; z-index:1;
    border-top:1px solid rgba(28,231,237,0.1);
    padding:40px 5%;
    display:flex; justify-content:space-between; align-items:center;
    flex-wrap:wrap; gap:20px;
  }
  .footer-brand {
    font-family:'Orbitron',sans-serif; font-size:11px;
    letter-spacing:3px; color:var(--teal);
  }
  .footer-copy { font-size:12px; color:rgba(234,251,255,0.4); letter-spacing:1px; }
 
  /* ── TICKER ── */
  .ticker {
    background:rgba(3,41,80,0.7); border-top:1px solid var(--glass-border);
    border-bottom:1px solid var(--glass-border);
    backdrop-filter:blur(10px);
    overflow:hidden; height:36px; position:relative; z-index:1;
  }
  .ticker-inner {
    display:flex; gap:64px; align-items:center; height:100%;
    white-space:nowrap;
    animation:ticker 25s linear infinite;
  }
  @keyframes ticker {
    0%   { transform:translateX(0); }
    100% { transform:translateX(-50%); }
  }
  .ticker-item {
    font-family:'Space Mono',monospace; font-size:11px;
    color:rgba(234,251,255,0.7); display:flex; gap:10px; align-items:center;
  }
  .ticker-item .up   { color:#1aed7a; }
  .ticker-item .name { color:var(--cyan); font-weight:700; }
  .ticker-item .sep  { color:rgba(28,231,237,0.3); }
 
  @media (max-width:900px) {
    .hero-visual { display:none; }
    .features-grid { grid-template-columns:1fr 1fr; }
    .referral-visual { grid-template-columns:1fr; }
    .nav-links { display:none; }
    .cycles-grid { gap:16px; }
    .cycle-card { padding:24px 20px; }
    .hero-actions { flex-direction:column; align-items:flex-start; }
  }
  @media (max-width:600px) {
    .features-grid { grid-template-columns:1fr; }
    .hero-stat-row { flex-wrap:wrap; gap:24px; }
    .cta-box { padding:40px 24px; }
    .deposit-cards { flex-direction:column; align-items:center; }
    .cta-actions { flex-direction:column; align-items:center; }
    .footer { flex-direction:column; text-align:center; gap:16px; }
    .ticker-item { font-size:10px; }
  }
  @media (max-width:480px) {
    .hero { padding:100px 5% 60px; }
    .hero h1 { font-size:clamp(28px,6vw,48px); }
    .hero-desc { font-size:16px; margin-bottom:32px; }
    .cycles-grid { grid-template-columns:1fr; gap:12px; }
    .cycle-card { padding:20px 16px; }
    .cycle-rate { font-size:32px; }
    .features-grid { gap:16px; }
    .feature-card { padding:24px; }
    .referral-visual { gap:40px; }
    .ref-level { padding:16px 20px; }
    .cta-box { padding:32px 20px; }
    .cta-box h2 { font-size:28px; }
  }
</style>
</head>
<body>
 
<div class="orb orb1"></div>
<div class="orb orb2"></div>
<div class="orb orb3"></div>
 
<!-- NAV -->
<nav>
  <div class="nav-logo">
    <div class="logo-k">K</div>
    <div class="nav-brand">
      KINETIC
      <span>TRADING SYSTEM</span>
    </div>
  </div>
  <div class="nav-links">
    <a href="#home">Accueil</a>
    <a href="#cycles-section">Cycles</a>
    <a href="#referral-section">Parrainage</a>
    <a href="#features-section">Fonctionnalités</a>
    <a href="#cta-section">Support</a>
  </div>
  <div class="nav-cta">
    @guest
    <a href="{{ route('login') }}" class="btn-glass">Connexion</a>
    <a href="{{ route('register') }}" class="btn-primary">Commencer</a>
    @endguest
  </div>
</nav>
 
<!-- TICKER -->
<div style="margin-top:72px;">
<div class="ticker">
  <div class="ticker-inner">
    <div class="ticker-item"><span class="name">KINETIC FLASH</span><span class="sep">|</span><span class="up">+15%/jr</span><span class="sep">·</span>7J</div>
    <div class="ticker-item"><span class="name">KINETIC BOOST</span><span class="sep">|</span><span class="up">+8%/jr</span><span class="sep">·</span>15J</div>
    <div class="ticker-item"><span class="name">KINETIC PRO-PERF</span><span class="sep">|</span><span class="up">+6%/jr</span><span class="sep">·</span>30J</div>
    <div class="ticker-item"><span class="name">KINETIC INFINITY</span><span class="sep">|</span><span class="up">+5%/jr</span><span class="sep">·</span>60J</div>
    <div class="ticker-item"><span class="name">LUMICASH</span><span class="sep">|</span>Dépôt actif</div>
    <div class="ticker-item"><span class="name">BANCOBU eNOTI</span><span class="sep">|</span>Dépôt actif</div>
    <!-- duplicate for loop -->
    <div class="ticker-item"><span class="name">KINETIC FLASH</span><span class="sep">|</span><span class="up">+15%/jr</span><span class="sep">·</span>7J</div>
    <div class="ticker-item"><span class="name">KINETIC BOOST</span><span class="sep">|</span><span class="up">+8%/jr</span><span class="sep">·</span>15J</div>
    <div class="ticker-item"><span class="name">KINETIC PRO-PERF</span><span class="sep">|</span><span class="up">+6%/jr</span><span class="sep">·</span>30J</div>
    <div class="ticker-item"><span class="name">KINETIC INFINITY</span><span class="sep">|</span><span class="up">+5%/jr</span><span class="sep">·</span>60J</div>
    <div class="ticker-item"><span class="name">LUMICASH</span><span class="sep">|</span>Dépôt actif</div>
    <div class="ticker-item"><span class="name">BANCOBU eNOTI</span><span class="sep">|</span>Dépôt actif</div>
  </div>
</div>
</div>
 
<!-- HERO -->
<section class="hero" id="home">
  <div class="hero-content">
    <div class="hero-badge">⚡ Plateforme Elite — Cycle Quotidien Actif</div>
    <h1>
      <span class="line1">Investissement</span>
      <span class="line2">Intelligent &amp; Puissant</span>
    </h1>
    <p class="hero-desc">
      Rejoignez l'écosystème d'investissement le plus avancé du marché.
      Profits journaliers automatiques, cycles répétables et commissions multi-niveaux
      pour bâtir votre liberté financière.
    </p>
    <div class="hero-actions">
      @auth
        <a href="{{ route('dashboard') }}" class="btn-primary btn-xl">Accéder au Dashboard</a>
      @else
        <a href="{{ route('register') }}" class="btn-primary btn-xl">Ouvrir un Compte</a>
      @endauth
      <a href="#cycles-section" class="btn-glass btn-xl">Voir les Cycles</a>
    </div>
    <div class="hero-stat-row">
      <div class="hero-stat">
        <span class="val">4 Cycles</span>
        <span class="lbl">de trading actifs</span>
      </div>
      <div class="hero-stat">
        <span class="val">15%/jr</span>
        <span class="lbl">profit maximum</span>
      </div>
      <div class="hero-stat">
        <span class="val">3 Niveaux</span>
        <span class="lbl">de parrainage</span>
      </div>
    </div>
  </div>
 
  <!-- Hero KPI Visual -->
  <div class="hero-visual">
    <div class="kpi-card kpi-main">
      <div class="kpi-label">Profit cumulé aujourd'hui</div>
      <div class="kpi-value" id="live-profit">$24,891.50</div>
      <div class="kpi-change">▲ +12.4% depuis hier</div>
    </div>
    <div class="kpi-row">
      <div class="kpi-card kpi-small">
        <div class="kpi-label">Investisseurs actifs</div>
        <div class="kpi-value">1,247</div>
      </div>
      <div class="kpi-card kpi-small">
        <div class="kpi-label">Ref. payés</div>
        <div class="kpi-value">$3,490</div>
      </div>
    </div>
    <div class="kpi-row">
      <div class="kpi-card kpi-small">
        <div class="kpi-label">Retraits traités</div>
        <div class="kpi-value">98%</div>
      </div>
      <div class="kpi-card kpi-small">
        <div class="kpi-label">Tranches dispo</div>
        <div class="kpi-value">K-1→18</div>
      </div>
    </div>
  </div>
</section>
 
<!-- TRADING CYCLES -->
<section class="cycles-section" id="cycles-section">
  <div class="section-header">
    <div class="section-eyebrow">// Cycles de Trading</div>
    <h2 class="section-title">Choisissez votre <span>stratégie de rendement</span></h2>
  </div>
 
  <div class="cycles-grid">
    <div class="cycle-card flash">
      <div class="cycle-name">KINETIC FLASH</div>
      <div class="cycle-days">07 jours · Cycle rapide</div>
      <div class="cycle-rate">15%</div>
      <div class="cycle-unit">profit journalier</div>
      <div class="cycle-return">
        <span class="key">Retour total</span>
        <span class="val">105%</span>
      </div>
      @auth
        <a href="{{ route('dashboard') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @else
        <a href="{{ route('register') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @endauth
    </div>
 
    <div class="cycle-card boost">
      <div class="cycle-name">KINETIC BOOST</div>
      <div class="cycle-days">15 jours · Cycle intermédiaire</div>
      <div class="cycle-rate">8%</div>
      <div class="cycle-unit">profit journalier</div>
      <div class="cycle-return">
        <span class="key">Retour total</span>
        <span class="val">120%</span>
      </div>
      @auth
        <a href="{{ route('dashboard') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @else
        <a href="{{ route('register') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @endauth
    </div>
 
    <div class="cycle-card pro">
      <div class="cycle-name">KINETIC PRO-PERF</div>
      <div class="cycle-days">30 jours · Cycle pro</div>
      <div class="cycle-rate">6%</div>
      <div class="cycle-unit">profit journalier</div>
      <div class="cycle-return">
        <span class="key">Retour total</span>
        <span class="val">180%</span>
      </div>
      @auth
        <a href="{{ route('dashboard') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @else
        <a href="{{ route('register') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @endauth
    </div>
 
    <div class="cycle-card infinity">
      <div class="cycle-name">KINETIC INFINITY</div>
      <div class="cycle-days">60 jours · Cycle élite</div>
      <div class="cycle-rate">5%</div>
      <div class="cycle-unit">profit journalier</div>
      <div class="cycle-return">
        <span class="key">Retour total</span>
        <span class="val">300%</span>
      </div>
      @auth
        <a href="{{ route('dashboard') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @else
        <a href="{{ route('register') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @endauth
    </div>
  </div>
</section>
 
<!-- REFERRAL -->
<section class="referral-section" id="referral-section">
  <div class="referral-visual">
    <div class="referral-tree">
      <div class="section-eyebrow" style="margin-bottom:8px;">// Système de Parrainage</div>
      <div class="ref-level">
        <div class="ref-level-badge">L1</div>
        <div class="ref-level-info">
          <div class="name">Niveau 1 — Direct</div>
          <div class="desc">Sur dépôts et achats de votre filleul direct</div>
        </div>
        <div class="ref-pct">10%</div>
      </div>
      <div class="ref-level">
        <div class="ref-level-badge">L2</div>
        <div class="ref-level-info">
          <div class="name">Niveau 2 — Indirect</div>
          <div class="desc">Sur les dépôts des filleuls de vos filleuls</div>
        </div>
        <div class="ref-pct">3%</div>
      </div>
      <div class="ref-level">
        <div class="ref-level-badge">L3</div>
        <div class="ref-level-info">
          <div class="name">Niveau 3 — Réseau</div>
          <div class="desc">Commissions sur le troisième niveau de réseau</div>
        </div>
        <div class="ref-pct">1%</div>
      </div>
    </div>
 
    <div class="referral-info">
      <h2>Gagnez sur <span>3 niveaux</span> de réseau</h2>
      <p>
        Invitez des partenaires, construisez votre réseau et percevez des commissions
        automatiques sur chaque dépôt et achat de tranche réalisé par votre équipe.
        Votre solde de parrainage est retirable à tout moment.
      </p>
      <a href="{{ route('register') }}" class="btn-primary btn-xl">Obtenir mon lien de parrainage</a>
    </div>
  </div>
</section>
 
<!-- FEATURES -->
<section class="features-section" id="features-section">
  <div class="section-header">
    <div class="section-eyebrow">// Fonctionnalités Clés</div>
    <h2 class="section-title">Une plateforme <span>conçue pour l'élite</span></h2>
  </div>
 
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon">⚡</div>
      <div class="feature-title">Profits Journaliers Automatiques</div>
      <div class="feature-desc">Chaque contrat actif génère ses profits toutes les 24h. Chaque achat est un actif indépendant suivi individuellement.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon">🔁</div>
      <div class="feature-title">Achats Répétables Illimités</div>
      <div class="feature-desc">Investissez dans la même tranche ou le même cycle autant de fois que vous le souhaitez. Chaque achat est un contrat unique.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon">🛡️</div>
      <div class="feature-title">Sécurité Niveau Institutionnel</div>
      <div class="feature-desc">JWT + RBAC, hachage bcrypt, contrôle d'accès à deux rôles avec surveillance anti-fraude en temps réel.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon">📊</div>
      <div class="feature-title">Dashboard Temps Réel</div>
      <div class="feature-desc">KPIs animés, compteurs de profits en direct, historique de transactions complet et analytics de portefeuille.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon">👑</div>
      <div class="feature-title">Cockpit Admin Absolu</div>
      <div class="feature-desc">L'administrateur dispose d'un contrôle total : ajustements manuels, gestion de cycles, approbation des retraits.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon">💬</div>
      <div class="feature-title">Support Intégré</div>
      <div class="feature-desc">Chat interne user-admin, tickets de support, résolution de litiges financiers et notifications en temps réel.</div>
    </div>
  </div>
</section>
 
<!-- DEPOSIT METHODS -->
<section class="deposit-section">
  <div class="section-eyebrow">// Méthodes de Paiement</div>
  <h2 class="section-title" style="margin-top:12px;">Dépôts <span>sécurisés &amp; locaux</span></h2>
 
  <div class="deposit-cards">
    <div class="deposit-card">
      <div class="icon">💳</div>
      <div class="name">LUMICASH</div>
      <div class="sub">Mobile Money · Burundi</div>
    </div>
    <div class="deposit-card">
      <div class="icon">🏦</div>
      <div class="name">BANCOBU eNOTI</div>
      <div class="sub">Virement Bancaire · Burundi</div>
    </div>
  </div>
</section>
 
<!-- CTA -->
<section class="cta-section" id="cta-section">
  <div class="cta-box">
    <h2>Rejoignez KTS dès aujourd'hui</h2>
    <p>Créez votre compte en moins de 2 minutes et commencez à générer des profits dès demain avec le système de trading le plus puissant du marché.</p>
    <div class="cta-actions">
      @guest
      <a href="{{route('register')}}" class="btn-primary btn-xl">Créer mon compte</a>
      <a href="{{route('login')}}" class="btn-glass btn-xl">Se connecter</a>
      @endguest
    </div>
  </div>
</section>
 
<!-- FOOTER -->
<footer>
  <div class="footer-brand">© 2025 KINETIC TRADING SYSTEM</div>
  <div class="footer-copy">Plateforme d'investissement premium · Tous droits réservés</div>
</footer>
 
<script>
  // Live profit counter animation
  const el = document.getElementById('live-profit');
  let val = 24891.50;
  setInterval(() => {
    val += Math.random() * 0.8;
    el.textContent = '$' + val.toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}).replace(',','.');
  }, 2400);
 
  // Intersection Observer for fade-in
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if(e.isIntersecting) {
        e.target.style.opacity = '1';
        e.target.style.transform = 'translateY(0)';
      }
    });
  }, { threshold: 0.1 });
 
  document.querySelectorAll('.cycle-card, .feature-card, .ref-level, .deposit-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(24px)';
    el.style.transition = 'opacity .6s ease, transform .6s ease';
    observer.observe(el);
  });
</script>
</body>
</html>