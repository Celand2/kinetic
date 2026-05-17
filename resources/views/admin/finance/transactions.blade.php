@extends('layouts.admin')

@section('title', 'Transactions - KINETIC Admin')
@section('back')<a href="{{ route('admin.dashboard') }}" class="kts-back-btn">← Dashboard</a>@endsection

@section('content')

{{-- Filtres --}}
<form method="GET" action="{{ route('admin.finance.transactions') }}"
      style="margin-bottom:1.5rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
    <select name="type" style="flex:1; min-width:140px;">
        <option value="">Tous les types</option>
        <option value="deposit"    {{ request('type')=='deposit'    ? 'selected':'' }}>Dépôts</option>
        <option value="withdrawal" {{ request('type')=='withdrawal' ? 'selected':'' }}>Retraits</option>
    </select>
    <select name="status" style="flex:1; min-width:140px;">
        <option value="">Tous les statuts</option>
        <option value="pending"   {{ request('status')=='pending'   ? 'selected':'' }}>En attente</option>
        <option value="completed" {{ request('status')=='completed' ? 'selected':'' }}>Approuvés</option>
        <option value="rejected"  {{ request('status')=='rejected'  ? 'selected':'' }}>Refusés</option>
    </select>
    <button type="submit" class="btn" style="white-space:nowrap;">Filtrer</button>
    @if(request('type') || request('status'))
        <a href="{{ route('admin.finance.transactions') }}"
           class="btn" style="background:transparent; border-color:rgba(201,162,39,0.4); white-space:nowrap;">✕ Reset</a>
    @endif
</form>

<div class="card">
    @if($transactions->count() > 0)
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Méthode</th>
                    <th>Preuve</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td style="font-size:0.82rem; color:#b0bfd9; white-space:nowrap;">
                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $transaction->user->full_name ?? '—' }}</div>
                        <div style="font-size:0.78rem; color:#b0bfd9;">{{ $transaction->user->email ?? '' }}</div>
                    </td>
                    <td>
                        @php
                            $typeColors = ['deposit'=>'#81c784','withdrawal'=>'#fbc02d','daily_profit'=>'#c9a227'];
                            $tc = $typeColors[$transaction->type] ?? '#b0bfd9';
                        @endphp
                        <span style="color:{{ $tc }}; font-size:0.85rem; text-transform:capitalize; font-weight:600;">
                            {{ $transaction->type === 'deposit' ? 'Dépôt' : ($transaction->type === 'withdrawal' ? 'Retrait' : ucfirst($transaction->type)) }}
                        </span>
                    </td>
                    <td style="color:#c9a227; font-weight:700; white-space:nowrap;">
                        {{ $transaction->formatted_amount }}
                        @if($transaction->display_currency !== 'USD')
                            <div style="font-size:0.75rem; color:#b0bfd9; font-weight:400;">
                                ≈ ${{ number_format($transaction->amount, 2) }} USD
                            </div>
                        @endif
                        @if($transaction->fee_amount > 0)
                            <div style="font-size:0.75rem; color:#b0bfd9; font-weight:400;">
                                Frais: ${{ number_format($transaction->fee_amount, 2) }}
                            </div>
                        @endif
                    </td>
                    <td style="font-size:0.82rem; color:#b0bfd9;">
                        {{ $transaction->payment_method ?? '—' }}
                    </td>
                    <td>
                        @php
                            $meta       = $transaction->metadata ?? [];
                            $screenshot = $meta['screenshot'] ?? null;
                        @endphp
                        @if($screenshot)
                            <a href="{{ Storage::url($screenshot) }}" target="_blank"
                               style="display:inline-block;" title="Voir la preuve">
                                <img src="{{ Storage::url($screenshot) }}"
                                     alt="Preuve"
                                     style="width:48px; height:48px; object-fit:cover; border-radius:6px;
                                            border:2px solid rgba(201,162,39,0.5); cursor:pointer;"
                                     onclick="openProof(this.src); return false;">
                            </a>
                        @else
                            <span style="color:#6b7a9a; font-size:0.8rem;">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $sc = ['pending'=>'#fbc02d','completed'=>'#81c784','rejected'=>'#ef5350','cancelled'=>'#b0bfd9'];
                            $s  = $sc[$transaction->status] ?? '#b0bfd9';
                            $sl = ['pending'=>'En attente','completed'=>'Approuvé','rejected'=>'Refusé','cancelled'=>'Annulé'];
                        @endphp
                        <span style="color:{{ $s }}; font-weight:600; font-size:0.85rem;">
                            {{ $sl[$transaction->status] ?? ucfirst($transaction->status) }}
                        </span>
                        @if($transaction->admin_notes)
                            <div style="font-size:0.75rem; color:#b0bfd9; max-width:140px; margin-top:2px;">
                                {{ Str::limit($transaction->admin_notes, 50) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($transaction->status === 'pending')
                        <div style="display:flex; flex-direction:column; gap:0.5rem;">
                            <form method="POST" action="{{ route('admin.finance.approve', $transaction) }}"
                                  onsubmit="return confirm('Approuver ce {{ $transaction->type === 'deposit' ? 'dépôt' : 'retrait' }} ?')">
                                @csrf
                                <button type="submit" class="btn"
                                        style="padding:4px 12px; font-size:0.8rem; width:100%;">
                                    ✓ Approuver
                                </button>
                            </form>
                            <button type="button" class="btn"
                                    onclick="openRejectModal({{ $transaction->id }})"
                                    style="padding:4px 12px; font-size:0.8rem; background:transparent;
                                           border-color:#ef5350; color:#ef5350; width:100%;">
                                ✕ Refuser
                            </button>
                        </div>
                        @else
                            <span style="font-size:0.78rem; color:#6b7a9a;">
                                @if($transaction->processed_at)
                                    {{ $transaction->processed_at->format('d/m H:i') }}
                                @endif
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div style="padding:1rem;">{{ $transactions->links() }}</div>
    @else
        <p style="color:#b0bfd9; text-align:center; padding:3rem;">Aucune transaction trouvée.</p>
    @endif
</div>

{{-- Modal Refus --}}
<div id="rejectModal" style="
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,0.7); align-items:center; justify-content:center; padding:1rem;
">
    <div style="
        background:#141a2e; border:1px solid rgba(201,162,39,0.3); border-radius:12px;
        padding:1.5rem; width:100%; max-width:440px;
    ">
        <h3 style="color:#ef5350; margin:0 0 1rem;">Refuser la transaction</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom:1rem;">
                <label style="color:#b0bfd9; font-size:0.9rem;">Motif du refus *</label>
                <textarea name="reason" rows="4" required
                          placeholder="Expliquez pourquoi cette transaction est refusée..."
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

{{-- Modal Preuve --}}
<div id="proofModal" style="
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,0.85); align-items:center; justify-content:center; padding:1rem;
" onclick="document.getElementById('proofModal').style.display='none'">
    <img id="proofImg" src="" alt="Preuve de paiement"
         style="max-width:100%; max-height:90vh; border-radius:8px; object-fit:contain;">
</div>

@push('scripts')
<script>
function openRejectModal(id) {
    const base = '{{ url('admin/finance/transactions') }}';
    document.getElementById('rejectForm').action = base + '/' + id + '/reject';
    const modal = document.getElementById('rejectModal');
    modal.style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
function openProof(src) {
    document.getElementById('proofImg').src = src;
    document.getElementById('proofModal').style.display = 'flex';
}
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endpush

@endsection
