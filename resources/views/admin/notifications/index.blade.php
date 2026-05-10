@extends('layouts.admin')

@section('title', 'Notifications - KINETIC Admin')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="color: #c9a227;">Send Notifications</h1>
    <a href="{{ route('admin.notifications.create') }}" class="btn">+ Send Notification</a>
</div>

<div class="card">
    @if($notifications->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $notification)
                    <tr>
                        <td>{{ $notification->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ $notification->user->full_name }}</td>
                        <td>{{ ucfirst($notification->type) }}</td>
                        <td>{{ $notification->title }}</td>
                        <td>
                            <span style="color: {{ $notification->is_read ? '#b0bfd9' : '#c9a227' }};">
                                {{ $notification->is_read ? 'Read' : 'Unread' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $notifications->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">No notifications sent yet.</p>
    @endif
</div>
@endsection
