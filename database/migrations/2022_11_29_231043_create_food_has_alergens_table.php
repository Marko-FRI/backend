<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodHasAlergensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_has_alergens', function (Blueprint $table) {
            $table->bigInteger('id_food')->index('food_has_alergens_fk');
            $table->bigInteger('id_alergen')->index('food_has_alergens2_fk');
            $table->timestamps();

            $table->primary(['id_food', 'id_alergen']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_has_alergens');
    }
}
