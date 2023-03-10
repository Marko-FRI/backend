<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRestaurantImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_images', function (Blueprint $table) {
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
        Schema::table('restaurant_images', function (Blueprint $table) {
            $table->dropForeign('fk_restaura_restauran_restaura');
        });
    }
}
