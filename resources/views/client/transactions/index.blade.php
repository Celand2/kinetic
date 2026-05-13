@extends('layouts.client')
@section('title', 'Transactions - KINETIC')
@section('back')<a href="{{ route('dashboard') }}" class="kts-back-btn">← Tableau de bord</a>@endsection

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
    <h1 style="color:#c9a227; font-size:1.2rem; margin:0;">Mes Transactions</h1>
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        <a href="{{ route('transactions.deposit') }}" class="kts-btn">💳 Dépôt</a>
        <a href="{{ route('transactions.withdraw') }}" class="kts-btn">💸 Retrait</a>
    </div>
</div>

<div class="card">
    @if($transactions->count() > 0)
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Référence</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                @php
                    $sc = ['completed'=>'#81c784','pending'=>'#fbc02d','rejected'=>'#ef5350','cancelled'=>'#b0bfd9'];
                    $sl = ['completed'=>'Complété','pending'=>'En attente','rejected'=>'Refusé','cancelled'=>'Annulé'];
                    $tc = ['deposit'=>'#81c784','withdrawal'=>'#fbc02d','daily_profit'=>'#c9a227'];
                    $tl = ['deposit'=>'Dépôt','withdrawal'=>'Retrait','daily_profit'=>'Profit','investment_buy'=>'Investissement','referral_bonus'=>'Parrainage','admin_adjustment'=>'Ajustement'];
                @endphp
                <tr>
                    <td style="font-size:0.8rem; color:#b0bfd9; white-space:nowrap;">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    <td style="font-family:monospace; font-size:0.78rem;">{{ $transaction->reference }}</td>
                    <td><span style="color:{{ $tc[$transaction->type] ?? '#b0bfd9' }}; font-size:0.82rem; font-weight:600;">{{ $tl[$transaction->type] ?? ucfirst($transaction->type) }}</span></td>
                    <td style="color:#c9a227; font-weight:700;">{{ $transaction->formatted_amount }}</td>
                    <td><span style="color:{{ $sc[$transaction->status] ?? '#b0bfd9' }}; font-size:0.82rem; font-weight:600;">{{ $sl[$transaction->status] ?? ucfirst($transaction->status) }}</span></td>
                    <td><a href="{{ route('transactions.show', $transaction) }}" class="kts-btn kts-btn-sm">Voir →</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div style="margin-top:1.5rem;">{{ $transactions->links() }}</div>
    @else
        <div style="text-align:center; padding:3rem; color:#b0bfd9;">
            <div style="font-size:2rem; margin-bottom:0.75rem;">📋</div>
            <p>Aucune transaction pour le moment.</p>
            <a href="{{ route('transactions.deposit') }}" class="kts-btn" style="margin-top:1rem;">💳 Faire un dépôt</a>
        </div>
    @endif
</div>
@endsection
