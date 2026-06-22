<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KTS') — Kinetic</title>

    @vite(['resources/css/app.css','resources/css/style.css','resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Rajdhani:wght@300;400;500;600;700&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── body : override le flex global du style.css ──────────────── */
        body { display: block !important; }

        /* ── TOPBAR ───────────────────────────────────────────────────── */
        .kts-topbar {
            position: sticky;
            top: 0;
            z-index: 200;                          /* au-dessus du body::before */
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            background: rgba(11,15,26,0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(201,162,39,0.2);
            box-shadow: 0 2px 20px rgba(0,0,0,0.4);
        }

        .topbar-left  { display: flex; flex-direction: row; align-items: center; gap: 0.75rem; }
        .topbar-right { display: flex; flex-direction: row; align-items: center; gap: 0.5rem; }

        /* Hamburger */
        .kts-hamburger {
            width: 38px; height: 38px;
            background: rgba(201,162,39,0.06);
            border: 1px solid rgba(201,162,39,0.25);
            border-radius: 8px;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 5px; cursor: pointer; padding: 0; flex-shrink: 0;
            transition: background 0.15s;
        }
        .kts-hamburger:hover { background: rgba(201,162,39,0.12); }
        .kts-hamburger span {
            display: block; width: 16px; height: 2px;
            background: #c9a227; border-radius: 2px; transition: all 0.25s;
        }
        .kts-hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .kts-hamburger.open span:nth-child(2) { opacity: 0; }
        .kts-hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .topbar-brand {
            font-family: 'Orbitron', sans-serif;
            color: #c9a227; font-size: 1.1rem; font-weight: 700;
            letter-spacing: 0.08em; text-decoration: none;
        }
        .topbar-clock {
            font-family: 'Space Mono', monospace;
            color: #3d4a62; font-size: 0.65rem;
        }

        /* Icônes topbar */
        .topbar-icon-btn {
            position: relative;
            width: 38px; height: 38px;
            background: rgba(201,162,39,0.06);
            border: 1px solid rgba(201,162,39,0.2);
            color: #c9a227; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; text-decoration: none; flex-shrink: 0;
            transition: background 0.15s;
        }
        .topbar-icon-btn:hover { background: rgba(201,162,39,0.14); }
        .notif-badge {
            position: absolute; top: -5px; right: -5px;
            background: #ef5350; color: #fff;
            font-size: 0.6rem; font-weight: 700;
            min-width: 17px; height: 17px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center; padding: 0 3px;
        }

        /* ── OVERLAY ──────────────────────────────────────────────────── */
        .kts-nav-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.6); z-index: 300;
        }
        .kts-nav-overlay.open { display: block; }

        /* ── DRAWER ───────────────────────────────────────────────────── */
        .kts-nav-drawer {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: min(300px, 88vw);
            background: #0a0e1c;
            border-right: 1px solid rgba(201,162,39,0.15);
            z-index: 301;
            transform: translateX(-100%);
            transition: transform 0.28s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column; overflow-y: auto;
        }
        .kts-nav-drawer.open { transform: translateX(0); }

        .drawer-head {
            padding: 1.25rem; border-bottom: 1px solid rgba(201,162,39,0.1);
        }
        .drawer-brand {
            font-family: 'Orbitron', sans-serif;
            color: #c9a227; font-size: 1.15rem; font-weight: 700; letter-spacing: 0.06em;
        }
        .drawer-user { margin-top: 0.55rem; font-size: 0.8rem; color: #8a9bb5; }
        .drawer-user strong { color: #d4c5a9; display: block; font-size: 0.88rem; margin-bottom: 1px; }

        .drawer-balance {
            margin-top: 0.75rem; padding: 0.6rem 0.75rem;
            background: rgba(201,162,39,0.06); border: 1px solid rgba(201,162,39,0.15); border-radius: 8px;
        }
        .drawer-balance .bal-label { font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.08em; color: #4a5568; margin-bottom: 2px; }
        .drawer-balance .bal-val { font-family: 'Space Mono', monospace; color: #81c784; font-size: 1rem; font-weight: 700; }

        .drawer-nav { flex: 1; padding: 0.5rem 0; }
        .drawer-section {
            padding: 0.6rem 1.25rem 0.15rem;
            font-size: 0.6rem; color: #2d3748;
            text-transform: uppercase; letter-spacing: 0.12em; font-weight: 700;
        }
        .drawer-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.72rem 1.25rem; color: #6b7a9a;
            text-decoration: none; font-size: 0.88rem;
            border-right: 3px solid transparent;
            transition: all 0.15s;
        }
        .drawer-link:hover { background: rgba(201,162,39,0.05); color: #c9a227; }
        .drawer-link.active { background: rgba(201,162,39,0.09); color: #c9a227; border-right-color: #c9a227; }
        .drawer-link .dl-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
        .dl-badge { margin-left: auto; background: #ef5350; color: #fff; font-size: 0.62rem; font-weight: 700; min-width: 18px; height: 18px; border-radius: 9px; display: flex; align-items: center; justify-content: center; padding: 0 4px; }
        .dl-badge-gold { margin-left: auto; background: rgba(201,162,39,0.15); color: #c9a227; border: 1px solid rgba(201,162,39,0.3); font-size: 0.62rem; font-weight: 700; min-width: 18px; height: 18px; border-radius: 9px; display: flex; align-items: center; justify-content: center; padding: 0 4px; }

        .drawer-footer { padding: 1rem 1.25rem; border-top: 1px solid rgba(201,162,39,0.1); }
        .drawer-footer form button { width: 100%; background: transparent; border: 1px solid rgba(201,162,39,0.2); color: #6b7a9a; padding: 0.65rem; border-radius: 8px; font-size: 0.85rem; cursor: pointer; transition: all 0.15s; }
        .drawer-footer form button:hover { background: rgba(201,162,39,0.07); color: #c9a227; }

        /* ── CONTENU ──────────────────────────────────────────────────── */
        .kts-main {
            position: relative;
            z-index: 1;                            /* au-dessus du body::before */
            min-height: calc(100vh - 54px);
        }
        .kts-content {
            padding: 1.25rem 1rem 4rem;
            max-width: 540px;
            margin: 0 auto;
            width: 100%;
        }

        /* ── Bouton retour ────────────────────────────────────────────── */
        .kts-back-row { margin-bottom: 1rem; }
        .kts-back-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 14px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px; color: #8a9bb5;
            font-size: 0.82rem; text-decoration: none;
            transition: all 0.15s;
        }
        .kts-back-btn:hover { color: #c9a227; border-color: rgba(201,162,39,0.3); background: rgba(201,162,39,0.05); }

        /* ── Boutons action (petits, dans les tableaux/listes) ────────── */
        .kts-btn {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 6px 14px;
            background: rgba(201,162,39,0.08);
            border: 1px solid rgba(201,162,39,0.28);
            border-radius: 7px; color: #c9a227;
            font-size: 0.8rem; font-weight: 600;
            text-decoration: none; cursor: pointer; white-space: nowrap;
            transition: all 0.15s;
        }
        .kts-btn:hover { background: rgba(201,162,39,0.18); border-color: rgba(201,162,39,0.5); }
        .kts-btn-sm { padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; }
        .kts-btn-danger { background: rgba(239,83,80,0.08); border-color: rgba(239,83,80,0.3); color: #ef5350; }
        .kts-btn-danger:hover { background: rgba(239,83,80,0.18); border-color: rgba(239,83,80,0.5); }
        .kts-btn-success { background: rgba(129,199,132,0.08); border-color: rgba(129,199,132,0.3); color: #81c784; }
        .kts-btn-success:hover { background: rgba(129,199,132,0.18); }

        /* Alertes */
        .kts-alert { display: flex; align-items: flex-start; gap: 0.6rem; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.88rem; line-height: 1.5; }
        .kts-alert.success { background: rgba(129,199,132,0.08); border: 1px solid rgba(129,199,132,0.28); color: #81c784; }
        .kts-alert.error   { background: rgba(239,83,80,0.08);   border: 1px solid rgba(239,83,80,0.28);   color: #ef5350; }
        .kts-alert.info    { background: rgba(122,156,198,0.08); border: 1px solid rgba(122,156,198,0.28); color: #7a9cc6; }
    </style>
    @stack('styles')
</head>
<body>

@php
    $user        = auth()->user();
    $unreadNotif = $user ? \App\Models\Notification::where('user_id',$user->id)->where('is_read',false)->count() : 0;
    $unreadMsg   = $user ? \App\Models\Conversation::where('user_id',$user->id)->where('unread_user_count','>',0)->count() : 0;
    // Solde en devise locale pour le drawer
    if ($user) {
        $drawerCurrency = $user->preferred_currency ?? 'USD';
        $drawerRate     = (float) \App\Models\ExchangeRate::rate($drawerCurrency);
        $drawerBalance  = $drawerCurrency === 'USD'
            ? '$' . number_format($user->balance, 2)
            : number_format(round((float)$user->balance * $drawerRate), 0, ',', ' ') . ' ' . $drawerCurrency;
    } else {
        $drawerCurrency = 'USD';
        $drawerBalance  = '$0.00';
    }
@endphp

{{-- TOPBAR --}}
<header class="kts-topbar">
    <div class="topbar-left">
        <button class="kts-hamburger" id="kts-hamburger" onclick="toggleDrawer()">
            <span></span><span></span><span></span>
        </button>
        <a href="{{ route('dashboard') }}" class="topbar-brand">KTS</a>
    </div>
    <div class="topbar-right">
        <span class="topbar-clock" id="kts-clock">00:00:00</span>
        <a href="{{ route('locale.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" class="topbar-icon-btn" title="{{ __('navigation.language') }}">
            {{ strtoupper(app()->getLocale()) }}
        </a>
        <a href="{{ route('notifications.index') }}" class="topbar-icon-btn" title="{{ __('navigation.notifications') }}">
            🔔@if($unreadNotif > 0)<span class="notif-badge">{{ $unreadNotif > 9 ? '9+' : $unreadNotif }}</span>@endif
        </a>
        <a href="{{ route('messages.index') }}" class="topbar-icon-btn" title="{{ __('navigation.messages') }}">
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
        <div class="drawer-user"><strong>{{ $user->full_name }}</strong>{{ $user->email }}</div>
        <div class="drawer-balance">
            <div class="bal-label">{{ __('navigation.balance') }} · {{ $drawerCurrency }}</div>
            <div class="bal-val">{{ $drawerBalance }}</div>
            @if($drawerCurrency !== 'USD')
                <div style="font-size:0.6rem; color:#2d3748; margin-top:2px;">${{ number_format($user->balance, 2) }} USD</div>
            @endif
        </div>
    </div>
    <div class="drawer-nav">
        <div class="drawer-section">{{ __('navigation.main') }}</div>
        <a href="{{ route('dashboard') }}" class="drawer-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="dl-icon">📊</span>{{ __('navigation.dashboard') }}</a>
        <a href="{{ route('investments.index') }}" class="drawer-link {{ request()->routeIs('investments.*') ? 'active' : '' }}"><span class="dl-icon">⚡</span>{{ __('navigation.my_contracts') }}</a>

        <div class="drawer-section">{{ __('navigation.finance') }}</div>
        <a href="{{ route('transactions.deposit') }}" class="drawer-link {{ request()->routeIs('transactions.deposit*') ? 'active' : '' }}"><span class="dl-icon">💳</span>{{ __('navigation.deposit') }}</a>
        <a href="{{ route('transactions.withdraw') }}" class="drawer-link {{ request()->routeIs('transactions.withdraw*') ? 'active' : '' }}"><span class="dl-icon">💸</span>{{ __('navigation.withdrawal') }}</a>
        <a href="{{ route('transactions.index') }}" class="drawer-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}"><span class="dl-icon">📋</span>{{ __('navigation.history') }}</a>

        <div class="drawer-section">{{ __('navigation.network') }}</div>
        <a href="{{ route('referral.dashboard') }}" class="drawer-link {{ request()->routeIs('referral.*') ? 'active' : '' }}"><span class="dl-icon">🌐</span>{{ __('navigation.referral') }}</a>

        <div class="drawer-section">{{ __('navigation.support') }}</div>
        <a href="{{ route('messages.index') }}" class="drawer-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
            <span class="dl-icon">💬</span>{{ __('navigation.messages') }}
            @if($unreadMsg > 0)<span class="dl-badge">{{ $unreadMsg }}</span>@endif
        </a>
        <a href="{{ route('notifications.index') }}" class="drawer-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <span class="dl-icon">🔔</span>{{ __('navigation.notifications') }}
            @if($unreadNotif > 0)<span class="dl-badge-gold">{{ $unreadNotif }}</span>@endif
        </a>
        <div class="drawer-section">{{ __('navigation.language') }}</div>
        <a href="{{ route('locale.switch', 'fr') }}" class="drawer-link {{ app()->getLocale() === 'fr' ? 'active' : '' }}"><span class="dl-icon">FR</span>Français</a>
        <a href="{{ route('locale.switch', 'en') }}" class="drawer-link {{ app()->getLocale() === 'en' ? 'active' : '' }}"><span class="dl-icon">EN</span>English</a>
    </div>
    <div class="drawer-footer">
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">🚪 {{ __('navigation.logout') }}</button></form>
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
