<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\TradingCycle;
use App\Models\Tranche;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $investments = Investment::where('user_id', $user->id)
            ->with(['tradingCycle', 'tranche'])
            ->latest()
            ->paginate(10);

        return view('client.investments.index', compact('investments'));
    }

    public function create()
    {
        $cycles = TradingCycle::where('is_active', true)
            ->with(['tranches' => function ($q) {
                $q->where('is_active', true)->orderBy('display_order');
            }])
            ->orderBy('display_order')
            ->get();

        return view('client.investments.create', compact('cycles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tranche_id' => 'required|exists:tranches,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $tranche = Tranche::findOrFail($validated['tranche_id']);
        $cycle = $tranche->tradingCycle;

        // Validate amount constraints
        if ($validated['amount'] < $tranche->min_amount) {
            return back()->withErrors(['amount' => 'Minimum investment is ' . $tranche->min_amount]);
        }

        if ($tranche->max_amount && $validated['amount'] > $tranche->max_amount) {
            return back()->withErrors(['amount' => 'Maximum investment is ' . $tranche->max_amount]);
        }

        // Check user balance
        if (Auth::user()->balance < $validated['amount']) {
            return back()->withErrors(['amount' => 'Insufficient balance']);
        }

        $user = Auth::user();
        
        // Create investment
        $investment = Investment::create([
            'user_id' => $user->id,
            'trading_cycle_id' => $cycle->id,
            'tranche_id' => $tranche->id,
            'amount' => $validated['amount'],
            'daily_profit_rate' => $cycle->daily_profit_percent,
            'total_return_rate' => $cycle->total_return_percent,
            'duration_days' => $cycle->duration_days,
            'started_at' => now(),
            'ends_at' => now()->addDays($cycle->duration_days),
            'status' => 'active',
            'reference' => 'INV-' . date('Y') . '-' . Str::padLeft(Investment::count() + 1, 6, '0'),
        ]);

        // Create transaction record
        Transaction::create([
            'user_id' => $user->id,
            'investment_id' => $investment->id,
            'type' => 'investment',
            'amount' => $validated['amount'],
            'status' => 'completed',
            'reference' => $investment->reference,
            'processed_at' => now(),
        ]);

        // Deduct from balance
        $user->decrement('balance', $validated['amount']);

        return redirect()->route('investments.show', $investment)
            ->with('success', 'Investment created successfully!');
    }

    public function show(Investment $investment)
    {
        $this->authorize('view', $investment);
        
        $investment->load(['tradingCycle', 'tranche', 'transactions']);

        return view('client.investments.show', compact('investment'));
    }
}
