<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function transactions(Request $request)
    {
        $query = Transaction::with('user')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.finance.transactions', compact('transactions'));
    }

    public function approveTransaction(Transaction $transaction)
    {
        if ($transaction->type === 'deposit' && $transaction->status === 'pending') {
            $transaction->update([
                'status'       => 'completed',
                'processed_at' => now(),
                'processed_by' => Auth::id(),
            ]);

            $user = $transaction->user;
            $user->increment('balance', $transaction->amount);

            Notification::create([
                'user_id'      => $user->id,
                'type'         => 'deposit_approved',
                'title'        => 'Dépôt approuvé',
                'body'         => 'Votre dépôt de $' . number_format($transaction->amount, 2) . ' a été approuvé et crédité sur votre compte.',
                'action_url'   => route('transactions.index'),
                'action_label' => 'Voir mes transactions',
                'created_by'   => Auth::id(),
            ]);

            return back()->with('success', 'Dépôt approuvé et crédit ajouté !');
        }

        if ($transaction->type === 'withdrawal' && $transaction->status === 'pending') {
            $transaction->update([
                'status'       => 'completed',
                'processed_at' => now(),
                'processed_by' => Auth::id(),
            ]);

            $user = $transaction->user;
            $user->decrement('balance', $transaction->amount + $transaction->fee_amount);

            Notification::create([
                'user_id'      => $user->id,
                'type'         => 'withdrawal_approved',
                'title'        => 'Retrait approuvé',
                'body'         => 'Votre demande de retrait de $' . number_format($transaction->amount, 2) . ' a été approuvée.',
                'action_url'   => route('transactions.index'),
                'action_label' => 'Voir mes transactions',
                'created_by'   => Auth::id(),
            ]);

            return back()->with('success', 'Retrait approuvé !');
        }

        return back()->withErrors(['error' => 'Impossible d\'approuver cette transaction.']);
    }

    public function rejectTransaction(Request $request, Transaction $transaction)
    {
        $validated = $request->validate(['reason' => 'required|string|max:500']);

        if ($transaction->status === 'pending') {
            $transaction->update([
                'status'       => 'rejected',
                'admin_notes'  => $validated['reason'],
                'processed_at' => now(),
                'processed_by' => Auth::id(),
            ]);

            $typeLabel = $transaction->type === 'deposit' ? 'dépôt' : 'retrait';
            $notifType = $transaction->type === 'deposit' ? 'deposit_rejected' : 'withdrawal_rejected';

            Notification::create([
                'user_id'      => $transaction->user_id,
                'type'         => $notifType,
                'title'        => ucfirst($typeLabel) . ' refusé',
                'body'         => 'Votre demande de ' . $typeLabel . ' de $' . number_format($transaction->amount, 2) . ' a été refusée. Motif : ' . $validated['reason'],
                'action_url'   => route('transactions.index'),
                'action_label' => 'Voir mes transactions',
                'created_by'   => Auth::id(),
            ]);

            return back()->with('success', 'Transaction refusée.');
        }

        return back()->withErrors(['error' => 'Impossible de refuser cette transaction.']);
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
