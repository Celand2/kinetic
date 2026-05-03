<?php
/**
 * MIGRATION : Table `trading_cycles`
 * Les 4 cycles KTS : FLASH (7j/15%), BOOST (15j/8%), PRO-PERF (30j/6%), INFINITY (60j/5%)
 * CHEMIN : database/migrations/2024_01_01_000002_create_trading_cycles_table.php
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trading_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();           // Ex: KINETIC FLASH
            $table->string('slug', 100)->unique();           // Ex: kinetic-flash
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('duration_days');    // 7, 15, 30, 60
            $table->decimal('daily_profit_percent', 5, 2);  // 15.00, 8.00, 6.00, 5.00
            $table->decimal('total_return_percent', 6, 2);  // duration × daily_rate
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('display_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trading_cycles');
    }
};
