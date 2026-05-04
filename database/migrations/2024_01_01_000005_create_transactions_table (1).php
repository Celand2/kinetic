<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Type de mouvement
            $table->enum('type', [
                'deposit',
                'withdrawal',
                'investment_buy',
                'daily_profit',
                'referral_bonus',
                'admin_adjustment',
                'withdrawal_fee',
            ]);

            $table->decimal('amount', 15, 2);

            
            $table->enum('direction', ['credit', 'debit']);
            $table->decimal('balance_after', 15, 2);

            
            $table->enum('status', ['pending', 'completed', 'rejected', 'cancelled'])->default('pending');

            
            $table->string('reference', 30)->unique();

        
            $table->foreignId('investment_id')->nullable()->constrained('investments')->nullOnDelete();
            $table->enum('payment_method', ['lumicash', 'bancobu_enoti', 'internal', 'admin'])->nullable();
            $table->string('external_reference', 100)->nullable(); // ID transaction Lumicash, etc.

            
            $table->decimal('fee_amount', 15, 2)->default(0.00);

            
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();

            
            $table->text('description')->nullable();    // Visible par l'user
            $table->text('admin_notes')->nullable();    // Interne admin
            $table->json('metadata')->nullable();       // Données extra flexibles

            $table->timestamps();
            

            // Index performances
            $table->index('type');
            $table->index('status');
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
