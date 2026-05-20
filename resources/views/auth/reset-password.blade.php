@extends('layouts.app')
@section('title', 'Réinitialiser le mot de passe - KINETIC')

@section('content')
<div class="card">
    <div class="card-header">Nouveau mot de passe</div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label class="form-label" for="email">Adresse email</label>
            <input type="email" class="form-control" id="email" name="email" required
                   value="{{ old('email', $email) }}" readonly
                   style="background:rgba(201,162,39,0.04); color:#b0bfd9; cursor:not-allowed;">
            @error('email')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required
                   placeholder="Minimum 8 caractères">
            @error('password')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="password_confirmation"
                   name="password_confirmation" required placeholder="Répétez le mot de passe">
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:0.5rem;">
            Réinitialiser le mot de passe
        </button>
    </form>
</div>
@endsection
