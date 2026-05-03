@extends('layouts.app')

@section('title', 'Investments Management - KINETIC Admin')

@section('content')
<h1 style="margin-bottom: 2rem; color: #c9a227;">Investment Monitoring</h1>

<div class="card">
    @if($investments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>User</th>
                    <th>Cycle</th>
                    <th>Amount</th>
                    <th>Profit Credited</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($investments as $investment)
                    <tr>
                        <td>{{ $investment->reference }}</td>
                        <td>{{ $investment->user->full_name }}</td>
                        <td>{{ $investment->tradingCycle->name }}</td>
                        <td>${{ number_format($investment->amount, 2) }}</td>
                        <td>${{ number_format($investment->total_profit_credited, 2) }}</td>
                        <td>{{ ucfirst($investment->status) }}</td>
                        <td><a href="{{ route('admin.investments.edit', $investment) }}" style="color: #c9a227;">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $investments->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">No investments found.</p>
    @endif
</div>
@endsection
