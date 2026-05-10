@extends('layouts.admin')

@section('title', 'Messages - KINETIC Admin')
@section('back')<a href="{{ route('admin.dashboard') }}" class="kts-back-btn">← Dashboard</a>@endsection

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
    <div style="display:flex; align-items:center; gap:1rem;">
        <h1 style="color:#c9a227; margin:0; font-size:1.2rem;">Conversations</h1>
        @if($unreadCount > 0)
            <span style="background:#c9a227; color:#0b0f1a; padding:3px 10px; border-radius:20px; font-weight:700; font-size:0.78rem;">
                {{ $unreadCount }} non lu{{ $unreadCount > 1 ? 's' : '' }}
            </span>
        @endif
    </div>
    <a href="{{ route('admin.messages.broadcast') }}" class="kts-btn">📢 Diffuser</a>
</div>

{{-- Filtres --}}
<form method="GET" action="{{ route('admin.messages.index') }}"
      style="margin-bottom:1.5rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
    <input type="search" name="search" value="{{ request('search') }}"
           placeholder="Rechercher par sujet, nom ou email..." style="flex:1; min-width:220px;">
    <select name="status">
        <option value="">Tous les statuts</option>
        <option value="open"     {{ request('status')=='open'     ? 'selected':'' }}>Ouvert</option>
        <option value="resolved" {{ request('status')=='resolved' ? 'selected':'' }}>Résolu</option>
        <option value="closed"   {{ request('status')=='closed'   ? 'selected':'' }}>Fermé</option>
    </select>
    <button type="submit" class="btn">Filtrer</button>
    @if(request('search') || request('status'))
        <a href="{{ route('admin.messages.index') }}" class="btn" style="background:transparent; border-color:rgba(201,162,39,0.4);">✕ Reset</a>
    @endif
</form>

<div class="card">
    @if($conversations->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Sujet</th>
                    <th>Catégorie</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                    <th>Dernier message</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($conversations as $conv)
                <tr style="{{ $conv->unread_admin_count > 0 ? 'font-weight:700;' : '' }}">
                    <td>
                        @if($conv->unread_admin_count > 0)
                            <span style="display:inline-block; width:8px; height:8px; border-radius:50%;
                                         background:#c9a227; margin-right:8px; vertical-align:middle;"></span>
                        @endif
                        <div style="font-weight:inherit;">{{ $conv->user->full_name ?? '—' }}</div>
                        <div style="color:#b0bfd9; font-size:0.8rem; font-weight:400;">{{ $conv->user->email ?? '' }}</div>
                    </td>
                    <td>{{ Str::limit($conv->subject, 45) }}</td>
                    <td>
                        <span style="background:rgba(201,162,39,0.12); color:#c9a227; padding:2px 10px;
                                     border-radius:20px; font-size:0.78rem; text-transform:capitalize;">
                            {{ $conv->category }}
                        </span>
                    </td>
                    <td>
                        @php
                            $pc = ['low'=>'#b0bfd9','normal'=>'#81c784','high'=>'#fbc02d','urgent'=>'#ef5350'];
                            $p = $pc[$conv->priority] ?? '#b0bfd9';
                        @endphp
                        <span style="color:{{ $p }}; text-transform:capitalize; font-size:0.85rem;">{{ $conv->priority }}</span>
                    </td>
                    <td>
                        @php
                            $sc = ['open'=>'#81c784','resolved'=>'#b0bfd9','closed'=>'#ef5350'];
                            $s = $sc[$conv->status] ?? '#b0bfd9';
                        @endphp
                        <span style="color:{{ $s }}; text-transform:capitalize; font-size:0.85rem;">{{ $conv->status }}</span>
                    </td>
                    <td style="color:#b0bfd9; font-size:0.82rem;">
                        {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : $conv->created_at->diffForHumans() }}
                        <div style="font-size:0.75rem;">{{ $conv->messages_count }} msg</div>
                    </td>
                    <td>
                        <a href="{{ route('admin.messages.show', $conv) }}" class="kts-btn kts-btn-sm">Voir →</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:2rem;">{{ $conversations->links() }}</div>
    @else
        <p style="color:#b0bfd9; text-align:center; padding:3rem;">Aucune conversation trouvée.</p>
    @endif
</div>
@endsection
