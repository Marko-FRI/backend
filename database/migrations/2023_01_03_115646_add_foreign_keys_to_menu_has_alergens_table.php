<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMenuHasAlergensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_has_alergens', function (Blueprint $table) {
            $table->foreign(['id_menu'], 'fk_menu_has_menu_has__menus')->references(['id_menu'])->on('menus')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_alergen'], 'fk_menu_has_menu_has__alergens')->references(['id_alergen'])->on('alergens')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_has_alergens', function (Blueprint $table) {
            $table->dropForeign('fk_menu_has_menu_has__menus');
            $table->dropForeign('fk_menu_has_menu_has__alergens');
        });
    }
}
