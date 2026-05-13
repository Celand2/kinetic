<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\TradingCycle;
use App\Models\Tranche;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvestmentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Liste des investissements de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        $investments = Investment::where('user_id', $user->id)
            ->with(['tradingCycle', 'tranche'])
            ->latest()
            ->paginate(10);

        return view('client.investments.index', compact('investments'));
    }

    /**
     * Étape 1 : Liste des cycles de trading actifs
     */
    public function create()
    {
        $cycles = TradingCycle::where('is_active', true)
            ->withCount(['tranches' => fn($q) => $q->where('is_active', true)])
            ->orderBy('display_order')
            ->get();

        return view('client.investments.create', compact('cycles'));
    }

    /**
     * Étape 2 : Affichage des tranches d'un cycle spécifique
     */
    public function showTranches(TradingCycle $cycle)
    {
        abort_if(!$cycle->is_active, 404);

        $tranches = $cycle->tranches()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();

        $user         = Auth::user();
        $userCurrency = $user->preferred_currency ?? 'USD';
        $currencyRate = \App\Models\ExchangeRate::rate($userCurrency);

        return view('client.investments.tranches', compact('cycle', 'tranches', 'userCurrency', 'currencyRate'));
    }

    /**
     * Étape 3 : Formulaire d'investissement pour une tranche
     */
    public function invest(TradingCycle $cycle, Tranche $tranche)
    {
        abort_if(!$cycle->is_active || !$tranche->is_active, 404);
        abort_if($tranche->trading_cycle_id !== $cycle->id, 404);

        $user         = Auth::user();
        $userCurrency = $user->preferred_currency ?? 'USD';
        $currencyRate = \App\Models\ExchangeRate::rate($userCurrency);

        // Contrats actifs ou en cours du même user dans cette tranche
        $activeInThisTranche = Investment::where('user_id', $user->id)
            ->where('tranche_id', $tranche->id)
            ->whereIn('status', ['active', 'pending'])
            ->orderByDesc('created_at')
            ->get();

        return view('client.investments.invest', compact(
            'cycle', 'tranche', 'user',
            'userCurrency', 'currencyRate',
            'activeInThisTranche'
        ));
    }

    /**
     * Traitement de l'investissement (Wallet ou via paiement externe en attente)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tranche_id' => 'required|exists:tranches,id',
            'amount'     => 'required|numeric|min:0.01',
        ]);

        $tranche      = Tranche::findOrFail($validated['tranche_id']);
        $cycle        = $tranche->tradingCycle;
        $user         = Auth::user();
        $userCurrency = $user->preferred_currency ?? 'USD';
        $currencyRate = \App\Models\ExchangeRate::rate($userCurrency);

        // L'utilisateur saisit en devise locale → convertir en USD pour stockage
        $localAmount = (float) $validated['amount'];
        $usdAmount   = \App\Models\ExchangeRate::toUSD($localAmount, $userCurrency);

        // Helper de formatage pour les messages d'erreur
        $fmt = function (float $usd) use ($userCurrency, $currencyRate): string {
            if ($userCurrency === 'USD') return '$' . number_format($usd, 2);
            return number_format(round($usd * $currencyRate), 0, ',', ' ') . ' ' . $userCurrency;
        };

        // Toujours wallet maintenant
        $paymentMethod = 'wallet';

        // 1. Vérifications des montants min/max (comparaison en USD)
        if ($usdAmount < $tranche->min_amount) {
            return back()->withErrors([
                'amount' => 'Le montant minimum pour cette tranche est ' . $fmt($tranche->min_amount),
            ])->withInput();
        }

        if ($tranche->max_amount && $usdAmount > $tranche->max_amount) {
            return back()->withErrors([
                'amount' => 'Le montant maximum pour cette tranche est ' . $fmt($tranche->max_amount),
            ])->withInput();
        }

        // 2. Vérifier solde suffisant (en USD)
        if ($paymentMethod === 'wallet') {
            if ($user->balance < $usdAmount) {
                return back()->withErrors([
                    'amount' => 'Solde insuffisant. Votre solde est de ' . $fmt($user->balance),
                ])->withInput();
            }
        }

        // 3. Génération de la référence unique
        $reference = 'INV-' . date('Y') . '-' . Str::padLeft(Investment::withTrashed()->count() + 1, 6, '0');

        // 4. Déterminer le statut initial
        $investmentStatus = ($paymentMethod === 'wallet') ? 'active' : 'pending';

        // 5. Création de l'investissement
        $investment = Investment::create([
            'user_id'           => $user->id,
            'trading_cycle_id'  => $cycle->id,
            'tranche_id'        => $tranche->id,
            'amount'            => $usdAmount,
            'daily_profit_rate' => $cycle->daily_profit_percent,
            'total_return_rate' => $cycle->total_return_percent,
            'duration_days'     => $cycle->duration_days,
            'started_at'        => $investmentStatus === 'active' ? now() : null,
            'ends_at'           => $investmentStatus === 'active' ? now()->addDays($cycle->duration_days) : null,
            'status'            => $investmentStatus,
            'reference'         => $reference,
        ]);

        // 6. Si wallet : créer la transaction et déduire immédiatement
        if ($paymentMethod === 'wallet') {
            Transaction::create([
                'user_id'        => $user->id,
                'investment_id'  => $investment->id,
                'type'           => 'investment_buy',
                'amount'         => $usdAmount,
                'direction'      => 'debit',
                'balance_after'  => $user->balance - $usdAmount,
                'status'         => 'completed',
                'reference'      => $reference,
                'payment_method' => 'wallet',
                'processed_at'   => now(),
                'description'    => 'Investissement – ' . $cycle->name . ' / ' . $tranche->name,
            ]);

            $user->decrement('balance', $usdAmount);

            $message = 'Félicitations ! Votre investissement a été activé avec succès.';
        } else {
            // Si paiement externe : transaction en attente
            Transaction::create([
                'user_id'        => $user->id,
                'investment_id'  => $investment->id,
                'type'           => 'investment_buy',
                'amount'         => $usdAmount,
                'direction'      => 'debit',
                'balance_after'  => $user->balance,
                'status'         => 'pending',
                'reference'      => $reference,
                'payment_method' => $paymentMethod,
                'description'    => 'Investissement via ' . ucfirst($paymentMethod) . ' – ' . $cycle->name . ' / ' . $tranche->name,
            ]);

            $message = 'Votre investissement a été enregistré en attente de confirmation. Référence : ' . $reference;
        }

        return redirect()
            ->route('investments.show', $investment)
            ->with('success', $message);
    }

    /**
     * Détails d'un investissement spécifique
     */
    public function show(Investment $investment)
    {
        $this->authorize('view', $investment);

        $investment->load(['tradingCycle', 'tranche', 'transactions']);

        $user         = Auth::user();
        $userCurrency = $user->preferred_currency ?? 'USD';
        $currencyRate = \App\Models\ExchangeRate::rate($userCurrency);

        return view('client.investments.show', compact('investment', 'userCurrency', 'currencyRate'));
    }
}