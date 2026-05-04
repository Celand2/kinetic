@extends('layouts.app')

@section('title', 'Message - KINETIC')

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ Auth::user()->id === $message->sender_id ? route('messages.sent') : route('messages.inbox') }}" style="color: #c9a227;">← Back</a>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div style="border-bottom: 1px solid rgba(201, 162, 39, 0.2); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
        <div style="color: #c9a227; font-size: 1.3rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $message->subject }}</div>
        <div style="color: #b0bfd9; font-size: 0.9rem;">From: <strong>{{ $message->sender->full_name }}</strong> on {{ $message->created_at->format('M d, Y H:i A') }}</div>
    </div>

    <div style="background: rgba(201, 162, 39, 0.05); padding: 1.5rem; border-radius: 8px; line-height: 1.8; white-space: pre-wrap;">
        {{ $message->content }}
    </div>

    @if(Auth::user()->id === $message->receiver_id)
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <a href="{{ route('messages.create') }}" class="btn">Reply</a>
        </div>
    @endif
</div>
@endsection
