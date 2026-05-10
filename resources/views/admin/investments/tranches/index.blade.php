@extends('layouts.admin')

@section('title', 'Tranches - {{ $cycle->name }} - KINETIC Admin')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
    <div>
        <h1 style="color: #c9a227;">Tranches</h1>
        <p style="color: #b0bfd9; margin: 0;">Cycle : <strong style="color: #fff;">{{ $cycle->name }}</strong>
            — {{ $cycle->duration_days }} jours
            — {{ $cycle->daily_profit_percent }}% / jour
        </p>
    </div>
    <a href="{{ route('admin.tranches.create', $cycle) }}" class="btn">+ Nouvelle Tranche</a>
</div>

<a href="{{ route('admin.cycles') }}" class="back-link" style="display:inline-block; margin-bottom: 1.5rem;">← Retour aux cycles</a>

@if(session('success'))
    <div class="alert alert-success" style="background: #1b3a2a; color: #81c784; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    @if($tranches->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Montant min</th>
                    <th>Montant max</th>
                    <th>Ordre</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tranches as $tranche)
                    <tr>
                        <td>
                            <strong>{{ $tranche->name }}</strong><br>
                            <small style="color: #b0bfd9;">{{ $tranche->slug }}</small>
                        </td>
                        <td>${{ number_format($tranche->min_amount, 2) }}</td>
                        <td>{{ $tranche->max_amount ? '$' . number_format($tranche->max_amount, 2) : '—' }}</td>
                        <td>{{ $tranche->display_order }}</td>
                        <td>
                            <span style="color: {{ $tranche->is_active ? '#81c784' : '#fbc02d' }};">
                                {{ $tranche->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.tranches.edit', $tranche) }}" style="color: #c9a227; margin-right: 1rem;">Modifier</a>
                            <form method="POST" action="{{ route('admin.tranches.delete', $tranche) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e57373; cursor: pointer; padding: 0;"
                                    onclick="return confirm('Supprimer cette tranche ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $tranches->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">
            Aucune tranche pour ce cycle.
            <br><br>
            <a href="{{ route('admin.tranches.create', $cycle) }}" class="btn">+ Créer la première tranche</a>
        </p>
    @endif
</div>
@endsection
