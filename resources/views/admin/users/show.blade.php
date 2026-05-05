@extends('layouts.app')

@section('title', 'User Details - KINETIC Admin')

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('admin.users.index') }}" style="color: #c9a227;">← Back to Users</a>
</div>

<div class="grid" style="grid-template-columns: 1fr 1fr;">
    
    
    <!-- User Info -->
    <div class="card">
        <div class="card-header">User Information</div>
        
        <div style="display: grid; gap: 1rem;">
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Full Name</div>
                <div>{{ $user->full_name }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Email</div>
                <div>{{ $user->email }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Phone</div>
                <div>{{ $user->phone }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Country</div>
                <div>{{ $user->country }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Referral Code</div>
                <div>{{ $user->referral_code }}</div>
            </div>
        </div>
    </div>

    <!-- Balances -->
    <div class="card">
        <div class="card-header">Financial Info</div>
        
        <div style="display: grid; gap: 1rem;">
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Main Balance</div>
                <div style="font-size: 1.2rem; color: #c9a227;">${{ number_format($user->balance, 2) }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Referral Balance</div>
                <div style="font-size: 1.2rem; color: #81c784;">${{ number_format($user->referral_balance, 2) }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Total Active Investments</div>
                <div style="font-size: 1.2rem;">{{ $user->investments()->where('status', 'active')->count() }}</div>
            </div>
            <div>
                <div style="color: #b0bfd9; font-size: 0.9rem;">Total Referrals</div>
                <div style="font-size: 1.2rem;">{{ $user->referrals()->count() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">Admin Actions</div>

    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn">Edit User</a>
        
        @if($user->status === 'active')
            <form method="POST" action="{{ route('admin.users.block', $user) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">Block User</button>
            </form>
        @else
            <form method="POST" action="{{ route('admin.users.unblock', $user) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn">Unblock User</button>
            </form>
        @endif

        <form method="POST" action="{{ route('admin.users.delete', $user) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to permanently delete this user?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-secondary" style="background: rgba(244, 67, 54, 0.2); color: #ef5350; border-color: #ef5350;">Delete Permanently</button>
        </form>
    </div>
</div>

<!-- Investment Summary -->
@if($user->investments->count() > 0)
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">Investment Summary</div>

        <table>
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Cycle</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Profit Credited</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->investments as $investment)
                    <tr>
                        <td>{{ $investment->reference }}</td>
                        <td>{{ $investment->tradingCycle->name }}</td>
                        <td>${{ number_format($investment->amount, 2) }}</td>
                        <td>{{ ucfirst($investment->status) }}</td>
                        <td>${{ number_format($investment->total_profit_credited, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
