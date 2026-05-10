@extends('layouts.client')

@section('title', 'Nouveau Message - KINETIC')
@section('back')<a href="{{ route('messages.index') }}" class="kts-back-btn">← Mes messages</a>@endsection

@section('content')
<div>
    <div class="card">
        <h2 style="color:#c9a227; margin-bottom:1.5rem;">Envoyer un message à l'administration</h2>

        <form method="POST" action="{{ route('messages.store') }}">
            @csrf

            <div class="form-group">
                <label for="subject">Sujet</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                       placeholder="Ex : Problème avec mon dépôt" required maxlength="200">
                @error('subject')<span style="color:#ef5350; font-size:0.85rem;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="category">Catégorie</label>
                <select id="category" name="category" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="support"      {{ old('category')=='support'      ? 'selected':'' }}>Support général</option>
                    <option value="deposit"      {{ old('category')=='deposit'      ? 'selected':'' }}>Dépôt</option>
                    <option value="withdrawal"   {{ old('category')=='withdrawal'   ? 'selected':'' }}>Retrait</option>
                    <option value="investment"   {{ old('category')=='investment'   ? 'selected':'' }}>Investissement</option>
                    <option value="referral"     {{ old('category')=='referral'     ? 'selected':'' }}>Parrainage</option>
                    <option value="dispute"      {{ old('category')=='dispute'      ? 'selected':'' }}>Litige</option>
                    <option value="other"        {{ old('category')=='other'        ? 'selected':'' }}>Autre</option>
                </select>
                @error('category')<span style="color:#ef5350; font-size:0.85rem;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="body">Message</label>
                <textarea id="body" name="body" rows="8" required
                          placeholder="Décrivez votre demande en détail...">{{ old('body') }}</textarea>
                @error('body')<span style="color:#ef5350; font-size:0.85rem;">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn" style="width:100%;">Envoyer le message</button>
        </form>
    </div>
</div>
@endsection
