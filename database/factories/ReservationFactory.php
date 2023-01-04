<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_user' => User::factory(),
            'date_and_time_of_reservation' => $this->faker->unique()->dateTimeBetween($startDate = '+1 days', $endDate = '+14 days')->format('d-m-Y H:i:s'),
            'note' => $this->faker->randomElement([NULL,NULL,NULL,'brez glutena','brez sladkorja'])
        ];
    }
}
