<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductImage;

class ProductWithImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::factory()->count(3)->create()->each(function ($user) {
            for($i=0;$i<2;$i++){
                $product = Product::create([
                    'name' => 'Product '.fake()->word(),
                    'description' => fake()->sentence(),
                    'category' => fake()->randomElement(['Electronics','Clothing','Stationery','Grocery']),
                    'price' => fake()->randomFloat(2,10,1000),
                    'url' => fake()->url(),
                    'status' => fake()->randomElement(['available','unavailable']),
                    'user_id' =>$user->id,
                ]);
                for($j=0;$j<rand(2,3);$j++){
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'product_image/sample'.rand(1,5).'.jpg',
                    ]);
                }
            }
        });
    }
}
