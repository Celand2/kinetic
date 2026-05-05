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

        return view('client.investments.tranches', compact('cycle', 'tranches'));
    }

    /**
     * Étape 3 : Formulaire d'investissement pour une tranche
     */
    public function invest(TradingCycle $cycle, Tranche $tranche)
    {
        abort_if(!$cycle->is_active || !$tranche->is_active, 404);
        abort_if($tranche->trading_cycle_id !== $cycle->id, 404);

        $user = Auth::user();

        // On passe l'utilisateur pour afficher son solde actuel sur la vue
        return view('client.investments.invest', compact('cycle', 'tranche', 'user'));
    }

    /**
     * Traitement de l'investissement (Wallet ou via paiement externe en attente)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tranche_id'      => 'required|exists:tranches,id',
            'amount'          => 'required|numeric|min:0.01',
            'payment_method'  => 'nullable|in:wallet,lumicash,bancobu_enoti',
        ]);

        $tranche = Tranche::findOrFail($validated['tranche_id']);
        $cycle   = $tranche->tradingCycle;
        $user    = Auth::user();

        // Par défaut wallet
        $paymentMethod = $validated['payment_method'] ?? 'wallet';

        // 1. Vérifications des montants min/max de la tranche
        if ($validated['amount'] < $tranche->min_amount) {
            return back()->withErrors([
                'amount' => 'Le montant minimum est $' . number_format($tranche->min_amount, 2)
            ])->withInput();
        }

        if ($tranche->max_amount && $validated['amount'] > $tranche->max_amount) {
            return back()->withErrors([
                'amount' => 'Le montant maximum est $' . number_format($tranche->max_amount, 2)
            ])->withInput();
        }

        // 2. Si paiement via wallet : vérifier solde suffisant
        if ($paymentMethod === 'wallet') {
            if ($user->balance < $validated['amount']) {
                return back()->withErrors([
                    'amount' => 'Solde insuffisant. Votre solde actuel est de $' . number_format($user->balance, 2)
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
            'amount'            => $validated['amount'],
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
                'amount'         => $validated['amount'],
                'direction'      => 'debit',
                'balance_after'  => $user->balance - $validated['amount'],
                'status'         => 'completed',
                'reference'      => $reference,
                'payment_method' => 'wallet',
                'processed_at'   => now(),
                'description'    => 'Investissement – ' . $cycle->name . ' / ' . $tranche->name,
            ]);

            $user->decrement('balance', $validated['amount']);

            $message = 'Félicitations ! Votre investissement a été activé avec succès.';
        } else {
            // Si paiement externe : transaction en attente
            Transaction::create([
                'user_id'        => $user->id,
                'investment_id'  => $investment->id,
                'type'           => 'investment_buy',
                'amount'         => $validated['amount'],
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
        // Vérifie que l'utilisateur est bien le propriétaire
        $this->authorize('view', $investment);

        $investment->load(['tradingCycle', 'tranche', 'transactions']);

        return view('client.investments.show', compact('investment'));
    }
}