@extends('layouts.admin')

@section('title', 'User Management - KINETIC Admin')

@section('back')<a href="{{ route('admin.dashboard') }}" class="kts-back-btn">← Dashboard</a>@endsection

@section('content')
<h1 style="margin-bottom:1.25rem; color:#c9a227; font-size:1.2rem;">Utilisateurs</h1>


<form method="GET" action="{{ route('admin.users.index') }}" style="margin-bottom: 1.5rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
    <input
        type="search"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search users by name, email, phone, or referral code"
        style="flex: 1; min-width: 220px;"
    >
    <button type="submit" class="btn">Search</button>
</form>

<div class="card">
    @if($users->count() > 0)
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr >
                    <th>Name</th>
                    <th>Email</th>
                    <th>Balance</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr >
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>${{ number_format($user->balance, 2) }}</td>
                        <td>{{ ucfirst($user->role) }}</td><br>
                        <td>
                            <span style="color: {{ $user->status === 'active' ? '#81c784' : '#fbc02d' }};">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td><br>
                        <td style="white-space:nowrap;">
                            <a href="{{ route('admin.users.show', $user) }}" class="kts-btn kts-btn-sm" style="margin-right:4px;">Voir</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="kts-btn kts-btn-sm">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <div style="margin-top: 2rem;">
            {{ $users->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">No users found.</p>
    @endif
</div>
@endsection
