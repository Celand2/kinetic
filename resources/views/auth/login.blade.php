@extends('layouts.app')

@section('title', 'Login - KINETIC')

@section('content')
<div class="card">
    <div class="card-header">Connexion</div>

    @if(session('error'))
        <div class="form-feedback-error" style="margin-bottom:1rem; padding:0.75rem; background:rgba(220,53,69,0.1); border-radius:6px;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">Adresse email</label>
            <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}" placeholder="exemple@email.com">
            @error('email')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
            @error('password')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:0.5rem;">Se connecter</button>

        <p style="text-align:center; margin-top:1rem; font-size:0.85rem;">
            <a href="{{ route('password.request') }}" style="color:#6b7a9a;">Mot de passe oublié ?</a>
        </p>

        <p style="text-align:center; margin-top:0.75rem; color:#b0bfd9; font-size:0.88rem;">
            Pas encore de compte ?
            <a href="{{ route('register') }}" style="color:#c9a227; font-weight:600;">S'inscrire</a>
        </p>
    </form>
</div>
@endsection
