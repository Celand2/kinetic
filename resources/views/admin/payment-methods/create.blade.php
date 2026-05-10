@extends('layouts.admin')

@section('title', 'Add Payment Method')

@section('back')<a href="{{ route('admin.payment-methods.index') }}" class="kts-back-btn">← Moyens de paiement</a>@endsection

@section('content')
<h1 style="color:#c9a227; font-size:1.2rem; margin-bottom:1.25rem;">Nouveau moyen de paiement</h1>

<div class="card" style="max-width:560px;">
<form action="{{ route('admin.payment-methods.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label class="form-label" for="name">Nom</label>
        <input class="form-control" id="name" name="name" value="{{ old('name') }}" required placeholder="Ex : Lumicash">
        @error('name')<span class="form-feedback-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="type">Type</label>
        <select class="form-control" id="type" name="type" required>
            <option value="">-- Choisir --</option>
            <option value="mobile_money" {{ old('type') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
            <option value="crypto"       {{ old('type') === 'crypto'       ? 'selected' : '' }}>Crypto</option>
            <option value="bank"         {{ old('type') === 'bank'         ? 'selected' : '' }}>Banque</option>
        </select>
        @error('type')<span class="form-feedback-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="currency">Devise acceptée</label>
        <select class="form-control" id="currency" name="currency" required>
            <option value="USD" {{ old('currency','USD') === 'USD' ? 'selected' : '' }}>USD — Dollar américain</option>
            @foreach(\App\Models\ExchangeRate::all() as $rate)
                <option value="{{ $rate->currency }}" {{ old('currency') === $rate->currency ? 'selected' : '' }}>
                    {{ $rate->currency }} — 1 USD = {{ number_format($rate->rate_to_usd, 0, ',', ' ') }} {{ $rate->currency }}
                </option>
            @endforeach
        </select>
        <span class="form-hint">La devise dans laquelle les utilisateurs déposeront avec ce moyen.</span>
        @error('currency')<span class="form-feedback-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="details">Instructions / Coordonnées</label>
        <textarea class="form-control" id="details" name="details" rows="4" required
                  placeholder="Numéro, IBAN, adresse wallet...">{{ old('details') }}</textarea>
        @error('details')<span class="form-feedback-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group" style="display:flex; align-items:center; gap:0.75rem;">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
        <label for="is_active" style="color:#b0bfd9; font-size:0.88rem; cursor:pointer;">Activer ce moyen de paiement</label>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;">Enregistrer</button>
</form>
</div>
@endsection