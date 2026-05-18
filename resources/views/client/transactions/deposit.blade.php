@extends('layouts.client')
@section('title', 'Depot - KINETIC')
@section('back')<a href="{{ route('dashboard') }}" class="kts-back-btn">← Tableau de bord</a>@endsection

@section('content')
@php $user = auth()->user(); $userCurr = $user->preferred_currency ?? 'USD'; $initialStep = old('payment_method') || $errors->any() ? 2 : 1; @endphp

<h1 style="color:#c9a227; font-size:1.2rem; margin-bottom:1.25rem;">Demande de Depot</h1>

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

<div class="card">
    <form method="POST" action="{{ route('transactions.deposit.store') }}" enctype="multipart/form-data" id="depositForm">
        @csrf

        <div id="depositStep1" style="display:{{ $initialStep === 1 ? 'block' : 'none' }};">
            <div class="form-group">
                <label class="form-label" for="payment_method">Choisissez un moyen de paiement</label>
                <select id="payment_method" name="payment_method" class="form-control" required onchange="updateCurrency(this.value)">
                    <option value="">-- Choisir --</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->name }}" data-currency="{{ $method->currency }}" data-rate="{{ $exchangeRates[$method->currency]->rate_to_usd ?? 1 }}" data-details="{{ $method->details }}" {{ old('payment_method') === $method->name ? 'selected' : '' }}>
                            {{ $method->name }} ({{ $method->currency }})
                        </option>
                    @endforeach
                </select>
                @error('payment_method')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div id="paymentDetails" style="display:none; margin-top:-0.75rem; margin-bottom:1rem; background:rgba(107,122,154,0.07); border:1px solid rgba(107,122,154,0.2); border-radius:8px; padding:0.65rem 1rem;">
                <div style="font-size:0.78rem; color:#b0bfd9;">Détails du paiement :</div>
                <div id="paymentDetailsContent" style="font-family:'Space Mono',monospace; color:#e8e8e8; font-size:0.9rem; margin-top:2px;"></div>
                <div style="margin-top:0.75rem; color:#c9a227; font-size:0.82rem;">Conservez votre capture, vous en aurez besoin à l'étape suivante.</div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem;">
                <button type="button" class="btn btn-secondary" onclick="goToStep(2)">Suivant →</button>
            </div>
        </div>

        <div id="depositStep2" style="display:{{ $initialStep === 2 ? 'block' : 'none' }};">
            <div class="form-group">
                <label class="form-label" for="payment_method">Moyen de paiement sélectionné</label>
                <input type="text" class="form-control" value="{{ old('payment_method') }}" readonly>
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
                <div style="font-size:0.78rem; color:#b0bfd9;">Equivalent USD (après validation) :</div>
                <div id="conversionValue" style="font-family:'Space Mono',monospace; color:#c9a227; font-size:1rem; font-weight:700; margin-top:2px;">$0.00</div>
                <div id="rateInfo" style="font-size:0.7rem; color:#6b7a9a; margin-top:2px;"></div>
            </div>

            <div class="form-group">
                <label class="form-label" for="screenshot">Capture d'écran du paiement *</label>
                <input type="file" id="screenshot" name="screenshot" class="form-control" accept="image/*" required>
                <span class="form-hint">Photo claire de la confirmation. Formats acceptés : jpeg, png, jpg, gif, webp. Max 8 Mo.</span>
                @error('screenshot')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div id="imgPreview" style="display:none; margin-top:-0.5rem; margin-bottom:1rem;">
                <img id="imgPreviewSrc" src="" alt="Apercu" style="max-width:100%; max-height:200px; border-radius:8px; border:2px solid rgba(201,162,39,0.4); object-fit:cover;">
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Notes (optionnel)</label>
                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Reference, remarques...">{{ old('description') }}</textarea>
            </div>

            <div style="display:flex; justify-content:space-between; gap:0.75rem; margin-top:1rem; flex-wrap:wrap;">
                <button type="button" class="btn btn-secondary" onclick="goToStep(1)">← Retour</button>
                <button type="submit" class="btn btn-primary" style="flex:1; min-width:160px;">Soumettre la demande</button>
            </div>
            <p style="text-align:center; margin-top:0.75rem; color:#6b7a9a; font-size:0.75rem;">L admin validera votre depot sous peu.</p>
        </div>
    </form>
</div>

@push('scripts')
<script>
const payMethods = @json($paymentMethods->keyBy('name'));
const rateMap    = @json($exchangeRates);
let currentStep = {{ $initialStep }};

function showStep(step) {
    currentStep = step;
    document.getElementById('depositStep1').style.display = step === 1 ? 'block' : 'none';
    document.getElementById('depositStep2').style.display = step === 2 ? 'block' : 'none';
}

function goToStep(step) {
    if (step === 2) {
        const methodSelect = document.getElementById('payment_method');
        if (!methodSelect.value) {
            alert('Veuillez choisir un moyen de paiement avant de passer à l\'étape suivante.');
            return;
        }
    }
    showStep(step);
}

function updateCurrency(methodName) {
    const method   = payMethods[methodName];
    const currency = method ? method.currency : null;
    const details  = method ? method.details : '';
    document.getElementById('currencyLabel').textContent  = currency || 'devise';
    document.getElementById('currencySymbol').textContent = currency || '—';

    const detailsDiv = document.getElementById('paymentDetails');
    const detailsContent = document.getElementById('paymentDetailsContent');
    if (details) {
        detailsContent.textContent = details;
        detailsDiv.style.display = 'block';
    } else {
        detailsDiv.style.display = 'none';
    }

    const selectedInput = document.querySelector('#depositStep2 input[readonly]');
    if (selectedInput) {
        selectedInput.value = methodName || '';
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
        const usd = (currency === 'USD') ? localAmt : (localAmt / rate);
        document.getElementById('conversionValue').textContent = '$' + usd.toFixed(2) + ' USD';
        if (currency !== 'USD') {
            document.getElementById('rateInfo').textContent =
                'Taux utilisé : 1 USD = ' + rate.toLocaleString('fr-FR', {maximumFractionDigits: 2}) + ' ' + currency;
        } else {
            document.getElementById('rateInfo').textContent = '';
        }
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}

document.getElementById('screenshot').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = ev => {
            document.getElementById('imgPreviewSrc').src = ev.target.result;
            document.getElementById('imgPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

window.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('payment_method');
    if (sel.value) {
        updateCurrency(sel.value);
        if (currentStep === 2) {
            showStep(2);
        }
    }
});
</script>
@endpush
@endsection