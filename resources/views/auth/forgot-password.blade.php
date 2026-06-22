@extends('layouts.app')
@section('title', __('auth.title.forgot_password'))

@section('content')
<div class="card">
    <div class="card-header">{{ __('auth.forgot_password') }}</div>
    <div style="text-align:right; margin-bottom:0.75rem;">
        <a href="{{ route('locale.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" style="color:#c9a227; font-size:0.9rem;">🌐 {{ __('auth.language') }}</a>
    </div>

    @if(session('status'))
        <div style="margin-bottom:1rem; padding:0.75rem 1rem; background:rgba(129,199,132,0.1); border:1px solid rgba(129,199,132,0.3); border-radius:6px; color:#81c784; font-size:0.9rem;">
            {{ session('status') }}
        </div>
    @endif

    <p style="color:#b0bfd9; font-size:0.88rem; margin-bottom:1.25rem;">
        {{ __('auth.forgot_password_desc') }}
    </p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">{{ __('auth.email') }}</label>
            <input type="email" class="form-control" id="email" name="email" required
                   value="{{ old('email') }}" placeholder="example@email.com">
            @error('email')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:0.5rem;">
            {{ __('auth.send_reset_link') }}
        </button>

        <p style="text-align:center; margin-top:1.25rem; color:#b0bfd9; font-size:0.88rem;">
            <a href="{{ route('login') }}" style="color:#c9a227;">{{ __('auth.back_to_login') }}</a>
        </p>
    </form>
</div>
@endsection
