@extends('layouts.admin')

@section('title', 'User Details - KINETIC Admin')

@section('back')<a href="{{ route('admin.users.index') }}" class="kts-back-btn">← Utilisateurs</a>@endsection

@section('content')

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
  <!-- Reset Password -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">Réinitialiser le mot de passe</div>

    @if(session('success'))
        <div style="background: rgba(129,199,132,0.1); border: 1px solid #81c784; color: #81c784; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
        @csrf
        <div class="form-group">
            <label>Nouveau mot de passe</label>
            <input type="password" name="new_password" required minlength="8" placeholder="Min. 8 caractères"
                style="border: 1px solid rgba(201,162,39,0.35); border-radius: 6px; width: 100%; padding: 0.6rem 0.85rem; background: rgba(255,255,255,0.04); color: inherit;">
            @error('new_password')<span style="color:#ef5350; font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label>Confirmer le mot de passe</label>
            <input type="password" name="new_password_confirmation" required minlength="8" placeholder="Répéter le mot de passe"
                style="border: 1px solid rgba(201,162,39,0.35); border-radius: 6px; width: 100%; padding: 0.6rem 0.85rem; background: rgba(255,255,255,0.04); color: inherit;">
        </div>
        <button type="submit"
            onclick="return confirm('Réinitialiser le mot de passe de {{ $user->full_name }} ?')"
            style="margin-top: 0.5rem; padding: 0.75rem 2rem; background: rgba(201,162,39,0.15); color: #c9a227; border: 1px solid rgba(201,162,39,0.5); border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; letter-spacing: 0.03em; transition: background 0.2s;"
            onmouseover="this.style.background='rgba(201,162,39,0.28)'"
            onmouseout="this.style.background='rgba(201,162,39,0.15)'">
            🔑 Réinitialiser le mot de passe
        </button>
    </form>

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
