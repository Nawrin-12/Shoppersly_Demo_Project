<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('No users or products found. Please seed users and products first.');
            return;
        }

        foreach ($users as $user) {
            // Generate 2 orders per user
            for ($i = 0; $i < 2; $i++) {
                $product = $products->random();

                Order::create([
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'product_details' => $product->name,
                    'product_id' => $product->id,
                ]);
            }
        }

        $this->command->info('Orders seeded successfully!');
    }
}
