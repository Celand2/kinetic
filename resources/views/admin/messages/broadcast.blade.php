@extends('layouts.app')

@section('title', 'Diffuser un Message - KINETIC Admin')
@section('page-title', 'DIFFUSION')
@section('page-subtitle', '// Envoyer un message à tous les utilisateurs')

@section('content')
<div style="margin-bottom:1.5rem;">
    <a href="{{ route('admin.messages.index') }}" style="color:#c9a227;">← Retour aux messages</a>
</div>

<div style="max-width:700px; margin:0 auto;">
    <div class="card">
        <h2 style="color:#c9a227; margin-bottom:0.5rem;">Diffuser un message</h2>
        <p style="color:#b0bfd9; margin-bottom:2rem; font-size:0.9rem;">
            Ce message créera une conversation dans la boîte de chaque utilisateur sélectionné.
        </p>

        <form method="POST" action="{{ route('admin.messages.broadcast.send') }}" id="broadcastForm">
            @csrf

            <div class="form-group">
                <label for="subject">Sujet</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                       placeholder="Ex : Mise à jour importante, Offre spéciale..." required maxlength="200">
                @error('subject')<span style="color:#ef5350; font-size:0.85rem;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="body">Message</label>
                <textarea id="body" name="body" rows="8" required
                          placeholder="Rédigez votre message ici...">{{ old('body') }}</textarea>
                @error('body')<span style="color:#ef5350; font-size:0.85rem;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Destinataires</label>
                <div style="display:flex; gap:1.5rem; margin-bottom:1rem; flex-wrap:wrap;">
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; color:#e8e8e8;">
                        <input type="radio" name="target" value="all"
                               {{ old('target','all') === 'all' ? 'checked' : '' }}
                               onchange="toggleUserList(this)">
                        Tous les utilisateurs actifs
                        <span style="color:#b0bfd9; font-size:0.82rem;">({{ $users->count() }} users)</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; color:#e8e8e8;">
                        <input type="radio" name="target" value="selected"
                               {{ old('target') === 'selected' ? 'checked' : '' }}
                               onchange="toggleUserList(this)">
                        Sélectionner des utilisateurs
                    </label>
                </div>
                @error('target')<span style="color:#ef5350; font-size:0.85rem;">{{ $message }}</span>@enderror
            </div>

            <div id="userList" style="display:{{ old('target') === 'selected' ? 'block' : 'none' }}; margin-bottom:1.5rem;">
                <div style="display:flex; gap:0.75rem; margin-bottom:0.75rem; flex-wrap:wrap;">
                    <button type="button" onclick="selectAll()" class="btn"
                            style="padding:4px 14px; font-size:0.82rem;">Tout sélectionner</button>
                    <button type="button" onclick="deselectAll()" class="btn"
                            style="padding:4px 14px; font-size:0.82rem; background:transparent; border-color:rgba(201,162,39,0.4);">Tout désélectionner</button>
                </div>
                <div class="card" style="max-height:320px; overflow-y:auto; padding:1rem;">
                    @foreach($users as $user)
                    <div style="margin-bottom:0.6rem;">
                        <label style="display:flex; align-items:center; color:#e8e8e8; cursor:pointer; gap:0.75rem;">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox"
                                   {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }}>
                            <span>
                                <strong>{{ $user->full_name }}</strong>
                                <span style="color:#b0bfd9; font-size:0.82rem;"> — {{ $user->email }}</span>
                            </span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('user_ids')<span style="color:#ef5350; font-size:0.85rem;">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn" style="width:100%;" onclick="return confirmSend()">
                📢 Envoyer la diffusion
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleUserList(radio) {
    document.getElementById('userList').style.display =
        radio.value === 'selected' ? 'block' : 'none';
}
function selectAll() {
    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = true);
}
function deselectAll() {
    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
}
function confirmSend() {
    const target = document.querySelector('input[name="target"]:checked').value;
    const count  = target === 'all'
        ? {{ $users->count() }}
        : document.querySelectorAll('.user-checkbox:checked').length;
    return confirm(`Confirmer l'envoi à ${count} utilisateur(s) ?`);
}
</script>
@endpush
