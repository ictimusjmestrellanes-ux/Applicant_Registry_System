<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permission = 'view_duplicates';

        DB::table('users')
            ->orderBy('id')
            ->where(function ($query) {
                $query->where('role', 'admin')
                    ->orWhere('role', 'staff');
            })
            ->each(function ($user) use ($permission) {
                $permissions = is_array($user->permissions) ? $user->permissions : json_decode($user->permissions ?? '[]', true) ?? [];
                if (! in_array($permission, $permissions, true)) {
                    $permissions[] = $permission;
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['permissions' => $permissions]);
                }
            });
    }

    public function down(): void
    {
        $permission = 'view_duplicates';

        DB::table('users')
            ->orderBy('id')
            ->each(function ($user) use ($permission) {
                $permissions = is_array($user->permissions) ? $user->permissions : json_decode($user->permissions ?? '[]', true) ?? [];
                $permissions = array_values(array_filter($permissions, fn ($p) => $p !== $permission));
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['permissions' => $permissions]);
            });
    }
};
