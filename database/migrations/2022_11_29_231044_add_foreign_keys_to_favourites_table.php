<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFavouritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favourites', function (Blueprint $table) {
            $table->foreign(['id_restaurant'], 'fk_favourit_favourite_restaura')->references(['id_restaurant'])->on('restaurants')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_user'], 'fk_favourit_favourite_users')->references(['id_user'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favourites', function (Blueprint $table) {
            $table->dropForeign('fk_favourit_favourite_restaura');
            $table->dropForeign('fk_favourit_favourite_users');
        });
    }
}
