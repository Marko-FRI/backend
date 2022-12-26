<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id_restaurant')->unique('restaurants_pk');
            $table->bigInteger('id_user')->index('restaurant_has_administrator_fk');
            $table->string('name', 1024);
            $table->string('address', 1024);
            $table->string('description', 1024)->nullable();
            $table->string('email', 1024);
            $table->string('phone_number', 1024);
            $table->string('facebook_link', 1024);
            $table->string('instagram_link', 1024);
            $table->string('twitter_link', 1024);

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
        Schema::dropIfExists('restaurants');
    }
}
