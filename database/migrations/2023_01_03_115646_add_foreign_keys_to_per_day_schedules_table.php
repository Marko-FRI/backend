<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPerDaySchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('per_day_schedules', function (Blueprint $table) {
            $table->foreign(['id_restaurant'], 'fk_per_day__restauran_restaura')->references(['id_restaurant'])->on('restaurants')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('per_day_schedules', function (Blueprint $table) {
            $table->dropForeign('fk_per_day__restauran_restaura');
        });
    }
}
