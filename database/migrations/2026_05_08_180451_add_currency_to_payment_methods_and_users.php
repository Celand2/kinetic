<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('currency', 10)->default('USD')->after('type');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('preferred_currency', 10)->nullable()->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preferred_currency');
        });
    }
};
