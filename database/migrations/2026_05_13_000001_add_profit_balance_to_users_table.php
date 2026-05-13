<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Gains retirables : profits journaliers + commissions parrainage + codes bonus
            // Le capital déposé (balance - profit_balance) n'est pas retirable
            $table->decimal('profit_balance', 15, 2)->default(0.00)->after('referral_balance');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profit_balance');
        });
    }
};
