<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Models\Investment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirection des admins vers leur dashboard dédié
        if (in_array($user->role, ['super_admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        }
        
        // Investissements actifs
        $activeInvestments = Investment::where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['tradingCycle', 'tranche'])
            ->get();

        // Investissements en attente
        $pendingInvestments = Investment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with(['tradingCycle', 'tranche'])
            ->get();

        // Calcul des gains quotidiens
        $dailyGains = $activeInvestments->sum(function ($investment) {
            return ($investment->amount * $investment->daily_profit_rate) / 100;
        });

        // Statistiques
        $completedInvestments = Investment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $totalInvested = Investment::where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->sum('amount');

        $totalEarned = Investment::where('user_id', $user->id)
            ->sum('total_profit_credited');

        // Transactions récentes
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Données de parrainage
        $referralCount = $user->referrals()->count();
        $referralEarnings = $user->referralCommissionsAsReferrer()
            ->where('status', 'paid')
            ->sum('commission_amount');

        // Devise préférée et taux de change
        $userCurrency = $user->preferred_currency ?? 'USD';
        $currencyRate = (float) ExchangeRate::rate($userCurrency);

        // Solde investissable = balance totale - gains retirables (capital déposé, non retirable)
        $investableBalance = max(0, (float) $user->balance - (float) $user->profit_balance);

        // Retrait disponible uniquement après le premier crédit de profit journalier (≥ 24h après 1er investissement)
        $canWithdraw = Transaction::where('user_id', $user->id)
            ->where('type', 'daily_profit')
            ->where('status', 'completed')
            ->exists();

        return view('client.dashboard.index', compact(
            'user',
            'activeInvestments',
            'pendingInvestments',
            'completedInvestments',
            'dailyGains',
            'totalInvested',
            'totalEarned',
            'recentTransactions',
            'referralCount',
            'referralEarnings',
            'userCurrency',
            'currencyRate',
            'investableBalance',
            'canWithdraw'
        ));
    }
}
