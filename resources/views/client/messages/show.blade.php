@extends('layouts.app')

@section('title', $conversation->subject . ' - KINETIC')
@section('page-title', 'CONVERSATION')
@section('page-subtitle', '// ' . $conversation->subject)

@section('content')
<div style="margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
    <a href="{{ route('messages.index') }}" style="color:#c9a227;">← Retour aux messages</a>
    @php
        $colors = ['open'=>'#81c784','resolved'=>'#b0bfd9','closed'=>'#ef5350'];
        $sc = $colors[$conversation->status] ?? '#b0bfd9';
    @endphp
    <span style="color:{{ $sc }}; border:1px solid {{ $sc }}; padding:4px 14px; border-radius:20px; font-size:0.85rem; text-transform:capitalize;">
        {{ $conversation->status }}
    </span>
</div>

{{-- Fil de messages --}}
<div style="max-width:800px; margin:0 auto;">
    <div class="card" style="margin-bottom:1.5rem;">
        <div style="border-bottom:1px solid rgba(201,162,39,0.2); padding-bottom:1rem; margin-bottom:1.5rem;">
            <div style="color:#c9a227; font-size:1.15rem; font-weight:700;">{{ $conversation->subject }}</div>
            <div style="color:#b0bfd9; font-size:0.82rem; margin-top:4px;">
                Catégorie : <span style="text-transform:capitalize;">{{ $conversation->category }}</span>
                &nbsp;·&nbsp; Ouvert le {{ $conversation->created_at->format('d/m/Y à H:i') }}
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:1.25rem;">
            @foreach($messages as $msg)
            @php $isMe = $msg->sender_id === Auth::id(); @endphp
            <div style="display:flex; flex-direction:column; align-items:{{ $isMe ? 'flex-end' : 'flex-start' }};">
                <div style="font-size:0.78rem; color:#b0bfd9; margin-bottom:4px;">
                    {{ $isMe ? 'Vous' : ($msg->sender->full_name ?? 'Admin') }}
                    &nbsp;·&nbsp; {{ $msg->created_at->format('d/m/Y H:i') }}
                </div>
                <div style="
                    max-width:80%;
                    padding:0.85rem 1.1rem;
                    border-radius:{{ $isMe ? '14px 14px 4px 14px' : '14px 14px 14px 4px' }};
                    background:{{ $isMe ? 'rgba(201,162,39,0.18)' : 'rgba(255,255,255,0.06)' }};
                    border:1px solid {{ $isMe ? 'rgba(201,162,39,0.35)' : 'rgba(255,255,255,0.1)' }};
                    color:#e8e8e8;
                    line-height:1.7;
                    white-space:pre-wrap;
                    word-break:break-word;
                ">{{ $msg->body }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Formulaire de réponse --}}
    @if($conversation->status !== 'closed')
    <div class="card">
        <h3 style="color:#c9a227; margin-bottom:1rem; font-size:1rem;">Répondre</h3>
        <form method="POST" action="{{ route('messages.reply', $conversation) }}">
            @csrf
            <div class="form-group" style="margin-bottom:1rem;">
                <textarea name="body" rows="5" required
                          placeholder="Votre réponse...">{{ old('body') }}</textarea>
                @error('body')<span style="color:#ef5350; font-size:0.85rem;">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn">Envoyer la réponse</button>
        </form>
    </div>
    @else
    <div style="text-align:center; color:#b0bfd9; padding:1.5rem; background:rgba(255,255,255,0.03); border-radius:8px;">
        Cette conversation est fermée.
    </div>
    @endif
</div>
@endsection
