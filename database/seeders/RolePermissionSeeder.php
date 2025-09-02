<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Permissions for LGA, Ward, Area, Category, Tariff
        $permissions = [
            'create-lga', 'edit-lga', 'delete-lga', 'approve-lga', 'reject-lga',
            'create-ward', 'edit-ward', 'delete-ward', 'approve-ward', 'reject-ward',
            'create-area', 'edit-area', 'delete-area', 'approve-area', 'reject-area',
            'create-category', 'edit-category', 'delete-category', 'approve-category', 'reject-category',
            'create-tariff', 'edit-tariff', 'delete-tariff', 'approve-tariff', 'reject-tariff',
            'manage-users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'staff']);
        }

        // Roles
        $superAdmin = Role::create(['name' => 'super-admin', 'guard_name' => 'staff']);
        $manager = Role::create(['name' => 'manager', 'guard_name' => 'staff']);

        // Assign permissions
        $superAdmin->givePermissionTo($permissions);
        $manager->givePermissionTo([
            'create-lga', 'edit-lga', 'create-ward', 'edit-ward', 'create-area', 'edit-area',
            'create-category', 'edit-category', 'create-tariff', 'edit-tariff',
        ]);
    }
}