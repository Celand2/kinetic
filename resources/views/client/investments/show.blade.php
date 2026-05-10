@extends('layouts.client')

@section('title', $investment->reference . ' - KINETIC')
@section('page-title', 'DÉTAILS INVESTISSEMENT')
@section('page-subtitle', '// ' . $investment->tradingCycle->name . ' · ' . $investment->tranche->name)

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('investments.index') }}" style="color: #c9a227; display: inline-flex; align-items: center; gap: 0.4rem;">
        ← Retour aux investissements
    </a>
</div>

{{-- Status Alert --}}
@if($investment->status === 'pending')
    <div style="background: rgba(251,192,45,0.1); border: 1px solid rgba(251,192,45,0.3); border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem;">
        <div style="color: #fbc02d; font-size: 0.9rem;">
            ⏳ <strong>En attente de confirmation</strong> — Votre investissement sera activé une fois approuvé par un admin.
            <br><strong>Référence :</strong> {{ $investment->reference }}
        </div>
    </div>
@endif

<div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
    {{-- Détails investissement --}}
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem; margin-bottom: 1rem;">
            Détails de l'investissement
        </div>
        <div style="display: grid; gap: 1rem;">
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Référence</div>
                <div style="font-size: 1rem; font-family: 'Space Mono', monospace; color: #c9a227; font-weight: 600;">{{ $investment->reference }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Cycle</div>
                <div style="font-size: 1rem;">{{ $investment->tradingCycle->name }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Tranche</div>
                <div style="font-size: 1rem;">{{ $investment->tranche->name }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Montant investi</div>
                <div style="font-size: 1.15rem; color: #c9a227; font-weight: 700;">${{ number_format($investment->amount, 2) }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Statut</div>
                <div style="font-size: 1rem;">
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; text-transform: uppercase; font-weight: 600;
                        {{ $investment->status === 'active' ? 'background: rgba(129,199,132,0.15); color: #81c784;' : ($investment->status === 'pending' ? 'background: rgba(251,192,45,0.15); color: #fbc02d;' : 'background: rgba(239,83,80,0.15); color: #ef5350;') }}">
                        {{ ucfirst($investment->status) }}
                    </span>
                </div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Durée</div>
                <div style="font-size: 1rem;">{{ $investment->duration_days }} jours</div>
            </div>
        </div>
    </div>

    {{-- Informations profit --}}
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem; margin-bottom: 1rem;">
            Informations profit
        </div>
        <div style="display: grid; gap: 1rem;">
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Profit /jour</div>
                <div style="font-size: 1.15rem; color: #c9a227; font-weight: 700;">{{ $investment->daily_profit_rate }}%</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Retour total</div>
                <div style="font-size: 1.15rem; color: #81c784; font-weight: 700;">{{ $investment->total_return_rate }}%</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Profit estimé</div>
                <div style="font-size: 1.15rem; color: #81c784; font-weight: 700;">
                    ${{ number_format($investment->amount * ($investment->total_return_rate / 100), 2) }}
                </div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Profit crédité</div>
                <div style="font-size: 1rem;">
                    ${{ number_format($investment->total_profit_credited ?? 0, 2) }}
                    @if($investment->days_credited)
                        <span style="color: #7a9cc6; font-size: 0.8rem;">
                            ({{ $investment->days_credited }}/{{ $investment->duration_days }} jours)
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Timeline --}}
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem; margin-bottom: 1rem;">
        Chronologie
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <div>
            <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Créé le</div>
            <div>{{ $investment->created_at->format('d/m/Y à H:i') }}</div>
        </div>
        @if($investment->started_at)
        <div>
            <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Démarré le</div>
            <div>{{ $investment->started_at->format('d/m/Y à H:i') }}</div>
        </div>
        <div>
            <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Se termine le</div>
            <div style="color: #c9a227; font-weight: 600;">{{ $investment->ends_at->format('d/m/Y à H:i') }}</div>
        </div>
        @endif
        <div>
            <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Dernier profit crédité</div>
            <div>
                @if($investment->last_profit_credited_at)
                    {{ $investment->last_profit_credited_at->format('d/m/Y à H:i') }}
                @else
                    <span style="color: #7a9cc6;">Pas encore crédité</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Transactions --}}
@if($investment->transactions && $investment->transactions->count() > 0)
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem; margin-bottom: 1rem;">
            Transactions associées
        </div>
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
                @foreach($investment->transactions as $transaction)
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
    </div>
@endif

@endsection
