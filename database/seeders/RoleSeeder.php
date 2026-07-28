<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin' => 'Admin',
            'staff' => 'Staff',
            'user' => 'User',
        ];

        foreach ($roles as $slug => $label) {
            Role::firstOrCreate(
                ['slug' => $slug],
                ['label' => $label]
            );
        }
    }
}
