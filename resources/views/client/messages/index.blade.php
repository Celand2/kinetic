@extends('layouts.client')
@section('title', 'Messages - KINETIC')
@section('back')<a href="{{ route('dashboard') }}" class="kts-back-btn">← Tableau de bord</a>@endsection

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
    <h1 style="color:#c9a227; font-size:1.2rem; margin:0;">Mes Messages</h1>
    <a href="{{ route('messages.create') }}" class="kts-btn">✉️ Nouveau message</a>
</div>

<div class="card">
    @if($conversations->count() > 0)
        <div style="display:flex; flex-direction:column; gap:0;">
            @foreach($conversations as $conv)
            @php $unread = $conv->unread_user_count > 0; @endphp
            <div style="padding:1rem; border-bottom:1px solid rgba(255,255,255,0.05); background:{{ $unread ? 'rgba(201,162,39,0.04)' : 'transparent' }}; {{ $loop->last ? 'border-bottom:none;' : '' }}">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:0.75rem;">
                    <div style="flex:1; min-width:0;">
                        <div style="font-weight:{{ $unread ? '700' : '500' }}; color:{{ $unread ? '#e8e8e8' : '#b0bfd9' }}; font-size:0.9rem; margin-bottom:4px;">
                            @if($unread)<span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#c9a227; margin-right:6px; vertical-align:middle;"></span>@endif
                            {{ $conv->subject }}
                        </div>
                        <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                            <span style="background:rgba(201,162,39,0.1); color:#c9a227; padding:2px 8px; border-radius:20px; font-size:0.72rem; text-transform:capitalize;">{{ $conv->category }}</span>
                            @php $sc=['open'=>'#81c784','resolved'=>'#b0bfd9','closed'=>'#ef5350']; $sl=['open'=>'Ouvert','resolved'=>'Résolu','closed'=>'Fermé']; @endphp
                            <span style="color:{{ $sc[$conv->status] ?? '#b0bfd9' }}; font-size:0.75rem;">{{ $sl[$conv->status] ?? $conv->status }}</span>
                            <span style="color:#4a5568; font-size:0.72rem;">{{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : $conv->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <a href="{{ route('messages.show', $conv) }}" class="kts-btn kts-btn-sm" style="flex-shrink:0;">Voir →</a>
                </div>
            </div>
            @endforeach
        </div>
        <div style="padding:0.75rem;">{{ $conversations->links() }}</div>
    @else
        <div style="text-align:center; padding:3rem; color:#b0bfd9;">
            <div style="font-size:2rem; margin-bottom:0.75rem;">💬</div>
            <p>Aucun message pour le moment.</p>
            <a href="{{ route('messages.create') }}" class="kts-btn" style="margin-top:1rem;">Envoyer un message</a>
        </div>
    @endif
</div>
@endsection
