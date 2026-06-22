@extends('layouts.client')
@section('title', __('transactions.title'))
@section('back')<a href="{{ route('dashboard') }}" class="kts-back-btn">← {{ __('common.dashboard') }}</a>@endsection

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
    <h1 style="color:#c9a227; font-size:1.2rem; margin:0;">{{ __('transactions.my_transactions') }}</h1>
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        <a href="{{ route('transactions.deposit') }}" class="kts-btn">💳 {{ __('transactions.deposit') }}</a>
        <a href="{{ route('transactions.withdraw') }}" class="kts-btn">💸 {{ __('transactions.withdrawal') }}</a>
    </div>
</div>

<div class="card">
    @if($transactions->count() > 0)
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>{{ __('common.date') }}</th>
                    <th>{{ __('common.reference') }}</th>
                    <th>{{ __('common.type') }}</th>
                    <th>{{ __('common.amount') }}</th>
                    <th>{{ __('common.status') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                @php
                    $sc = ['completed'=>'#81c784','pending'=>'#fbc02d','rejected'=>'#ef5350','cancelled'=>'#b0bfd9'];
                    $sl = ['completed'=>__('common.completed'),'pending'=>__('common.pending'),'rejected'=>__('common.rejected'),'cancelled'=>__('common.cancelled')];
                    $tc = ['deposit'=>'#81c784','withdrawal'=>'#fbc02d','daily_profit'=>'#c9a227'];
                    $tl = ['deposit'=>__('transactions.deposit'),'withdrawal'=>__('transactions.withdrawal'),'daily_profit'=>__('transactions.daily_profit'),'investment_buy'=>__('transactions.investment_buy'),'referral_bonus'=>__('transactions.referral_bonus'),'admin_adjustment'=>__('transactions.admin_adjustment')];
                @endphp
                <tr>
                    <td style="font-size:0.8rem; color:#b0bfd9; white-space:nowrap;">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    <td style="font-family:monospace; font-size:0.78rem;">{{ $transaction->reference }}</td>
                    <td><span style="color:{{ $tc[$transaction->type] ?? '#b0bfd9' }}; font-size:0.82rem; font-weight:600;">{{ $tl[$transaction->type] ?? ucfirst($transaction->type) }}</span></td>
                    <td style="color:#c9a227; font-weight:700;">{{ $transaction->formatted_amount }}</td>
                    <td><span style="color:{{ $sc[$transaction->status] ?? '#b0bfd9' }}; font-size:0.82rem; font-weight:600;">{{ $sl[$transaction->status] ?? ucfirst($transaction->status) }}</span></td>
                    <td><a href="{{ route('transactions.show', $transaction) }}" class="kts-btn kts-btn-sm">{{ __('common.view') }} →</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div style="margin-top:1.5rem;">{{ $transactions->links() }}</div>
    @else
        <div style="text-align:center; padding:3rem; color:#b0bfd9;">
            <div style="font-size:2rem; margin-bottom:0.75rem;">📋</div>
            <p>{{ __('transactions.no_transaction') }}</p>
            <a href="{{ route('transactions.deposit') }}" class="kts-btn" style="margin-top:1rem;">💳 {{ __('transactions.make_deposit') }}</a>
        </div>
    @endif
</div>
@endsection
