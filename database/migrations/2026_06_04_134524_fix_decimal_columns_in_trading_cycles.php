<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   
    public function up()
{
    Schema::table('trading_cycles', function (Blueprint $table) {
        $table->decimal('daily_profit_percent', 8, 2)->change();  // max 999999.99
        $table->decimal('total_return_percent', 10, 2)->change(); // max 99999999.99
    });
}

public function down()
{
    Schema::table('trading_cycles', function (Blueprint $table) {
        $table->decimal('daily_profit_percent', 5, 2)->change();
        $table->decimal('total_return_percent', 6, 2)->change();
    });
}
};
