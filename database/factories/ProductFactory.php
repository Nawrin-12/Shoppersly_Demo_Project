<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'category'    => $this->faker->randomElement(['electronics', 'clothing', 'furniture']),
            'price'       => $this->faker->randomFloat(2, 50, 1500),
            'url'         => $this->faker->url(),
            'status'      => $this->faker->randomElement(['available', 'unavailable']),
        ];
    }
}
