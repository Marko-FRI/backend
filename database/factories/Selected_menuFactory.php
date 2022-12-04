<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Restaurant_has_drink;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Selected_menu>
 */
class Selected_menuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'note' => $this->faker->randomElement([NULL,NULL,NULL,'brez glutena','brez sladkorja']),
            'id_restaurant_has_drink' => Restaurant_has_drink::all()->random()->id_restaurant_has_drink
        ];
    }
}
