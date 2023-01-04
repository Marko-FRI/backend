<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
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
            'name' => $this->faker->unique()->sentence(1),
            'description' => $this->faker->sentence(rand(15,25)),
            'address' => $this->faker->unique()->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->randomElement(['01 3450 223', '031 221 667']),
            'facebook_link' => 'https://facebook.com/' . $this->faker->unique()->word(),
            'instagram_link' => 'https://instagram.com/' . $this->faker->unique()->word(),
            'twitter_link' => 'https://twitter.com/' . $this->faker->unique()->word()
        ];
    }
}
