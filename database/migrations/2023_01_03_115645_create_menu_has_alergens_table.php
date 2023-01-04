<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuHasAlergensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_has_alergens', function (Blueprint $table) {
            $table->bigInteger('id_menu')->index('menu_has_alergens_fk');
            $table->bigInteger('id_alergen')->index('menu_has_alergens2_fk');
            $table->timestamps();

            $table->primary(['id_menu', 'id_alergen']);
            $table->unique(['id_menu', 'id_alergen'], 'menu_has_alergens_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_has_alergens');
    }
}
