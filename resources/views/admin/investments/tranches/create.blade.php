@extends('layouts.app')

@section('title', 'Créer une Tranche - KINETIC Admin')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 0.5rem; color: #c9a227;">Créer une Tranche</h1>
    <p style="color: #b0bfd9; margin-bottom: 1rem;">Cycle : <strong style="color: #fff;">{{ $cycle->name }}</strong></p>
    <a href="{{ route('admin.tranches', $cycle) }}" class="back-link">← Retour aux tranches</a>

    <div class="card">
        <form method="POST" action="{{ route('admin.tranches.store', $cycle) }}">
            @csrf

            <div class="form-group">
                <label for="name">Nom de la tranche</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input class="form-control" type="text" id="slug" name="slug" value="{{ old('slug') }}" required>
                @error('slug')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="min_amount">Montant minimum ($)</label>
                    <input class="form-control" type="number" id="min_amount" name="min_amount" value="{{ old('min_amount') }}" step="0.01" min="0" required>
                    @error('min_amount')<span class="form-feedback-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="max_amount">Montant maximum ($) <small style="color:#b0bfd9;">(optionnel)</small></label>
                    <input class="form-control" type="number" id="max_amount" name="max_amount" value="{{ old('max_amount') }}" step="0.01" min="0">
                    @error('max_amount')<span class="form-feedback-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="display_order">Ordre d'affichage</label>
                <input class="form-control" type="number" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
                @error('display_order')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Créer la tranche</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('name').addEventListener('input', function () {
        const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        document.getElementById('slug').value = slug;
    });
</script>
@endsection
