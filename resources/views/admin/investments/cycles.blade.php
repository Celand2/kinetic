@extends('layouts.app')

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
                            <span style="color: {{ $cycle->is_active ? '#81c784' : '#fbc02d' }};">
                                {{ $cycle->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.cycles.edit', $cycle) }}" style="color: #c9a227; margin-right: 1rem;">Edit</a>
                            <a href="{{ route('admin.tranches', $cycle) }}" style="color: #c9a227;">Tranches</a>
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
