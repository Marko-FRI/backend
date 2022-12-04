<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationHasTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_has_table', function (Blueprint $table) {
            $table->bigInteger('id_table')->index('tables_are_in_reservations_fk');
            $table->bigInteger('id_reservation')->index('reservations_have_tables_fk');
            $table->date('date_and_time_of_reservation');
            $table->timestamps();

            $table->primary(['id_table', 'id_reservation', 'date_and_time_of_reservation']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_has_table');
    }
}
