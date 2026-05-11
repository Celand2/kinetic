@extends('layouts.admin')

@section('title', 'Retraits en attente - KINETIC Admin')
@section('back')<a href="{{ route('admin.finance.transactions') }}" class="kts-back-btn">← Transactions</a>@endsection

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
    <div>
        <h2 style="color:#c9a227; font-family:'Orbitron',sans-serif; font-size:1.1rem; margin:0;">
            Retraits en attente
        </h2>
        <p style="color:#6b7a9a; font-size:0.82rem; margin:4px 0 0;">
            {{ $withdrawals->count() }} demande(s) — adresses de paiement complètes
        </p>
    </div>
    <a href="{{ route('admin.finance.withdrawals.download') }}"
       class="btn" style="white-space:nowrap; font-size:0.85rem;">
        ⬇ Télécharger CSV
    </a>
</div>

@if(session('success'))
    <div style="background:rgba(129,199,132,0.12); border:1px solid #81c784; border-radius:8px;
                padding:0.75rem 1rem; margin-bottom:1rem; color:#81c784; font-size:0.9rem;">
        {{ session('success') }}
    </div>
@endif

@if($withdrawals->count() > 0)

    {{-- VUE CARTES (mobile-first, lisible sur tous les écrans) --}}
    <div style="display:flex; flex-direction:column; gap:1rem;">
        @foreach($withdrawals as $txn)
        @php
            $meta    = $txn->metadata ?? [];
            $details = $meta['wallet_details'] ?? '—';
            $method  = $txn->payment_method ?? '—';
            $total   = $txn->amount + $txn->fee_amount;
            $copyId  = 'copy-' . $txn->id;
        @endphp
        <div class="card" style="padding:1.25rem; position:relative;">

            {{-- En-tête : ref + date + statut --}}
            <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:0.5rem; margin-bottom:1rem;">
                <div>
                    <span style="font-family:'Space Mono',monospace; color:#c9a227; font-size:0.82rem; font-weight:700;">
                        {{ $txn->reference }}
                    </span>
                    <div style="font-size:0.75rem; color:#6b7a9a; margin-top:2px;">
                        {{ $txn->created_at->format('d/m/Y à H:i') }}
                    </div>
                </div>
                <span style="background:rgba(251,192,45,0.12); border:1px solid rgba(251,192,45,0.4);
                             color:#fbc02d; padding:3px 10px; border-radius:20px; font-size:0.75rem; font-weight:700;">
                    En attente
                </span>
            </div>

            {{-- Grille infos --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px,1fr)); gap:0.75rem; margin-bottom:1rem;">

                <div style="background:rgba(201,162,39,0.05); border:1px solid rgba(201,162,39,0.15); border-radius:8px; padding:0.75rem;">
                    <div style="font-size:0.7rem; color:#6b7a9a; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Nom complet</div>
                    <div style="color:#e8eaf6; font-weight:600; font-size:0.95rem;">{{ $txn->user->full_name ?? '—' }}</div>
                </div>

                <div style="background:rgba(201,162,39,0.05); border:1px solid rgba(201,162,39,0.15); border-radius:8px; padding:0.75rem;">
                    <div style="font-size:0.7rem; color:#6b7a9a; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Téléphone</div>
                    <div style="color:#e8eaf6; font-weight:600; font-size:0.95rem; display:flex; align-items:center; gap:0.5rem;">
                        <span id="phone-{{ $txn->id }}">{{ $txn->user->phone ?? '—' }}</span>
                        <button onclick="copyText('phone-{{ $txn->id }}')"
                                style="background:transparent; border:1px solid rgba(201,162,39,0.3); color:#c9a227;
                                       border-radius:4px; padding:2px 7px; font-size:0.7rem; cursor:pointer;"
                                title="Copier">⧉</button>
                    </div>
                </div>

                <div style="background:rgba(201,162,39,0.05); border:1px solid rgba(201,162,39,0.15); border-radius:8px; padding:0.75rem;">
                    <div style="font-size:0.7rem; color:#6b7a9a; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Méthode</div>
                    <div style="color:#e8eaf6; font-weight:600;">
                        @if($method === 'lumicash')
                            <span style="color:#c9a227;">💛 Lumicash</span>
                        @elseif($method === 'bancobu_enoti')
                            <span style="color:#81c784;">🏦 Bancobu / e-Noti</span>
                        @else
                            {{ $method }}
                        @endif
                    </div>
                </div>

                <div style="background:rgba(201,162,39,0.05); border:1px solid rgba(201,162,39,0.15); border-radius:8px; padding:0.75rem;">
                    <div style="font-size:0.7rem; color:#6b7a9a; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Montant demandé</div>
                    <div style="color:#c9a227; font-weight:700; font-size:1.05rem;">${{ number_format($txn->amount, 2) }}</div>
                    <div style="color:#b0bfd9; font-size:0.75rem;">Frais : ${{ number_format($txn->fee_amount, 2) }}</div>
                    <div style="color:#ef5350; font-weight:700; font-size:0.85rem;">Total déduit : ${{ number_format($total, 2) }}</div>
                </div>

            </div>

            {{-- Adresse de paiement (champ libre) --}}
            <div style="background:rgba(201,162,39,0.05); border:1px solid rgba(201,162,39,0.25); border-radius:8px; padding:0.9rem; margin-bottom:1rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                    <div style="font-size:0.7rem; color:#6b7a9a; text-transform:uppercase; letter-spacing:0.08em;">
                        Adresse / Détails de paiement
                    </div>
                    <button onclick="copyText('details-{{ $txn->id }}')"
                            style="background:rgba(201,162,39,0.1); border:1px solid rgba(201,162,39,0.4);
                                   color:#c9a227; border-radius:6px; padding:4px 12px; font-size:0.75rem; cursor:pointer;">
                        ⧉ Copier
                    </button>
                </div>
                <div id="details-{{ $txn->id }}"
                     style="color:#e8eaf6; font-size:0.9rem; white-space:pre-wrap; word-break:break-word; line-height:1.6;">{{ $details }}</div>
            </div>

            {{-- Bloc à copier en un clic --}}
            <div style="margin-bottom:1rem;">
                <button onclick="copyBlock({{ $txn->id }})"
                        style="width:100%; background:rgba(201,162,39,0.08); border:1px dashed rgba(201,162,39,0.4);
                               color:#c9a227; border-radius:8px; padding:0.6rem 1rem; font-size:0.82rem;
                               cursor:pointer; text-align:left;">
                    📋 Copier toutes les infos (nom + téléphone + adresse + montant)
                </button>
                <div id="block-{{ $txn->id }}" style="display:none;">{{ $txn->user->full_name ?? '' }} | {{ $txn->user->phone ?? '' }} | {{ $method }} | {{ $details }} | ${{ number_format($txn->amount, 2) }}</div>
            </div>

            {{-- Actions --}}
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                <form method="POST" action="{{ route('admin.finance.approve', $txn) }}"
                      onsubmit="return confirm('Approuver ce retrait de ${{ number_format($txn->amount,2) }} ?')"
                      style="flex:1; min-width:120px;">
                    @csrf
                    <button type="submit" class="btn" style="width:100%; font-size:0.85rem;">
                        ✓ Approuver
                    </button>
                </form>
                <button type="button" onclick="openRejectModal({{ $txn->id }})"
                        class="btn" style="flex:1; min-width:120px; background:transparent;
                                           border-color:#ef5350; color:#ef5350; font-size:0.85rem;">
                    ✕ Refuser
                </button>
            </div>

        </div>
        @endforeach
    </div>

@else
    <div class="card" style="text-align:center; padding:3rem; color:#6b7a9a;">
        Aucun retrait en attente pour le moment.
    </div>
@endif

{{-- Modal Refus --}}
<div id="rejectModal" style="
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,0.7); align-items:center; justify-content:center; padding:1rem;">
    <div style="background:#141a2e; border:1px solid rgba(201,162,39,0.3); border-radius:12px;
                padding:1.5rem; width:100%; max-width:440px;">
        <h3 style="color:#ef5350; margin:0 0 1rem;">Refuser le retrait</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom:1rem;">
                <label style="color:#b0bfd9; font-size:0.9rem;">Motif du refus *</label>
                <textarea name="reason" rows="4" required
                          placeholder="Expliquez pourquoi ce retrait est refusé..."
                          style="margin-top:0.5rem;"></textarea>
            </div>
            <div style="display:flex; gap:0.75rem;">
                <button type="submit" class="btn"
                        style="flex:1; background:#ef5350; border-color:#ef5350;">
                    Confirmer le refus
                </button>
                <button type="button" onclick="closeRejectModal()"
                        class="btn" style="flex:1; background:transparent; border-color:rgba(201,162,39,0.4);">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Toast copie --}}
<div id="copyToast" style="
    display:none; position:fixed; bottom:1.5rem; right:1.5rem; z-index:10000;
    background:#1e2a45; border:1px solid rgba(201,162,39,0.5); color:#c9a227;
    padding:0.6rem 1.2rem; border-radius:8px; font-size:0.85rem; font-weight:600;">
    ✓ Copié !
</div>

@push('scripts')
<script>
function copyText(elementId) {
    const text = document.getElementById(elementId).innerText.trim();
    navigator.clipboard.writeText(text).then(() => showToast());
}

function copyBlock(id) {
    const text = document.getElementById('block-' + id).innerText.trim();
    navigator.clipboard.writeText(text).then(() => showToast());
}

function showToast() {
    const t = document.getElementById('copyToast');
    t.style.display = 'block';
    setTimeout(() => { t.style.display = 'none'; }, 2000);
}

function openRejectModal(id) {
    const base = '{{ url('admin/finance/transactions') }}';
    document.getElementById('rejectForm').action = base + '/' + id + '/reject';
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endpush

@endsection
