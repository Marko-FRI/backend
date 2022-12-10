<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id_menu')->unique('menus_pk');
            $table->bigInteger('id_restaurant')->index('restaurant_has_menu_fk');
            $table->bigInteger('id_category')->references('id_category')->on('categories')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->string('image_path', 1024)->nullable();
            $table->float('price', 0, 0);
            $table->string('description', 1024)->nullable();
            $table->integer('discount')->nullable();
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
        Schema::dropIfExists('menus');
    }
}
