@extends('layouts.app')

@section('title', 'Send Message - KINETIC')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 2rem; color: #c9a227;">Send Message</h1>

    <div class="card">
        <form method="POST" action="{{ route('messages.store') }}">
            @csrf

            <div class="form-group">
                <label for="receiver_id">Recipient</label>
                <input type="text" id="receiver_id" name="receiver_id" placeholder="Enter recipient's name or ID" required>
                @error('receiver_id')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required value="{{ old('subject') }}">
                @error('subject')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="content">Message</label>
                <textarea id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                @error('content')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn" style="width: 100%;">Send Message</button>
        </form>
    </div>
</div>
@endsection
