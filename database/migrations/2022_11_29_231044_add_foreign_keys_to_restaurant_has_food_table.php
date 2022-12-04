<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRestaurantHasFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_has_food', function (Blueprint $table) {
            $table->foreign(['id_food'], 'fk_restaura_food_belo_food')->references(['id_food'])->on('food')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign(['id_restaurant'], 'fk_restaura_restauran_restaura')->references(['id_restaurant'])->on('restaurants')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_has_food', function (Blueprint $table) {
            $table->dropForeign('fk_restaura_food_belo_food');
            $table->dropForeign('fk_restaura_restauran_restaura');
        });
    }
}
