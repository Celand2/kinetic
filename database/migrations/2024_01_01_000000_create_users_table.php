<?php
/**
 * MIGRATION : Table `users`
 *
 * Table centrale de la plateforme KTS.
 * Stocke les investisseurs et les admins.
 *
 * CHEMIN : database/migrations/2024_01_01_000001_create_users_table.php
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── TABLE PRINCIPALE DES UTILISATEURS ─────────────────────────────
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            // Identité
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone', 20)->unique();
            $table->string('country', 100);
            $table->string('password');

            // Soldes financiers
            // balance          = solde principal (dépôts + profits)
            // referral_balance = commissions de parrainage uniquement
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->decimal('referral_balance', 15, 2)->default(0.00);

            // Rôle : 'user' (investisseur) ou 'super_admin'
            $table->enum('role', ['user', 'super_admin'])->default('user');

            // Statut : active | frozen (temporaire) | blocked (permanent)
            $table->enum('status', ['active', 'frozen', 'blocked'])->default('active');

            // Parrainage
            $table->string('referral_code', 20)->unique();       // Ex: KTS-AB1234
            $table->foreignId('referred_by_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Fortify
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();

            // Index performances
            $table->index('status');
            $table->index('role');
        });

        // Requis par Fortify (reset password)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions PHP
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
