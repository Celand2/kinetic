@extends('layouts.app')

@section('title', 'Mes Messages - KINETIC')
@section('page-title', 'MESSAGES')
@section('page-subtitle', '// Support & Communication')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
    <h1 style="color:#c9a227; margin:0;">Mes Conversations</h1>
    <a href="{{ route('messages.create') }}" class="btn">+ Nouveau message</a>
</div>

<div class="card">
    @if($conversations->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Sujet</th>
                    <th>Catégorie</th>
                    <th>Statut</th>
                    <th>Dernier message</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($conversations as $conv)
                <tr style="{{ $conv->unread_user_count > 0 ? 'font-weight:700;' : '' }}">
                    <td>
                        @if($conv->unread_user_count > 0)
                            <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#c9a227; margin-right:8px; vertical-align:middle;"></span>
                        @endif
                        {{ $conv->subject }}
                    </td>
                    <td>
                        <span style="background:rgba(201,162,39,0.15); color:#c9a227; padding:2px 10px; border-radius:20px; font-size:0.8rem; text-transform:capitalize;">
                            {{ $conv->category }}
                        </span>
                    </td>
                    <td>
                        @php
                            $colors = ['open'=>'#81c784','resolved'=>'#b0bfd9','closed'=>'#ef5350'];
                            $sc = $colors[$conv->status] ?? '#b0bfd9';
                        @endphp
                        <span style="color:{{ $sc }}; text-transform:capitalize;">{{ $conv->status }}</span>
                    </td>
                    <td style="color:#b0bfd9; font-size:0.88rem;">
                        {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : $conv->created_at->diffForHumans() }}
                    </td>
                    <td>
                        <a href="{{ route('messages.show', $conv) }}" style="color:#c9a227;">Voir →</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:2rem;">{{ $conversations->links() }}</div>
    @else
        <div style="text-align:center; padding:4rem; color:#b0bfd9;">
            <div style="font-size:2.5rem; margin-bottom:1rem;">💬</div>
            <p>Aucune conversation pour le moment.</p>
            <a href="{{ route('messages.create') }}" class="btn" style="margin-top:1rem;">Envoyer un message</a>
        </div>
    @endif
</div>
@endsection
