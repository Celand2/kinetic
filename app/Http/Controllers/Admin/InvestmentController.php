<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\TradingCycle;
use App\Models\Tranche;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    // Trading Cycles Management
    public function cycles()
    {
        $cycles = TradingCycle::with('tranches')->paginate(10);
        return view('admin.investments.cycles', compact('cycles'));
    }

    public function createCycle()
    {
        return view('admin.investments.cycle-create');
    }

    public function storeCycle(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:trading_cycles',
            'slug' => 'required|string|unique:trading_cycles',
            'description' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
            'daily_profit_percent' => 'required|numeric|min:0|max:100',
            'total_return_percent' => 'required|numeric|min:0',
            'display_order' => 'nullable|integer',
        ]);

        TradingCycle::create($validated);

        return redirect()->route('admin.cycles')
            ->with('success', 'Trading cycle created!');
    }

    public function editCycle(TradingCycle $cycle)
    {
        return view('admin.investments.cycle-edit', compact('cycle'));
    }

    public function updateCycle(Request $request, TradingCycle $cycle)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:trading_cycles,name,' . $cycle->id,
            'slug' => 'required|string|unique:trading_cycles,slug,' . $cycle->id,
            'description' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
            'daily_profit_percent' => 'required|numeric|min:0|max:100',
            'total_return_percent' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $cycle->update($validated);

        return redirect()->route('admin.cycles')
            ->with('success', 'Trading cycle updated!');
    }

    public function deleteCycle(TradingCycle $cycle)
    {
        $cycle->delete();
        return back()->with('success', 'Trading cycle deleted!');
    }

    // Tranches Management
    public function tranches(TradingCycle $cycle)
    {
        $tranches = $cycle->tranches()->paginate(10);
        return view('admin.investments.tranches', compact('cycle', 'tranches'));
    }

    public function createTranche(TradingCycle $cycle)
    {
        return view('admin.investments.tranche-create', compact('cycle'));
    }

    public function storeTranche(Request $request, TradingCycle $cycle)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'description' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'display_order' => 'nullable|integer',
        ]);

        $cycle->tranches()->create($validated);

        return redirect()->route('admin.tranches', $cycle)
            ->with('success', 'Tranche created!');
    }

    public function editTranche(Tranche $tranche)
    {
        return view('admin.investments.tranche-edit', compact('tranche'));
    }

    public function updateTranche(Request $request, Tranche $tranche)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'description' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $tranche->update($validated);

        return redirect()->route('admin.tranches', $tranche->trading_cycle_id)
            ->with('success', 'Tranche updated!');
    }

    public function deleteTranche(Tranche $tranche)
    {
        $cycleId = $tranche->trading_cycle_id;
        $tranche->delete();
        return redirect()->route('admin.tranches', $cycleId)
            ->with('success', 'Tranche deleted!');
    }

    // Investments Monitoring
    public function investments()
    {
        $investments = Investment::with('user', 'tradingCycle', 'tranche')
            ->latest()
            ->paginate(20);

        return view('admin.investments.list', compact('investments'));
    }

    public function editInvestment(Investment $investment)
    {
        return view('admin.investments.edit', compact('investment'));
    }

    public function updateInvestment(Request $request, Investment $investment)
    {
        $validated = $request->validate([
            'total_profit_credited' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,active,completed,cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $investment->update($validated);

        return redirect()->route('admin.investments')
            ->with('success', 'Investment updated!');
    }
}
