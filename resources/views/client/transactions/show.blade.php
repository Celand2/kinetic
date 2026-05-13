@extends('layouts.client')
@section('title', 'Détail Transaction - KINETIC')
@section('back')<a href="{{ route('transactions.index') }}" class="kts-back-btn">← Mes Transactions</a>@endsection

@section('content')
@php
    $statusColors = ['completed'=>'#81c784','pending'=>'#fbc02d','rejected'=>'#ef5350','cancelled'=>'#b0bfd9'];
    $statusLabels = ['completed'=>'Complété','pending'=>'En attente','rejected'=>'Refusé','cancelled'=>'Annulé'];
    $typeLabels   = ['deposit'=>'Dépôt','withdrawal'=>'Retrait','daily_profit'=>'Profit journalier','investment_buy'=>'Investissement','referral_bonus'=>'Commission parrainage','admin_adjustment'=>'Ajustement admin'];
@endphp

<div class="card" style="max-width:560px; margin:0 auto;">
    <div style="text-align:center; padding:1.5rem 0 1rem;">
        {{-- Montant principal --}}
        <div style="font-family:'Space Mono',monospace; font-size:2rem; font-weight:700; color:#c9a227; line-height:1.1;">
            {{ $transaction->formatted_amount }}
        </div>
        {{-- Équivalent USD si devise locale --}}
        @if($transaction->display_currency !== 'USD')
            <div style="font-size:0.78rem; color:#6b7a9a; margin-top:4px;">
                ≈ ${{ number_format($transaction->amount, 2) }} USD
            </div>
        @endif
        {{-- Statut --}}
        <div style="margin-top:0.75rem;">
            <span style="background:rgba({{ $transaction->status==='completed' ? '129,199,132' : ($transaction->status==='pending' ? '251,192,45' : '239,83,80') }},0.12); color:{{ $statusColors[$transaction->status] ?? '#b0bfd9' }}; padding:4px 14px; border-radius:20px; font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">
                {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
            </span>
        </div>
    </div>

    <div style="border-top:1px solid rgba(255,255,255,0.07); padding-top:1.25rem; display:grid; gap:1rem;">

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="color:#6b7a9a; font-size:0.82rem;">Type</span>
            <span style="font-weight:600;">{{ $typeLabels[$transaction->type] ?? ucfirst(str_replace('_',' ',$transaction->type)) }}</span>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="color:#6b7a9a; font-size:0.82rem;">Référence</span>
            <span style="font-family:monospace; font-size:0.85rem; color:#b0bfd9;">{{ $transaction->reference }}</span>
        </div>

        @if($transaction->payment_method)
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="color:#6b7a9a; font-size:0.82rem;">Moyen de paiement</span>
            <span style="font-weight:600;">{{ $transaction->payment_method }}</span>
        </div>
        @endif

        @if($transaction->display_currency !== 'USD')
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="color:#6b7a9a; font-size:0.82rem;">Devise</span>
            <span style="background:rgba(201,162,39,0.1); border:1px solid rgba(201,162,39,0.25); color:#c9a227; padding:2px 8px; border-radius:20px; font-size:0.75rem; font-weight:700;">
                {{ $transaction->display_currency }}
            </span>
        </div>
        @if(isset($transaction->metadata['rate_used']) && $transaction->metadata['rate_used'])
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="color:#6b7a9a; font-size:0.82rem;">Taux appliqué</span>
            <span style="font-size:0.82rem; color:#b0bfd9;">1 USD = {{ number_format($transaction->metadata['rate_used'], 0, ',', ' ') }} {{ $transaction->display_currency }}</span>
        </div>
        @endif
        @endif

        @if($transaction->fee_amount && $transaction->fee_amount > 0)
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="color:#6b7a9a; font-size:0.82rem;">Frais</span>
            <span style="color:#fbc02d;">${{ number_format($transaction->fee_amount, 2) }}</span>
        </div>
        @endif

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="color:#6b7a9a; font-size:0.82rem;">Date</span>
            <span style="font-size:0.85rem;">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>

        @if($transaction->processed_at)
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="color:#6b7a9a; font-size:0.82rem;">Traité le</span>
            <span style="font-size:0.85rem;">{{ $transaction->processed_at->format('d/m/Y H:i') }}</span>
        </div>
        @endif

        @if($transaction->description)
        <div>
            <div style="color:#6b7a9a; font-size:0.82rem; margin-bottom:4px;">Description</div>
            <div style="font-size:0.88rem; color:#b0bfd9;">{{ $transaction->description }}</div>
        </div>
        @endif

        @if($transaction->admin_notes)
        <div style="background:rgba(201,162,39,0.06); border:1px solid rgba(201,162,39,0.18); border-radius:8px; padding:0.75rem;">
            <div style="color:#6b7a9a; font-size:0.78rem; margin-bottom:4px; text-transform:uppercase; letter-spacing:0.05em;">Note admin</div>
            <div style="font-size:0.88rem; color:#e8e8e8;">{{ $transaction->admin_notes }}</div>
        </div>
        @endif

        {{-- Capture d'écran si disponible --}}
        @if(isset($transaction->metadata['screenshot']))
        <div>
            <div style="color:#6b7a9a; font-size:0.82rem; margin-bottom:6px;">Preuve de paiement</div>
            <img src="{{ asset('storage/' . $transaction->metadata['screenshot']) }}"
                 alt="Preuve"
                 style="max-width:100%; border-radius:8px; border:1px solid rgba(201,162,39,0.3);">
        </div>
        @endif

    </div>
</div>
@endsection
