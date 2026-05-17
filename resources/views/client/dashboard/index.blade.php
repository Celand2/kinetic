@extends('layouts.client')

@section('title', 'Tableau de bord - KINETIC')
@section('content')
@php
    // Helper de formatage en devise locale
    function fmtLocal($usd, $currency, $rate) {
        if ($currency === 'USD') return '$' . number_format($usd, 2);
        return number_format($usd * $rate, 0, ',', ' ') . ' ' . $currency;
    }
@endphp

<div style="margin-bottom:1.25rem;">
    <h1 style="color:#c9a227; font-size:1.15rem; margin:0;">
        Bonjour, {{ explode(' ', $user->full_name)[0] }} 👋
    </h1>
    @if($userCurrency !== 'USD')
        <div style="font-size:0.72rem; color:#6b7a9a; margin-top:2px;">
            Devise : <span style="color:#c9a227; font-weight:600;">{{ $userCurrency }}</span>
            &nbsp;·&nbsp; 1 $ = {{ number_format($currencyRate, 0, ',', ' ') }} {{ $userCurrency }}
        </div>
    @endif
</div>

{{-- SOLDES ────────────────────────────────────────────────────────── --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">

    {{-- Gains retirables (profit_balance) --}}
    <div class="card" style="padding:1rem; margin-bottom:0; border-color:rgba(129,199,132,0.35);">
        <div style="font-size:0.62rem; text-transform:uppercase; letter-spacing:0.08em; color:#6b7a9a; margin-bottom:4px;">Gains retirables</div>
        <div style="font-family:'Space Mono',monospace; font-size:1.2rem; font-weight:700; color:#81c784; line-height:1.1;">
            {{ fmtLocal($user->profit_balance, $userCurrency, $currencyRate) }}
        </div>
        @if($userCurrency !== 'USD')
            <div style="font-size:0.68rem; color:#4a5568; margin-top:2px;">${{ number_format($user->profit_balance, 2) }} USD</div>
        @endif
        <div style="font-size:0.6rem; color:#81c784; opacity:0.6; margin-top:3px;">
            {{ $canWithdraw ? '✓ Retrait disponible' : '⏳ Dispo après 1er profit' }}
        </div>
    </div>

    {{-- Solde investissable = balance - profit_balance (capital déposé, non retirable) --}}
    <div class="card" style="padding:1rem; margin-bottom:0; border-color:rgba(122,156,198,0.35);">
        <div style="font-size:0.62rem; text-transform:uppercase; letter-spacing:0.08em; color:#6b7a9a; margin-bottom:4px;">Solde investissable</div>
        <div style="font-family:'Space Mono',monospace; font-size:1.2rem; font-weight:700; color:#7a9cc6; line-height:1.1;">
            {{ fmtLocal($investableBalance, $userCurrency, $currencyRate) }}
        </div>
        @if($userCurrency !== 'USD')
            <div style="font-size:0.68rem; color:#4a5568; margin-top:2px;">${{ number_format($investableBalance, 2) }} USD</div>
        @endif
        <div style="font-size:0.6rem; color:#7a9cc6; opacity:0.6; margin-top:3px;">Capital déposé · non retirable</div>
    </div>

    {{-- Gains / jour --}}
    <div class="card" style="padding:1rem; margin-bottom:0;">
        <div style="font-size:0.62rem; text-transform:uppercase; letter-spacing:0.08em; color:#6b7a9a; margin-bottom:4px;">Gains / jour</div>
        <div style="font-family:'Space Mono',monospace; font-size:1.2rem; font-weight:700; color:#c9a227; line-height:1.1;">
            +{{ fmtLocal($dailyGains, $userCurrency, $currencyRate) }}
        </div>
        @if($userCurrency !== 'USD')
            <div style="font-size:0.68rem; color:#4a5568; margin-top:2px;">+${{ number_format($dailyGains, 2) }} USD/j</div>
        @endif
    </div>

    {{-- Total investi --}}
    <div class="card" style="padding:1rem; margin-bottom:0;">
        <div style="font-size:0.62rem; text-transform:uppercase; letter-spacing:0.08em; color:#6b7a9a; margin-bottom:4px;">Total investi</div>
        <div style="font-family:'Space Mono',monospace; font-size:1.1rem; font-weight:700; color:#c9a227; line-height:1.1;">
            {{ fmtLocal($totalInvested, $userCurrency, $currencyRate) }}
        </div>
        @if($userCurrency !== 'USD')
            <div style="font-size:0.68rem; color:#4a5568; margin-top:2px;">${{ number_format($totalInvested, 2) }} USD</div>
        @endif
    </div>

</div>

{{-- Bannière retrait bloqué si pas encore de profit journalier --}}
@if(!$canWithdraw && $user->profit_balance > 0)
<div style="background:rgba(251,192,45,0.08); border:1px solid rgba(251,192,45,0.3); border-radius:8px; padding:0.7rem 1rem; margin-bottom:1rem; font-size:0.8rem; color:#fbc02d;">
    ⏳ <strong>Retrait temporairement indisponible</strong> — Votre premier profit journalier doit être crédité avant tout retrait (automatiquement sous 24h après votre investissement).
</div>
@endif

<div class="card" style="margin-bottom: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
    <div class="card" style="background: linear-gradient(135deg, rgba(201,162,39,0.1), rgba(201,162,39,0.05)); border: 1px solid rgba(201,162,39,0.3);">
        <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 1rem; padding-bottom: 0.75rem;">Actions Rapides</div>
        <div style="display: grid; gap: 0.75rem;">
            <a href="{{ route('investments.create') }}" class="btn">Investir</a>
            <a href="{{ route('transactions.deposit') }}" class="btn">Demander un Dépôt</a>
            <a href="{{ route('transactions.withdraw') }}" class="btn">Demander un Retrait</a>
            <a href="{{ route('redeem-bonus.show') }}" class="btn">Échanger Code Bonus</a>
            <a href="{{ route('messages.create') }}" class="btn">Message à l'Admin</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 1rem; padding-bottom: 0.75rem;">Parrainage</div>
        <div style="display: grid; gap: 1rem;">
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Parrainages Actifs</div>
                <div style="font-size: 1.5rem; color: #c9a227; font-weight: 600;">{{ $referralCount }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Gains Parrainage</div>
                <div style="font-size: 1.5rem; color: #81c784; font-weight: 600;">{{ fmtLocal($referralEarnings, $userCurrency, $currencyRate) }}</div>
            </div>
            <a href="{{ route('referral.dashboard') }}" class="btn">Voir Tableau Parrainage</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 1rem; padding-bottom: 0.75rem;">Activité Récente</div>
        <div style="display: grid; gap: 1rem;">
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Investissements Actifs</div>
                <div style="font-size: 1.5rem; color: #81c784; font-weight: 600;">{{ $activeInvestments->count() }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Investissements Complétés</div>
                <div style="font-size: 1.5rem; color: #c9a227; font-weight: 600;">{{ $completedInvestments }}</div>
            </div>
            <a href="{{ route('transactions.index') }}" class="btn">Voir Transactions</a>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    {{-- Investissements en attente --}}
    @if($pendingInvestments->count() > 0)
        <div style="background: rgba(251,192,45,0.1); border: 1px solid rgba(251,192,45,0.3); border-radius: 10px; padding: 1rem; margin-bottom: 1rem;">
            <div style="color: #fbc02d; font-size: 0.9rem;">
                ⏳ <strong>{{ $pendingInvestments->count() }} investissement(s) en attente d'approbation</strong>
                <table style="margin-top: 0.75rem; width: 100%;">
                    <tr style="font-size: 0.85rem; opacity: 0.9;">
                        <td style="padding: 0.5rem 0;">
                            @foreach($pendingInvestments as $inv)
                                <div>{{ $inv->reference }} · {{ $inv->tradingCycle->name }} · ${{ number_format($inv->amount, 2) }} USD</div>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endif

    <div class="card-header">Investissements Actifs</div>

    @if($activeInvestments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Cycle</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Fin</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeInvestments as $investment)
                    <tr>
                        <td><strong>{{ $investment->reference }}</strong></td>
                        <td>{{ $investment->tradingCycle->name }}</td>
                        <td style="color: #c9a227; font-weight: 600;">
                            {{ fmtLocal($investment->amount, $userCurrency, $currencyRate) }}
                            @if($userCurrency !== 'USD')
                                <div style="font-size:0.68rem; color:#4a5568;">${{ number_format($investment->amount, 2) }}</div>
                            @endif
                        </td>
                        <td><span style="color: #81c784; font-size: 0.85rem; text-transform: uppercase; font-weight: 600;">{{ ucfirst($investment->status) }}</span></td>
                        <td>{{ $investment->ends_at->format('d/m/Y') }}</td>
                        <td><a href="{{ route('investments.show', $investment) }}" style="color: #c9a227;">Voir →</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 2rem;">Aucun investissement actif. <a href="{{ route('investments.create') }}" style="color: #c9a227;">Commencer à investir</a></p>
    @endif
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem; margin-bottom: 1rem;">Transactions Récentes</div>

    @if($recentTransactions->count() > 0)
        <table>
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                    <th style="text-align: left; padding: 0.75rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Date</th>
                    <th style="text-align: left; padding: 0.75rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Type</th>
                    <th style="text-align: left; padding: 0.75rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Montant</th>
                    <th style="text-align: left; padding: 0.75rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $transaction)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                        <td style="padding: 0.75rem 0;">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 0.75rem 0;">{{ str_replace('_', ' ', ucfirst($transaction->type)) }}</td>
                        <td style="padding: 0.75rem 0; color: #c9a227; font-weight: 600;">{{ $transaction->formatted_amount }}</td>
                        <td style="padding: 0.75rem 0;">
                            <span style="font-size: 0.8rem; text-transform: uppercase; font-weight: 600; color: {{ $transaction->status === 'completed' ? '#81c784' : ($transaction->status === 'pending' ? '#fbc02d' : '#ef5350') }};">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 2rem;">Aucune transaction pour le moment. <a href="{{ route('transactions.deposit') }}" style="color: #c9a227;">Faire un dépôt</a></p>
    @endif
</div>
@endsection
