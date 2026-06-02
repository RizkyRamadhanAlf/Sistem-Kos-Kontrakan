<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'phone' => '081234567890',
                'address' => 'Admin Address',
                'role' => 'admin',
                'password' => Hash::make('secret'), // ganti password setelah deploy
            ]
        );
    }
}