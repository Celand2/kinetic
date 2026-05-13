@extends('layouts.client')
@section('title', 'Demande de Retrait - KINETIC')
@section('back')<a href="{{ route('dashboard') }}" class="kts-back-btn">← Tableau de bord</a>@endsection

@section('content')
@php $user = auth()->user(); @endphp

<h1 style="color:#c9a227; font-size:1.2rem; margin-bottom:1.25rem;">Demande de Retrait</h1>

@if($paymentMethods->count())
<div class="card" style="margin-bottom:1.25rem; padding:1rem 1.25rem;">
    <div style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.08em; color:#6b7a9a; margin-bottom:0.75rem; font-weight:600;">Moyens de paiement disponibles</div>
    @foreach($paymentMethods as $method)
    <div style="padding:0.6rem 0; border-bottom:1px solid rgba(255,255,255,0.05); display:flex; justify-content:space-between; align-items:center; {{ $loop->last ? 'border:none;' : '' }}">
        <div>
            <span style="color:#e8e8e8; font-weight:600; font-size:0.9rem;">{{ $method->name }}</span>
            <span style="color:#6b7a9a; font-size:0.78rem; margin-left:8px;">{{ ucfirst(str_replace('_',' ',$method->type)) }}</span>
        </div>
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <span style="background:rgba(201,162,39,0.1); border:1px solid rgba(201,162,39,0.25); color:#c9a227; padding:2px 8px; border-radius:20px; font-size:0.72rem; font-weight:700;">{{ $method->currency }}</span>
            @php $r = $exchangeRates[$method->currency] ?? null; @endphp
            @if($r && $method->currency !== 'USD')
                <span style="color:#6b7a9a; font-size:0.72rem;">1 USD = {{ number_format($r->rate_to_usd, 0, ',', ' ') }} {{ $method->currency }}</span>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

@php
    $wUser     = auth()->user();
    $wCurrency = $wUser->preferred_currency ?? 'USD';
    $wRate     = (float) \App\Models\ExchangeRate::rate($wCurrency);
    $wProfit   = $wCurrency === 'USD'
        ? '$' . number_format($wUser->profit_balance, 2)
        : number_format(round((float)$wUser->profit_balance * $wRate), 0, ',', ' ') . ' ' . $wCurrency;
@endphp
<div class="card" style="margin-bottom:1rem; background:rgba(129,199,132,0.06); border:1px solid rgba(129,199,132,0.25); padding:0.75rem 1rem;">
    <div style="font-size:0.78rem; color:#81c784;">
        ✅ Gains retirables : <strong>{{ $wProfit }}</strong>
        @if($wCurrency !== 'USD')
            <span style="color:#4a5568;">(${{ number_format($wUser->profit_balance, 2) }} USD)</span>
        @endif
    </div>
    <div style="font-size:0.72rem; color:#4a5568; margin-top:3px;">
        Frais 10% inclus · Seuls vos profits, commissions et bonus sont retirables
    </div>
</div>

<div class="card">
    <form method="POST" action="{{ route('transactions.withdraw.store') }}" enctype="multipart/form-data" id="withdrawForm">
        @csrf

        <div class="form-group">
            <label class="form-label" for="payment_method">Moyen de paiement</label>
            <select id="payment_method" name="payment_method" class="form-control" required onchange="updateCurrency(this.value)">
                <option value="">-- Choisir --</option>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->name }}"
                        data-currency="{{ $method->currency }}"
                        data-rate="{{ $exchangeRates[$method->currency]->rate_to_usd ?? 1 }}"
                        data-details="{{ $method->details }}"
                        {{ old('payment_method') === $method->name ? 'selected' : '' }}>
                        {{ $method->name }} ({{ $method->currency }})
                    </option>
                @endforeach
            </select>
            @error('payment_method')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div id="paymentDetails" style="display:none; margin-top:-0.75rem; margin-bottom:1rem; background:rgba(107,122,154,0.07); border:1px solid rgba(107,122,154,0.2); border-radius:8px; padding:0.65rem 1rem;">
            <div style="font-size:0.78rem; color:#b0bfd9;">Coordonnées pour réception :</div>
            <div id="paymentDetailsContent" style="font-family:'Space Mono',monospace; color:#e8e8e8; font-size:0.9rem; margin-top:2px;"></div>
        </div>

        <div class="form-group">
            <label class="form-label" for="amount">Montant en <span id="currencyLabel" style="color:#c9a227;">—</span></label>
            <div style="position:relative;">
                <input type="number" id="amount" name="amount" class="form-control" min="1" step="1" value="{{ old('amount') }}" required placeholder="0" style="padding-right:70px;" oninput="updatePreview()">
                <span id="currencySymbol" style="position:absolute; right:14px; top:50%; transform:translateY(-50%); color:#c9a227; font-weight:700; font-size:0.85rem; pointer-events:none;">—</span>
            </div>
            @error('amount')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div id="conversionPreview" style="display:none; margin-top:-0.75rem; margin-bottom:1rem; background:rgba(201,162,39,0.07); border:1px solid rgba(201,162,39,0.2); border-radius:8px; padding:0.65rem 1rem;">
            <div style="font-size:0.78rem; color:#b0bfd9;">Équivalent USD :</div>
            <div id="conversionValue" style="font-family:'Space Mono',monospace; color:#c9a227; font-size:1rem; font-weight:700; margin-top:2px;">$0.00</div>
            <div id="feeInfo" style="font-size:0.72rem; color:#fbc02d; margin-top:4px;"></div>
            <div id="rateInfo" style="font-size:0.7rem; color:#6b7a9a; margin-top:2px;"></div>
        </div>

        <div class="form-group">
            <label class="form-label" for="wallet_details">Coordonnées de réception *</label>
            <textarea id="wallet_details" name="wallet_details" class="form-control" rows="3" required
                placeholder="Numéro de compte, adresse, IBAN...">{{ old('wallet_details') }}</textarea>
            @error('wallet_details')<span class="form-feedback-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Notes (optionnel)</label>
            <textarea id="description" name="description" class="form-control" rows="2"
                placeholder="Remarques...">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;" id="submitBtn">Soumettre la demande</button>
        <p style="text-align:center; margin-top:0.75rem; color:#6b7a9a; font-size:0.75rem;">L'admin validera votre retrait sous peu.</p>
    </form>
</div>

@push('scripts')
<script>
const payMethods = @json($paymentMethods->keyBy('name'));
const rateMap    = @json($exchangeRates);

function updateCurrency(methodName) {
    const method   = payMethods[methodName];
    const currency = method ? method.currency : null;
    const details  = method ? method.details : '';

    document.getElementById('currencyLabel').textContent  = currency || 'devise';
    document.getElementById('currencySymbol').textContent = currency || '—';
    document.getElementById('amount').value = '';

    const detailsDiv     = document.getElementById('paymentDetails');
    const detailsContent = document.getElementById('paymentDetailsContent');
    if (details) {
        detailsContent.textContent = details;
        detailsDiv.style.display = 'block';
    } else {
        detailsDiv.style.display = 'none';
    }

    updatePreview();
}

function updatePreview() {
    const methodName = document.getElementById('payment_method').value;
    const method     = payMethods[methodName];
    const currency   = method ? method.currency : null;
    const localAmt   = parseFloat(document.getElementById('amount').value) || 0;
    const preview    = document.getElementById('conversionPreview');

    if (localAmt > 0 && currency) {
        const rateObj = rateMap[currency];
        const rate    = rateObj ? parseFloat(rateObj.rate_to_usd) : 1;
        const usd     = (currency === 'USD') ? localAmt : (localAmt / rate);
        const fee     = usd * 0.10;
        const total   = usd + fee;

        document.getElementById('conversionValue').textContent = '$' + usd.toFixed(2) + ' USD';
        document.getElementById('feeInfo').textContent =
            'Frais (10%) : $' + fee.toFixed(2) + ' — Total débité : $' + total.toFixed(2);

        if (currency !== 'USD') {
            document.getElementById('rateInfo').textContent =
                'Taux : 1 USD = ' + rate.toLocaleString('fr-FR', {maximumFractionDigits: 2}) + ' ' + currency;
        } else {
            document.getElementById('rateInfo').textContent = '';
        }

        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}

window.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('payment_method');
    if (sel.value) updateCurrency(sel.value);
});
</script>
@endpush
@endsection
