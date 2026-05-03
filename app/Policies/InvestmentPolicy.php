<?php

namespace App\Policies;

use App\Models\Investment;
use App\Models\User;

class InvestmentPolicy
{
    public function view(User $user, Investment $investment): bool
    {
        return $user->id === $investment->user_id || $user->role === 'super_admin';
    }

    public function update(User $user, Investment $investment): bool
    {
        return $user->role === 'super_admin';
    }
}
