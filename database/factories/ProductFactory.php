<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        return [

            'name' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'category' => $this->faker->word,
            'price' => $this->faker->randomFloat(2,1,10),
            'url' => $this->faker->word,
            'status' => $this->faker->randomElement(['available', 'unavailable']),
            'user_id' => User::factory()->vendor(),

            'name'        => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'category'    => $this->faker->randomElement(['electronics', 'clothing', 'furniture']),
            'price'       => $this->faker->randomFloat(2, 50, 1500),
            'url'         => $this->faker->url(),
            'status'      => $this->faker->randomElement(['available', 'unavailable']),


        ];
    }
}
