@extends('layouts.admin')

@section('title', 'Générer des Codes Bonus - KINETIC')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="color: #c9a227; margin-bottom: 2rem;">Générer des Codes Bonus en Masse</h1>

    <div class="card">
        <form method="POST" action="{{ route('admin.bonus-codes.generate-batch.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="quantity">Nombre de Codes <span style="color: #ef5350;">*</span></label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="quantity" 
                    name="quantity" 
                    value="{{ old('quantity', 10) }}"
                    min="1"
                    max="100"
                    required
                >
                <small style="color: #6b7a9a; display: block; margin-top: 0.5rem;">Maximum 100 codes à la fois</small>
                @error('quantity')
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
                <label class="form-label" for="prefix">Préfixe du Code (optionnel)</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="prefix" 
                    name="prefix" 
                    value="{{ old('prefix', 'BONUS') }}"
                    placeholder="Ex: BONUS, PROMO, VIP"
                    style="text-transform: uppercase;"
                    maxlength="20"
                >
                <small style="color: #6b7a9a; display: block; margin-top: 0.5rem;">Format final : PREFIXE-XXXXX</small>
                @error('prefix')
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
                <small style="color: #6b7a9a; display: block; margin-top: 0.5rem;">S'applique à tous les codes générés</small>
                @error('expires_at')
                    <span class="form-feedback-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="background: rgba(122, 156, 198, 0.1); border: 1px solid rgba(122, 156, 198, 0.3); border-radius: 8px; padding: 1rem; margin-bottom: 2rem;">
                <div style="font-size: 0.85rem; color: #7a9cc6; margin-bottom: 0.5rem;">📋 Résumé</div>
                <div style="font-size: 0.9rem; color: #b0bfd9;">
                    <div>Codes à générer : <strong id="qty-display">10</strong></div>
                    <div>Montant chacun : <strong id="amt-display">-</strong></div>
                    <div>Montant total : <strong id="tot-display">-</strong></div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Générer les Codes</button>
                <a href="{{ route('admin.bonus-codes.index') }}" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none;">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('quantity').addEventListener('change', updateSummary);
    document.getElementById('bonus_amount').addEventListener('change', updateSummary);

    function updateSummary() {
        const qty = parseInt(document.getElementById('quantity').value) || 0;
        const amt = parseFloat(document.getElementById('bonus_amount').value) || 0;
        
        document.getElementById('qty-display').textContent = qty;
        document.getElementById('amt-display').textContent = '$' + amt.toFixed(2);
        document.getElementById('tot-display').textContent = '$' + (qty * amt).toFixed(2);
    }

    updateSummary();
</script>
@endsection
