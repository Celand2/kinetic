<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $referrals = $user->referrals()
            ->with('investments')
            ->paginate(10);

        $totalReferrals = $user->referrals()->count();
        
        $commissions = $user->referralCommissionsAsReferrer()
            ->with('referred')
            ->latest()
            ->paginate(15);

        $totalCommissions = $user->referralCommissionsAsReferrer()
            ->where('status', 'completed')
            ->sum('commission_amount');

        $pendingCommissions = $user->referralCommissionsAsReferrer()
            ->where('status', 'pending')
            ->sum('commission_amount');

        return view('client.referral.dashboard', compact(
            'user',
            'referrals',
            'totalReferrals',
            'commissions',
            'totalCommissions',
            'pendingCommissions'
        ));
    }

    public function generateLink()
    {
        $user = Auth::user();
        return response()->json(['referral_link' => route('register') . '?ref=' . $user->referral_code]);
    }
}
