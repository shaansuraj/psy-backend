<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view-user',
            'create-user',
            'edit-user',
            'delete-user',
            'view-role',
            'create-role',
            'edit-role',
            'delete-role',
            'view-app-user',
            'create-app-user',
            'edit-app-user',
            'delete-app-user',
            'view-post',
            'edit-post',
            'delete-post',
            'verify-post',
            'view-reports',
            'delete-reports',
        ];

        // Looping and Inserting Array's Permissions into Permission Table
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);

        $admin->givePermissionTo([
            'view-user',
            'create-user',
            'edit-user',
        ]);
    }
}
