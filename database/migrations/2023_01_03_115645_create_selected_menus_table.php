<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectedMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected_menus', function (Blueprint $table) {
            $table->increments('id_selected_menu')->unique('selected_menus_pk');
            $table->bigInteger('id_reservation')->index('reservation_has_selected_menu_fk');
            $table->bigInteger('id_menu')->index('selected_menu_has_menu_fk');
            $table->smallInteger('quantity')->nullable(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selected_menus');
    }
}
