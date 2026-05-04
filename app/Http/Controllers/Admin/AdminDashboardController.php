<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $activeInvestments = Investment::where('status', 'active')->count();
        $pendingDeposits = Transaction::where('type', 'deposit')->where('status', 'pending')->count();
        $pendingWithdrawals = Transaction::where('type', 'withdrawal')->where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'usersCount',
            'activeInvestments',
            'pendingDeposits',
            'pendingWithdrawals'
        ));
    }
}

