@extends('layouts.admin')
@section('title', 'Exchange Rates')
@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
    <h1 style="color:#c9a227; font-size:1.2rem; margin:0;">Taux de change</h1>
    <a href="{{ route('admin.exchange-rates.create') }}" class="kts-btn">+ Ajouter</a>
</div>

@if(session('success'))
    <div class="kts-alert success"><span>✅</span><span>{{ session('success') }}</span></div>
@endif

<div class="card" style="overflow-x:auto;">
    <table style="min-width:500px;">
        <thead>
            <tr>
                <th>Devise</th>
                <th>Taux → USD</th>
                <th>Mis à jour</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exchangeRates as $rate)
                <tr>
                    <td style="color:#c9a227; font-weight:700;">{{ $rate->currency }}</td>
                    <td style="font-family:monospace;">{{ number_format($rate->rate_to_usd, 6) }}</td>
                    <td style="color:#b0bfd9; font-size:0.82rem;">{{ $rate->updated_at->format('d/m/Y H:i') }}</td>
                    <td style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                        <a href="{{ route('admin.exchange-rates.edit', $rate) }}" class="kts-btn kts-btn-sm">Modifier</a>
                        <form action="{{ route('admin.exchange-rates.destroy', $rate) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="kts-btn kts-btn-sm kts-btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection