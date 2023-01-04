<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign(['id_restaurant'], 'fk_reviews_restauran_restaura')->references(['id_restaurant'])->on('restaurants')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_user'], 'fk_reviews_user_give_users')->references(['id_user'])->on('users')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign('fk_reviews_restauran_restaura');
            $table->dropForeign('fk_reviews_user_give_users');
        });
    }
}
