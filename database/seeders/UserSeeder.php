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
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'number' => '1234567555',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'vendor@example.com'],
            [
                'name' => 'Vendor User',
                'number' => '1234437890',
                'password' => Hash::make('password'),
                'role' => 'vendor',
            ]
        );
//        $vendorCount = 5;

        $vendor = User::factory()->vendor()->create();
        Product::factory()->count(1)->create([
            'user_id' => $vendor->id,
        ]);
    }
}
