@extends('layouts.client')

@section('title', __('dashboard.title'))
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
        {{ __('dashboard.greeting', ['name' => explode(' ', $user->full_name)[0]]) }}
    </h1>
    @if($userCurrency !== 'USD')
        <div style="font-size:0.72rem; color:#6b7a9a; margin-top:2px;">
            {{ __('dashboard.currency') }} : <span style="color:#c9a227; font-weight:600;">{{ $userCurrency }}</span>
            &nbsp;·&nbsp; 1 $ = {{ number_format($currencyRate, 0, ',', ' ') }} {{ $userCurrency }}
        </div>
    @endif
</div>

{{-- SOLDES ────────────────────────────────────────────────────────── --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">

    {{-- Gains retirables (profit_balance) --}}
    <div class="card" style="padding:1rem; margin-bottom:0; border-color:rgba(129,199,132,0.35);">
        <div style="font-size:0.62rem; text-transform:uppercase; letter-spacing:0.08em; color:#6b7a9a; margin-bottom:4px;">{{ __('dashboard.withdrawable_gains') }}</div>
        <div style="font-family:'Space Mono',monospace; font-size:1.2rem; font-weight:700; color:#81c784; line-height:1.1;">
            {{ fmtLocal($user->profit_balance, $userCurrency, $currencyRate) }}
        </div>
        @if($userCurrency !== 'USD')
            <div style="font-size:0.68rem; color:#4a5568; margin-top:2px;">${{ number_format($user->profit_balance, 2) }} USD</div>
        @endif
        <div style="font-size:0.6rem; color:#81c784; opacity:0.6; margin-top:3px;">
            {{ $canWithdraw ? __('dashboard.withdrawal_available') : __('dashboard.available_after_first_profit') }}
        </div>
    </div>

    {{-- Solde investissable = balance - profit_balance (capital déposé, non retirable) --}}
    <div class="card" style="padding:1rem; margin-bottom:0; border-color:rgba(122,156,198,0.35);">
        <div style="font-size:0.62rem; text-transform:uppercase; letter-spacing:0.08em; color:#6b7a9a; margin-bottom:4px;">{{ __('dashboard.investable_balance') }}</div>
        <div style="font-family:'Space Mono',monospace; font-size:1.2rem; font-weight:700; color:#7a9cc6; line-height:1.1;">
            {{ fmtLocal($investableBalance, $userCurrency, $currencyRate) }}
        </div>
        @if($userCurrency !== 'USD')
            <div style="font-size:0.68rem; color:#4a5568; margin-top:2px;">${{ number_format($investableBalance, 2) }} USD</div>
        @endif
        <div style="font-size:0.6rem; color:#7a9cc6; opacity:0.6; margin-top:3px;">{{ __('dashboard.deposited_capital_not_withdrawable') }}</div>
    </div>

    {{-- Gains / jour --}}
    <div class="card" style="padding:1rem; margin-bottom:0;">
        <div style="font-size:0.62rem; text-transform:uppercase; letter-spacing:0.08em; color:#6b7a9a; margin-bottom:4px;">{{ __('dashboard.daily_gains') }}</div>
        <div style="font-family:'Space Mono',monospace; font-size:1.2rem; font-weight:700; color:#c9a227; line-height:1.1;">
            +{{ fmtLocal($dailyGains, $userCurrency, $currencyRate) }}
        </div>
        @if($userCurrency !== 'USD')
            <div style="font-size:0.68rem; color:#4a5568; margin-top:2px;">+${{ number_format($dailyGains, 2) }} USD/j</div>
        @endif
    </div>

    {{-- Total investi --}}
    <div class="card" style="padding:1rem; margin-bottom:0;">
        <div style="font-size:0.62rem; text-transform:uppercase; letter-spacing:0.08em; color:#6b7a9a; margin-bottom:4px;">{{ __('dashboard.total_invested') }}</div>
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
    ⏳ <strong>{{ __('dashboard.withdrawal_temporarily_unavailable') }}</strong> — {{ __('dashboard.first_profit_required') }}
</div>
@endif

<div class="card" style="margin-bottom: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
    <div class="card" style="background: linear-gradient(135deg, rgba(201,162,39,0.1), rgba(201,162,39,0.05)); border: 1px solid rgba(201,162,39,0.3);">
        <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 1rem; padding-bottom: 0.75rem;">{{ __('dashboard.quick_actions') }}</div>
        <div style="display: grid; gap: 0.75rem;">
            <a href="{{ route('investments.create') }}" class="btn">{{ __('dashboard.invest') }}</a>
            <a href="{{ route('transactions.deposit') }}" class="btn">{{ __('dashboard.request_deposit') }}</a>
            <a href="{{ route('transactions.withdraw') }}" class="btn">{{ __('dashboard.request_withdrawal') }}</a>
            <a href="{{ route('redeem-bonus.show') }}" class="btn">{{ __('dashboard.redeem_bonus') }}</a>
            <a href="{{ route('messages.create') }}" class="btn">{{ __('dashboard.message_admin') }}</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 1rem; padding-bottom: 0.75rem;">{{ __('dashboard.referral') }}</div>
        <div style="display: grid; gap: 1rem;">
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('dashboard.active_referrals') }}</div>
                <div style="font-size: 1.5rem; color: #c9a227; font-weight: 600;">{{ $referralCount }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('dashboard.referral_earnings') }}</div>
                <div style="font-size: 1.5rem; color: #81c784; font-weight: 600;">{{ fmtLocal($referralEarnings, $userCurrency, $currencyRate) }}</div>
            </div>
            <a href="{{ route('referral.dashboard') }}" class="btn">{{ __('dashboard.view_referral_dashboard') }}</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 1rem; padding-bottom: 0.75rem;">{{ __('dashboard.recent_activity') }}</div>
        <div style="display: grid; gap: 1rem;">
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('dashboard.active_investments') }}</div>
                <div style="font-size: 1.5rem; color: #81c784; font-weight: 600;">{{ $activeInvestments->count() }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('dashboard.completed_investments') }}</div>
                <div style="font-size: 1.5rem; color: #c9a227; font-weight: 600;">{{ $completedInvestments }}</div>
            </div>
            <a href="{{ route('transactions.index') }}" class="btn">{{ __('dashboard.view_transactions') }}</a>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    {{-- Investissements en attente --}}
    @if($pendingInvestments->count() > 0)
        <div style="background: rgba(251,192,45,0.1); border: 1px solid rgba(251,192,45,0.3); border-radius: 10px; padding: 1rem; margin-bottom: 1rem;">
            <div style="color: #fbc02d; font-size: 0.9rem;">
                ⏳ <strong>{{ __('dashboard.pending_investments', ['count' => $pendingInvestments->count()]) }}</strong>
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

    <div class="card-header">{{ __('dashboard.active_investments') }}</div>

    @if($activeInvestments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>{{ __('common.reference') }}</th>
                    <th>{{ __('dashboard.cycle') }}</th>
                    <th>{{ __('common.amount') }}</th>
                    <th>{{ __('common.status') }}</th>
                    <th>{{ __('dashboard.end') }}</th>
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
                        <td><a href="{{ route('investments.show', $investment) }}" style="color: #c9a227;">{{ __('common.view') }} →</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 2rem;">{{ __('dashboard.no_active_investment') }} <a href="{{ route('investments.create') }}" style="color: #c9a227;">{{ __('dashboard.start_investing') }}</a></p>
    @endif
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem; margin-bottom: 1rem;">{{ __('dashboard.recent_transactions') }}</div>

    @if($recentTransactions->count() > 0)
        <table>
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                    <th style="text-align: left; padding: 0.75rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('common.date') }}</th>
                    <th style="text-align: left; padding: 0.75rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('common.type') }}</th>
                    <th style="text-align: left; padding: 0.75rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('common.amount') }}</th>
                    <th style="text-align: left; padding: 0.75rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('common.status') }}</th>
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
        <p style="color: #b0bfd9; text-align: center; padding: 2rem;">{{ __('dashboard.no_transaction') }} <a href="{{ route('transactions.deposit') }}" style="color: #c9a227;">{{ __('dashboard.make_deposit') }}</a></p>
    @endif
</div>
@endsection
