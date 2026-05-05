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
                    <option value="">-- Choisir un type --</option>
                    <option value="broadcast">Broadcast (général)</option>
                    <option value="system">Système</option>
                    <option value="investment_active">Investissement activé</option>
                    <option value="investment_complete">Investissement terminé</option>
                    <option value="profit_credited">Profit crédité</option>
                    <option value="deposit_approved">Dépôt approuvé</option>
                    <option value="deposit_rejected">Dépôt rejeté</option>
                    <option value="withdrawal_approved">Retrait approuvé</option>
                    <option value="withdrawal_rejected">Retrait rejeté</option>
                    <option value="referral_bonus">Bonus parrainage</option>
                    <option value="message_received">Message reçu</option>
                    <option value="account_frozen">Compte suspendu</option>
                </select>
                @error('type')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="title">Title</label>
                <input class="form-control" type="text" id="title" name="title" required>
                @error('title')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="body">Message</label>
                <textarea class="form-control" id="body" name="body" rows="6" required></textarea>
                @error('body')<span class="form-feedback-error">{{ $message }}</span>@enderror
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
