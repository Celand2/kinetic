<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Change payment_method from enum to string to allow more flexible values
            // including 'wallet', 'lumicash', 'bancobu_enoti', 'internal', 'admin'
            DB::statement("ALTER TABLE transactions CHANGE payment_method payment_method VARCHAR(50) NULL");
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Revert back to enum if needed
            DB::statement("ALTER TABLE transactions CHANGE payment_method payment_method ENUM('lumicash', 'bancobu_enoti', 'internal', 'admin') NULL");
        });
    }
};
