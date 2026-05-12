<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create Default Accountant (Optional)
        User::updateOrCreate(
            ['email' => 'zeeshan@gmail.com'],
            [
                'name' => 'Zeeshan Accountant',
                'password' => Hash::make('password'),
                'role' => 'accountant',
            ]
        );
    }
}
