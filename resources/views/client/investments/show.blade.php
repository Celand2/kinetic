@extends('layouts.app')

@section('title', 'Investment Details - KINETIC')

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('investments.index') }}" style="color: #c9a227;">← Back to Investments</a>
</div>

<div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
    <div class="card">
        <div class="card-header">Investment Details</div>
        <div style="display: grid; gap: 1rem;">
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Reference</div>
                <div style="font-size: 1.1rem;">{{ $investment->reference }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Cycle</div>
                <div style="font-size: 1.1rem;">{{ $investment->tradingCycle->name }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Tranche</div>
                <div style="font-size: 1.1rem;">{{ $investment->tranche->name }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Amount</div>
                <div style="font-size: 1.1rem; color: #c9a227;">${{ number_format($investment->amount, 2) }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Status</div>
                <div style="font-size: 1.1rem; color: #81c784;">{{ ucfirst($investment->status) }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Profit Information</div>
        <div style="display: grid; gap: 1rem;">
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Daily Profit Rate</div>
                <div style="font-size: 1.1rem;">{{ $investment->daily_profit_rate }}%</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Total Return Rate</div>
                <div style="font-size: 1.1rem;">{{ $investment->total_return_rate }}%</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Credited Profit</div>
                <div style="font-size: 1.1rem; color: #81c784;">${{ number_format($investment->total_profit_credited, 2) }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Days Credited</div>
                <div style="font-size: 1.1rem;">{{ $investment->days_credited }} / {{ $investment->duration_days }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">Timeline</div>
    <div style="display: grid; gap: 1.5rem;">
        <div>
            <div style="color: #b0bfd9; font-size: 0.9rem;">Started</div>
            <div>{{ $investment->started_at->format('M d, Y H:i A') }}</div>
        </div>
        <div>
            <div style="color: #b0bfd9; font-size: 0.9rem;">Ends</div>
            <div>{{ $investment->ends_at->format('M d, Y H:i A') }}</div>
        </div>
        <div>
            <div style="color: #b0bfd9; font-size: 0.9rem;">Last Profit Credited</div>
            <div>{{ $investment->last_profit_credited_at?->format('M d, Y H:i A') ?? 'Not yet credited' }}</div>
        </div>
    </div>
</div>

@if($investment->transactions->count() > 0)
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">Related Transactions</div>
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
                @foreach($investment->transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ ucfirst($transaction->type) }}</td>
                        <td>${{ number_format($transaction->amount, 2) }}</td>
                        <td>{{ ucfirst($transaction->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
