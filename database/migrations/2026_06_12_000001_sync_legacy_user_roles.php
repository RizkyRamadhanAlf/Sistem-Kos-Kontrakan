<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('model_has_roles')) {
            return;
        }

        $now = now();

        foreach (['admin', 'owner', 'tenant'] as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role, 'guard_name' => 'web'],
                ['updated_at' => $now, 'created_at' => $now],
            );
        }

        $roleIds = DB::table('roles')
            ->where('guard_name', 'web')
            ->pluck('id', 'name');

        DB::table('users')
            ->orderBy('id')
            ->each(function (object $user) use ($roleIds): void {
                $canonicalRole = match ($user->role) {
                    'admin' => 'admin',
                    'owner' => 'owner',
                    'tenant' => 'owner',
                    'penyewa', 'member' => 'tenant',
                    default => null,
                };

                if (! $canonicalRole) {
                    return;
                }

                $hasSpatieRole = DB::table('model_has_roles')
                    ->where('model_type', User::class)
                    ->where('model_id', $user->id)
                    ->exists();

                if ($hasSpatieRole) {
                    return;
                }

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['role' => $canonicalRole]);

                DB::table('model_has_roles')->insertOrIgnore([
                    'role_id' => $roleIds[$canonicalRole],
                    'model_type' => User::class,
                    'model_id' => $user->id,
                ]);
            });
    }

    public function down(): void
    {
        // Legacy role names cannot be restored reliably.
    }
};
