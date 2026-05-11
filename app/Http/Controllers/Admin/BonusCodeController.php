<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BonusCodeController extends Controller
{
    /**
     * Display a listing of bonus codes
     */
    public function index(Request $request)
    {
        $query = BonusCode::query();

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Search by code
        if ($search = $request->input('search')) {
            $query->where('code', 'like', "%{$search}%");
        }

        $bonusCodes = $query->with('usedByUser')->latest()->paginate(20);

        return view('admin.bonus-codes.index', compact('bonusCodes'));
    }

    /**
     * Show the form for creating a new bonus code
     */
    public function create()
    {
        return view('admin.bonus-codes.create');
    }

    /**
     * Store a newly created bonus code
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:bonus_codes,code|max:100',
            'bonus_amount' => 'required|numeric|min:0.01',
            'expires_at' => 'nullable|date|after:today',
            'description' => 'nullable|string|max:500',
        ], [
            'code.required' => 'Le code est requis.',
            'code.unique' => 'Ce code existe déjà.',
            'bonus_amount.required' => 'Le montant est requis.',
            'bonus_amount.min' => 'Le montant doit être supérieur à 0.',
            'expires_at.after' => 'La date d\'expiration doit être future.',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['status'] = 'active';

        BonusCode::create($validated);

        return redirect()->route('admin.bonus-codes.index')
            ->with('success', 'Code bonus créé avec succès !');
    }

    /**
     * Show bonus code details
     */
    public function show(BonusCode $bonusCode)
    {
        $bonusCode->load('usedByUser');
        return view('admin.bonus-codes.show', compact('bonusCode'));
    }

    /**
     * Show the form for editing a bonus code
     */
    public function edit(BonusCode $bonusCode)
    {
        return view('admin.bonus-codes.edit', compact('bonusCode'));
    }

    /**
     * Update the bonus code
     */
    public function update(Request $request, BonusCode $bonusCode)
    {
        // Don't allow editing if already used
        if ($bonusCode->isUsed()) {
            return redirect()->route('admin.bonus-codes.show', $bonusCode)
                ->with('error', 'Impossible de modifier un code déjà utilisé.');
        }

        $validated = $request->validate([
            'bonus_amount' => 'required|numeric|min:0.01',
            'expires_at' => 'nullable|date|after:today',
            'description' => 'nullable|string|max:500',
        ]);

        $bonusCode->update($validated);

        return redirect()->route('admin.bonus-codes.show', $bonusCode)
            ->with('success', 'Code bonus mis à jour avec succès !');
    }

    /**
     * Delete bonus code
     */
    public function destroy(BonusCode $bonusCode)
    {
        // Don't allow deleting if already used
        if ($bonusCode->isUsed()) {
            return redirect()->route('admin.bonus-codes.index')
                ->with('error', 'Impossible de supprimer un code déjà utilisé.');
        }

        $bonusCode->delete();

        return redirect()->route('admin.bonus-codes.index')
            ->with('success', 'Code bonus supprimé avec succès !');
    }

    /**
     * Generate multiple random bonus codes
     */
    public function generateBatch(Request $request)
    {
        if ($request->method() === 'GET') {
            return view('admin.bonus-codes.generate-batch');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
            'bonus_amount' => 'required|numeric|min:0.01',
            'expires_at' => 'nullable|date|after:today',
            'prefix' => 'nullable|string|max:20',
        ]);

        $prefix = $validated['prefix'] ?? 'BONUS';
        $codes = [];

        for ($i = 0; $i < $validated['quantity']; $i++) {
            do {
                $code = strtoupper($prefix . '-' . Str::random(10));
            } while (BonusCode::where('code', $code)->exists());

            $codes[] = [
                'code' => $code,
                'bonus_amount' => $validated['bonus_amount'],
                'status' => 'active',
                'expires_at' => $validated['expires_at'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        BonusCode::insert($codes);

        return redirect()->route('admin.bonus-codes.index')
            ->with('success', "✓ {$validated['quantity']} codes bonus générés avec succès !");
    }
}
