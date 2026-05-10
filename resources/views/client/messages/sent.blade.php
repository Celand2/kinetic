@extends('layouts.client')

@section('title', 'Sent Messages - KINETIC')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="color: #c9a227;">Sent Messages</h1>
    <a href="{{ route('messages.create') }}" class="btn">+ New Message</a>
</div>

<div class="card">
    @if($messages->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>To</th>
                    <th>Subject</th>
                    <th>Date Sent</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                    <tr>
                        <td>{{ $message->receiver->full_name }}</td>
                        <td>{{ $message->subject }}</td>
                        <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                        <td><a href="{{ route('messages.show', $message) }}" style="color: #c9a227;">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">{{ $messages->links() }}</div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">No sent messages.</p>
    @endif
</div>
@endsection
