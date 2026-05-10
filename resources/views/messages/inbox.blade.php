@extends('layouts.client')

@section('title', 'Messages - KINETIC')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="color: #c9a227;">Inbox</h1>
    <a href="{{ route('messages.create') }}" class="btn">+ New Message</a>
</div>

<div class="card">
    @if($messages->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                    <tr style="{{ $message->is_read ? '' : 'font-weight: bold;' }}">
                        <td>{{ $message->sender->full_name }}</td>
                        <td>{{ $message->subject }}</td>
                        <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <span style="color: {{ $message->is_read ? '#b0bfd9' : '#c9a227' }};">
                                {{ $message->is_read ? 'Read' : 'Unread' }}
                            </span>
                        </td>
                        <td><a href="{{ route('messages.show', $message) }}" style="color: #c9a227;">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $messages->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">No messages in your inbox.</p>
    @endif
</div>
@endsection
