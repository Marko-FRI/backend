<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFoodHasAlergensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_has_alergens', function (Blueprint $table) {
            $table->foreign(['id_alergen'], 'fk_food_has_food_has__alergens')->references(['id_alergen'])->on('alergens')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign(['id_food'], 'fk_food_has_food_has__food')->references(['id_food'])->on('food')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food_has_alergens', function (Blueprint $table) {
            $table->dropForeign('fk_food_has_food_has__alergens');
            $table->dropForeign('fk_food_has_food_has__food');
        });
    }
}
