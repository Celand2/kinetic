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
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $exchangeRates  = \App\Models\ExchangeRate::all()->keyBy('currency');
        return view('client.transactions.deposit', compact('paymentMethods', 'exchangeRates'));
    }

    public function storeDeposit(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => [
                'required',
                Rule::exists('payment_methods', 'name')->where('is_active', true),
            ],
            'screenshot' => 'required|image|max:4096',
            'description' => 'nullable|string|max:500',
        ]);

        // Récupérer la devise du moyen de paiement choisi
        $paymentMethod = \App\Models\PaymentMethod::where('name', $validated['payment_method'])
            ->where('is_active', true)->first();
        $localCurrency = $paymentMethod->currency ?? 'USD';

        // Montant saisi par le user (dans sa devise locale)
        $localAmount = (float) $validated['amount'];

        // Convertir en USD pour le stockage interne
        $usdAmount = \App\Models\ExchangeRate::toUSD($localAmount, $localCurrency);

        $metadata = [
            'payment_method'   => $validated['payment_method'],
            'local_amount'     => $localAmount,
            'local_currency'   => $localCurrency,
            'usd_amount'       => $usdAmount,
            'rate_used'        => \App\Models\ExchangeRate::rate($localCurrency),
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

        // Sauvegarder la devise préférée du user
        if ($localCurrency !== 'USD') {
            $user->update(['preferred_currency' => $localCurrency]);
        }

        // Notifier tous les super_admins
        $super_admins = User::where('role', 'super_admin')->get();
        foreach ($super_admins as $super_admin) {
            Notification::create([
                "user_id"      => $super_admin->id,
                "type"         => "system",
                "title"        => "Nouvelle preuve de depot",
                "body"         => $user->full_name . " a soumis une preuve de depot de $" . number_format($validated["amount"], 2) . " via " . $validated["payment_method"] . ".",
                "action_url"   => route("admin.finance.transactions"),
                "action_label" => "Voir les transactions",
                "created_by"   => $user->id,
            ]);
        }

        return redirect()->route("transactions.index")
            ->with("success", "Demande de depot enregistree. L’admin va la valider sous peu.");
    }

    public function createWithdrawal()
    {
        return view('client.transactions.withdraw');
    }

    public function storeWithdrawal(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:lumicash,bancobu_enoti',
            'wallet_details' => 'required|string|max:1000',
            'screenshot' => 'nullable|image|max:4096',
            'description' => 'nullable|string|max:500',
        ]);

        $fee = round($validated['amount'] * 0.10, 2);
        $totalAmount = $validated['amount'] + $fee;

        if ($totalAmount > $user->balance) {
            return back()->withErrors(['amount' => 'Solde insuffisant pour couvrir le montant et les frais.'])->withInput();
        }

        $metadata = [
            'payment_method' => $validated['payment_method'],
            'wallet_details' => $validated['wallet_details'],
            'fee' => $fee,
        ];

        if ($request->hasFile('screenshot')) {
            $metadata['screenshot'] = $request->file('screenshot')->store('client/withdrawals', 'public');
        }

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $validated['amount'],
            'direction' => 'debit',
            'balance_after' => $user->balance,
            'fee_amount' => $fee,
            'status' => 'pending',
            'reference' => 'TXN-' . date('Y') . '-' . Str::padLeft(Transaction::count() + 1, 6, '0'),
            'payment_method' => $validated['payment_method'],
            'description' => $validated['description'] ?? 'Withdrawal request submitted',
            'metadata' => $metadata,
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
