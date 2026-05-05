<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExchangeRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exchangeRates = ExchangeRate::orderByDesc('updated_at')->get();
        return view('admin.exchange-rates.index', compact('exchangeRates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.exchange-rates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency' => 'required|string|max:10',
            'rate_to_usd' => 'required|numeric|min:0',
        ]);

        $todayExisting = ExchangeRate::where('currency', $validated['currency'])
            ->whereDate('updated_at', now())
            ->first();

        if ($todayExisting) {
            $todayExisting->update([
                'rate_to_usd' => $validated['rate_to_usd'],
                'updated_at' => now(),
            ]);
        } else {
            ExchangeRate::create([
                'id' => Str::uuid(),
                'currency' => $validated['currency'],
                'rate_to_usd' => $validated['rate_to_usd'],
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.exchange-rates.index')->with('success', 'Exchange rate saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $exchangeRate = ExchangeRate::findOrFail($id);
        return view('admin.exchange-rates.edit', compact('exchangeRate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exchangeRate = ExchangeRate::findOrFail($id);

        $validated = $request->validate([
            'currency' => 'required|string|max:10',
            'rate_to_usd' => 'required|numeric|min:0',
        ]);

        $exchangeRate->update([
            'currency' => $validated['currency'],
            'rate_to_usd' => $validated['rate_to_usd'],
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.exchange-rates.index')->with('success', 'Exchange rate updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exchangeRate = ExchangeRate::findOrFail($id);
        $exchangeRate->delete();

        return redirect()->route('admin.exchange-rates.index')->with('success', 'Exchange rate deleted.');
    }
}
