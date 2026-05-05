@extends('layouts.app')

@section('title', 'Modifier Tranche - KINETIC Admin')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 0.5rem; color: #c9a227;">Modifier la Tranche</h1>
    <p style="color: #b0bfd9; margin-bottom: 1rem;">Cycle : <strong style="color: #fff;">{{ $tranche->tradingCycle->name }}</strong></p>
    <a href="{{ route('admin.tranches', $tranche->tradingCycle) }}" class="back-link">← Retour aux tranches</a>

    <div class="card">
        <form method="POST" action="{{ route('admin.tranches.update', $tranche) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nom de la tranche</label>
                <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $tranche->name) }}" required>
                @error('name')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input class="form-control" type="text" id="slug" name="slug" value="{{ old('slug', $tranche->slug) }}" required>
                @error('slug')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $tranche->description) }}</textarea>
                @error('description')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="min_amount">Montant minimum ($)</label>
                    <input class="form-control" type="number" id="min_amount" name="min_amount" value="{{ old('min_amount', $tranche->min_amount) }}" step="0.01" min="0" required>
                    @error('min_amount')<span class="form-feedback-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="max_amount">Montant maximum ($) <small style="color:#b0bfd9;">(optionnel)</small></label>
                    <input class="form-control" type="number" id="max_amount" name="max_amount" value="{{ old('max_amount', $tranche->max_amount) }}" step="0.01" min="0">
                    @error('max_amount')<span class="form-feedback-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="display_order">Ordre d'affichage</label>
                <input class="form-control" type="number" id="display_order" name="display_order" value="{{ old('display_order', $tranche->display_order) }}" min="0">
                @error('display_order')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="is_active" class="form-label">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $tranche->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Enregistrer les modifications</button>
        </form>
    </div>
</div>
@endsection
