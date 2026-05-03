@extends('layouts.app')

@section('title', 'Create Investment - KINETIC')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 2rem; color: #c9a227;">Create New Investment</h1>

    <div class="card">
        <form method="POST" action="{{ route('investments.store') }}">
            @csrf

            <div class="form-group">
                <label for="cycle">Select Investment Cycle</label>
                <select id="cycle" name="cycle_id" required onchange="updateTranches()">
                    <option value="">-- Choose a Cycle --</option>
                    @foreach($cycles as $cycle)
                        <option value="{{ $cycle->id }}">
                            {{ $cycle->name }} - {{ $cycle->duration_days }} days / {{ $cycle->daily_profit_percent }}% daily
                        </option>
                    @endforeach
                </select>
                @error('cycle_id')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="tranche">Select Investment Level</label>
                <select id="tranche" name="tranche_id" required>
                    <option value="">-- Choose a Level --</option>
                </select>
                @error('tranche_id')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="amount">Investment Amount ($)</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0" required value="{{ old('amount') }}">
                <small style="color: #b0bfd9;" id="amount-hint"></small>
                @error('amount')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn" style="width: 100%;">Create Investment</button>
                <a href="{{ route('investments.index') }}" class="btn btn-secondary" style="width: 100%; text-align: center; margin-top: 0.5rem;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    const cyclesData = @json($cycles);

    function updateTranches() {
        const cycleId = document.getElementById('cycle').value;
        const tranches = [];

        cyclesData.forEach(cycle => {
            if (cycle.id == cycleId) {
                tranches.push(...cycle.tranches);
            }
        });

        const trancheSelect = document.getElementById('tranche');
        trancheSelect.innerHTML = '<option value="">-- Choose a Level --</option>';

        tranches.forEach(tranche => {
            const option = document.createElement('option');
            option.value = tranche.id;
            option.textContent = `${tranche.name} - Min: $${tranche.min_amount}${tranche.max_amount ? `, Max: $${tranche.max_amount}` : ''}`;
            option.dataset.min = tranche.min_amount;
            option.dataset.max = tranche.max_amount;
            trancheSelect.appendChild(option);
        });

        updateAmountHint();
    }

    function updateAmountHint() {
        const trancheSelect = document.getElementById('tranche');
        const selected = trancheSelect.options[trancheSelect.selectedIndex];
        const hint = document.getElementById('amount-hint');

        if (selected && selected.dataset.min) {
            let text = `Minimum: $${selected.dataset.min}`;
            if (selected.dataset.max) {
                text += `, Maximum: $${selected.dataset.max}`;
            }
            hint.textContent = text;
        }
    }

    document.getElementById('tranche').addEventListener('change', updateAmountHint);
</script>
@endsection
