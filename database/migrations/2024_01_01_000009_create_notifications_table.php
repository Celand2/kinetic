<?php
/**
 * MIGRATION : Table `kts_notifications`
 * Notifications internes KTS (séparée des notifications Laravel natif)
 * CHEMIN : database/migrations/2024_01_01_000009_create_notifications_table.php
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kts_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('title', 200);
            $table->text('body');
            $table->enum('type', [
                'profit_credited',
                'deposit_approved',
                'deposit_rejected',
                'withdrawal_approved',
                'withdrawal_rejected',
                'referral_bonus',
                'investment_active',
                'investment_complete',
                'message_received',
                'account_frozen',
                'broadcast',
                'system',
            ]);
            $table->string('action_url', 300)->nullable();
            $table->string('action_label', 50)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['user_id', 'is_read']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kts_notifications');
    }
};
