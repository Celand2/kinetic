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
        
        $activeInvestments = Investment::where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['tradingCycle', 'tranche'])
            ->get();

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
            ->where('status', 'approved')
            ->sum('commission_amount');

        return view('dashboard.index', compact(
            'user',
            'activeInvestments',
            'completedInvestments',
            'totalInvested',
            'totalEarned',
            'recentTransactions',
            'referralCount',
            'referralEarnings'
        ));
    }
}
