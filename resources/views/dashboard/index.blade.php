@extends('layouts.app')

@section('title', 'Dashboard - KINETIC')

@section('content')
<h1 style="margin-bottom: 2rem; color: #c9a227;">Dashboard</h1>

<!-- Quick Stats -->
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
        <div class="stat-label">Total Invested</div>
        <div class="stat-value">${{ number_format($totalInvested, 2) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Total Earned</div>
        <div class="stat-value">${{ number_format($totalEarned, 2) }}</div>
    </div>
</div>

<!-- Active Investments -->
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        Active Investments
        <a href="{{ route('investments.create') }}" class="btn" style="float: right; margin-top: -0.5rem;">+ New Investment</a>
    </div>
    
    <div class="card-header">
        admin
        <a href="{{ route('admin.finance.transactions') }}" class="btn" style="float: right; margin-top: -0.5rem;">admin</a>
    </div>

    @if($activeInvestments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Cycle</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Progress</th>
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
                        <td>
                            @php
                                $days = $investment->started_at->diffInDays(now());
                                $progress = ($days / $investment->duration_days) * 100;
                            @endphp
                            {{ $days }} / {{ $investment->duration_days }} days ({{ round($progress) }}%)
                        </td>
                        <td><a href="{{ route('investments.show', $investment) }}" style="color: #c9a227;">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 2rem;">
            No active investments yet. <a href="{{ route('investments.create') }}" style="color: #c9a227;">Start investing now</a>
        </p>
    @endif
</div>

<!-- Recent Transactions -->
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
                        <td>
                            <span style="color: {{ $transaction->status === 'completed' ? '#81c784' : '#fbc02d' }};">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 2rem;">No transactions yet.</p>
    @endif
</div>
@endsection
