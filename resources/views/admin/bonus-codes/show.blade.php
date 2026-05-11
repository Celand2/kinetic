@extends('layouts.admin')

@section('title', 'Code Bonus - KINETIC')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: #c9a227; margin: 0;">Détails du Code Bonus</h1>
        @if(!$bonusCode->isUsed())
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('admin.bonus-codes.edit', $bonusCode) }}" class="btn btn-secondary">Éditer</a>
                <form method="POST" action="{{ route('admin.bonus-codes.destroy', $bonusCode) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                </form>
            </div>
        @endif
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            
            <div>
                <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7a9a; margin-bottom: 0.5rem;">Code</div>
                <div style="font-family: monospace; font-size: 1.25rem; font-weight: 700; color: #c9a227; word-break: break-all;">{{ $bonusCode->code }}</div>
            </div>

            <div>
                <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7a9a; margin-bottom: 0.5rem;">Montant</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: #81c784;">${{ number_format($bonusCode->bonus_amount, 2) }}</div>
            </div>

            <div>
                <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7a9a; margin-bottom: 0.5rem;">Statut</div>
                <div>
                    @if($bonusCode->status === 'active')
                        <span style="background: rgba(129, 199, 132, 0.2); color: #81c784; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.8rem; text-transform: uppercase; font-weight: 600;">Actif</span>
                    @elseif($bonusCode->status === 'used')
                        <span style="background: rgba(156, 39, 176, 0.2); color: #ab47bc; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.8rem; text-transform: uppercase; font-weight: 600;">Utilisé</span>
                    @else
                        <span style="background: rgba(239, 83, 80, 0.2); color: #ef5350; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.8rem; text-transform: uppercase; font-weight: 600;">Expiré</span>
                    @endif
                </div>
            </div>

        </div>

        <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 1.5rem 0;">

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            
            <div>
                <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7a9a; margin-bottom: 0.5rem;">Créé le</div>
                <div style="color: #b0bfd9;">{{ $bonusCode->created_at->format('d/m/Y H:i') }}</div>
            </div>

            <div>
                <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7a9a; margin-bottom: 0.5rem;">Date d'Expiration</div>
                <div style="color: #b0bfd9;">
                    @if($bonusCode->expires_at)
                        <span style="color: {{ $bonusCode->expires_at->isPast() ? '#ef5350' : '#fbc02d' }};">
                            {{ $bonusCode->expires_at->format('d/m/Y') }}
                        </span>
                    @else
                        <span style="color: #81c784;">Pas d'expiration</span>
                    @endif
                </div>
            </div>

        </div>

        @if($bonusCode->isUsed())
            <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 1.5rem 0;">

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7a9a; margin-bottom: 0.5rem;">Utilisé Par</div>
                    <div>
                        <a href="{{ route('admin.users.show', $bonusCode->usedByUser) }}" style="color: #7a9cc6;">
                            {{ $bonusCode->usedByUser->full_name }}
                        </a>
                        <div style="font-size: 0.85rem; color: #6b7a9a;">{{ $bonusCode->usedByUser->email }}</div>
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7a9a; margin-bottom: 0.5rem;">Utilisé le</div>
                    <div style="color: #b0bfd9;">{{ $bonusCode->used_at->format('d/m/Y H:i') }}</div>
                </div>

            </div>
        @endif

        @if($bonusCode->description)
            <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 1.5rem 0;">

            <div>
                <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7a9a; margin-bottom: 0.5rem;">Description</div>
                <div style="color: #b0bfd9; white-space: pre-wrap; word-wrap: break-word;">{{ $bonusCode->description }}</div>
            </div>
        @endif
    </div>

    <a href="{{ route('admin.bonus-codes.index') }}" style="color: #c9a227;">← Retour à la liste</a>
</div>
@endsection
