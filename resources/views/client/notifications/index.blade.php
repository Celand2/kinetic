@extends('layouts.client')

@section('title', 'Notifications - KINETIC')
@section('back')<a href="{{ route('dashboard') }}" class="kts-back-btn">← Tableau de bord</a>@endsection

@section('content')

@php
$icons = [
    'deposit_approved'    => ['icon' => '✅', 'color' => '#81c784'],
    'deposit_rejected'    => ['icon' => '❌', 'color' => '#ef5350'],
    'withdrawal_approved' => ['icon' => '💸', 'color' => '#81c784'],
    'withdrawal_rejected' => ['icon' => '❌', 'color' => '#ef5350'],
    'message_received'    => ['icon' => '💬', 'color' => '#c9a227'],
    'investment_active'   => ['icon' => '⚡', 'color' => '#c9a227'],
    'investment_complete' => ['icon' => '🏆', 'color' => '#81c784'],
    'profit_credited'     => ['icon' => '📈', 'color' => '#81c784'],
    'referral_bonus'      => ['icon' => '🌐', 'color' => '#c9a227'],
    'account_frozen'      => ['icon' => '🔒', 'color' => '#ef5350'],
    'broadcast'           => ['icon' => '📢', 'color' => '#7a9cc6'],
    'system'              => ['icon' => 'ℹ️',  'color' => '#7a9cc6'],
];
@endphp

<div class="card">
    @if($notifications->count() > 0)
        <div style="display:flex; flex-direction:column; gap:0;">
            @foreach($notifications as $notif)
            @php
                $meta  = $icons[$notif->type] ?? ['icon'=>'🔔','color'=>'#b0bfd9'];
                $unread = !$notif->is_read;
            @endphp
            <div style="
                display:flex; align-items:flex-start; gap:1rem;
                padding:1rem 1.25rem;
                border-bottom:1px solid rgba(255,255,255,0.06);
                background:{{ $unread ? 'rgba(201,162,39,0.05)' : 'transparent' }};
                {{ $loop->last ? 'border-bottom:none;' : '' }}
            ">
                <div style="
                    width:38px; height:38px; border-radius:50%; flex-shrink:0;
                    background:{{ $meta['color'] }}22;
                    border:1px solid {{ $meta['color'] }}55;
                    display:flex; align-items:center; justify-content:center;
                    font-size:1.1rem;
                ">{{ $meta['icon'] }}</div>

                <div style="flex:1; min-width:0;">
                    <div style="
                        font-weight:{{ $unread ? '700' : '500' }};
                        color:{{ $unread ? '#e8e8e8' : '#b0bfd9' }};
                        font-size:0.95rem;
                        margin-bottom:2px;
                    ">{{ $notif->title }}</div>
                    <div style="color:#b0bfd9; font-size:0.85rem; line-height:1.5;">{{ $notif->body }}</div>
                    <div style="margin-top:6px; display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                        <span style="font-size:0.75rem; color:#6b7a9a;">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                        @if($notif->action_url)
                        <a href="{{ $notif->action_url }}"
                           style="font-size:0.8rem; color:{{ $meta['color'] }}; text-decoration:none; font-weight:600;">
                            {{ $notif->action_label ?? 'Voir →' }}
                        </a>
                        @endif
                    </div>
                </div>

                @if($unread)
                <div style="width:8px; height:8px; border-radius:50%; background:#c9a227; flex-shrink:0; margin-top:6px;"></div>
                @endif
            </div>
            @endforeach
        </div>
        <div style="padding:1rem 1.25rem;">{{ $notifications->links() }}</div>
    @else
        <div style="text-align:center; padding:4rem 1rem; color:#b0bfd9;">
            <div style="font-size:2.5rem; margin-bottom:1rem;">🔔</div>
            <p>Aucune notification pour le moment.</p>
        </div>
    @endif
</div>

@endsection
