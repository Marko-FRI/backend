<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigInteger('id_user')->index('user_gives_review_fk');
            $table->bigInteger('id_restaurant')->index('restaurant_has_review_fk');
            $table->string('comment', 1024)->nullable();
            $table->smallInteger('rating');
            $table->timestamps();

            $table->primary(['id_user', 'id_restaurant']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
