<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\Transaction;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (in_array($user->role, ['super_admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        }
        
        $activeInvestments = Investment::where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['tradingCycle', 'tranche'])
            ->get();

        $dailyGains = $activeInvestments->sum(function ($investment) {
            return ($investment->amount * $investment->daily_profit_rate) / 100;
        });

        $completedInvestments = Investment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $totalInvested = Investment::where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->sum('amount');

        $totalEarned = Investment::where('user_id', $user->id)
            ->sum('total_profit_credited');

        $recentTransactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $referralCount = $user->referrals()->count();
        $referralEarnings = $user->referralCommissionsAsReferrer()
            ->where('status', 'completed')
            ->sum('commission_amount');

        return view('client.dashboard.index', compact(
            'user',
            'activeInvestments',
            'completedInvestments',
            'dailyGains',
            'totalInvested',
            'totalEarned',
            'recentTransactions',
            'referralCount',
            'referralEarnings'
        ));
    }
}
