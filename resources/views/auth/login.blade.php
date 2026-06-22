@extends('layouts.app')

@section('title', __('auth.title.login'))

@section('content')
<div class="card">
    <div class="card-header">{{ __('auth.login') }}</div>
    <div style="text-align:right; margin-bottom:0.75rem;">
        <a href="{{ route('locale.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" style="color:#c9a227; font-size:0.9rem;">🌐 {{ __('auth.language') }}</a>
    </div>

    @if(session('error'))
        <div class="form-feedback-error" style="margin-bottom:1rem; padding:0.75rem; background:rgba(220,53,69,0.1); border-radius:6px;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">{{ __('auth.email') }}</label>
            <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}" placeholder="example@email.com">
            @error('email')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">{{ __('auth.password') }}</label>
            <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
            @error('password')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:0.5rem;">{{ __('auth.signin') }}</button>

        <p style="text-align:center; margin-top:1rem; font-size:0.85rem;">
            <a href="{{ route('password.request') }}" style="color:#6b7a9a;">{{ __('auth.forgot_password') }} ?</a>
        </p>

        <p style="text-align:center; margin-top:0.75rem; color:#b0bfd9; font-size:0.88rem;">
            {{ __('auth.not_account') }}
            <a href="{{ route('register') }}" style="color:#c9a227; font-weight:600;">{{ __('auth.signup') }}</a>
        </p>
    </form>
</div>
@endsection
