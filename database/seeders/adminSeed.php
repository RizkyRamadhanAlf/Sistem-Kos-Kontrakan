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
        if (! app()->isLocal()) {
            return;
        }

        $plainPassword = env('ADMIN_PASSWORD', 'secret');

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'phone' => '081234567890',
                'address' => 'Admin Address',
                'role' => 'admin',
                'password' => Hash::make($plainPassword),
            ]
        );
    }
    }
}