<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantHasFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_has_food', function (Blueprint $table) {
            $table->increments('id_restaurant_has_food')->unique('restaurant_has_food_pk');
            $table->bigInteger('id_food')->index('food_belongs_to_restaurant_fk');
            $table->bigInteger('id_restaurant')->index('restaurant_has_food_fk');
            $table->timestamps();

            $table->unique(['id_food', 'id_restaurant'], 'food_and_restaurant_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_has_food');
    }
}
