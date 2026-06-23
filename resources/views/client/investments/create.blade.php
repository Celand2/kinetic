@extends('layouts.client')

@section('title', __('investments.create_title'))
@section('page-title', __('investments.create_heading'))
@section('page-subtitle', __('investments.create_subtitle'))

@push('styles')
<style>
    .cycles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    .cycle-card {
        background: linear-gradient(135deg, #0d1f35 0%, #0a1628 100%);
        border: 1px solid #1e3a5f;
        border-radius: 16px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: transform .2s, border-color .2s, box-shadow .2s;
        text-decoration: none;
        display: block;
        color: inherit;
    }
    .cycle-card:hover {
        transform: translateY(-4px);
        border-color: #c9a227;
        box-shadow: 0 8px 32px rgba(201,162,39,.25);
    }
    .cycle-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 80px; height: 80px;
        background: radial-gradient(circle, rgba(201,162,39,.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .cycle-badge {
        font-size: .72rem;
        font-family: 'Space Mono', monospace;
        color: #c9a227;
        letter-spacing: .1em;
        text-transform: uppercase;
        margin-bottom: .75rem;
    }
    .cycle-name {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: .4rem;
    }
    .cycle-duration {
        font-size: .85rem;
        color: #b0bfd9;
        margin-bottom: 1.5rem;
    }
    .cycle-rate-big {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        font-weight: 900;
        color: #c9a227;
        line-height: 1;
    }
    .cycle-rate-label {
        font-size: .78rem;
        color: #7a9cc6;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-top: .3rem;
        margin-bottom: 1.5rem;
    }
    .cycle-return-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255,255,255,.04);
        border-radius: 8px;
        padding: .6rem 1rem;
        margin-bottom: 1.5rem;
    }
    .cycle-return-row .key { font-size: .8rem; color: #7a9cc6; }
    .cycle-return-row .val { font-family: 'Orbitron', sans-serif; font-size: .95rem; color: #81c784; font-weight: 700; }
    .cycle-tranches-count {
        font-size: .8rem;
        color: #b0bfd9;
        margin-bottom: 1.25rem;
    }
    .cycle-cta {
        display: block;
        width: 100%;
        text-align: center;
        background: linear-gradient(135deg, #c9a227, #a07d1a);
        color: #0a1628;
        font-family: 'Orbitron', sans-serif;
        font-size: .85rem;
        font-weight: 700;
        padding: .75rem;
        border-radius: 8px;
        letter-spacing: .05em;
        text-decoration: none;
        transition: opacity .2s;
    }
    .cycle-cta:hover { opacity: .9; }
    .cycle-inactive {
        opacity: .45;
        pointer-events: none;
    }
    .section-eyebrow {
        font-family: 'Space Mono', monospace;
        font-size: .75rem;
        color: #c9a227;
        letter-spacing: .15em;
        margin-bottom: .5rem;
    }
    .section-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.4rem;
        color: #fff;
        margin-bottom: 0;
    }
    .section-title span { color: #c9a227; }
</style>
@endpush

@section('content')
<div class="section-eyebrow">{{ __('investments.cycles_eyebrow') }}</div>
<h2 class="section-title">{{ __('investments.choose_strategy') }}</h2>

@if($cycles->isEmpty())
    <div style="text-align:center; padding: 4rem 2rem; color: #b0bfd9;">
        {{ __('investments.no_cycles') }}
    </div>
@else
    <div class="cycles-grid">
        @foreach($cycles as $cycle)
            <a href="{{ route('investments.cycle.tranches', $cycle) }}"
               class="cycle-card {{ !$cycle->is_active ? 'cycle-inactive' : '' }}">

                <div class="cycle-badge">{{ __('investments.cycle_badge', ['number' => $loop->iteration]) }}</div>
                <div class="cycle-name">{{ $cycle->name }}</div>
                <div class="cycle-duration">{{ $cycle->duration_days }} jours · {{ $cycle->description ?? __('investments.default_description') }}</div>

                <div class="cycle-rate-big">{{ $cycle->daily_profit_percent }}%</div>
                <div class="cycle-rate-label">{{ __('investments.profit_daily') }}</div>

                <div class="cycle-return-row">
                    <span class="key">{{ __('investments.total_return') }}</span>
                    <span class="val">{{ $cycle->total_return_percent }}%</span>
                </div>

                <div class="cycle-tranches-count">
                    {{ trans_choice('investments.tranches_available', $cycle->tranches_count, ['count' => $cycle->tranches_count]) }}
                </div>

                <span class="cycle-cta">{{ __('investments.choose_cycle') }}</span>
            </a>
        @endforeach
    </div>
@endif
@endsection
