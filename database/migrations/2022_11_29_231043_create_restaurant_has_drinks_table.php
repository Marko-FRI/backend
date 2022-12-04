<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantHasDrinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_has_drinks', function (Blueprint $table) {
            $table->increments('id_restaurant_has_drink')->unique('restaurant_has_drinks_pk');
            $table->bigInteger('id_restaurant')->index('restaurant_has_drinks_fk');
            $table->bigInteger('id_drink_has_volume')->index('drink_belongs_to_restaurant_fk');
            $table->timestamps();
            
            $table->unique(['id_drink_has_volume', 'id_restaurant'], 'restaurant_and_drink_has_volume_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_has_drinks');
    }
}
