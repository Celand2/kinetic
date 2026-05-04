@extends('layouts.app')

@section('title', 'Send Notification - KINETIC Admin')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 1rem; color: #c9a227;">Send Notification</h1>
    <a href="{{ route('admin.notifications') }}" class="back-link">← Retour aux notifications</a>

    <div class="card">
        <form method="POST" action="{{ route('admin.notifications.send') }}">
            @csrf

            <div class="form-group">
                <label for="type">Notification Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="k1">K1</option>
                    <option value="k2">K2</option>
                    <option value="info">Information</option>
                    <option value="warning">Warning</option>
                    <option value="success">Success</option>
                    <option value="alert">Alert</option>
                </select>
                @error('type')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="title">Title</label>
                <input class="form-control" type="text" id="title" name="title" required>
                @error('title')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                @error('message')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Select Users</label>
                <div class="card" style="max-height: 300px; overflow-y: auto; padding: 1rem;">
                    @foreach($users as $user)
                        <div style="margin-bottom: 0.5rem;">
                            <label style="display: flex; align-items: center; color: #ffffff; cursor: pointer; gap: 0.75rem;">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}">
                                {{ $user->full_name }} ({{ $user->email }})
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('user_ids')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Send Notifications</button>
        </form>
    </div>
</div>
@endsection
