@extends('layouts.admin')

@section('title', $conversation->subject . ' - Admin KINETIC')
@section('back')<a href="{{ route('admin.messages.index') }}" class="kts-back-btn">← Messages</a>@endsection

@section('content')
<div style="display:flex; justify-content:flex-end; align-items:center; flex-wrap:wrap; gap:0.75rem; margin-bottom:1rem;">
    <div style="display:flex; gap:0.75rem; align-items:center; flex-wrap:wrap;">
        @php
            $sc = ['open'=>'#81c784','resolved'=>'#b0bfd9','closed'=>'#ef5350'];
            $scolor = $sc[$conversation->status] ?? '#b0bfd9';
        @endphp
        <span style="color:{{ $scolor }}; border:1px solid {{ $scolor }}; padding:4px 14px; border-radius:20px; font-size:0.85rem; text-transform:capitalize;">
            {{ $conversation->status }}
        </span>
        @if($conversation->status === 'open')
        <form method="POST" action="{{ route('admin.messages.close', $conversation) }}">
            @csrf
            <button type="submit" class="btn"
                    style="padding:4px 14px; font-size:0.85rem; background:transparent; border-color:rgba(201,162,39,0.4);">
                Marquer résolu
            </button>
        </form>
        @endif
    </div>
</div>

{{-- En-tête utilisateur --}}
<div style="max-width:800px; margin:0 auto;">
    <div class="card" style="margin-bottom:1.25rem; padding:1rem 1.5rem;">
        <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:1rem; align-items:center;">
            <div>
                <div style="color:#c9a227; font-weight:700; font-size:1.1rem;">{{ $conversation->user->full_name ?? '—' }}</div>
                <div style="color:#b0bfd9; font-size:0.82rem;">{{ $conversation->user->email ?? '' }}</div>
            </div>
            <div style="text-align:right; color:#b0bfd9; font-size:0.82rem;">
                <div>Catégorie : <span style="text-transform:capitalize; color:#e8e8e8;">{{ $conversation->category }}</span></div>
                <div>Ouvert le {{ $conversation->created_at->format('d/m/Y à H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- Fil de messages --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <div style="display:flex; flex-direction:column; gap:1.25rem;">
            @foreach($messages as $msg)
            @php $isAdmin = $msg->sender->role === 'super_admin' || $msg->sender->role === 'admin'; @endphp
            <div style="display:flex; flex-direction:column; align-items:{{ $isAdmin ? 'flex-end' : 'flex-start' }};">
                <div style="font-size:0.78rem; color:#b0bfd9; margin-bottom:4px;">
                    {{ $isAdmin ? '🛡 Admin (' . $msg->sender->full_name . ')' : $msg->sender->full_name }}
                    &nbsp;·&nbsp; {{ $msg->created_at->format('d/m/Y H:i') }}
                </div>
                <div style="
                    max-width:80%;
                    padding:0.85rem 1.1rem;
                    border-radius:{{ $isAdmin ? '14px 14px 4px 14px' : '14px 14px 14px 4px' }};
                    background:{{ $isAdmin ? 'rgba(201,162,39,0.18)' : 'rgba(255,255,255,0.06)' }};
                    border:1px solid {{ $isAdmin ? 'rgba(201,162,39,0.35)' : 'rgba(255,255,255,0.1)' }};
                    color:#e8e8e8;
                    line-height:1.7;
                    white-space:pre-wrap;
                    word-break:break-word;
                ">{{ $msg->body }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Formulaire de réponse admin --}}
    @if($conversation->status !== 'closed')
    <div class="card">
        <h3 style="color:#c9a227; margin-bottom:1rem; font-size:1rem;">Répondre en tant qu'Admin</h3>
        <form method="POST" action="{{ route('admin.messages.reply', $conversation) }}">
            @csrf
            <div class="form-group" style="margin-bottom:1rem;">
                <textarea name="body" rows="5" required
                          placeholder="Votre réponse...">{{ old('body') }}</textarea>
                @error('body')<span style="color:#ef5350; font-size:0.85rem;">{{ $message }}</span>@enderror
            </div>
            <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                <button type="submit" class="btn">Envoyer la réponse</button>
            </div>
        </form>
    </div>
    @else
    <div style="text-align:center; color:#b0bfd9; padding:1.5rem; background:rgba(255,255,255,0.03); border-radius:8px;">
        Cette conversation est fermée.
    </div>
    @endif
</div>
@endsection
