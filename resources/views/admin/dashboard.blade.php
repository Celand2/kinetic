@extends('layouts.admin')
@section('title', 'Dashboard - KINETIC Admin')

@push('styles')
<style>
    /* ── Cartes fond blanc ──────────────────────────────────────────── */
    .wb-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.18), 0 4px 16px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    .wb-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
    }
    .wb-card.accent-green::before  { background: linear-gradient(90deg,#1aedaa,#22c55e); }
    .wb-card.accent-gold::before   { background: linear-gradient(90deg,#c9a227,#f0b429); }
    .wb-card.accent-blue::before   { background: linear-gradient(90deg,#3b82f6,#1DA7DB); }
    .wb-card.accent-red::before    { background: linear-gradient(90deg,#ef5350,#f43f5e); }
    .wb-card.accent-purple::before { background: linear-gradient(90deg,#8b5cf6,#a855f7); }
    .wb-card.accent-teal::before   { background: linear-gradient(90deg,#1F9AA5,#1CE7ED); }

    .wb-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.35rem;
    }
    .wb-value {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.1;
    }
    .wb-value.green  { color: #16a34a; }
    .wb-value.gold   { color: #b45309; }
    .wb-value.blue   { color: #1d4ed8; }
    .wb-value.red    { color: #dc2626; }
    .wb-value.purple { color: #7c3aed; }
    .wb-value.teal   { color: #0f766e; }

    .wb-sub {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 4px;
    }
    .wb-icon {
        position: absolute;
        top: 1rem; right: 1rem;
        font-size: 1.6rem;
        opacity: 0.12;
    }

    /* ── Grille KPI ─────────────────────────────────────────────────── */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    /* ── Carte graphe ───────────────────────────────────────────────── */
    .chart-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.15), 0 4px 16px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }
    .chart-title {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #475569;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    /* ── Tableau transactions récentes ──────────────────────────────── */
    .wb-table-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.15), 0 4px 16px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .wb-table-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .wb-table-title {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #475569;
        font-weight: 700;
    }
    .wb-table-card table { width: 100%; border-collapse: collapse; }
    .wb-table-card thead th {
        padding: 0.6rem 1rem;
        background: #f8fafc;
        color: #64748b;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-weight: 600;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    .wb-table-card tbody td {
        padding: 0.75rem 1rem;
        color: #334155;
        font-size: 0.84rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .wb-table-card tbody tr:last-child td { border-bottom: none; }
    .wb-table-card tbody tr:hover td { background: #f8fafc; }

    /* ── Alerte pending ─────────────────────────────────────────────── */
    .pending-alert {
        background: #fffbeb;
        border: 1px solid #fcd34d;
        border-radius: 10px;
        padding: 0.9rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .pending-alert-text { color: #92400e; font-size: 0.88rem; font-weight: 600; }
    .pending-alert-sub  { color: #b45309; font-size: 0.78rem; margin-top: 2px; }

    /* Badge status */
    .status-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-pending   { background: #fef3c7; color: #92400e; }
    .status-completed { background: #dcfce7; color: #166534; }
    .status-rejected  { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')

{{-- ── Titre ──────────────────────────────────────────────────────── --}}
<div style="margin-bottom:1.5rem;">
    <h1 style="color:#c9a227; font-size:1.3rem; margin:0;">Dashboard</h1>
    <div style="color:#6b7a9a; font-size:0.78rem; margin-top:2px;">{{ now()->format('l d F Y') }}</div>
</div>

{{-- ── Alerte pending ─────────────────────────────────────────────── --}}
@if($pendingDeposits > 0 || $pendingWithdrawals > 0)
<div class="pending-alert">
    <div>
        <div class="pending-alert-text">⚠️ Transactions en attente de validation</div>
        <div class="pending-alert-sub">
            {{ $pendingDeposits }} dépôt{{ $pendingDeposits > 1 ? 's' : '' }}
            · {{ $pendingWithdrawals }} retrait{{ $pendingWithdrawals > 1 ? 's' : '' }}
        </div>
    </div>
    <a href="{{ route('admin.finance.transactions', ['status' => 'pending']) }}" class="kts-btn">
        Voir maintenant →
    </a>
</div>
@endif

{{-- ── KPI row 1 : chiffres financiers ───────────────────────────── --}}
<div class="kpi-grid">

    <div class="wb-card accent-green">
        <span class="wb-icon">💰</span>
        <div class="wb-label">Total Dépôts</div>
        <div class="wb-value green">${{ number_format($totalDeposits, 0, ',', ' ') }}</div>
        <div class="wb-sub">Dépôts approuvés</div>
    </div>

    <div class="wb-card accent-red">
        <span class="wb-icon">💸</span>
        <div class="wb-label">Total Retraits</div>
        <div class="wb-value red">${{ number_format($totalWithdrawals, 0, ',', ' ') }}</div>
        <div class="wb-sub">Retraits approuvés</div>
    </div>

    <div class="wb-card accent-teal">
        <span class="wb-icon">📈</span>
        <div class="wb-label">Profits versés</div>
        <div class="wb-value teal">${{ number_format($totalProfitsPaid, 0, ',', ' ') }}</div>
        <div class="wb-sub">Gains quotidiens cumulés</div>
    </div>

    <div class="wb-card accent-gold">
        <span class="wb-icon">⚡</span>
        <div class="wb-label">Capital investi</div>
        <div class="wb-value gold">${{ number_format($totalInvested, 0, ',', ' ') }}</div>
        <div class="wb-sub">Contrats actifs</div>
    </div>

    <div class="wb-card accent-blue">
        <span class="wb-icon">💼</span>
        <div class="wb-label">Solde net</div>
        <div class="wb-value blue">${{ number_format($netBalance, 0, ',', ' ') }}</div>
        <div class="wb-sub">Dépôts − Retraits</div>
    </div>

    <div class="wb-card accent-purple">
        <span class="wb-icon">👥</span>
        <div class="wb-label">Utilisateurs</div>
        <div class="wb-value purple">{{ number_format($usersCount) }}</div>
        <div class="wb-sub">+{{ $newUsersToday }} aujourd'hui</div>
    </div>

    <div class="wb-card accent-teal">
        <span class="wb-icon">⚡</span>
        <div class="wb-label">Invest. actifs</div>
        <div class="wb-value teal">{{ $activeInvestments }}</div>
        <div class="wb-sub">Contrats en cours</div>
    </div>

    <div class="wb-card accent-red">
        <span class="wb-icon">⏳</span>
        <div class="wb-label">En attente</div>
        <div class="wb-value red" style="font-size:1.3rem;">
            {{ $pendingDeposits + $pendingWithdrawals }}
        </div>
        <div class="wb-sub">{{ $pendingDeposits }} dépôts · {{ $pendingWithdrawals }} retraits</div>
    </div>

</div>

{{-- ── Graphe : Dépôts & Retraits (14j) ──────────────────────────── --}}
<div class="chart-card">
    <div class="chart-title">📊 Dépôts & Retraits — 14 derniers jours</div>
    <canvas id="chartFinance" height="90"></canvas>
</div>

{{-- ── Graphe : Nouveaux utilisateurs (14j) ───────────────────────── --}}
<div class="chart-card">
    <div class="chart-title">👥 Nouveaux utilisateurs — 14 derniers jours</div>
    <canvas id="chartUsers" height="60"></canvas>
</div>

{{-- ── Transactions récentes ───────────────────────────────────────── --}}
<div class="wb-table-card">
    <div class="wb-table-head">
        <div class="wb-table-title">🕐 Transactions récentes</div>
        <a href="{{ route('admin.finance.transactions') }}" class="kts-btn kts-btn-sm">Voir tout</a>
    </div>
    @if($recentTransactions->count() > 0)
    <div style="overflow-x:auto;">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Utilisateur</th>
                <th>Type</th>
                <th>Montant</th>
                <th>Méthode</th>
                <th>Statut</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentTransactions as $txn)
            <tr>
                <td style="white-space:nowrap; color:#64748b; font-size:0.78rem;">{{ $txn->created_at->format('d/m H:i') }}</td>
                <td>
                    <div style="font-weight:600; color:#1e293b;">{{ $txn->user->full_name ?? '—' }}</div>
                    <div style="font-size:0.72rem; color:#94a3b8;">{{ $txn->user->email ?? '' }}</div>
                </td>
                <td>
                    @if($txn->type === 'deposit')
                        <span style="color:#16a34a; font-weight:600; font-size:0.82rem;">↓ Dépôt</span>
                    @else
                        <span style="color:#dc2626; font-weight:600; font-size:0.82rem;">↑ Retrait</span>
                    @endif
                </td>
                <td style="font-weight:700; color:#0f172a;">${{ number_format($txn->amount, 2) }}</td>
                <td style="color:#64748b; font-size:0.8rem;">{{ $txn->payment_method ?? '—' }}</td>
                <td>
                    @php
                        $cls = ['pending'=>'status-pending','completed'=>'status-completed','rejected'=>'status-rejected'];
                        $lbl = ['pending'=>'En attente','completed'=>'Approuvé','rejected'=>'Refusé'];
                    @endphp
                    <span class="status-badge {{ $cls[$txn->status] ?? '' }}">
                        {{ $lbl[$txn->status] ?? $txn->status }}
                    </span>
                </td>
                <td>
                    @if($txn->status === 'pending')
                        <a href="{{ route('admin.finance.transactions', ['status'=>'pending']) }}" class="kts-btn kts-btn-sm">Valider</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @else
        <div style="padding:2rem; text-align:center; color:#94a3b8; font-size:0.88rem;">Aucune transaction récente.</div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels      = @json($chartLabels);
const deposits    = @json($chartDeposits);
const withdrawals = @json($chartWithdrawals);
const users       = @json($chartUsers);

// ── Graphe Finance ────────────────────────────────────────────────────
new Chart(document.getElementById('chartFinance'), {
    type: 'bar',
    data: {
        labels,
        datasets: [
            {
                label: 'Dépôts ($)',
                data: deposits,
                backgroundColor: 'rgba(22,163,74,0.75)',
                borderColor: '#16a34a',
                borderWidth: 1,
                borderRadius: 5,
                order: 2,
            },
            {
                label: 'Retraits ($)',
                data: withdrawals,
                backgroundColor: 'rgba(220,38,38,0.65)',
                borderColor: '#dc2626',
                borderWidth: 1,
                borderRadius: 5,
                order: 2,
            },
            {
                label: 'Net ($)',
                data: deposits.map((d, i) => d - withdrawals[i]),
                type: 'line',
                borderColor: '#c9a227',
                backgroundColor: 'rgba(201,162,39,0.08)',
                borderWidth: 2,
                pointBackgroundColor: '#c9a227',
                pointRadius: 3,
                tension: 0.35,
                fill: true,
                order: 1,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { labels: { color: '#475569', font: { size: 11 } } },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.dataset.label}: $${Number(ctx.parsed.y).toLocaleString('fr-FR', {minimumFractionDigits:2})}`
                }
            }
        },
        scales: {
            x: { ticks: { color: '#94a3b8', font: { size: 10 } }, grid: { color: '#f1f5f9' } },
            y: {
                ticks: {
                    color: '#94a3b8', font: { size: 10 },
                    callback: v => '$' + Number(v).toLocaleString('fr-FR')
                },
                grid: { color: '#f1f5f9' }
            }
        }
    }
});

// ── Graphe Utilisateurs ───────────────────────────────────────────────
new Chart(document.getElementById('chartUsers'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Nouveaux utilisateurs',
            data: users,
            borderColor: '#7c3aed',
            backgroundColor: 'rgba(124,58,237,0.1)',
            borderWidth: 2,
            pointBackgroundColor: '#7c3aed',
            pointRadius: 4,
            tension: 0.35,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: '#475569', font: { size: 11 } } }
        },
        scales: {
            x: { ticks: { color: '#94a3b8', font: { size: 10 } }, grid: { color: '#f1f5f9' } },
            y: { ticks: { color: '#94a3b8', font: { size: 10 }, stepSize: 1 }, grid: { color: '#f1f5f9' }, beginAtZero: true }
        }
    }
});
</script>
@endpush

@endsection
