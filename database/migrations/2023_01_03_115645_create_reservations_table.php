<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id_reservation')->unique('reservations_pk');
            $table->bigInteger('id_user')->index('user_has_reservation_fk');
            $table->bigInteger('id_table')->nullable()->index('reservation_has_table_fk');
            $table->smallInteger('number_of_personel');
            $table->dateTime('date_and_time_of_reservation');
            $table->string('note', 1024)->nullable();
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
        Schema::dropIfExists('reservations');
    }
}
