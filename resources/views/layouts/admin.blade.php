<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin KTS') — Kinetic</title>

    @vite(['resources/css/app.css','resources/css/style.css','resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Rajdhani:wght@300;400;500;600;700&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { display: block !important; }

        /* ── TOPBAR ───────────────────────────────────────────────────── */
        .kts-topbar {
            position: sticky; top: 0; z-index: 200;
            height: 54px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1rem;
            background: rgba(10,14,28,0.97);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(201,162,39,0.25);
            box-shadow: 0 2px 20px rgba(0,0,0,0.5);
        }
        .topbar-left  { display: flex; flex-direction: row; align-items: center; gap: 0.75rem; }
        .topbar-right { display: flex; flex-direction: row; align-items: center; gap: 0.5rem; }

        .kts-hamburger {
            width: 38px; height: 38px;
            background: rgba(201,162,39,0.06); border: 1px solid rgba(201,162,39,0.25);
            border-radius: 8px; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 5px; cursor: pointer; padding: 0; flex-shrink: 0; transition: background 0.15s;
        }
        .kts-hamburger:hover { background: rgba(201,162,39,0.12); }
        .kts-hamburger span { display: block; width: 16px; height: 2px; background: #c9a227; border-radius: 2px; transition: all 0.25s; }
        .kts-hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .kts-hamburger.open span:nth-child(2) { opacity: 0; }
        .kts-hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .topbar-brand { font-family: 'Orbitron', sans-serif; color: #c9a227; font-size: 1.1rem; font-weight: 700; letter-spacing: 0.08em; text-decoration: none; }
        .topbar-admin-tag { font-size: 0.6rem; background: rgba(201,162,39,0.12); border: 1px solid rgba(201,162,39,0.28); color: #c9a227; padding: 2px 7px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; }
        .topbar-clock { font-family: 'Space Mono', monospace; color: #3d4a62; font-size: 0.65rem; }

        .topbar-icon-btn {
            position: relative; width: 38px; height: 38px;
            background: rgba(201,162,39,0.06); border: 1px solid rgba(201,162,39,0.2);
            color: #c9a227; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; text-decoration: none; flex-shrink: 0; transition: background 0.15s;
        }
        .topbar-icon-btn:hover { background: rgba(201,162,39,0.14); }
        .notif-badge { position: absolute; top: -5px; right: -5px; background: #ef5350; color: #fff; font-size: 0.6rem; font-weight: 700; min-width: 17px; height: 17px; border-radius: 9px; display: flex; align-items: center; justify-content: center; padding: 0 3px; }

        /* ── OVERLAY & DRAWER ─────────────────────────────────────────── */
        .kts-nav-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 300; }
        .kts-nav-overlay.open { display: block; }

        .kts-nav-drawer {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: min(300px, 88vw); background: #08091a;
            border-right: 1px solid rgba(201,162,39,0.15); z-index: 301;
            transform: translateX(-100%); transition: transform 0.28s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column; overflow-y: auto;
        }
        .kts-nav-drawer.open { transform: translateX(0); }

        .drawer-head { padding: 1.25rem; border-bottom: 1px solid rgba(201,162,39,0.1); }
        .drawer-brand { font-family: 'Orbitron', sans-serif; color: #c9a227; font-size: 1.1rem; font-weight: 700; letter-spacing: 0.06em; }
        .drawer-admin-tag { display: inline-block; margin-top: 4px; font-size: 0.6rem; background: rgba(201,162,39,0.1); border: 1px solid rgba(201,162,39,0.22); color: #c9a227; padding: 2px 7px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.1em; }
        .drawer-user { margin-top: 0.55rem; font-size: 0.8rem; color: #8a9bb5; }
        .drawer-user strong { color: #d4c5a9; display: block; font-size: 0.88rem; margin-bottom: 1px; }

        .drawer-nav { flex: 1; padding: 0.5rem 0; }
        .drawer-section { padding: 0.6rem 1.25rem 0.15rem; font-size: 0.58rem; color: #2a3040; text-transform: uppercase; letter-spacing: 0.12em; font-weight: 700; }
        .drawer-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.7rem 1.25rem; color: #6b7a9a; text-decoration: none; font-size: 0.88rem; border-right: 3px solid transparent; transition: all 0.15s; }
        .drawer-link:hover { background: rgba(201,162,39,0.05); color: #c9a227; }
        .drawer-link.active { background: rgba(201,162,39,0.09); color: #c9a227; border-right-color: #c9a227; }
        .drawer-link .dl-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
        .dl-badge { margin-left: auto; background: #ef5350; color: #fff; font-size: 0.6rem; font-weight: 700; min-width: 18px; height: 18px; border-radius: 9px; display: flex; align-items: center; justify-content: center; padding: 0 4px; }
        .dl-badge-gold { margin-left: auto; background: rgba(201,162,39,0.12); color: #c9a227; border: 1px solid rgba(201,162,39,0.28); font-size: 0.6rem; font-weight: 700; min-width: 18px; height: 18px; border-radius: 9px; display: flex; align-items: center; justify-content: center; padding: 0 4px; }

        .drawer-footer { padding: 1rem 1.25rem; border-top: 1px solid rgba(201,162,39,0.1); }
        .drawer-footer form button { width: 100%; background: transparent; border: 1px solid rgba(201,162,39,0.18); color: #6b7a9a; padding: 0.65rem; border-radius: 8px; font-size: 0.85rem; cursor: pointer; transition: all 0.15s; }
        .drawer-footer form button:hover { background: rgba(201,162,39,0.07); color: #c9a227; }

        /* ── CONTENU ADMIN (full-width) ───────────────────────────────── */
        .kts-main { position: relative; z-index: 1; min-height: calc(100vh - 54px); }
        .kts-content { padding: 1.25rem 1rem 4rem; max-width: 1100px; margin: 0 auto; width: 100%; overflow-x: hidden; }
        /* ── Bouton retour ────────────────────────────────────────────── */
        .kts-back-row { margin-bottom: 1.25rem; }
        .kts-back-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #8a9bb5; font-size: 0.82rem; text-decoration: none; transition: all 0.15s; }
        .kts-back-btn:hover { color: #c9a227; border-color: rgba(201,162,39,0.3); background: rgba(201,162,39,0.05); }

        /* ── Boutons action ───────────────────────────────────────────── */
        .kts-btn { display: inline-flex; align-items: center; gap: 4px; padding: 6px 14px; background: rgba(201,162,39,0.08); border: 1px solid rgba(201,162,39,0.28); border-radius: 7px; color: #c9a227; font-size: 0.8rem; font-weight: 600; text-decoration: none; cursor: pointer; white-space: nowrap; transition: all 0.15s; }
        .kts-btn:hover { background: rgba(201,162,39,0.18); border-color: rgba(201,162,39,0.5); }
        .kts-btn-sm { padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; }
        .kts-btn-danger { background: rgba(239,83,80,0.08); border-color: rgba(239,83,80,0.28); color: #ef5350; }
        .kts-btn-danger:hover { background: rgba(239,83,80,0.18); border-color: rgba(239,83,80,0.5); }
        .kts-btn-success { background: rgba(129,199,132,0.08); border-color: rgba(129,199,132,0.28); color: #81c784; }
        .kts-btn-success:hover { background: rgba(129,199,132,0.18); }

        /* Alertes */
        .kts-alert { display: flex; align-items: flex-start; gap: 0.6rem; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.88rem; line-height: 1.5; }
        .kts-alert.success { background: rgba(129,199,132,0.08); border: 1px solid rgba(129,199,132,0.28); color: #81c784; }
        .kts-alert.error   { background: rgba(239,83,80,0.08);   border: 1px solid rgba(239,83,80,0.28);   color: #ef5350; }
        .kts-alert.info    { background: rgba(122,156,198,0.08); border: 1px solid rgba(122,156,198,0.28); color: #7a9cc6; }
        /* ── SCROLL TABLES ─────────────────────────────────────────── */
.card { overflow-x: auto; }

table {
    width: 100%;
    min-width: 600px;
    border-collapse: collapse;
}

/* Scroll vertical sur les grandes listes */
.kts-main {
    overflow-y: auto;
    overflow-x: hidden;
}

/* Scrollbar stylée */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: rgba(255,255,255,0.03); }
::-webkit-scrollbar-thumb { background: rgba(201,162,39,0.3); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: rgba(201,162,39,0.55); }
    </style>
    @stack('styles')
</head>
<body>

@php
    $admin      = auth()->user();
    $unreadNotif = $admin ? \App\Models\Notification::where('user_id',$admin->id)->where('is_read',false)->count() : 0;
    $unreadMsg  = \App\Models\Conversation::where('unread_admin_count','>',0)->count();
    $pendingTxn = \App\Models\Transaction::where('status','pending')->whereIn('type',['deposit','withdrawal'])->count();
@endphp

{{-- TOPBAR --}}
<header class="kts-topbar">
    <div class="topbar-left">
        <button class="kts-hamburger" id="kts-hamburger" onclick="toggleDrawer()">
            <span></span><span></span><span></span>
        </button>
        <a href="{{ route('admin.dashboard') }}" class="topbar-brand">KTS</a>
        <span class="topbar-admin-tag">Admin</span>
    </div>
    <div class="topbar-right">
        <span class="topbar-clock" id="kts-clock">00:00:00</span>
        <a href="{{ route('admin.notifications') }}" class="topbar-icon-btn" title="Notifications">
            🔔@if($unreadNotif > 0)<span class="notif-badge">{{ $unreadNotif > 9 ? '9+' : $unreadNotif }}</span>@endif
        </a>
        <a href="{{ route('admin.messages.index') }}" class="topbar-icon-btn" title="Messages">
            💬@if($unreadMsg > 0)<span class="notif-badge">{{ $unreadMsg > 9 ? '9+' : $unreadMsg }}</span>@endif
        </a>
    </div>
</header>

{{-- OVERLAY --}}
<div class="kts-nav-overlay" id="kts-overlay" onclick="closeDrawer()"></div>

{{-- DRAWER --}}
<nav class="kts-nav-drawer" id="kts-drawer">
    @auth
    <div class="drawer-head">
        <div class="drawer-brand">KINETIC</div>
        <span class="drawer-admin-tag">Super Admin</span>
        <div class="drawer-user"><strong>{{ $admin->full_name }}</strong>{{ $admin->email }}</div>
    </div>
    <div class="drawer-nav">
        <div class="drawer-section">Dashboard</div>
        <a href="{{ route('admin.dashboard') }}" class="drawer-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span class="dl-icon">📊</span>Dashboard</a>
        <a href="{{ route('admin.users.index') }}" class="drawer-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><span class="dl-icon">👥</span>Utilisateurs</a>

        <div class="drawer-section">Finance</div>
        <a href="{{ route('admin.finance.transactions') }}" class="drawer-link {{ request()->routeIs('admin.finance.transactions') ? 'active' : '' }}">
            <span class="dl-icon">💳</span>Transactions
            @if($pendingTxn > 0)<span class="dl-badge">{{ $pendingTxn }}</span>@endif
        </a>
        <a href="{{ route('admin.finance.withdrawals') }}" class="drawer-link {{ request()->routeIs('admin.finance.withdrawals*') ? 'active' : '' }}">
            <span class="dl-icon">💸</span>Retraits en attente
            @php $pendingW = \App\Models\Transaction::where('type','withdrawal')->where('status','pending')->count(); @endphp
            @if($pendingW > 0)<span class="dl-badge">{{ $pendingW }}</span>@endif
        </a>

        <div class="drawer-section">Investissements</div>
        <a href="{{ route('admin.cycles') }}" class="drawer-link {{ request()->routeIs('admin.cycles*') || request()->routeIs('admin.tranches*') ? 'active' : '' }}"><span class="dl-icon">⚡</span>Cycles & Tranches</a>
        <a href="{{ route('admin.investments') }}" class="drawer-link {{ request()->routeIs('admin.investments*') ? 'active' : '' }}"><span class="dl-icon">📈</span>Investissements</a>

        <div class="drawer-section">Communication</div>
        <a href="{{ route('admin.messages.index') }}" class="drawer-link {{ request()->routeIs('admin.messages.index') ? 'active' : '' }}">
            <span class="dl-icon">💬</span>Messages
            @if($unreadMsg > 0)<span class="dl-badge">{{ $unreadMsg }}</span>@endif
        </a>
        <a href="{{ route('admin.messages.broadcast') }}" class="drawer-link {{ request()->routeIs('admin.messages.broadcast*') ? 'active' : '' }}"><span class="dl-icon">📢</span>Diffuser</a>
        <a href="{{ route('admin.notifications.create') }}" class="drawer-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
            <span class="dl-icon">🔔</span>Notifications
            @if($unreadNotif > 0)<span class="dl-badge-gold">{{ $unreadNotif }}</span>@endif
        </a>

        <div class="drawer-section">Paramètres</div>
        <a href="{{ route('admin.payment-methods.index') }}" class="drawer-link {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}"><span class="dl-icon">💰</span>Moyens de paiement</a>
        <a href="{{ route('admin.exchange-rates.index') }}" class="drawer-link {{ request()->routeIs('admin.exchange-rates.*') ? 'active' : '' }}"><span class="dl-icon">💱</span>Taux de change</a>
        <a href="{{ route('admin.bonus-codes.index') }}" class="drawer-link {{ request()->routeIs('admin.bonus-codes.*') ? 'active' : '' }}"><span class="dl-icon">🎁</span>Codes Bonus</a>
    </div>
    <div class="drawer-footer">
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">🚪 Déconnexion</button></form>
    </div>
    @endauth
</nav>

{{-- CONTENU --}}
<div class="kts-main">
    <main class="kts-content">
        @hasSection('back')
            <div class="kts-back-row">@yield('back')</div>
        @endif

        @if(session('success'))<div class="kts-alert success"><span>✅</span><span>{{ session('success') }}</span></div>@endif
        @if(session('error'))<div class="kts-alert error"><span>❌</span><span>{{ session('error') }}</span></div>@endif
        @if(session('info'))<div class="kts-alert info"><span>ℹ️</span><span>{{ session('info') }}</span></div>@endif
        @if($errors->any())
            <div class="kts-alert error"><span>⚠️</span><ul style="list-style:none;padding:0;margin:0;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        @yield('content')
    </main>
</div>

<script>
(function(){
    const el=document.getElementById('kts-clock');
    if(!el)return;
    function t(){const n=new Date();el.textContent=[n.getHours(),n.getMinutes(),n.getSeconds()].map(v=>String(v).padStart(2,'0')).join(':');}
    t();setInterval(t,1000);
})();
function toggleDrawer(){
    const d=document.getElementById('kts-drawer'),o=document.getElementById('kts-overlay'),b=document.getElementById('kts-hamburger');
    const open=d.classList.toggle('open');o.classList.toggle('open',open);b.classList.toggle('open',open);
    document.body.style.overflow=open?'hidden':'';
}
function closeDrawer(){
    document.getElementById('kts-drawer').classList.remove('open');
    document.getElementById('kts-overlay').classList.remove('open');
    document.getElementById('kts-hamburger')?.classList.remove('open');
    document.body.style.overflow='';
}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeDrawer();});
</script>
@stack('scripts')
</body>
</html>
