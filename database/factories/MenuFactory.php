<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Restaurant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'image_path' => 'food.jpg',
            'price' => $this->faker->randomFloat('2', 10, 40),
            'description' => $this->faker->unique()->sentence(rand(5,15)),
            'discount' => $this->faker->randomElement([NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,
                                                    NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,
                                                    NULL,NULL,NULL,NULL,NULL,NULL,NULL,20,10,5])
        ];
    }
}
