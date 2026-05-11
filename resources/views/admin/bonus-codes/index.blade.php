@extends('layouts.admin')

@section('title', 'Gestion des Codes Bonus - KINETIC')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="margin: 0; color: #c9a227;">Codes Bonus</h1>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('admin.bonus-codes.create') }}" class="btn btn-primary">+ Créer un Code</a>
        <a href="{{ route('admin.bonus-codes.generate-batch') }}" class="btn btn-secondary">⚡ Générer en Masse</a>
    </div>
</div>

@if(session('success'))
    <div style="background: rgba(129, 199, 132, 0.15); border: 1px solid #81c784; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; color: #81c784;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: rgba(239, 83, 80, 0.15); border: 1px solid #ef5350; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; color: #ef5350;">
        {{ session('error') }}
    </div>
@endif

<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
        <input 
            type="text" 
            name="search" 
            placeholder="Rechercher par code..." 
            value="{{ request('search') }}"
            class="form-control"
            style="flex: 1; max-width: 300px;"
        >

        <select name="status" class="form-control" style="max-width: 150px;">
            <option value="">Tous les statuts</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
            <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Utilisé</option>
            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expiré</option>
        </select>

        <button type="submit" class="btn btn-secondary">Filtrer</button>
        <a href="{{ route('admin.bonus-codes.index') }}" class="btn btn-secondary">Réinitialiser</a>
    </form>
</div>

<div class="card">
    @if($bonusCodes->count() > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid rgba(255,255,255,0.1);">
                    <th style="text-align: left; padding: 1rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Code</th>
                    <th style="text-align: left; padding: 1rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Montant</th>
                    <th style="text-align: left; padding: 1rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Statut</th>
                    <th style="text-align: left; padding: 1rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Utilisé Par</th>
                    <th style="text-align: left; padding: 1rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Date Utilisation</th>
                    <th style="text-align: left; padding: 1rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Expiration</th>
                    <th style="text-align: center; padding: 1rem 0; color: #b0bfd9; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bonusCodes as $code)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 1rem 0; font-weight: 600; font-family: monospace; color: #c9a227;">{{ $code->code }}</td>
                        <td style="padding: 1rem 0; color: #81c784; font-weight: 600;">${{ number_format($code->bonus_amount, 2) }}</td>
                        <td style="padding: 1rem 0;">
                            @if($code->status === 'active')
                                <span style="background: rgba(129, 199, 132, 0.2); color: #81c784; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.8rem; text-transform: uppercase; font-weight: 600;">Actif</span>
                            @elseif($code->status === 'used')
                                <span style="background: rgba(156, 39, 176, 0.2); color: #ab47bc; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.8rem; text-transform: uppercase; font-weight: 600;">Utilisé</span>
                            @else
                                <span style="background: rgba(239, 83, 80, 0.2); color: #ef5350; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.8rem; text-transform: uppercase; font-weight: 600;">Expiré</span>
                            @endif
                        </td>
                        <td style="padding: 1rem 0;">
                            @if($code->usedByUser)
                                <a href="{{ route('admin.users.show', $code->usedByUser) }}" style="color: #7a9cc6;">
                                    {{ $code->usedByUser->full_name }}
                                </a>
                            @else
                                <span style="color: #6b7a9a;">—</span>
                            @endif
                        </td>
                        <td style="padding: 1rem 0; color: #b0bfd9; font-size: 0.9rem;">
                            @if($code->used_at)
                                {{ $code->used_at->format('d/m/Y H:i') }}
                            @else
                                —
                            @endif
                        </td>
                        <td style="padding: 1rem 0; color: #b0bfd9; font-size: 0.9rem;">
                            @if($code->expires_at)
                                <span style="color: {{ $code->expires_at->isPast() ? '#ef5350' : '#fbc02d' }};">
                                    {{ $code->expires_at->format('d/m/Y') }}
                                </span>
                            @else
                                <span style="color: #81c784;">Pas d'expiration</span>
                            @endif
                        </td>
                        <td style="padding: 1rem 0; text-align: center;">
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <a href="{{ route('admin.bonus-codes.show', $code) }}" style="color: #c9a227; text-decoration: none;">Voir</a>
                                @if(!$code->isUsed())
                                    <a href="{{ route('admin.bonus-codes.edit', $code) }}" style="color: #7a9cc6; text-decoration: none;">Éditer</a>
                                    <form method="POST" action="{{ route('admin.bonus-codes.destroy', $code) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #ef5350; cursor: pointer; text-decoration: none; padding: 0;" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            {{ $bonusCodes->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">
            Aucun code bonus trouvé. <a href="{{ route('admin.bonus-codes.create') }}" style="color: #c9a227;">Créer le premier code</a>
        </p>
    @endif
</div>
@endsection
