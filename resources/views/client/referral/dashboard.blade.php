@extends('layouts.client')

@section('title', 'Referral Dashboard - KINETIC')

@section('content')
<h1 style="margin-bottom: 2rem; color: #c9a227;">Referral Dashboard</h1>

<div class="grid">
    <div class="stat-box">
        <div class="stat-label">Total Referrals</div>
        <div class="stat-value">{{ $totalReferrals }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Total Commissions</div>
        <div class="stat-value">${{ number_format($totalCommissions, 2) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Pending Commissions</div>
        <div class="stat-value">${{ number_format($pendingCommissions, 2) }}</div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">Your Referral Link</div>
    <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <input type="text" id="referral-link" value="{{ route('register', ['ref' => $user->referral_code]) }}" readonly style="flex: 1; min-width: 240px;">
        <button class="btn" id="copyBtn" onclick="copyReferralLink()">Copier le lien</button>
    </div>
    <div style="margin-top: 1rem; color: #b0bfd9;">Partagez ce lien pour gagner des commissions sur les dépôts de vos filleuls.</div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">Structure des commissions</div>
    <div style="display: flex; flex-direction: column; gap: 0.75rem; padding: 0.25rem 0;">
        <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1rem; background: rgba(201,162,39,0.08); border: 1px solid rgba(201,162,39,0.25); border-radius: 10px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(201,162,39,0.2); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #c9a227; font-size: 0.85rem; flex-shrink: 0;">N1</div>
            <div style="flex: 1;">
                <div style="color: #e8e8e8; font-weight: 600; font-size: 0.9rem;">Niveau 1 — Filleuls directs</div>
                <div style="color: #b0bfd9; font-size: 0.78rem;">Personnes inscrites via votre lien</div>
            </div>
            <div style="font-size: 1.2rem; font-weight: 800; color: #c9a227;">10%</div>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1rem; background: rgba(107,122,154,0.08); border: 1px solid rgba(107,122,154,0.2); border-radius: 10px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(107,122,154,0.2); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #b0bfd9; font-size: 0.85rem; flex-shrink: 0;">N2</div>
            <div style="flex: 1;">
                <div style="color: #e8e8e8; font-weight: 600; font-size: 0.9rem;">Niveau 2 — Filleuls de vos filleuls</div>
                <div style="color: #b0bfd9; font-size: 0.78rem;">2ème degré de votre réseau</div>
            </div>
            <div style="font-size: 1.2rem; font-weight: 800; color: #b0bfd9;">3%</div>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1rem; background: rgba(107,122,154,0.05); border: 1px solid rgba(107,122,154,0.15); border-radius: 10px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(107,122,154,0.12); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #6b7a9a; font-size: 0.85rem; flex-shrink: 0;">N3</div>
            <div style="flex: 1;">
                <div style="color: #e8e8e8; font-weight: 600; font-size: 0.9rem;">Niveau 3 — 3ème degré</div>
                <div style="color: #b0bfd9; font-size: 0.78rem;">Filleuls des filleuls de vos filleuls</div>
            </div>
            <div style="font-size: 1.2rem; font-weight: 800; color: #6b7a9a;">1%</div>
        </div>
        <div style="font-size: 0.75rem; color: #6b7a9a; text-align: center; padding-top: 0.25rem;">Les commissions sont créditées automatiquement à chaque dépôt validé de votre réseau.</div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">Your Referrals</div>
    @if($referrals->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Active Investments</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($referrals as $referral)
                    <tr>
                        <td>{{ $referral->full_name }}</td>
                        <td>{{ $referral->email }}</td>
                        <td>{{ $referral->investments()->where('status', 'active')->count() }}</td>
                        <td>{{ $referral->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">{{ $referrals->links() }}</div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">Aucun filleul pour l'instant. <button class="btn" onclick="copyReferralLink()" style="font-size: 0.82rem; padding: 6px 14px; margin-left: 6px;">Copier le lien</button></p>
    @endif
</div>

<div class="card">
    <div class="card-header">Commission History</div>
    @if($commissions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Referral</th>
                    <th>Type</th>
                    <th>Commission</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commissions as $commission)
                    <tr>
                        <td>{{ $commission->created_at->format('M d, Y') }}</td>
                        <td>{{ $commission->sourceUser->full_name ?? '—' }}</td>
                        <td>Niveau {{ $commission->level }}</td>
                        <td>${{ number_format($commission->commission_amount, 2) }}</td>
                        <td><span style="color: {{ $commission->status === 'paid' ? '#81c784' : '#fbc02d' }};">{{ ucfirst($commission->status) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">{{ $commissions->links() }}</div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">No commissions yet.</p>
    @endif
</div>

<script>
    function copyReferralLink() {
        const link = document.getElementById('referral-link').value;
        const btn  = document.getElementById('copyBtn');
        navigator.clipboard.writeText(link).then(() => {
            if (btn) {
                const original = btn.textContent;
                btn.textContent = 'Copié ✓';
                btn.disabled = true;
                setTimeout(() => { btn.textContent = original; btn.disabled = false; }, 2000);
            }
        });
    }
</script>
@endsection
