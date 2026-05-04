@extends('layouts.app')

@section('title', 'Create Investment - KINETIC')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <h1 style="margin-bottom: 1rem; color: #c9a227;">Create New Investment</h1>
    <a href="{{ route('investments.index') }}" class="back-link">← Retour aux investissements</a>

    <div class="card">
        <form method="POST" action="{{ route('investments.store') }}">
            @csrf

            <div class="form-group">
                <label for="cycle">Select Investment Cycle</label>
                <select class="form-control" id="cycle" name="cycle_id" required onchange="updateTranches()">
                    <option value="">-- Choose a Cycle --</option>
                    @foreach($cycles as $cycle)
                        <option value="{{ $cycle->id }}">
                            {{ $cycle->name }} - {{ $cycle->duration_days }} days / {{ $cycle->daily_profit_percent }}% daily
                        </option>
                    @endforeach
                </select>
                @error('cycle_id')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="tranche">Select Investment Level</label>
                <select class="form-control" id="tranche" name="tranche_id" required>
                    <option value="">-- Choose a Level --</option>
                </select>
                @error('tranche_id')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="amount">Investment Amount ($)</label>
                <input class="form-control" type="number" id="amount" name="amount" step="0.01" min="0" required value="{{ old('amount') }}">
                <small style="color: #b0bfd9;" id="amount-hint"></small>
                @error('amount')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                <button type="submit" class="btn btn-primary" style="flex: 1; min-width: 180px;">Create Investment</button>
                <a href="{{ route('investments.index') }}" class="btn btn-secondary" style="flex: 1; min-width: 180px; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    const cyclesData = @json($cycles);

    function updateTranches() {
        const cycleId = document.getElementById('cycle').value;
        const trancheSelect = document.getElementById('tranche');
        trancheSelect.innerHTML = '<option value="">-- Choose a Level --</option>';

        const selectedCycle = cyclesData.find(cycle => cycle.id == cycleId);
        if (!selectedCycle) {
            document.getElementById('amount-hint').textContent = '';
            return;
        }

        selectedCycle.tranches.forEach(tranche => {
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
        const selected = document.getElementById('tranche').selectedOptions[0];
        const hint = document.getElementById('amount-hint');

        if (selected && selected.dataset.min) {
            let text = `Minimum: $${selected.dataset.min}`;
            if (selected.dataset.max) {
                text += `, Maximum: $${selected.dataset.max}`;
            }
            hint.textContent = text;
        } else {
            hint.textContent = '';
        }
    }

    document.getElementById('cycle').addEventListener('change', updateTranches);
    document.getElementById('tranche').addEventListener('change', updateAmountHint);
</script>
@endsection
