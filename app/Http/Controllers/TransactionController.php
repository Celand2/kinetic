<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('client.transactions.index', compact('transactions'));
    }

    public function createDeposit()
    {
        $paymentMethods     = PaymentMethod::where('is_active', true)->get();
        $exchangeRates      = \App\Models\ExchangeRate::all()->keyBy('currency');
        $firstDepositMethod = Transaction::where('user_id', Auth::id())
            ->where('type', 'deposit')
            ->oldest('created_at')
            ->value('payment_method');
        return view('client.transactions.deposit', compact('paymentMethods', 'exchangeRates', 'firstDepositMethod'));
    }

    public function storeDeposit(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'amount'         => 'required|numeric|min:1',
            'payment_method' => [
                'required',
                Rule::exists('payment_methods', 'name')->where('is_active', true),
            ],
            'screenshot'  => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'description' => 'nullable|string|max:500',
        ]);

        $firstMethod = Transaction::where('user_id', $user->id)
            ->where('type', 'deposit')->oldest('created_at')->value('payment_method');
        if ($firstMethod && $validated['payment_method'] !== $firstMethod) {
            return back()->withErrors([
                'payment_method' => 'Vous devez utiliser votre méthode initiale : ' . $firstMethod,
            ])->withInput();
        }

        $paymentMethod = \App\Models\PaymentMethod::where('name', $validated['payment_method'])
            ->where('is_active', true)->first();
        $localCurrency = $paymentMethod->currency ?? 'USD';
        $localAmount   = (float) $validated['amount'];
        $usdAmount     = \App\Models\ExchangeRate::toUSD($localAmount, $localCurrency);

        $metadata = [
            'payment_method' => $validated['payment_method'],
            'local_amount'   => $localAmount,
            'local_currency' => $localCurrency,
            'usd_amount'     => $usdAmount,
            'rate_used'      => \App\Models\ExchangeRate::rate($localCurrency),
        ];

        if ($request->hasFile('screenshot')) {
            $metadata['screenshot'] = $request->file('screenshot')->store('client/deposits', 'public');
        }

        Transaction::create([
            'user_id'        => $user->id,
            'type'           => 'deposit',
            'amount'         => $usdAmount,
            'direction'      => 'credit',
            'balance_after'  => $user->balance,
            'status'         => 'pending',
            'reference'      => 'TXN-' . date('Y') . '-' . Str::padLeft(Transaction::count() + 1, 6, '0'),
            'payment_method' => $validated['payment_method'],
            'description'    => $validated['description'] ?? 'Depot soumis',
            'metadata'       => $metadata,
        ]);

        if ($localCurrency !== 'USD') {
            $user->update(['preferred_currency' => $localCurrency]);
        }

        $super_admins = User::where('role', 'super_admin')->get();
        foreach ($super_admins as $super_admin) {
            Notification::create([
                'user_id'      => $super_admin->id,
                'type'         => 'system',
                'title'        => 'Nouvelle preuve de depot',
                'body'         => $user->full_name . ' a soumis une preuve de depot de $' . number_format($validated['amount'], 2) . ' via ' . $validated['payment_method'] . '.',
                'action_url'   => route('admin.finance.transactions'),
                'action_label' => 'Voir les transactions',
                'created_by'   => $user->id,
            ]);
        }

        return redirect()->route('transactions.index')
            ->with('success', "Demande de depot enregistree. L'admin va la valider sous peu.");
    }

    public function createWithdrawal()
    {
        $paymentMethods     = PaymentMethod::where('is_active', true)->get();
        $exchangeRates      = \App\Models\ExchangeRate::all()->keyBy('currency');
        $canWithdraw        = Transaction::where('user_id', Auth::id())
            ->where('type', 'daily_profit')
            ->where('status', 'completed')
            ->exists();
        $firstDepositMethod = Transaction::where('user_id', Auth::id())
            ->where('type', 'deposit')
            ->oldest('created_at')
            ->value('payment_method');
        return view('client.transactions.withdraw', compact('paymentMethods', 'exchangeRates', 'canWithdraw', 'firstDepositMethod'));
    }

    public function storeWithdrawal(Request $request)
    {
        $user = Auth::user();

        $hasFirstProfit = Transaction::where('user_id', $user->id)
            ->where('type', 'daily_profit')
            ->where('status', 'completed')
            ->exists();

        if (!$hasFirstProfit) {
            return back()->withErrors([
                'amount' => 'Retrait non disponible : votre premier profit journalier doit être crédité avant tout retrait (minimum 24h après votre premier investissement actif).',
            ])->withInput();
        }

        $hasPendingWithdrawal = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingWithdrawal) {
            return back()->withErrors([
                'amount' => "Vous avez déjà un retrait en attente. Veuillez attendre son approbation avant d'en soumettre un nouveau.",
            ])->withInput();
        }

        $validated = $request->validate([
            'amount'         => 'required|numeric|min:1',
            'payment_method' => [
                'required',
                Rule::exists('payment_methods', 'name')->where('is_active', true),
            ],
            'wallet_details' => 'required|string|max:1000',
            'screenshot'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'description'    => 'nullable|string|max:500',
        ]);

        $firstMethod = Transaction::where('user_id', $user->id)
            ->where('type', 'deposit')->oldest('created_at')->value('payment_method');
        if ($firstMethod && $validated['payment_method'] !== $firstMethod) {
            return back()->withErrors([
                'payment_method' => 'Vous devez utiliser votre méthode initiale : ' . $firstMethod,
            ])->withInput();
        }

        $paymentMethod = PaymentMethod::where('name', $validated['payment_method'])
            ->where('is_active', true)->first();
        $localCurrency = $paymentMethod->currency ?? 'USD';
        $localAmount   = (float) $validated['amount'];
        $usdAmount     = \App\Models\ExchangeRate::toUSD($localAmount, $localCurrency);

        // Minimum de retrait : $0.25
        if ($usdAmount < 0.25) {
            return back()->withErrors([
                'amount' => 'Le montant minimum de retrait est de $0.25.',
            ])->withInput();
        }

        // Frais 3% — déduits du montant envoyé, pas du wallet
        $fee      = round($usdAmount * 0.03, 2);
        $received = round($usdAmount - $fee, 2);

        // Vérification solde : on compare usdAmount (ce qui est retiré du wallet)
        if ($usdAmount > $user->profit_balance) {
            $userCurrency = $user->preferred_currency ?? 'USD';
            $rate         = \App\Models\ExchangeRate::rate($userCurrency);
            $availableFmt = $userCurrency === 'USD'
                ? '$' . number_format($user->profit_balance, 2)
                : number_format(round((float)$user->profit_balance * $rate), 0, ',', ' ') . ' ' . $userCurrency;
            return back()->withErrors([
                'amount' => 'Gains insuffisants. Gains disponibles : ' . $availableFmt . '.',
            ])->withInput();
        }

        $rateUsed = \App\Models\ExchangeRate::rate($localCurrency);

        $metadata = [
            'payment_method'  => $validated['payment_method'],
            'local_amount'    => $localAmount,    // montant saisi par le user
            'local_currency'  => $localCurrency,
            'usd_amount'      => $usdAmount,      // montant retiré du wallet
            'rate_used'       => $rateUsed,
            'wallet_details'  => $validated['wallet_details'],
            'fee'             => $fee,            // frais 3%
            'received'        => $received,       // montant envoyé au user
        ];

        if ($request->hasFile('screenshot')) {
            $metadata['screenshot'] = $request->file('screenshot')->store('client/withdrawals', 'public');
        }

        Transaction::create([
            'user_id'        => $user->id,
            'type'           => 'withdrawal',
            'amount'         => $usdAmount,   // $100 → débité du wallet
            'fee_amount'     => $fee,         // $3  → frais de transfert
            'direction'      => 'debit',
            'balance_after'  => $user->balance,
            'status'         => 'pending',
            'reference'      => 'TXN-' . date('Y') . '-' . Str::padLeft(Transaction::count() + 1, 6, '0'),
            'payment_method' => $validated['payment_method'],
            'description'    => $validated['description'] ?? 'Demande de retrait soumise',
            'metadata'       => $metadata,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Demande de retrait enregistrée. Un admin va la valider rapidement.');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        return view('client.transactions.show', compact('transaction'));
    }
}