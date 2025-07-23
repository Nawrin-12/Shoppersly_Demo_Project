<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'number' => '1234567555',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'vendor@example.com'],
            [
                'name' => 'Vendor User',
                'number' => '1234437890',
                'password' => Hash::make('password'),
                'role' => 'vendor',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'number' => '1234567730',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // Check if vendor already exists before creating a new one using factory
        $vendor = User::where('role', 'vendor')->first();

        if ($vendor && !$vendor->products()->exists()) {
            Product::factory()->count(1)->create([
                'user_id' => $vendor->id,
            ]);
        }
    }
}
