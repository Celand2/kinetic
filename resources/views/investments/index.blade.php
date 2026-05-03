@extends('layouts.app')

@section('title', 'Investments - KINETIC')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="color: #c9a227;">My Investments</h1>
    <a href="{{ route('investments.create') }}" class="btn">+ New Investment</a>
</div>

<div class="card">
    @if($investments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Cycle</th>
                    <th>Tranche</th>
                    <th>Amount</th>
                    <th>Profit Rate</th>
                    <th>Status</th>
                    <th>Ends</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($investments as $investment)
                    <tr>
                        <td>{{ $investment->reference }}</td>
                        <td>{{ $investment->tradingCycle->name }}</td>
                        <td>{{ $investment->tranche->name }}</td>
                        <td>${{ number_format($investment->amount, 2) }}</td>
                        <td>{{ $investment->daily_profit_rate }}% daily</td>
                        <td>
                            <span style="color: {{ $investment->status === 'active' ? '#81c784' : '#fbc02d' }};">
                                {{ ucfirst($investment->status) }}
                            </span>
                        </td>
                        <td>{{ $investment->ends_at->format('M d, Y') }}</td>
                        <td><a href="{{ route('investments.show', $investment) }}" style="color: #c9a227;">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $investments->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">
            No investments yet. <a href="{{ route('investments.create') }}" style="color: #c9a227;">Create your first investment</a>
        </p>
    @endif
</div>
@endsection
