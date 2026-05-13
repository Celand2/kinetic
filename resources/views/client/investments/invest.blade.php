@extends('layouts.client')

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

@section('back')<a href="{{ route('investments.cycle.tranches', $cycle) }}" class="kts-back-btn">← Tranches</a>@endsection

@section('content')
@php
    function fmtInvest($usd, $currency, $rate) {
        if ($currency === 'USD') return '$' . number_format($usd, 2);
        return number_format(round($usd * $rate), 0, ',', ' ') . ' ' . $currency;
    }
    // Limites converties en devise locale pour l'input HTML
    $localMin     = $userCurrency === 'USD' ? $tranche->min_amount : round($tranche->min_amount * $currencyRate);
    $localMax     = $tranche->max_amount ? ($userCurrency === 'USD' ? $tranche->max_amount : round($tranche->max_amount * $currencyRate)) : null;
    $localDefault = old('amount', $localMin);
    $step         = $userCurrency === 'USD' ? '0.01' : '1';
@endphp

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
                <span class="rval">{{ fmtInvest($tranche->min_amount, $userCurrency, $currencyRate) }}</span>
            </div>
            @if($tranche->max_amount)
            <div class="recap-row">
                <span class="rkey">Montant max.</span>
                <span class="rval">{{ fmtInvest($tranche->max_amount, $userCurrency, $currencyRate) }}</span>
            </div>
            @endif
            @if($userCurrency !== 'USD')
            <div class="recap-row">
                <span class="rkey">Taux</span>
                <span class="rval" style="font-size:0.78rem; color:#6b7a9a;">1 USD = {{ number_format($currencyRate, 0, ',', ' ') }} {{ $userCurrency }}</span>
            </div>
            @endif

            <div class="profit-preview">
                <div class="pp-label">Profit estimé (sur votre montant)</div>
                <div class="pp-val" id="profit-estimate">{{ fmtInvest(0, $userCurrency, $currencyRate) }}</div>
                <div class="pp-sub">= montant × {{ $cycle->total_return_percent }}% retour total</div>
            </div>

            <div class="wallet-balance">
                <span class="wlabel">Votre solde</span>
                <span class="wval">{{ fmtInvest($user->balance, $userCurrency, $currencyRate) }}</span>
            </div>
        </div>
    </div>

    {{-- Colonne droite : formulaire --}}
    <div class="form-card">
        {{-- Badge réinvestissement libre --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:1.25rem;
                    background:rgba(129,199,132,0.07); border:1px solid rgba(129,199,132,0.22);
                    border-radius:8px; padding:0.6rem 0.9rem;">
            <span style="color:#81c784; font-size:1rem;">🔁</span>
            <div>
                <div style="font-size:0.78rem; color:#81c784; font-weight:700;">Réinvestissement libre</div>
                <div style="font-size:0.7rem; color:#4a5568;">Investissez dans cette tranche autant de fois que vous voulez. Chaque contrat est indépendant.</div>
            </div>
        </div>

        {{-- Contrats actifs dans cette tranche --}}
        @if($activeInThisTranche->count())
        <div style="margin-bottom:1.25rem; background:rgba(201,162,39,0.05); border:1px solid rgba(201,162,39,0.18); border-radius:8px; padding:0.75rem 0.9rem;">
            <div style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.08em; color:#6b7a9a; margin-bottom:0.5rem;">
                Contrats actifs dans cette tranche ({{ $activeInThisTranche->count() }})
            </div>
            @foreach($activeInThisTranche as $inv)
            <div style="display:flex; justify-content:space-between; align-items:center;
                        padding:0.4rem 0; border-bottom:1px solid rgba(255,255,255,0.04);
                        font-size:0.78rem; {{ $loop->last ? 'border:none' : '' }}">
                <span style="font-family:monospace; color:#b0bfd9;">{{ $inv->reference }}</span>
                <span style="color:#c9a227;">{{ fmtInvest($inv->amount, $userCurrency, $currencyRate) }}</span>
                <span style="color:#6b7a9a;">
                    @if($inv->ends_at) expire {{ $inv->ends_at->format('d/m/Y') }} @else en attente @endif
                </span>
            </div>
            @endforeach
            <div style="font-size:0.7rem; color:#4a5568; margin-top:0.5rem;">
                Les gains de tous ces contrats s'accumulent dans vos <span style="color:#81c784;">gains retirables</span>.
            </div>
        </div>
        @endif

        <h3>Nouveau contrat</h3>

        <form method="POST" action="{{ route('investments.store') }}">
            @csrf
            <input type="hidden" name="tranche_id" value="{{ $tranche->id }}">

            <div class="form-group">
                <label for="amount">Montant à investir ({{ $userCurrency }})</label>
                <input
                    class="form-control"
                    type="number"
                    id="amount"
                    name="amount"
                    step="{{ $step }}"
                    min="{{ $localMin }}"
                    @if($localMax) max="{{ $localMax }}" @endif
                    value="{{ $localDefault }}"
                    required
                    placeholder="{{ number_format($localMin, 0) }}"
                >
                <div class="amount-hint">
                    Min : {{ fmtInvest($tranche->min_amount, $userCurrency, $currencyRate) }}
                    @if($tranche->max_amount)
                        &nbsp;·&nbsp; Max : {{ fmtInvest($tranche->max_amount, $userCurrency, $currencyRate) }}
                    @else
                        &nbsp;·&nbsp; Pas de maximum
                    @endif
                    @if($userCurrency !== 'USD')
                        <span style="color:#4a5568; margin-left:6px;">(base admin : ${{ number_format($tranche->min_amount, 2) }}{{ $tranche->max_amount ? ' – $' . number_format($tranche->max_amount, 2) : '' }} USD)</span>
                    @endif
                </div>
                @error('amount')<div style="color:#ef5350;font-size:.8rem;margin-top:.35rem;">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-invest">Confirmer l'investissement →</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const totalReturnPct = {{ $cycle->total_return_percent }};
    const userCurrency   = '{{ $userCurrency }}';
    const currencyRate   = {{ $currencyRate > 0 ? $currencyRate : 1 }};
    const amountInput    = document.getElementById('amount');
    const profitEl       = document.getElementById('profit-estimate');

    function fmtLocal(usdAmt) {
        if (userCurrency === 'USD') return '$' + usdAmt.toFixed(2);
        const local = usdAmt * currencyRate;
        return new Intl.NumberFormat('fr-FR').format(Math.round(local)) + ' ' + userCurrency;
    }

    function updateProfit() {
        const localAmt = parseFloat(amountInput.value) || 0;
        // Convertir la saisie locale en USD pour calculer le profit
        const usdAmt   = (userCurrency === 'USD') ? localAmt : (localAmt / currencyRate);
        const usdProfit = usdAmt * (totalReturnPct / 100);
        profitEl.textContent = fmtLocal(usdProfit);
    }

    amountInput.addEventListener('input', updateProfit);
    updateProfit();
</script>
@endpush
