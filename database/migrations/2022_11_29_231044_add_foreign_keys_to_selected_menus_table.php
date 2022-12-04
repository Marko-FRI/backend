<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSelectedMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selected_menus', function (Blueprint $table) {
            $table->foreign(['id_reservation'], 'fk_selected_reservati_reservat')->references(['id_reservation'])->on('reservations')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_menu'], 'fk_selected_selected__menus')->references(['id_menu'])->on('menus')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign(['id_restaurant_has_drink'], 'fk_selected_selected__restaura')->references(['id_restaurant_has_drink'])->on('restaurant_has_drinks')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selected_menus', function (Blueprint $table) {
            $table->dropForeign('fk_selected_reservati_reservat');
            $table->dropForeign('fk_selected_selected__menus');
            $table->dropForeign('fk_selected_selected__restaura');
        });
    }
}
