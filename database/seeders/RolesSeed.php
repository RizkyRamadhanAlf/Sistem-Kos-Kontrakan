<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (! app()->isLocal()) {
            return;
        }

        $plainPassword = env('ADMIN_PASSWORD', 'password');

        Role::findOrCreate('admin');
        Role::findOrCreate('owner');
        Role::findOrCreate('tenant');

        // seed admin default
        $admin = User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make($plainPassword),
            'phone' => '081234567890',
            'address' => 'Admin Address',
            'role' => 'admin',
        ]);
        $admin->syncRoles(['admin']);
    }
}
