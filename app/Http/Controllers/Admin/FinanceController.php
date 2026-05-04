<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function transactions()
    {
        $transactions = Transaction::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.finance.transactions', compact('transactions'));
    }

    public function approveTransaction(Transaction $transaction)
    {
        if ($transaction->type === 'deposit' && $transaction->status === 'pending') {
            $transaction->update(['status' => 'completed', 'processed_at' => now()]);
            
            $user = $transaction->user;
            $user->increment('balance', $transaction->amount);

            return back()->with('success', 'Deposit approved!');
        }

        return back()->withErrors(['error' => 'Cannot approve this transaction']);
    }

    public function rejectTransaction(Request $request, Transaction $transaction)
    {
        $validated = $request->validate(['reason' => 'required|string']);

        if ($transaction->status === 'pending') {
            $transaction->update([
                'status' => 'rejected',
                'admin_notes' => $validated['reason'],
                'processed_at' => now()
            ]);

            return back()->with('success', 'Transaction rejected!');
        }

        return back()->withErrors(['error' => 'Cannot reject this transaction']);
    }

    public function manualAdjustment(Request $request, User $user)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:add,subtract',
            'reason' => 'required|string|max:500',
        ]);

        if ($validated['type'] === 'add') {
            $user->increment('balance', $validated['amount']);
        } else {
            $user->decrement('balance', $validated['amount']);
        }

        // Log transaction
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'adjustment',
            'amount' => $validated['amount'],
            'status' => 'completed',
            'admin_notes' => $validated['reason'],
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Balance adjusted successfully!');
    }
}
