<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\Notification;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreditDailyProfits extends Command
{
    protected $signature   = 'profits:credit';
    protected $description = 'Crédite les profits journaliers sur tous les investissements actifs';

    public function handle(): int
    {
        $investments = Investment::with('user', 'tradingCycle')
            ->where('status', 'active')
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNull('last_profit_credited_at')
                       ->where('started_at', '<=', now()->subHours(24));
                })->orWhere('last_profit_credited_at', '<=', now()->subHours(24));
            })
            ->get();

        $count = 0;

        foreach ($investments as $investment) {
            $user = $investment->user;

            if (!$user || $user->status !== 'active') {
                continue;
            }

            if ($investment->days_credited >= $investment->duration_days) {
                $investment->update(['status' => 'completed']);
                continue;
            }

            $profit = round((float) $investment->amount * (float) $investment->daily_profit_rate / 100, 2);

            if ($profit <= 0) {
                continue;
            }

            $user->increment('balance', $profit);
            $user->increment('profit_balance', $profit);
            $user->refresh();

            Transaction::create([
                'user_id'       => $user->id,
                'type'          => 'daily_profit',
                'amount'        => $profit,
                'direction'     => 'credit',
                'balance_after' => (float) $user->balance,
                'status'        => 'completed',
                'reference'     => 'PRF-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'investment_id' => $investment->id,
                'description'   => 'Profit journalier — ' . ($investment->tradingCycle->name ?? ''),
                'processed_at'  => now(),
            ]);

            $newDays  = $investment->days_credited + 1;
            $newTotal = round((float) $investment->total_profit_credited + $profit, 2);
            $done     = $newDays >= $investment->duration_days;

            $investment->update([
                'days_credited'           => $newDays,
                'total_profit_credited'   => $newTotal,
                'last_profit_credited_at' => now(),
                'status'                  => $done ? 'completed' : 'active',
            ]);

            Notification::create([
                'user_id'      => $user->id,
                'type'         => $done ? 'investment_complete' : 'profit_credited',
                'title'        => $done ? 'Investissement terminé' : 'Profit journalier crédité',
                'body'         => $done
                    ? 'Votre investissement de $' . number_format($investment->amount, 2) . ' est arrivé à terme. Profit total : $' . number_format($newTotal, 2) . '.'
                    : 'Profit de $' . number_format($profit, 2) . ' crédité (Jour ' . $newDays . '/' . $investment->duration_days . ').',
                'action_url'   => route('transactions.index'),
                'action_label' => 'Voir mes transactions',
            ]);

            $count++;
        }

        $this->info("$count profit(s) journalier(s) crédité(s).");

        return Command::SUCCESS;
    }
}
