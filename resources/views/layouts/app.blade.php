{{--
    ╔══════════════════════════════════════════════════════════════════╗
    ║  LAYOUT PRINCIPAL — KINETIC TRADING SYSTEM                      ║
    ║  Fichier : resources/views/layouts/app.blade.php                ║
    ║                                                                  ║
    ║  Design : Deep Ocean Tech — inspiré de la palette KTS           ║
    ║  Palette :                                                       ║
    ║    #032950 — Deep Ocean Blue (fond principal)                   ║
    ║    #1CE7ED — Electric Cyan (accent primaire, glow, actif)       ║
    ║    #1F9AA5 — Tech Teal (textes secondaires, borders)            ║
    ║    #1DA7DB — Digital Aqua (hover states, gradients)             ║
    ║    #1A71E0 — Royal Neon Blue (boutons, CTA)                     ║
    ║    #EAFBFF — Ice White (textes clairs)                          ║
    ║                                                                  ║
    ║  Utilisation :                                                   ║
    ║    @extends('layouts.app')                                      ║
    ║    @section('page-title', 'MON TITRE')                         ║
    ║    @section('page-subtitle', 'sous-titre')                      ║
    ║    @section('content') ... @endsection                          ║
    ╚══════════════════════════════════════════════════════════════════╝
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
 
    <title>@yield('title', 'KTS') — Kinetic Trading System</title>
 
    {{-- ── TYPOGRAPHIE ──────────────────────────────────────────────────── --}}
    {{-- Orbitron  : display font futuriste — titres, logos, chiffres clés --}}
    {{-- Rajdhani  : corps de texte — lisible, techy, moderne              --}}
    {{-- Space Mono: données, codes, références — monospace technique      --}}
    @VITE(['resources/css/app.css','resources/css/style.css' ,'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Rajdhani:wght@300;400;500;600;700&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
 
    
 
    {{-- Styles supplémentaires injectés par les pages enfants --}}
    @stack('styles')
</head>
 
<body>
 
{{-- ════════════════════════════════════════════════════════════════════
     SIDEBAR
     ════════════════════════════════════════════════════════════════════ --}}
<aside class="kts-sidebar">
 
    {{-- Logo --}}
    {{-- <div class="sidebar-logo">
        <div class="logo-emblem">K</div>
        <div class="logo-text-wrap">
            <span class="logo-brand">KINETIC</span>
            <span class="logo-tagline">Trading System</span>
        </div>
    </div>
 
    {{-- Utilisateur connecté --}}
    {{-- <div class="sidebar-user">
        <div class="user-avatar">{{ auth()->user()->initials }}</div>
        <div class="user-info">
            <div class="user-name">{{ auth()->user()->full_name }}</div>
            <div class="user-badge">
                <span class="user-badge-dot"></span>
                <span class="user-badge-text">Actif</span>
            </div>
        </div>
    </div>
 
    {{-- Solde rapide --}}
    {{-- <div class="sidebar-balance">
        <div class="balance-label">Solde Principal</div>
        <div class="balance-amount">
            <span>$</span>{{ number_format(auth()->user()->balance, 2) }}
        </div>
        <div class="balance-ref">
            <div class="balance-ref-item">
                🌐 Parr. :&nbsp;<span class="balance-ref-val">${{ number_format(auth()->user()->referral_balance, 2) }}</span>
            </div>
        </div>
    </div> --}} 

    {{-- Navigation principale --}}
    <nav class="nav-wrap">
 
        <div class="nav-group">
            <div class="nav-group-label">Principal</div>
 
            <a href="{{ route('dashboard') }}"
               {{-- class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"> --}}
                <span class="nav-icon">📊</span>
                <span class="nav-label-text">Dashboard</span>
            </a>
 
            <a href="{{ route('investments.index') }}"
               class="nav-link {{ request()->routeIs('investments.*') ? 'active' : '' }}">
                <span class="nav-icon">⚡</span>
                <span class="nav-label-text">Mes Contrats</span>
            </a>
        </div>
 
        <div class="nav-group">
            <div class="nav-group-label">Finance</div>
 
            {{-- <a href="{{ route('financial.deposit') }}"
               {{-- class="nav-link {{ request()->routeIs('financial.deposit*') ? 'active' : '' }}"> --}}
                {{-- <span class="nav-icon">💳</span>
                <span class="nav-label-text">Dépôt</span>
            </a> --}} --}}
 
            {{-- <a href="{{ route('financial.withdrawal') }}"
               class="nav-link {{ request()->routeIs('financial.withdrawal*') ? 'active' : '' }}">
                <span class="nav-icon">💸</span>
                <span class="nav-label-text">Retrait</span>
            </a>
  {{-- --}}
            {{-- <a href="{{ route('financial.transactions') }}"
               class="nav-link {{ request()->routeIs('financial.transactions') ? 'active' : '' }}">
                <span class="nav-icon">📋</span>
                <span class="nav-label-text">Transactions</span>
            </a> --}}
        </div>
  --}}
        <div class="nav-group">
            <div class="nav-group-label">Réseau</div>
 
            {{-- <a href="{{ route('user.referral.index') }}"
               class="nav-link {{ request()->routeIs('user.referral.*') ? 'active' : '' }}">
                <span class="nav-icon">🌐</span>
                <span class="nav-label-text">Parrainage</span>
            </a> --}}
        </div>
 
        <div class="nav-group">
            <div class="nav-group-label">Support</div>
 
            {{-- <a href="{{ route('messages.index') }}"
               class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                <span class="nav-icon">💬</span>
                <span class="nav-label-text">Messages</span>
 
                @php
                    $unread = auth()->user()->conversations()
                        ->where('unread_user_count', '>', 0)->count();
                @endphp
                @if($unread > 0)
                    <span class="nav-badge">{{ $unread }}</span>
                @endif
            </a> --}}
        </div>
 
    </nav>
 
    {{-- Déconnexion --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <span>🚪</span>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
 
</aside>
 
{{-- ════════════════════════════════════════════════════════════════════
     CONTENU PRINCIPAL
     ════════════════════════════════════════════════════════════════════ --}}
<div class="kts-main">
 
    {{-- Topbar --}}
    <header class="kts-topbar">
        <div class="topbar-left">
            <div class="page-title">@yield('page-title', 'DASHBOARD')</div>
            <div class="page-subtitle">@yield('page-subtitle', '// Vue d\'ensemble')</div>
        </div>
 
        <div class="topbar-right">
            {{-- Horloge temps réel --}}
            <div class="topbar-clock" id="kts-clock">00:00:00</div>
 
            {{-- Notifications --}}
            {{-- <a href="{{ route('user.messages.index') }}" class="topbar-icon-btn" title="Messages">
                💬
                @if(isset($unread) && $unread > 0)
                    <span class="notif-pip"></span>
                @endif
            </a> --}}
        </div>
    </header>
 
    {{-- Zone de contenu --}}
    <main class="kts-content">
 
        {{-- ── Alertes flash session ─────────────────────────────────────── --}}
        @if(session('success'))
            <div class="kts-alert success">
                <span class="kts-alert-icon">✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
 
        @if(session('error'))
            <div class="kts-alert error">
                <span class="kts-alert-icon">❌</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif
 
        @if(session('info'))
            <div class="kts-alert info">
                <span class="kts-alert-icon">ℹ️</span>
                <span>{{ session('info') }}</span>
            </div>
        @endif
 
        {{-- ── Erreurs de validation Laravel ───────────────────────────────── --}}
        @if($errors->any())
            <div class="kts-alert error">
                <span class="kts-alert-icon">⚠️</span>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:4px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
 
        {{-- ── Contenu de la page enfant ────────────────────────────────────── --}}
        @yield('content')
 
    </main>
</div>
 
{{-- ════════════════════════════════════════════════════════════════════
     SCRIPTS
     ════════════════════════════════════════════════════════════════════ --}}
<script>
    /* ── Horloge temps réel ─────────────────────────────────────────── */
    (function() {
        const el = document.getElementById('kts-clock');
        if (!el) return;
        function tick() {
            const now = new Date();
            const h   = String(now.getHours()).padStart(2, '0');
            const m   = String(now.getMinutes()).padStart(2, '0');
            const s   = String(now.getSeconds()).padStart(2, '0');
            el.textContent = `${h}:${m}:${s}`;
        }
        tick();
        setInterval(tick, 1000);
    })();
</script>
 
{{-- Scripts injectés par les pages enfants --}}
@stack('scripts')
 
</body>
<html>