@extends('layouts.app')

@section('title', 'Admin Dashboard - KINETIC')

@section('content')
<div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem;">
    <h1 style="margin: 0; color: #c9a227;">Admin Dashboard</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-secondary">Déconnexion</button>
    </form>
</div>

<div class="grid" style="grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="stat-box">
        <div class="stat-label">Users</div>
        <div class="stat-value">{{ $usersCount ?? 0 }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Active Investments</div>
        <div class="stat-value">{{ $activeInvestments ?? 0 }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Pending Deposits</div>
        <div class="stat-value">{{ $pendingDeposits ?? 0 }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Pending Withdrawals</div>
        <div class="stat-value">{{ $pendingWithdrawals ?? 0 }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">Admin Quick Links</div>
    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
        <a href="{{ route('admin.users.index') }}" class="btn">Manage Users</a>
        <a href="{{ route('admin.finance.transactions') }}" class="btn">Review Transactions</a>
        <a href="{{ route('admin.cycles') }}" class="btn">Investment Cycles</a>
        <a href="{{ route('admin.notifications') }}" class="btn">Send Notification</a>
    </div>
</div>
@endsection