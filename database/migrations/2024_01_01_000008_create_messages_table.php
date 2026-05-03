<?php
/**
 * MIGRATION : Tables `conversations` + `messages`
 *
 * PLAN DE LA MESSAGERIE INTERNE KTS :
 * ┌─────────────────────────────────────────────────────────────┐
 * │  USER  →  Ouvre un ticket (conversation)                    │
 * │  ADMIN →  Voit tous les tickets, répond                     │
 * │  Les deux parties voient l'historique complet               │
 * │  Compteurs non-lus par côté (user / admin)                  │
 * │  Catégories : support / dépôt / retrait / investissement    │
 * │                / parrainage / litige / autre                │
 * │  Priorités admin : low / normal / high / urgent             │
 * └─────────────────────────────────────────────────────────────┘
 *
 * CHEMIN : database/migrations/2024_01_01_000008_create_messages_table.php
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── CONVERSATIONS ─────────────────────────────────────────────────
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject', 200);
            $table->enum('category', ['support','deposit','withdrawal','investment','referral','dispute','other'])->default('support');
            $table->enum('status', ['open', 'resolved', 'closed'])->default('open');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->timestamp('last_message_at')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // Admin assigné
            $table->unsignedSmallInteger('unread_admin_count')->default(0);
            $table->unsignedSmallInteger('unread_user_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'status']);
            $table->index('priority');
        });

        // ── MESSAGES ──────────────────────────────────────────────────────
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->enum('type', ['text', 'system', 'file'])->default('text');
            $table->string('attachment_path', 300)->nullable();
            $table->string('attachment_name', 200)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['conversation_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};
