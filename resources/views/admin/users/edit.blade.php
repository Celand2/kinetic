@extends('layouts.admin')

@section('title', 'Edit User - KINETIC Admin')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.users.show', $user) }}" style="color: #c9a227;">← Back</a>
    </div>

    <div class="card">
        <div class="card-header">Edit User</div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="{{ $user->full_name }}" required>
                @error('full_name')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}" required>
                @error('email')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" value="{{ $user->phone }}" required>
                @error('phone')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" value="{{ $user->country }}" required>
                @error('country')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div style="background:rgba(201,162,39,0.05); border:1px solid rgba(201,162,39,0.2); border-radius:8px; padding:1rem; margin-bottom:1rem;">
                <div style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.08em; color:#6b7a9a; margin-bottom:0.75rem; font-weight:600;">Balances</div>

                <div class="form-group">
                    <label for="balance">Capital total (balance principale)</label>
                    <input type="number" id="balance" name="balance" step="0.01" value="{{ $user->balance }}">
                    <span style="font-size:0.73rem; color:#6b7a9a;">Inclut les dépôts validés. Non retirable par le client.</span>
                    @error('balance')<span style="color: #ef5350;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="profit_balance">Balance profits (retirable)</label>
                    <input type="number" id="profit_balance" name="profit_balance" step="0.01" value="{{ $user->profit_balance }}">
                    <span style="font-size:0.73rem; color:#6b7a9a;">Profits journaliers + commissions + bonus. Seule partie retirable.</span>
                    @error('profit_balance')<span style="color: #ef5350;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label for="referral_balance">Balance parrainage</label>
                    <input type="number" id="referral_balance" name="referral_balance" step="0.01" value="{{ $user->referral_balance }}">
                    <span style="font-size:0.73rem; color:#6b7a9a;">Total des commissions de parrainage reçues.</span>
                    @error('referral_balance')<span style="color: #ef5350;">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="frozen" {{ $user->status === 'frozen' ? 'selected' : '' }}>Frozen</option>
                    <option value="blocked" {{ $user->status === 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
                @error('status')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn" style="width: 100%;">Save Changes</button>
        </form>
    </div>
</div>
@endsection
