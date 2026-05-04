@extends('layouts.app')

@section('title', 'Transactions - KINETIC Admin')

@section('content')
<h1 style="margin-bottom: 2rem; color: #c9a227;">Transaction Management</h1>

<div class="card">
    @if($transactions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ $transaction->user->full_name }}</td>
                        <td>{{ ucfirst($transaction->type) }}</td>
                        <td>${{ number_format($transaction->amount, 2) }}</td>
                        <td>
                            <span style="color: {{ $transaction->status === 'completed' ? '#81c784' : '#fbc02d' }};">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td>
                            @if($transaction->status === 'pending')
                                <form method="POST" action="{{ route('admin.finance.approve', $transaction) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Approve</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $transactions->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">No transactions found.</p>
    @endif
</div>
@endsection
