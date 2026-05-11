@extends('layouts.admin')

@section('title', 'Éditer Code Bonus - KINETIC')

@section('content')
<div style="max-width: 500px; margin: 0 auto;">
    <h1 style="color: #c9a227; margin-bottom: 2rem;">Éditer Code Bonus</h1>

    <div class="card" style="margin-bottom: 1rem;">
        <div style="background: rgba(201, 162, 39, 0.1); border: 1px solid rgba(201, 162, 39, 0.3); border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
            <div style="font-size: 0.85rem; color: #b0bfd9; margin-bottom: 0.5rem;">Code</div>
            <div style="font-family: monospace; font-size: 1.2rem; color: #c9a227; font-weight: 600;">{{ $bonusCode->code }}</div>
        </div>

        <form method="POST" action="{{ route('admin.bonus-codes.update', $bonusCode) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="bonus_amount">Montant du Bonus (USD) <span style="color: #ef5350;">*</span></label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="bonus_amount" 
                    name="bonus_amount" 
                    value="{{ old('bonus_amount', $bonusCode->bonus_amount) }}"
                    step="0.01"
                    min="0.01"
                    required
                >
                @error('bonus_amount')
                    <span class="form-feedback-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="expires_at">Date d'Expiration (optionnel)</label>
                <input 
                    type="date" 
                    class="form-control" 
                    id="expires_at" 
                    name="expires_at" 
                    value="{{ old('expires_at', $bonusCode->expires_at?->format('Y-m-d')) }}"
                    min="{{ now()->addDay()->format('Y-m-d') }}"
                >
                @error('expires_at')
                    <span class="form-feedback-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description (optionnel)</label>
                <textarea 
                    class="form-control" 
                    id="description" 
                    name="description" 
                    rows="3"
                >{{ old('description', $bonusCode->description) }}</textarea>
                @error('description')
                    <span class="form-feedback-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Mettre à Jour</button>
                <a href="{{ route('admin.bonus-codes.show', $bonusCode) }}" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none;">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
