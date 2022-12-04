<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDrinkHasVolumeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drink_has_volume', function (Blueprint $table) {
            $table->foreign(['id_drink'], 'fk_drink_ha_drink_has_drinks')->references(['id_drink'])->on('drinks')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign(['id_volume'], 'fk_drink_ha_volume_ha_volumes')->references(['id_volume'])->on('volumes')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drink_has_volume', function (Blueprint $table) {
            $table->dropForeign('fk_drink_ha_drink_has_drinks');
            $table->dropForeign('fk_drink_ha_volume_ha_volumes');
        });
    }
}
