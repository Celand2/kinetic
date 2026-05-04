@extends('layouts.app')

@section('title', 'Transactions - KINETIC')

@section('content')
<h1 style="margin-bottom: 2rem; color: #c9a227;">Transaction History</h1>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <div></div>
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <a href="{{ route('transactions.deposit') }}" class="btn">Deposit</a>
        <a href="{{ route('transactions.withdraw') }}" class="btn">Withdraw</a>
    </div>
</div>

<div class="card">
    @if($transactions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ $transaction->reference }}</td>
                        <td>{{ ucfirst($transaction->type) }}</td>
                        <td>${{ number_format($transaction->amount, 2) }}</td>
                        <td><span style="color: {{ $transaction->status === 'completed' ? '#81c784' : '#fbc02d' }};">{{ ucfirst($transaction->status) }}</span></td>
                        <td><a href="{{ route('transactions.show', $transaction) }}" style="color: #c9a227;">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $transactions->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">No transactions yet.</p>
    @endif
</div>
@endsection
