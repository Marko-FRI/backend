<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->foreign(['id_category'], 'fk_menus_menu_has__categori')->references(['id_category'])->on('categories')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign(['id_restaurant'], 'fk_menus_restauran_restaura')->references(['id_restaurant'])->on('restaurants')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign('fk_menus_menu_has__categori');
            $table->dropForeign('fk_menus_restauran_restaura');
        });
    }
}
