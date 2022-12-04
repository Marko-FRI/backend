<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMenuHasFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_has_food', function (Blueprint $table) {
            $table->foreign(['id_menu'], 'fk_menu_has_menu_has__menus')->references(['id_menu'])->on('menus')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_restaurant_has_food'], 'fk_menu_has_menu_has__restaura')->references(['id_restaurant_has_food'])->on('restaurant_has_food')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_has_food', function (Blueprint $table) {
            $table->dropForeign('fk_menu_has_menu_has__menus');
            $table->dropForeign('fk_menu_has_menu_has__restaura');
        });
    }
}
