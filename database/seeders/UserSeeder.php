<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Admin
        User::updateOrCreate(
            ['email' => 'admin@fanhelm.com'],
            [
                'name' => 'Admin Fan Helm',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '082118079547',
                'address' => 'Jl. Eyang Weri, Kuningan, Jawa Barat',
            ]
        );

        // Default Customer
        User::updateOrCreate(
            ['email' => 'customer@fanhelm.com'],
            [
                'name' => 'Fauzi Akbar',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '081234567890',
                'address' => 'Jl. Sudirman No. 45, Jakarta Selatan',
            ]
        );
    }
}
