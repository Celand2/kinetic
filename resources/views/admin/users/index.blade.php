@extends('layouts.app')

@section('title', 'User Management - KINETIC Admin')

@section('content')
<h1 style="margin-bottom: 2rem; color: #c9a227;">User Management</h1>

<div class="card">
    @if($users->count() > 0)
        <table>
            <thead>
                <tr>
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
                    <tr>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>${{ number_format($user->balance, 2) }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <span style="color: {{ $user->status === 'active' ? '#81c784' : '#fbc02d' }};">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" style="color: #c9a227; margin-right: 1rem;">View</a>
                            <a href="{{ route('admin.users.edit', $user) }}" style="color: #c9a227;">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $users->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">No users found.</p>
    @endif
</div>
@endsection
