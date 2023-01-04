<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavouritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favourites', function (Blueprint $table) {
            $table->bigInteger('id_user')->index('favourites_fk');
            $table->bigInteger('id_restaurant')->index('favourites2_fk');
            $table->timestamps();

            $table->primary(['id_user', 'id_restaurant']);
            $table->unique(['id_user', 'id_restaurant'], 'favourites_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favourites');
    }
}
