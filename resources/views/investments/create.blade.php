@extends('layouts.app')

@section('title', 'Create Investment - KINETIC')

@section('content')
<!-- TRADING CYCLES -->
<section class="cycles-section" id="cycles-section">
  <div class="section-header">
    <div class="section-eyebrow">// Cycles de Trading</div>
    <h2 class="section-title">Choisissez votre <span>stratégie de rendement</span></h2>
  </div>
 
  <div class="cycles-grid">
    <div class="cycle-card flash">
      <div class="cycle-name">KINETIC FLASH</div>
      <div class="cycle-days">07 jours · Cycle rapide</div>
      <div class="cycle-rate">15%</div>
      <div class="cycle-unit">profit journalier</div>
      <div class="cycle-return">
        <span class="key">Retour total</span>
        <span class="val">105%</span>
      </div>
      @auth
        <a href="{{ route('dashboard') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @else
        <a href="{{ route('register') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @endauth
    </div>
 
    <div class="cycle-card boost">
      <div class="cycle-name">KINETIC BOOST</div>
      <div class="cycle-days">15 jours · Cycle intermédiaire</div>
      <div class="cycle-rate">8%</div>
      <div class="cycle-unit">profit journalier</div>
      <div class="cycle-return">
        <span class="key">Retour total</span>
        <span class="val">120%</span>
      </div>
      @auth
        <a href="{{ route('dashboard') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @else
        <a href="{{ route('register') }}" class="btn-primary" style="display:block;text-align:center;margin-top:20px;">Investir →</a>
      @endauth
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
