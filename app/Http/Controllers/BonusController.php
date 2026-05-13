<?php

namespace App\Http\Controllers;

use App\Models\BonusCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BonusController extends Controller
{
    /**
     * Show the bonus redemption form
     */
    public function show()
    {
        return view('client.redeem-bonus');
    }

    /**
     * Redeem a bonus code
     */
    public function redeem()
    {
        $user = Auth::user();

        // Validate input
        $validated = request()->validate([
            'code' => 'required|string|max:100',
        ], [
            'code.required' => 'Le code bonus est requis.',
            'code.max' => 'Le code bonus ne peut pas dépasser 100 caractères.',
        ]);

        // Find the bonus code (case-insensitive)
        $bonusCode = BonusCode::whereRaw('LOWER(code) = ?', [strtolower($validated['code'])])->first();

        // Check if code exists
        if (!$bonusCode) {
            throw ValidationException::withMessages([
                'code' => 'Code bonus invalide ou introuvable.',
            ]);
        }

        // Check if already used
        if ($bonusCode->isUsed()) {
            throw ValidationException::withMessages([
                'code' => 'Ce code a déjà été utilisé.',
            ]);
        }

        // Check if expired
        if ($bonusCode->expires_at && $bonusCode->expires_at->isPast()) {
            $bonusCode->update(['status' => 'expired']);
            throw ValidationException::withMessages([
                'code' => 'Ce code a expiré.',
            ]);
        }

        // Check if still active
        if ($bonusCode->status !== 'active') {
            throw ValidationException::withMessages([
                'code' => 'Ce code n\'est plus valide.',
            ]);
        }

        // Mark code as used and add bonus to user's balance
        $bonusCode->update([
            'status' => 'used',
            'used_by' => $user->id,
            'used_at' => now(),
        ]);

        // Le bonus est un gain : ajoute au solde total ET aux gains retirables
        $user->increment('balance', $bonusCode->bonus_amount);
        $user->increment('profit_balance', $bonusCode->bonus_amount);

        return redirect()->route('dashboard')
            ->with('success', "Code bonus appliqué ! +\${$bonusCode->bonus_amount} ajouté à vos gains retirables.");
    }
}
