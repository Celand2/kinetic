@extends('layouts.app')

@section('title', 'Tableau de bord - KINETIC')
@section('page-title', 'TABLEAU DE BORD')
@section('page-subtitle', '// Vos investissements et soldes')

@section('content')
<div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem;">
    <h1 style="margin: 0; color: #c9a227;">Bienvenue, {{ $user->first_name ?? 'Utilisateur' }}</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-secondary">Déconnexion</button>
    </form>
</div>

<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="stat-box">
        <div class="stat-label">Solde Principal</div>
        <div class="stat-value" style="color: #81c784;">${{ number_format($user->balance, 2) }}</div>
        @if($exchangeRate)
            <div style="font-size: 0.8rem; color: #7a9cc6; margin-top: 0.25rem;">
                ≈ {{ strtoupper($exchangeRate->currency) }} {{ number_format($user->balance * $exchangeRate->rate_to_usd, 2) }}
            </div>
        @endif
    </div>
    <div class="stat-box">
        <div class="stat-label">Solde Parrainage</div>
        <div class="stat-value" style="color: #c9a227;">${{ number_format($user->referral_balance, 2) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Gains /jour (Estimé)</div>
        <div class="stat-value" style="color: #81c784;">+${{ number_format($dailyGains, 2) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Total Investi</div>
        <div class="stat-value" style="color: #c9a227;">${{ number_format($totalInvested, 2) }}</div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
    <div class="card" style="background: linear-gradient(135deg, rgba(201,162,39,0.1), rgba(201,162,39,0.05)); border: 1px solid rgba(201,162,39,0.3);">
        <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 1rem; padding-bottom: 0.75rem;">Actions Rapides</div>
        <div style="display: grid; gap: 0.75rem;">
            <a href="{{ route('investments.create') }}" class="btn">Investir</a>
            <a href="{{ route('transactions.deposit') }}" class="btn">Demander un Dépôt</a>
            <a href="{{ route('transactions.withdraw') }}" class="btn">Demander un Retrait</a>
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
                <div style="font-size: 1.5rem; color: #81c784; font-weight: 600;">${{ number_format($referralEarnings, 2) }}</div>
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
                                <div>{{ $inv->reference }} · {{ $inv->tradingCycle->name }} · ${{ number_format($inv->amount, 2) }}</div>
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
                        <td style="color: #c9a227; font-weight: 600;">${{ number_format($investment->amount, 2) }}</td>
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
                        <td style="padding: 0.75rem 0; color: #c9a227; font-weight: 600;">${{ number_format($transaction->amount, 2) }}</td>
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
