@extends('layouts.app')

@section('title', 'Investir - ' . $tranche->name . ' - KINETIC')
@section('page-title', 'CONFIRMER L\'INVESTISSEMENT')
@section('page-subtitle', '// ' . $cycle->name . ' · ' . $tranche->name)

@push('styles')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        color: #7a9cc6;
        font-size: .85rem;
        text-decoration: none;
        margin-bottom: 1.5rem;
        transition: color .2s;
    }
    .back-link:hover { color: #c9a227; }

    .invest-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 1.5rem;
        align-items: start;
    }
    @media (max-width: 860px) {
        .invest-layout { grid-template-columns: 1fr; }
    }

    /* Récapitulatif */
    .recap-card {
        background: linear-gradient(135deg, #0d1f35, #0a1628);
        border: 1px solid #1e3a5f;
        border-radius: 14px;
        padding: 1.5rem;
    }
    .recap-title {
        font-family: 'Space Mono', monospace;
        font-size: .72rem;
        color: #c9a227;
        letter-spacing: .12em;
        text-transform: uppercase;
        margin-bottom: 1.25rem;
    }
    .recap-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding: .55rem 0;
        border-bottom: 1px solid rgba(255,255,255,.05);
    }
    .recap-row:last-of-type { border-bottom: none; }
    .recap-row .rkey { font-size: .82rem; color: #7a9cc6; }
    .recap-row .rval {
        font-family: 'Orbitron', sans-serif;
        font-size: .9rem;
        color: #fff;
        font-weight: 600;
    }
    .recap-row .rval.gold  { color: #c9a227; }
    .recap-row .rval.green { color: #81c784; }

    .profit-preview {
        background: rgba(129,199,132,.07);
        border: 1px solid rgba(129,199,132,.2);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1.25rem;
        text-align: center;
    }
    .profit-preview .pp-label { font-size: .75rem; color: #81c784; margin-bottom: .3rem; }
    .profit-preview .pp-val {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.6rem;
        font-weight: 900;
        color: #81c784;
    }
    .profit-preview .pp-sub { font-size: .72rem; color: #7a9cc6; margin-top: .2rem; }

    .wallet-balance {
        background: rgba(201,162,39,.07);
        border: 1px solid rgba(201,162,39,.2);
        border-radius: 10px;
        padding: .85rem 1rem;
        margin-top: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .wallet-balance .wlabel { font-size: .8rem; color: #7a9cc6; }
    .wallet-balance .wval {
        font-family: 'Orbitron', sans-serif;
        font-size: 1rem;
        color: #c9a227;
        font-weight: 700;
    }

    /* Formulaire */
    .form-card {
        background: #0d1f35;
        border: 1px solid #1e3a5f;
        border-radius: 14px;
        padding: 1.75rem;
    }
    .form-card h3 {
        font-family: 'Orbitron', sans-serif;
        font-size: 1rem;
        color: #fff;
        margin-bottom: 1.5rem;
    }
    .form-group { margin-bottom: 1.25rem; }
    .form-group label {
        display: block;
        font-size: .82rem;
        color: #b0bfd9;
        margin-bottom: .45rem;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .form-control {
        width: 100%;
        background: #0a1628;
        border: 1px solid #1e3a5f;
        border-radius: 8px;
        color: #fff;
        padding: .7rem 1rem;
        font-size: 1rem;
        font-family: 'Rajdhani', sans-serif;
        transition: border-color .2s;
        box-sizing: border-box;
    }
    .form-control:focus { outline: none; border-color: #c9a227; }
    .amount-hint {
        font-size: .78rem;
        color: #7a9cc6;
        margin-top: .4rem;
    }

    /* Moyens de paiement */
    .payment-methods { display: flex; flex-direction: column; gap: .75rem; }
    .pm-option { display: none; }
    .pm-label {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #0a1628;
        border: 2px solid #1e3a5f;
        border-radius: 10px;
        padding: .9rem 1.1rem;
        cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .pm-option:checked + .pm-label {
        border-color: #c9a227;
        background: rgba(201,162,39,.06);
    }
    .pm-icon {
        width: 36px; height: 36px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        background: rgba(255,255,255,.05);
        flex-shrink: 0;
    }
    .pm-info .pm-name {
        font-weight: 600;
        color: #fff;
        font-size: .95rem;
    }
    .pm-info .pm-desc {
        font-size: .76rem;
        color: #7a9cc6;
        margin-top: .1rem;
    }

    .notice-box {
        background: rgba(251,192,45,.07);
        border: 1px solid rgba(251,192,45,.25);
        border-radius: 8px;
        padding: .75rem 1rem;
        font-size: .8rem;
        color: #fbc02d;
        margin-top: 1rem;
        display: none;
    }

    .btn-invest {
        display: block;
        width: 100%;
        background: linear-gradient(135deg, #c9a227, #a07d1a);
        color: #0a1628;
        font-family: 'Orbitron', sans-serif;
        font-size: .9rem;
        font-weight: 700;
        padding: .9rem;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        letter-spacing: .05em;
        margin-top: 1.5rem;
        transition: opacity .2s;
    }
    .btn-invest:hover { opacity: .88; }
</style>
@endpush

@section('content')
<a href="{{ route('investments.cycle.tranches', $cycle) }}" class="back-link">← Retour aux tranches</a>

<div class="invest-layout">

    {{-- Colonne gauche : récapitulatif --}}
    <div>
        <div class="recap-card">
            <div class="recap-title">// Récapitulatif</div>

            <div class="recap-row">
                <span class="rkey">Cycle</span>
                <span class="rval gold">{{ $cycle->name }}</span>
            </div>
            <div class="recap-row">
                <span class="rkey">Tranche</span>
                <span class="rval">{{ $tranche->name }}</span>
            </div>
            <div class="recap-row">
                <span class="rkey">Durée</span>
                <span class="rval">{{ $cycle->duration_days }} jours</span>
            </div>
            <div class="recap-row">
                <span class="rkey">Profit journalier</span>
                <span class="rval gold">{{ $cycle->daily_profit_percent }}%</span>
            </div>
            <div class="recap-row">
                <span class="rkey">Retour total</span>
                <span class="rval green">{{ $cycle->total_return_percent }}%</span>
            </div>
            <div class="recap-row">
                <span class="rkey">Montant min.</span>
                <span class="rval">${{ number_format($tranche->min_amount, 2) }}</span>
            </div>
            @if($tranche->max_amount)
            <div class="recap-row">
                <span class="rkey">Montant max.</span>
                <span class="rval">${{ number_format($tranche->max_amount, 2) }}</span>
            </div>
            @endif

            <div class="profit-preview">
                <div class="pp-label">Profit estimé (sur votre montant)</div>
                <div class="pp-val" id="profit-estimate">$0.00</div>
                <div class="pp-sub">= montant × {{ $cycle->total_return_percent }}% de retour total</div>
            </div>

            <div class="wallet-balance">
                <span class="wlabel">Votre solde wallet</span>
                <span class="wval">${{ number_format($user->balance, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Colonne droite : formulaire --}}
    <div class="form-card">
        <h3>Formulaire d'investissement</h3>

        <form method="POST" action="{{ route('investments.store') }}">
            @csrf
            <input type="hidden" name="tranche_id" value="{{ $tranche->id }}">

            <div class="form-group">
                <label for="amount">Montant à investir ($)</label>
                <input
                    class="form-control"
                    type="number"
                    id="amount"
                    name="amount"
                    step="0.01"
                    min="{{ $tranche->min_amount }}"
                    @if($tranche->max_amount) max="{{ $tranche->max_amount }}" @endif
                    value="{{ old('amount', $tranche->min_amount) }}"
                    required
                    placeholder="{{ number_format($tranche->min_amount, 2) }}"
                >
                <div class="amount-hint">
                    Min : ${{ number_format($tranche->min_amount, 2) }}
                    @if($tranche->max_amount)
                        &nbsp;·&nbsp; Max : ${{ number_format($tranche->max_amount, 2) }}
                    @else
                        &nbsp;·&nbsp; Pas de maximum
                    @endif
                </div>
                @error('amount')<div style="color:#ef5350;font-size:.8rem;margin-top:.35rem;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Moyen de paiement</label>
                <div class="payment-methods">

                    {{-- Wallet --}}
                    <input class="pm-option" type="radio" name="payment_method" id="pm_wallet" value="wallet"
                        {{ old('payment_method', 'wallet') === 'wallet' ? 'checked' : '' }}>
                    <label class="pm-label" for="pm_wallet">
                        <div class="pm-icon">💳</div>
                        <div class="pm-info">
                            <div class="pm-name">Solde Wallet</div>
                            <div class="pm-desc">Débit immédiat · Activation instantanée</div>
                        </div>
                    </label>

                    {{-- Lumicash --}}
                    <input class="pm-option" type="radio" name="payment_method" id="pm_lumicash" value="lumicash"
                        {{ old('payment_method') === 'lumicash' ? 'checked' : '' }}>
                    <label class="pm-label" for="pm_lumicash">
                        <div class="pm-icon">📱</div>
                        <div class="pm-info">
                            <div class="pm-name">Lumicash</div>
                            <div class="pm-desc">Paiement mobile · Confirmation par admin</div>
                        </div>
                    </label>

                    {{-- Bancobu --}}
                    <input class="pm-option" type="radio" name="payment_method" id="pm_bancobu" value="bancobu_enoti"
                        {{ old('payment_method') === 'bancobu_enoti' ? 'checked' : '' }}>
                    <label class="pm-label" for="pm_bancobu">
                        <div class="pm-icon">🏦</div>
                        <div class="pm-info">
                            <div class="pm-name">Banque / Bancobu</div>
                            <div class="pm-desc">Virement bancaire · Confirmation par admin</div>
                        </div>
                    </label>

                </div>
                @error('payment_method')<div style="color:#ef5350;font-size:.8rem;margin-top:.35rem;">{{ $message }}</div>@enderror
            </div>

            <div class="notice-box" id="external-notice">
                ⚠️ Votre investissement sera en attente jusqu'à ce qu'un admin confirme la réception de votre paiement.
                Contactez le support avec votre référence après le virement.
            </div>

            <button type="submit" class="btn-invest">Confirmer l'investissement →</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const totalReturnPct = {{ $cycle->total_return_percent }};
    const amountInput    = document.getElementById('amount');
    const profitEl       = document.getElementById('profit-estimate');
    const noticeEl       = document.getElementById('external-notice');
    const pmRadios       = document.querySelectorAll('input[name="payment_method"]');

    function updateProfit() {
        const amt = parseFloat(amountInput.value) || 0;
        const profit = amt * (totalReturnPct / 100);
        profitEl.textContent = '$' + profit.toFixed(2);
    }

    function updateNotice() {
        const selected = document.querySelector('input[name="payment_method"]:checked');
        noticeEl.style.display = (selected && selected.value !== 'wallet') ? 'block' : 'none';
    }

    amountInput.addEventListener('input', updateProfit);
    pmRadios.forEach(r => r.addEventListener('change', updateNotice));

    updateProfit();
    updateNotice();
</script>
@endpush
