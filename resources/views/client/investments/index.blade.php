@extends('layouts.client')

@section('title', 'Mes Investissements - KINETIC')
@php
    $iUser      = auth()->user();
    $iCurrency  = $iUser->preferred_currency ?? 'USD';
    $iRate      = \App\Models\ExchangeRate::rate($iCurrency);
    function fmtInvAmt($usd, $currency, $rate) {
        if ($currency === 'USD') return '$' . number_format($usd, 2);
        return number_format($usd * $rate, 0, ',', ' ') . ' ' . $currency;
    }
@endphp
@section('page-title', 'INVESTISSEMENTS')
@section('page-subtitle', '// Vos placements actifs')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <h1 style="color: #c9a227; margin: 0;">Mes Investissements</h1>
    <a href="{{ route('investments.create') }}" class="btn">+ Nouvel investissement</a>
</div>

<div class="card">
    @if($investments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Cycle</th>
                    <th>Tranche</th>
                    <th>Montant</th>
                    <th>Profit /jour</th>
                    <th>Statut</th>
                    <th>Fin</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($investments as $investment)
                    <tr>
                        <td><strong>{{ $investment->reference }}</strong></td>
                        <td>{{ $investment->tradingCycle->name }}</td>
                        <td>{{ $investment->tranche->name }}</td>
                        <td>
                            {{ fmtInvAmt($investment->amount, $iCurrency, $iRate) }}
                            @if($iCurrency !== 'USD')
                                <div style="font-size:0.7rem; color:#4a5568;">${{ number_format($investment->amount, 2) }}</div>
                            @endif
                        </td>
                        <td>{{ $investment->daily_profit_rate }}%</td>
                        <td>
                            <span style="color: {{ $investment->status === 'active' ? '#81c784' : ($investment->status === 'pending' ? '#fbc02d' : '#ef5350') }}; text-transform: uppercase; font-size: 0.82rem; font-weight: 600;">
                                {{ ucfirst($investment->status) }}
                            </span>
                        </td>
                        <td>
                            @if($investment->ends_at)
                                {{ $investment->ends_at->format('d/m/Y') }}
                            @else
                                —
                            @endif
                        </td>
                        <td style="display:flex; gap:0.4rem; align-items:center; flex-wrap:wrap;">
                            <a href="{{ route('investments.show', $investment) }}" class="kts-btn kts-btn-sm">Voir →</a>
                            @if($investment->tradingCycle->is_active && $investment->tranche->is_active)
                            <a href="{{ route('investments.invest', [$investment->tradingCycle, $investment->tranche]) }}"
                               class="kts-btn kts-btn-sm kts-btn-success" title="Réinvestir dans cette tranche">🔁</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $investments->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 3rem; color: #b0bfd9;">
            <div style="font-size: 2.5rem; margin-bottom: 1rem;">📈</div>
            <p>Aucun investissement pour le moment.</p>
            <a href="{{ route('investments.create') }}" class="btn" style="margin-top: 1rem;">Créer votre premier investissement</a>
        </div>
    @endif
</div>
@endsection

