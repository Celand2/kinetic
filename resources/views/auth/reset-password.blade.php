@extends('layouts.app')
@section('title', __('auth.title.reset_password'))

@section('content')
<div class="card">
    <div class="card-header">{{ __('auth.reset_header') }}</div>
    <div style="text-align:right; margin-bottom:0.75rem;">
        <a href="{{ route('locale.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" style="color:#c9a227; font-size:0.9rem;">🌐 {{ __('auth.language') }}</a>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label class="form-label" for="email">{{ __('auth.email') }}</label>
            <input type="email" class="form-control" id="email" name="email" required
                   value="{{ old('email', $email) }}" readonly
                   style="background:rgba(201,162,39,0.04); color:#b0bfd9; cursor:not-allowed;">
            @error('email')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">{{ __('auth.password') }}</label>
            <input type="password" class="form-control" id="password" name="password" required
                   placeholder="Minimum 8 caractères">
            @error('password')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">{{ __('auth.password_confirmation') }}</label>
            <input type="password" class="form-control" id="password_confirmation"
                   name="password_confirmation" required placeholder="Répétez le mot de passe">
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:0.5rem;">
            {{ __('auth.password_reset') }}
        </button>
    </form>
</div>
@endsection
