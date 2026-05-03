<?php
/**
 * MIGRATION : Table `referral_commissions`
 * Commissions 3 niveaux : N1=10%, N2=3%, N3=1% sur chaque dépôt validé
 * CHEMIN : database/migrations/2024_01_01_000006_create_referral_commissions_table.php
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();  // Qui reçoit
            $table->foreignId('source_user_id')->constrained('users')->cascadeOnDelete();  // Qui a déposé
            $table->unsignedTinyInteger('level');            // 1, 2 ou 3
            $table->decimal('source_amount', 15, 2);        // Montant du dépôt source
            $table->decimal('rate_percent', 5, 2);          // 10.00, 3.00 ou 1.00
            $table->decimal('commission_amount', 15, 2);    // Montant calculé
            $table->foreignId('source_transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('credit_transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['referrer_id', 'status']);
            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');
    }
};
