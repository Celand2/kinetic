@extends('layouts.app')

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

            <div class="form-group">
                <label for="balance">Main Balance</label>
                <input type="number" id="balance" name="balance" step="0.01" value="{{ $user->balance }}">
                @error('balance')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="referral_balance">Referral Balance</label>
                <input type="number" id="referral_balance" name="referral_balance" step="0.01" value="{{ $user->referral_balance }}">
                @error('referral_balance')<span style="color: #ef5350;">{{ $message }}</span>@enderror
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
