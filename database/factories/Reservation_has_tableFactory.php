<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Reservation;
use App\Models\Table;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation_has_table>
 */
class Reservation_has_tableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_table' => Table::all()->random()->id_table,
            'date_and_time_of_reservation' => $this->faker->unique()->dateTimeBetween('+01 days', '+1 month')
        ];
    }
}
