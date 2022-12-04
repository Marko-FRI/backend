<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_user')->unique('users_pk');
            $table->string('name', 1024);
            $table->string('surname', 1024);
            $table->string('email', 1024);
            $table->string('password', 1024);
            $table->string('credit_card', 1024)->nullable();
            $table->string('profile_image_path', 1024)->nullable();
            $table->string('role', 1024)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
