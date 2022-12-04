<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerDaySchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('per_day_schedules', function (Blueprint $table) {
            $table->increments('id_per_day_schedule')->unique('per_day_schedules_pk');
            $table->bigInteger('id_restaurant')->index('restaurant_has_per_day_schedule_fk');
            $table->time('start_of_shift');
            $table->time('end_of_shift');
            $table->string('day', 1024);
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
        Schema::dropIfExists('per_day_schedules');
    }
}
