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
        $table->smallInteger('duration_days')->unsigned()->change();
        // smallInteger unsigned = max 65535, largement suffisant
    });
}

public function down()
{
    Schema::table('trading_cycles', function (Blueprint $table) {
        $table->tinyInteger('duration_days')->change();
    });
}
};
