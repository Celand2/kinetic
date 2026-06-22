<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @laravelPWA
    <title>@yield('title', 'KTS') — Kinetic Trading System</title>

    @vite(['resources/css/app.css','resources/css/style.css','resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Rajdhani:wght@300;400;500;600;700&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        /*
         * On surcharge le body pour les pages auth :
         * - le CSS global met display:flex (sidebar+main côte à côte)
         * - body::before/::after sont en position:fixed z-index:0 et couvrent
         *   tout contenu sans position explicite → on force un contexte d'empilement
         */
        body {
            display: block !important;   /* annule le flex global */
            min-height: 100vh;
        }

        /* Wrapper principal : au-dessus du body::before (z-index:0) */
        .kts-auth-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .kts-auth-brand {
            font-family: 'Orbitron', sans-serif;
            color: #c9a227;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-align: center;
            line-height: 1;
        }

        .kts-auth-tagline {
            font-family: 'Space Mono', monospace;
            font-size: 0.62rem;
            color: #4a5568;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            text-align: center;
            margin-top: 0.35rem;
            margin-bottom: 2rem;
        }

        /* Limiter la largeur du formulaire */
        .kts-auth-wrap > .card {
            width: 100%;
            max-width: 460px;
        }

        .kts-alert {
            display: flex; align-items: flex-start; gap: 0.6rem;
            padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1rem;
            font-size: 0.88rem; line-height: 1.5; max-width: 460px; width: 100%;
        }
        .kts-alert.success { background: rgba(26,237,170,0.1); border: 1px solid rgba(26,237,170,0.3); color: #1AEDAA; }
        .kts-alert.error   { background: rgba(255,77,109,0.1);  border: 1px solid rgba(255,77,109,0.3);  color: #FF4D6D; }
    </style>

    @stack('styles')
</head>
<body>

<div class="kts-auth-wrap">

    <div class="kts-auth-brand">KINETIC</div>
    <div class="kts-auth-tagline">{{ __('auth.tagline') }}</div>

    @if(session('success'))
        <div class="kts-alert success"><span>✅</span><span>{{ session('success') }}</span></div>
    @endif
    @if(session('error'))
        <div class="kts-alert error"><span>❌</span><span>{{ session('error') }}</span></div>
    @endif
    @if($errors->any())
        <div class="kts-alert error">
            <span>⚠️</span>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:3px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @yield('content')

</div>

@stack('scripts')
</body>
</html>
