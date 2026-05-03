<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('trading_cycle_id')->constrained('trading_cycles')->restrictOnDelete();
            $table->foreignId('tranche_id')->constrained('tranches')->restrictOnDelete();

            // Capital
            $table->decimal('amount', 15, 2);

            // Taux copiés au moment de l'achat (JAMAIS modifiés après)
            $table->decimal('daily_profit_rate', 5, 2);
            $table->decimal('total_return_rate', 6, 2);
            $table->unsignedSmallInteger('duration_days');

            // Suivi profits
            $table->decimal('total_profit_credited', 15, 2)->default(0.00);
            $table->unsignedSmallInteger('days_credited')->default(0);

            // Dates
            $table->dateTime('started_at');
            $table->dateTime('ends_at');
            $table->timestamp('last_profit_credited_at')->nullable();

            // Statut : pending → active → completed | cancelled
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');

            // Référence unique lisible : INV-2024-000001
            $table->string('reference', 30)->unique();
            $table->text('admin_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index performances
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index('ends_at');
            $table->index('last_profit_credited_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
