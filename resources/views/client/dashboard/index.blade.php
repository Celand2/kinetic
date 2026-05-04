@extends('layouts.app')

@section('title', 'Dashboard - KINETIC')

@section('content')
<div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem;">
    <h1 style="margin: 0; color: #c9a227;">Client Dashboard</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-secondary">Déconnexion</button>
    </form>
</div>

<div class="grid">
    <div class="stat-box">
        <div class="stat-label">Account Balance</div>
        <div class="stat-value">${{ number_format($user->balance, 2) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Referral Balance</div>
        <div class="stat-value">${{ number_format($user->referral_balance, 2) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Daily Gains Estimate</div>
        <div class="stat-value">${{ number_format($dailyGains, 2) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Total Invested</div>
        <div class="stat-value">${{ number_format($totalInvested, 2) }}</div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem;">
    <div class="card" style="background: rgba(201, 162, 39, 0.08);">
        <div class="card-header">Quick Actions</div>
        <div style="display: grid; gap: 0.75rem;">
            <a href="{{ route('investments.create') }}" class="btn">Invest Now</a>
            <a href="{{ route('transactions.deposit') }}" class="btn">Request Deposit</a>
            <a href="{{ route('transactions.withdraw') }}" class="btn">Request Withdrawal</a>
            <a href="{{ route('messages.create') }}" class="btn">Message Admin</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Referral Progress</div>
        <div style="display: grid; gap: 1rem;">
            <div class="stat-box">
                <div class="stat-label">Active Referrals</div>
                <div class="stat-value">{{ $referralCount }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Referral Earnings</div>
                <div class="stat-value">${{ number_format($referralEarnings, 2) }}</div>
            </div>
            <a href="{{ route('referral.dashboard') }}" class="btn">View Referral Dashboard</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Latest Activity</div>
        <div style="display: grid; gap: 1rem;">
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Active Investments</div>
                <div style="font-size: 1.5rem;">{{ $activeInvestments->count() }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Completed Investments</div>
                <div style="font-size: 1.5rem;">{{ $completedInvestments }}</div>
            </div>
            <a href="{{ route('transactions.index') }}" class="btn">View Transactions</a>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">Active Investments</div>

    @if($activeInvestments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Cycle</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Ends</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeInvestments as $investment)
                    <tr>
                        <td>{{ $investment->reference }}</td>
                        <td>{{ $investment->tradingCycle->name }}</td>
                        <td>${{ number_format($investment->amount, 2) }}</td>
                        <td><span style="color: #81c784;">{{ ucfirst($investment->status) }}</span></td>
                        <td>{{ $investment->ends_at->format('M d, Y') }}</td>
                        <td><a href="{{ route('investments.show', $investment) }}" style="color: #c9a227;">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 2rem;">No active investments yet. <a href="{{ route('investments.create') }}" style="color: #c9a227;">Start investing now</a></p>
    @endif
</div>

<div class="card">
    <div class="card-header">Recent Transactions</div>

    @if($recentTransactions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ ucfirst($transaction->type) }}</td>
                        <td>${{ number_format($transaction->amount, 2) }}</td>
                        <td><span style="color: {{ $transaction->status === 'completed' ? '#81c784' : '#fbc02d' }};">{{ ucfirst($transaction->status) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 2rem;">No transactions yet.</p>
    @endif
</div>
@endsection
