@extends('layouts.app')

@section('title', 'Send Notification - KINETIC Admin')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 2rem; color: #c9a227;">Send Notification</h1>

    <div class="card">
        <form method="POST" action="{{ route('admin.notifications.send') }}">
            @csrf

            <div class="form-group">
                <label for="type">Notification Type</label>
                <select id="type" name="type" required>
                    <option value="k1">K1</option>
                    <option value="k2">K2</option>
                    <option value="info">Information</option>
                    <option value="warning">Warning</option>
                    <option value="success">Success</option>
                    <option value="alert">Alert</option>
                </select>
                @error('type')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
                @error('title')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" required></textarea>
                @error('message')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Select Users</label>
                <div style="max-height: 300px; overflow-y: auto; border: 1px solid rgba(201, 162, 39, 0.2); border-radius: 8px; padding: 1rem;">
                    @foreach($users as $user)
                        <div style="margin-bottom: 0.5rem;">
                            <label style="display: flex; align-items: center; color: #ffffff; cursor: pointer;">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" style="margin-right: 0.5rem;">
                                {{ $user->full_name }} ({{ $user->email }})
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('user_ids')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn" style="width: 100%;">Send Notifications</button>
        </form>
    </div>
</div>
@endsection
