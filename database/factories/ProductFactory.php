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
            'name' => $this->faker->words(3, true), // Better product name
            'description' => $this->faker->paragraph(3), // More detailed description
            'category' => $this->faker->randomElement([
                'Electronics',
                'Clothing',
                'Furniture', 
                'Books',
                'Sports',
                'Beauty',
                'Home & Garden',
                'Toys'
            ]),
            'price' => $this->faker->randomFloat(2, 10, 1000), // More realistic price range
            'url' => $this->faker->url(), // Valid URL format
            'status' => $this->faker->randomElement(['available', 'unavailable']),
            'user_id' => User::factory()->vendor(), // Create vendor user
        ];
    }
}