<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Per_day_schedule>
 */
class Per_day_scheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'start_of_shift' => $this->faker->randomElement([date('H:i:s', 21600), date('H:i:s', 25200)]),
            'end_of_shift' => $this->faker->randomElement([date('H:i:s', 79200), date('H:i:s', 82800)]),
            'note' => $this->faker->randomElement([NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'dela prost dan'])
        ];
    }
}
