@extends('layouts.admin')
@section('title', 'Payment Methods')
@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
    <h1 style="color:#c9a227; font-size:1.2rem; margin:0;">Moyens de paiement</h1>
    <a href="{{ route('admin.payment-methods.create') }}" class="kts-btn">+ Ajouter</a>
</div>

@if(session('success'))
    <div class="kts-alert success"><span>✅</span><span>{{ session('success') }}</span></div>
@endif

<div class="card" style="overflow-x:auto;">
    <table style="min-width:600px;">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>Détails</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentMethods as $method)
                <tr>
                    <td style="color:#c9a227; font-weight:700;">{{ $method->name }}</td>
                    <td style="color:#b0bfd9;">{{ ucfirst(str_replace('_', ' ', $method->type)) }}</td>
                    <td style="font-size:0.82rem; color:#b0bfd9;">{{ $method->details }}</td>
                    <td>
                        @if($method->is_active)
                            <span style="color:#81c784; font-weight:700;">✅ Oui</span>
                        @else
                            <span style="color:#ef5350; font-weight:700;">❌ Non</span>
                        @endif
                    </td>
                    <td style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                        <a href="{{ route('admin.payment-methods.edit', $method) }}" class="kts-btn kts-btn-sm">Modifier</a>
                        <form action="{{ route('admin.payment-methods.destroy', $method) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer ?')">
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