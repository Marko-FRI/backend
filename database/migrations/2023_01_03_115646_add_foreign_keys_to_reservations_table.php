<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreign(['id_table'], 'fk_reservat_reservati_tables')->references(['id_table'])->on('tables')->onUpdate('CASCADE');
            $table->foreign(['id_user'], 'fk_reservat_user_has__users')->references(['id_user'])->on('users')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign('fk_reservat_reservati_tables');
            $table->dropForeign('fk_reservat_user_has__users');
        });
    }
}
