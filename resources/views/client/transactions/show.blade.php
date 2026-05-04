@extends('layouts.app')

@section('title', 'Transaction Details - KINETIC')

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('transactions.index') }}" style="color: #c9a227;">← Back to Transactions</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">Transaction Details</div>

    <div style="display: grid; gap: 1.5rem;">
        <div>
            <div style="color: #b0bfd9; font-size: 0.9rem;">Reference</div>
            <div style="font-size: 1.1rem; font-weight: bold;">{{ $transaction->reference }}</div>
        </div>

        <div>
            <div style="color: #b0bfd9; font-size: 0.9rem;">Type</div>
            <div style="font-size: 1.1rem;">{{ ucfirst($transaction->type) }}</div>
        </div>

        <div>
            <div style="color: #b0bfd9; font-size: 0.9rem;">Amount</div>
            <div style="font-size: 1.5rem; color: #c9a227; font-weight: bold;">${{ number_format($transaction->amount, 2) }}</div>
        </div>

        <div>
            <div style="color: #b0bfd9; font-size: 0.9rem;">Status</div>
            <div style="font-size: 1.1rem; color: {{ $transaction->status === 'completed' ? '#81c784' : '#fbc02d' }};">{{ ucfirst($transaction->status) }}</div>
        </div>

        <div>
            <div style="color: #b0bfd9; font-size: 0.9rem;">Date Created</div>
            <div>{{ $transaction->created_at->format('M d, Y H:i A') }}</div>
        </div>

        @if($transaction->processed_at)
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Date Processed</div>
                <div>{{ $transaction->processed_at->format('M d, Y H:i A') }}</div>
            </div>
        @endif

        @if($transaction->fee_amount && $transaction->fee_amount > 0)
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Fees</div>
                <div>${{ number_format($transaction->fee_amount, 2) }}</div>
            </div>
        @endif

        @if($transaction->metadata)
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Details</div>
                <pre style="background: rgba(201, 162, 39, 0.05); padding: 1rem; border-radius: 8px;">{{ json_encode($transaction->metadata, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        @endif

        @if($transaction->admin_notes)
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Admin Notes</div>
                <div style="background: rgba(201, 162, 39, 0.05); padding: 1rem; border-radius: 8px;">{{ $transaction->admin_notes }}</div>
            </div>
        @endif
    </div>
</div>
@endsection
