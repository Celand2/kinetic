<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ReferralCommission;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

            $this->creditReferralCommissions($transaction, $user);

            return back()->with('success', 'Dépôt approuvé et crédit ajouté !');
        }

        if ($transaction->type === 'withdrawal' && $transaction->status === 'pending') {
            $transaction->update([
                'status'       => 'completed',
                'processed_at' => now(),
                'processed_by' => Auth::id(),
            ]);

            $user = $transaction->user;
            $totalDeducted = $transaction->amount + $transaction->fee_amount;
            $user->decrement('balance', $totalDeducted);
            // Déduire aussi des gains retirables (ne peut pas dépasser le solde actuel)
            $user->decrement('profit_balance', min($totalDeducted, (float) $user->profit_balance));

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
            'amount' => 'required|numeric|min:0.01',
            'type'   => 'required|in:add,subtract',
            'reason' => 'required|string|max:500',
        ]);

        $amount    = (float) $validated['amount'];
        $direction = $validated['type'] === 'add' ? 'credit' : 'debit';

        if ($validated['type'] === 'add') {
            $user->increment('balance', $amount);
        } else {
            $user->decrement('balance', $amount);
        }

        Transaction::create([
            'user_id'       => $user->id,
            'type'          => 'admin_adjustment',
            'amount'        => $amount,
            'direction'     => $direction,
            'balance_after' => (float) $user->fresh()->balance,
            'status'        => 'completed',
            'reference'     => 'ADJ-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'admin_notes'   => $validated['reason'],
            'processed_by'  => Auth::id(),
            'processed_at'  => now(),
        ]);

        return back()->with('success', 'Solde ajusté avec succès !');
    }

    public function withdrawals(Request $request)
    {
        $query = Transaction::with('user')
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->latest();

        $withdrawals = $query->get();

        return view('admin.finance.withdrawals', compact('withdrawals'));
    }

    public function downloadWithdrawals()
    {
        $withdrawals = Transaction::with('user')
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $lines   = [];
        $lines[] = implode(';', ['Référence', 'Date', 'Nom', 'Téléphone', 'Méthode', 'Détails adresse', 'Montant ($)', 'Frais ($)', 'Total ($)']);

        foreach ($withdrawals as $txn) {
            $meta    = $txn->metadata ?? [];
            $details = $meta['wallet_details'] ?? '—';
            $details = str_replace(["\r\n", "\n", "\r", ';'], [' ', ' ', ' ', ' '], $details);

            $lines[] = implode(';', [
                $txn->reference,
                $txn->created_at->format('d/m/Y H:i'),
                $txn->user->full_name  ?? '—',
                $txn->user->phone      ?? '—',
                $txn->payment_method   ?? '—',
                $details,
                number_format($txn->amount, 2, '.', ''),
                number_format($txn->fee_amount, 2, '.', ''),
                number_format($txn->amount + $txn->fee_amount, 2, '.', ''),
            ]);
        }

        $csv      = implode("\n", $lines);
        $filename = 'retraits-en-attente-' . date('Y-m-d') . '.csv';

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function creditReferralCommissions(Transaction $depositTransaction, User $depositor): void
    {
        $rates   = [1 => 10, 2 => 3, 3 => 1];
        $current = $depositor;

        for ($level = 1; $level <= 3; $level++) {
            $referrer = $current->referrer;

            if (!$referrer) {
                break;
            }

            $rate       = $rates[$level];
            $commission = round((float) $depositTransaction->amount * $rate / 100, 2);

            if ($commission <= 0) {
                $current = $referrer;
                continue;
            }

            $referrer->increment('balance', $commission);
            $referrer->increment('referral_balance', $commission);
            $referrer->increment('profit_balance', $commission);

            $creditTransaction = Transaction::create([
                'user_id'       => $referrer->id,
                'type'          => 'referral_bonus',
                'amount'        => $commission,
                'direction'     => 'credit',
                'balance_after' => (float) $referrer->fresh()->balance,
                'status'        => 'completed',
                'reference'     => 'REF-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'description'   => 'Commission parrainage niveau ' . $level . ' — dépôt de ' . $depositor->full_name,
                'processed_at'  => now(),
                'processed_by'  => Auth::id(),
            ]);

            ReferralCommission::create([
                'referrer_id'           => $referrer->id,
                'source_user_id'        => $depositor->id,
                'level'                 => $level,
                'source_amount'         => $depositTransaction->amount,
                'rate_percent'          => $rate,
                'commission_amount'     => $commission,
                'source_transaction_id' => $depositTransaction->id,
                'credit_transaction_id' => $creditTransaction->id,
                'status'                => 'paid',
                'paid_at'               => now(),
            ]);

            Notification::create([
                'user_id'      => $referrer->id,
                'type'         => 'referral_bonus',
                'title'        => 'Commission parrainage reçue',
                'body'         => 'Vous avez reçu $' . number_format($commission, 2) . ' de commission (niveau ' . $level . ') suite au dépôt de ' . $depositor->full_name . '.',
                'action_url'   => route('referral.dashboard'),
                'action_label' => 'Voir mes parrainages',
                'created_by'   => Auth::id(),
            ]);

            $current = $referrer;
        }
    }
}
