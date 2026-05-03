<?php
/**
 * MIGRATION : Table `tranches`
 *
 * Les tranches sont les niveaux d'investissement au sein d'un cycle de trading.
 * Exemples : Basic, Pro, VIP, Premium, etc.
 *
 * CHEMIN : database/migrations/2024_01_01_000003_create_tranches_table.php
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tranches', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('trading_cycle_id')->constrained('trading_cycles')->cascadeOnDelete();
            
            // Tranche info
            $table->string('name', 100);                    // Ex: "Basic", "Pro", "VIP"
            $table->string('slug', 100);                    // Ex: "basic", "pro", "vip"
            $table->text('description')->nullable();
            $table->decimal('min_amount', 15, 2);           // Montant minimum d'investissement
            $table->decimal('max_amount', 15, 2)->nullable(); // Montant maximum (NULL = illimité)
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('display_order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['trading_cycle_id', 'is_active']);
            $table->unique(['trading_cycle_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tranches');
    }
};
