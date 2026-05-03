@extends('layouts.app')

@section('title', 'Referral Dashboard - KINETIC')

@section('content')
<h1 style="margin-bottom: 2rem; color: #c9a227;">Referral Dashboard</h1>

<!-- Stats -->
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

<!-- Referral Link -->
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">Your Referral Link</div>
    
    <div style="display: flex; gap: 1rem;">
        <input type="text" id="referral-link" value="{{ route('register', ['ref' => $user->referral_code]) }}" readonly style="flex: 1;">
        <button class="btn" onclick="copyReferralLink()">Copy Link</button>
    </div>

    <div style="margin-top: 1rem;">
        <small style="color: #b0bfd9;">Share this link to earn commissions on deposits and investments from your referrals.</small>
    </div>
</div>

<!-- Referrals Table -->
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

        <div style="margin-top: 2rem;">
            {{ $referrals->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">
            No referrals yet. <a href="#" onclick="copyReferralLink()" style="color: #c9a227;">Share your link</a> to start earning!
        </p>
    @endif
</div>

<!-- Commissions Table -->
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
                        <td>{{ $commission->referred->full_name }}</td>
                        <td>{{ ucfirst($commission->transaction_type) }} (Level {{ $commission->commission_level }})</td>
                        <td>${{ number_format($commission->commission_amount, 2) }}</td>
                        <td>
                            <span style="color: {{ $commission->status === 'approved' ? '#81c784' : '#fbc02d' }};">
                                {{ ucfirst($commission->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $commissions->links() }}
        </div>
    @else
        <p style="color: #b0bfd9; text-align: center; padding: 3rem;">No commissions yet.</p>
    @endif
</div>

<script>
    function copyReferralLink() {
        const link = document.getElementById('referral-link');
        link.select();
        document.execCommand('copy');
        alert('Referral link copied to clipboard!');
    }
</script>
@endsection
