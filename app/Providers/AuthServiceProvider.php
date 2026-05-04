<?php

namespace App\Providers;

use App\Models\Investment;
use App\Models\Message;
use App\Models\Transaction;
use App\Policies\InvestmentPolicy;
use App\Policies\MessagePolicy;
use App\Policies\TransactionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Investment::class => InvestmentPolicy::class,
        Message::class => MessagePolicy::class,
        Transaction::class => TransactionPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates if needed
        Gate::define('admin', function ($user) {
            return in_array($user->role, ['super_admin', 'admin']);
        });
    }
}
