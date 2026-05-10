 @extends('layouts.app') 

@section('title', 'Register - KINETIC')

@section('content')
<div class="card">
    <div class="card-header">Créer un compte</div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="full_name">Nom complet</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required value="{{ old('full_name') }}" placeholder="Jean Dupont">
            @error('full_name')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Adresse email</label>
            <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}" placeholder="exemple@email.com">
            @error('email')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="phone">Téléphone</label>
            <input type="tel" class="form-control" id="phone" name="phone" required value="{{ old('phone') }}" placeholder="+257 XX XXX XXX">
            @error('phone')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="referral_code">Code de parrainage (optionnel)</label>
            <input type="text" class="form-control" id="referral_code" name="referral_code" value="{{ old('referral_code', request('ref')) }}" placeholder="Ex : KTS-ABC12345">
            <span class="form-hint">Saisissez un code si vous avez été parrainé.</span>
            @error('referral_code')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="country">Pays</label>
            <input type="text" class="form-control" id="country" name="country" required value="{{ old('country') }}" placeholder="Burundi">
            @error('country')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required placeholder="Min. 8 caractères">
            @error('password')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:0.5rem;">Créer mon compte</button>

        <p style="text-align:center; margin-top:1.5rem; color:#b0bfd9; font-size:0.88rem;">
            Déjà un compte ?
            <a href="{{ route('login') }}" style="color:#c9a227; font-weight:600;">Se connecter</a>
        </p>
    </form>
</div>
@endsection
