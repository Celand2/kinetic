@extends('layouts.client')

@section('title', $cycle->name . ' - Tranches - KINETIC')
@section('page-title', strtoupper($cycle->name))
@section('page-subtitle', '// Choisissez votre niveau d\'investissement')

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

    .cycle-summary {
        background: linear-gradient(135deg, #0d1f35, #0a1628);
        border: 1px solid #1e3a5f;
        border-left: 4px solid #c9a227;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .cycle-summary-item .label {
        font-size: .75rem;
        color: #7a9cc6;
        text-transform: uppercase;
        letter-spacing: .08em;
    }
    .cycle-summary-item .value {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.1rem;
        color: #c9a227;
        font-weight: 700;
    }

    .tranches-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    .tranche-card {
        background: #0d1f35;
        border: 1px solid #1e3a5f;
        border-radius: 14px;
        padding: 1.75rem;
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform .2s, border-color .2s, box-shadow .2s;
        position: relative;
    }
    .tranche-card:hover {
        transform: translateY(-3px);
        border-color: #c9a227;
        box-shadow: 0 6px 24px rgba(201,162,39,.2);
    }
    .tranche-name {
        font-family: 'Orbitron', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: .3rem;
    }
    .tranche-slug {
        font-family: 'Space Mono', monospace;
        font-size: .7rem;
        color: #7a9cc6;
        margin-bottom: 1.25rem;
    }
    .tranche-amounts {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem;
        margin-bottom: 1.5rem;
    }
    .amount-box {
        background: rgba(255,255,255,.04);
        border-radius: 8px;
        padding: .6rem .75rem;
    }
    .amount-box .alabel {
        font-size: .7rem;
        color: #7a9cc6;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .amount-box .aval {
        font-family: 'Orbitron', sans-serif;
        font-size: .95rem;
        color: #81c784;
        font-weight: 700;
        margin-top: .15rem;
    }
    .amount-box .aval.unlimited { color: #b0bfd9; font-size: .85rem; font-family: 'Rajdhani', sans-serif; }
    .tranche-desc {
        font-size: .83rem;
        color: #b0bfd9;
        margin-bottom: 1.25rem;
        line-height: 1.5;
    }
    .tranche-cta {
        display: block;
        width: 100%;
        text-align: center;
        background: linear-gradient(135deg, #c9a227, #a07d1a);
        color: #0a1628;
        font-family: 'Orbitron', sans-serif;
        font-size: .8rem;
        font-weight: 700;
        padding: .65rem;
        border-radius: 8px;
        letter-spacing: .04em;
        text-decoration: none;
        transition: opacity .2s;
    }
    .tranche-cta:hover { opacity: .88; }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #b0bfd9;
    }
    .empty-state a { color: #c9a227; text-decoration: none; }
</style>
@endpush

@section('content')
@php
    function fmtTranche($usd, $currency, $rate) {
        if ($currency === 'USD') return '$' . number_format($usd, 0);
        return number_format(round($usd * $rate), 0, ',', ' ') . ' ' . $currency;
    }
@endphp
<a href="{{ route('investments.create') }}" class="back-link">← Retour aux cycles</a>

<div class="cycle-summary">
    <div class="cycle-summary-item">
        <div class="label">Cycle</div>
        <div class="value" style="font-size:.9rem; color:#fff;">{{ $cycle->name }}</div>
    </div>
    <div class="cycle-summary-item">
        <div class="label">Durée</div>
        <div class="value">{{ $cycle->duration_days }} jours</div>
    </div>
    <div class="cycle-summary-item">
        <div class="label">Profit / jour</div>
        <div class="value">{{ $cycle->daily_profit_percent }}%</div>
    </div>
    <div class="cycle-summary-item">
        <div class="label">Retour total</div>
        <div class="value" style="color:#81c784;">{{ $cycle->total_return_percent }}%</div>
    </div>
</div>

@if($tranches->isEmpty())
    <div class="empty-state">
        <p>Aucune tranche disponible pour ce cycle.</p>
        <a href="{{ route('investments.create') }}">← Choisir un autre cycle</a>
    </div>
@else
    <div class="tranches-grid">
        @foreach($tranches as $tranche)
            <a href="{{ route('investments.invest', [$cycle, $tranche]) }}" class="tranche-card">
                <div class="tranche-name">{{ $tranche->name }}</div>
                <div class="tranche-slug">// {{ $tranche->slug }}</div>

                <div class="tranche-amounts">
                    <div class="amount-box">
                        <div class="alabel">Min</div>
                        <div class="aval">{{ fmtTranche($tranche->min_amount, $userCurrency, $currencyRate) }}</div>
                        @if($userCurrency !== 'USD')
                            <div style="font-size:0.65rem; color:#4a5568; margin-top:2px;">${{ number_format($tranche->min_amount, 0) }}</div>
                        @endif
                    </div>
                    <div class="amount-box">
                        <div class="alabel">Max</div>
                        <div class="aval {{ !$tranche->max_amount ? 'unlimited' : '' }}">
                            {{ $tranche->max_amount ? fmtTranche($tranche->max_amount, $userCurrency, $currencyRate) : 'Illimité' }}
                        </div>
                        @if($userCurrency !== 'USD' && $tranche->max_amount)
                            <div style="font-size:0.65rem; color:#4a5568; margin-top:2px;">${{ number_format($tranche->max_amount, 0) }}</div>
                        @endif
                    </div>
                </div>

                @if($tranche->description)
                    <div class="tranche-desc">{{ $tranche->description }}</div>
                @endif

                <span class="tranche-cta">Investir dans cette tranche →</span>
            </a>
        @endforeach
    </div>
@endif
@endsection
