<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
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
        return view('client.transactions.deposit', compact('paymentMethods'));
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

        $metadata = [
            'payment_method' => $validated['payment_method'],
        ];

        if ($request->hasFile('screenshot')) {
            $metadata['screenshot'] = $request->file('screenshot')->store('client/deposits', 'public');
        }

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $validated['amount'],
            'direction' => 'credit',
            'balance_after' => $user->balance,
            'status' => 'pending',
            'reference' => 'TXN-' . date('Y') . '-' . Str::padLeft(Transaction::count() + 1, 6, '0'),
            'payment_method' => $validated['payment_method'],
            'description' => $validated['description'] ?? 'Deposit request submitted',
            'metadata' => $metadata,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Demande de dépôt enregistrée. L’admin va la valider sous peu.');
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
