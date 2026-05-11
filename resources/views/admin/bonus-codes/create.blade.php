@extends('layouts.admin')

@section('title', 'Créer un Code Bonus - KINETIC')

@section('content')
<div style="max-width: 500px; margin: 0 auto;">
    <h1 style="color: #c9a227; margin-bottom: 2rem;">Créer un Code Bonus</h1>

    <div class="card">
        <form method="POST" action="{{ route('admin.bonus-codes.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="code">Code <span style="color: #ef5350;">*</span></label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="code" 
                    name="code" 
                    value="{{ old('code') }}"
                    placeholder="Ex: BONUS-ABC12345"
                    style="text-transform: uppercase;"
                    required
                >
                @error('code')
                    <span class="form-feedback-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="bonus_amount">Montant du Bonus (USD) <span style="color: #ef5350;">*</span></label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="bonus_amount" 
                    name="bonus_amount" 
                    value="{{ old('bonus_amount') }}"
                    placeholder="Ex: 100.00"
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
                    value="{{ old('expires_at') }}"
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
                    placeholder="Ex: Bonus de bienvenue pour nouveaux clients"
                    rows="3"
                >{{ old('description') }}</textarea>
                @error('description')
                    <span class="form-feedback-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Créer le Code</button>
                <a href="{{ route('admin.bonus-codes.index') }}" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none;">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
