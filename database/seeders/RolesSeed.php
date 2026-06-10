<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

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

        $plainPassword = env('ADMIN_PASSWORD');

        $adminRole = Role::create(['name' => 'admin']);
        $ownerRole = Role::create(['name' => 'owner']);
        $tenantRole = Role::create(['name' => 'tenant']);


        //seed admin default
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make($plainPassword),
            'phone' => '081234567890',
            'address' => 'Admin Address',
            'role' => 'admin',

        ]);
        $admin->assignRole('admin');
    }
}
