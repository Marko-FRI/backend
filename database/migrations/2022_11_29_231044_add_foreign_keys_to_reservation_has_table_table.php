<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToReservationHasTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservation_has_table', function (Blueprint $table) {
            $table->foreign(['id_reservation'], 'fk_reservat_reservati_reservat')->references(['id_reservation'])->on('reservations')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_table'], 'fk_reservat_tables_ar_tables')->references(['id_table'])->on('tables')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservation_has_table', function (Blueprint $table) {
            $table->dropForeign('fk_reservat_reservati_reservat');
            $table->dropForeign('fk_reservat_tables_ar_tables');
        });
    }
}
