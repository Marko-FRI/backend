<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuHasFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_has_food', function (Blueprint $table) {
            $table->bigInteger('id_restaurant_has_food')->index('restaurant_food_belongs_to_menus_fk');
            $table->bigInteger('id_menu')->index('menus_have_food_fk');
            $table->timestamps();

            $table->primary(['id_restaurant_has_food', 'id_menu']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_has_food');
    }
}
