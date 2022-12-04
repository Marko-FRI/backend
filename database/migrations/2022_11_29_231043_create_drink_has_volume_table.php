<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrinkHasVolumeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drink_has_volume', function (Blueprint $table) {
            $table->increments('id_drink_has_volume')->unique('drink_has_volume_pk');
            $table->bigInteger('id_volume')->index('volume_has_drinks_fk');
            $table->bigInteger('id_drink')->index('drink_has_volume_fk');
            $table->float('price', 0, 0);
            $table->timestamps();

            
            $table->unique(['id_volume', 'id_drink'], 'volume_and_drink_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drink_has_volume');
    }
}
