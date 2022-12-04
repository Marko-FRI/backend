<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRestaurantHasDrinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_has_drinks', function (Blueprint $table) {
            $table->foreign(['id_drink_has_volume'], 'fk_restaura_drink_bel_drink_ha')->references(['id_drink_has_volume'])->on('drink_has_volume')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::table('restaurant_has_drinks', function (Blueprint $table) {
            $table->dropForeign('fk_restaura_drink_bel_drink_ha');
            $table->dropForeign('fk_restaura_restauran_restaura');
        });
    }
}
