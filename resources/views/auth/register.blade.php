 @extends('layouts.app')

@section('title', __('auth.title.register'))

@push('styles')
<style>
/* ── Overlay vidéo intro ─────────────────────────────────────────── */
#kts-intro-overlay {
    position: fixed; inset: 0; z-index: 99999;
    background: rgba(1,9,20,0.97);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 1rem;
    transition: opacity 0.55s ease;
}
#kts-intro-overlay.hiding {
    opacity: 0;
    pointer-events: none;
}
.kts-intro-header {
    font-family: 'Orbitron', sans-serif;
    color: #c9a227; font-size: 0.75rem;
    letter-spacing: 0.25em; text-transform: uppercase;
    margin-bottom: 1rem; text-align: center;
}
.kts-intro-player-wrap {
    width: 100%;
    max-width: 780px;
    aspect-ratio: 16/9;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(201,162,39,0.3);
    box-shadow: 0 0 60px rgba(201,162,39,0.12);
    background: #000;
    position: relative;
}
.kts-intro-player-wrap iframe {
    width: 100%; height: 100%;
    border: none; display: block;
}
.kts-intro-skip {
    margin-top: 1.5rem;
    display: flex; align-items: center; gap: 1.5rem;
    flex-wrap: wrap; justify-content: center;
}
#kts-skip-btn {
    padding: 10px 28px;
    background: rgba(201,162,39,0.1);
    border: 1px solid rgba(201,162,39,0.45);
    border-radius: 6px;
    color: #c9a227; font-family: 'Rajdhani', sans-serif;
    font-size: 0.95rem; font-weight: 600;
    letter-spacing: 0.08em; cursor: pointer;
    transition: all 0.2s;
}
#kts-skip-btn:hover {
    background: rgba(201,162,39,0.2);
    border-color: #c9a227;
}
.kts-intro-hint {
    font-size: 0.75rem; color: #4a5568;
    font-family: 'Space Mono', monospace;
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════
     OVERLAY VIDÉO INTRO
     Remplace YOUR_VIDEO_ID par ton ID YouTube
     Ex : https://youtube.com/watch?v=dQw4w9WgXcQ
          → ID = dQw4w9WgXcQ
════════════════════════════════════════════ --}}
<div id="kts-intro-overlay">
    <div class="kts-intro-header">{{ __('auth.intro_overlay_title') }}</div>

    <div class="kts-intro-player-wrap">
        <div id="kts-yt-player"></div>
    </div>

    <div class="kts-intro-skip">
        <button id="kts-skip-btn" onclick="skipIntroVideo()">
            {{ __('auth.intro_skip') }}
        </button>
        <span class="kts-intro-hint">{{ __('auth.intro_hint') }}</span>
    </div>
</div>
<div class="card">
    <div class="card-header">{{ __('auth.register') }}</div>
    <div style="text-align:right; margin-bottom:0.75rem;">
        <a href="{{ route('locale.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" style="color:#c9a227; font-size:0.9rem;">🌐 {{ __('auth.language') }}</a>
    </div>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="full_name">{{ __('auth.full_name') }}</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required value="{{ old('full_name') }}" placeholder="Jean Dupont">
            @error('full_name')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">{{ __('auth.email') }}</label>
            <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}" placeholder="example@email.com">
            @error('email')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="phone">{{ __('auth.phone') }}</label>
            <input type="tel" class="form-control" id="phone" name="phone" required value="{{ old('phone') }}" placeholder="+257 XX XXX XXX">
            @error('phone')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="referral_code">{{ __('auth.referral_code') }}</label>
            <input type="text" class="form-control" id="referral_code" name="referral_code" value="{{ old('referral_code', request('ref')) }}" placeholder="Ex : KTS-ABC12345">
            <span class="form-hint">{{ __('auth.referral_hint') }}</span>
            @error('referral_code')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="country">{{ __('auth.country') }}</label>
            <input type="text" class="form-control" id="country" name="country" required value="{{ old('country') }}" placeholder="Burundi">
            @error('country')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">{{ __('auth.password') }}</label>
            <input type="password" class="form-control" id="password" name="password" required placeholder="Min. 8 caractères">
            @error('password')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">{{ __('auth.password_confirmation') }}</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:0.5rem;">{{ __('auth.register') }}</button>

        <p style="text-align:center; margin-top:1.5rem; color:#b0bfd9; font-size:0.88rem;">
            {{ __('auth.already_account') }}
            <a href="{{ route('login') }}" style="color:#c9a227; font-weight:600;">{{ __('auth.signin') }}</a>
        </p>
    </form>
</div>
@endsection

@push('scripts')
<script>
/* ── YouTube IFrame API (chargé de façon asynchrone) ── */
(function() {
    var tag = document.createElement('script');
    tag.src = 'https://www.youtube.com/iframe_api';
    tag.async = true;
    document.head.appendChild(tag);
})();

var ktsYTPlayer;

function onYouTubeIframeAPIReady() {
    ktsYTPlayer = new YT.Player('kts-yt-player', {
        height: '100%',
        width: '100%',
        /* ↓↓↓ REMPLACE ICI PAR TON ID YOUTUBE ↓↓↓ */
        videoId: 'TdAzTE-Le80',
        /* ↑↑↑ exemple : 'dQw4w9WgXcQ' ↑↑↑ */
        playerVars: {
            autoplay: 0,       // 0 = ne pas lancer auto (navigateurs bloquent l'audio auto)
            rel: 0,            // pas de vidéos suggérées à la fin
            modestbranding: 1, // interface YouTube discrète
            color: 'white',
        },
        events: {
            onStateChange: function(e) {
                // YT.PlayerState.ENDED = 0
                if (e.data === 0) { skipIntroVideo(); }
            }
        }
    });
}

function skipIntroVideo() {
    var overlay = document.getElementById('kts-intro-overlay');
    overlay.classList.add('hiding');
    // Stopper la vidéo si elle tourne encore
    try { ktsYTPlayer.stopVideo(); } catch(e) {}
    setTimeout(function() { overlay.style.display = 'none'; }, 580);
}
</script>
@endpush
