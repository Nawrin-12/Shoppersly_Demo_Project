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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'number' => '1234567555',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Vendor User',
            'email' => 'vendor@example.com',
            'number' => '1234437890',
            'password' => Hash::make('password'),
            'role' => 'vendor',
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'number' => '1234567730',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $vendor = User::factory()->vendor()->create();
        Product::factory()->count(1)->create([
            'user_id' => $vendor->id,
        ]);
    }
}
