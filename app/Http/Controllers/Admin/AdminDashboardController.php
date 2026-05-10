<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ── Compteurs généraux ────────────────────────────────────────
        $usersCount         = User::where('role', 'user')->count();
        $activeInvestments  = Investment::where('status', 'active')->count();
        $pendingDeposits    = Transaction::where('type', 'deposit')->where('status', 'pending')->count();
        $pendingWithdrawals = Transaction::where('type', 'withdrawal')->where('status', 'pending')->count();
        $newUsersToday      = User::where('role', 'user')->whereDate('created_at', today())->count();

        // ── Totaux financiers ─────────────────────────────────────────
        $totalDeposits    = (float) Transaction::where('type', 'deposit')
            ->where('status', 'completed')->sum('amount');
        $totalWithdrawals = (float) Transaction::where('type', 'withdrawal')
            ->where('status', 'completed')->sum('amount');
        $totalProfitsPaid = (float) Transaction::where('type', 'daily_profit')
            ->where('status', 'completed')->sum('amount');
        $totalInvested    = (float) Investment::where('status', 'active')->sum('amount');
        $netBalance       = $totalDeposits - $totalWithdrawals;

        // ── Données graphe : 14 derniers jours ────────────────────────
        $days = collect(range(13, 0))->map(function ($ago) {
            $date = now()->subDays($ago)->toDateString();
            return [
                'label'       => now()->subDays($ago)->format('d/m'),
                'deposits'    => (float) Transaction::where('type', 'deposit')
                    ->where('status', 'completed')
                    ->whereDate('created_at', $date)->sum('amount'),
                'withdrawals' => (float) Transaction::where('type', 'withdrawal')
                    ->where('status', 'completed')
                    ->whereDate('created_at', $date)->sum('amount'),
                'users'       => User::where('role', 'user')
                    ->whereDate('created_at', $date)->count(),
            ];
        });

        $chartLabels      = $days->pluck('label');
        $chartDeposits    = $days->pluck('deposits');
        $chartWithdrawals = $days->pluck('withdrawals');
        $chartUsers       = $days->pluck('users');

        // ── Transactions récentes ─────────────────────────────────────
        $recentTransactions = Transaction::with('user')
            ->whereIn('type', ['deposit', 'withdrawal'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'usersCount', 'activeInvestments', 'pendingDeposits', 'pendingWithdrawals', 'newUsersToday',
            'totalDeposits', 'totalWithdrawals', 'totalProfitsPaid', 'totalInvested', 'netBalance',
            'chartLabels', 'chartDeposits', 'chartWithdrawals', 'chartUsers',
            'recentTransactions'
        ));
    }
}
