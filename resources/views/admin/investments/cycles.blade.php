@extends('layouts.admin')

@section('title', 'Trading Cycles - KINETIC Admin')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="color: #c9a227;">Trading Cycles</h1>
    <a href="{{ route('admin.cycles.create') }}" class="btn">+ New Cycle</a>
</div>

<div class="card">
    @if($cycles->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Duration</th>
                <th>Daily Rate</th>
                <th>Total Return</th>
                <th>Tranches</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cycles as $cycle)
            <tr>
                <td>{{ $cycle->name }}</td>
                <td>{{ $cycle->duration_days }} days</td>
                <td>{{ $cycle->daily_profit_percent }}%</td>
                <td>{{ $cycle->total_return_percent }}%</td>
                <td>{{ $cycle->tranches->count() }}</td>
                <td>
                    <span style = "{{ 'color: ' . ($cycle->is_active ? '#81c784' : '#fbc02d') . ';' }}">
                        {{ $cycle->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.cycles.edit', $cycle) }}" style="color: #c9a227; margin-right: 1rem;">Edit</a>
                    <a href="{{ route('admin.tranches', $cycle) }}" style="color: #c9a227;">Tranches</a>

                    <form method="POST" action="{{ route('admin.cycles.update', $cycle) }}" style="display:inline; margin-left:1rem;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_active" value="{{ $cycle->is_active ? 0 : 1 }}">
                        <input type="hidden" name="name" value="{{ $cycle->name }}">
                        <input type="hidden" name="slug" value="{{ $cycle->slug }}">
                        <input type="hidden" name="duration_days" value="{{ $cycle->duration_days }}">
                        <input type="hidden" name="daily_profit_percent" value="{{ $cycle->daily_profit_percent }}">
                        <input type="hidden" name="total_return_percent" value="{{ $cycle->total_return_percent }}">
                        @php
                            $btnColor = $cycle->is_active ? '#e57373' : '#81c784';
                            $btnText = $cycle->is_active ? 'Désactiver' : 'Activer';
                        @endphp
                        <button type="submit" style="background: none; border: none; cursor: pointer; color: {{ $btnColor }};">
                            {{ $btnText }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 2rem;">
        {{ $cycles->links() }}
    </div>
    @else
    <p style="color: #b0bfd9; text-align: center; padding: 3rem;">No cycles found.</p>
    @endif
</div>
@endsection