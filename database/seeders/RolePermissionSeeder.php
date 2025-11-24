<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create Permissions
        $permissions = [
            'create-staff', 'edit-staff', 'delete-staff', 'approve-staff', 'reject-staff', 'view-staff',
            'create-role', 'edit-role', 'delete-role',
            'create-permission', 'edit-permission', 'delete-permission',
            'create-lga', 'edit-lga', 'delete-lga', 'approve-lga', 'reject-lga',
            'create-ward', 'edit-ward', 'delete-ward', 'approve-ward', 'reject-ward',
            'create-area', 'edit-area', 'delete-area', 'approve-area', 'reject-area',
            'create-category', 'edit-category', 'delete-category', 'approve-category', 'reject-category',
            'create-tariff', 'edit-tariff', 'delete-tariff', 'approve-tariff', 'reject-tariff',
            'view-customers', 'view-customer', 'create-customer', 'edit-customer', 'delete-customer',
            'approve-customer', 'reject-customer',
            'view-locations', 'view-categories', 'view-tariffs', 'manage-users', 'view-audit-trail',
            'create-bill', 'approve-bill', 'reject-bill', 'delete-bill', 'view-bill', 'view-report',
            'view-payment', 'view-analytics',
            'create-zone', 'edit-zone', 'delete-zone', 'approve-zone', 'reject-zone', 'view-zones',
            'create-district', 'edit-district', 'delete-district', 'approve-district', 'reject-district', 'view-districts',
            'create-paypoint', 'edit-paypoint', 'delete-paypoint', 'approve-paypoint', 'reject-paypoint', 'view-paypoints',
            'assign-staff-role', 'revoke-staff-role', 'manage-staff-permissions',
            'manage-district-wards', 'view-location-details',
            'manage-staff', 'manage-tickets', 'approve-actions', 'view-gis',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'staff'
            ], ['status' => 'approved']);
        }

        Permission::firstOrCreate([
            'name' => 'view-bill',
            'guard_name' => 'customer'
        ], ['status' => 'approved']);

        // Create Roles
        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'staff'
        ], ['status' => 'approved']);
        $superAdmin->syncPermissions($permissions);

        $manager = Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'staff'
        ], ['status' => 'approved']);
        $manager->syncPermissions([
            'create-staff', 'edit-staff', 'delete-staff', 'view-staff',
            'create-lga', 'edit-lga', 'delete-lga',
            'create-ward', 'edit-ward', 'delete-ward',
            'create-area', 'edit-area', 'delete-area',
            'create-category', 'edit-category', 'delete-category',
            'create-tariff', 'edit-tariff', 'delete-tariff',
            'view-customers', 'view-customer', 'create-customer', 'edit-customer', 'delete-customer',
            'view-locations', 'view-categories', 'view-tariffs', 'view-audit-trail',
            'view-report',
            'view-zones', 'view-districts', 'view-paypoints',
            'manage-district-wards', 'view-location-details',
            'manage-staff', 'manage-tickets',
        ]);

        $staffRole = Role::firstOrCreate([
            'name' => 'staff',
            'guard_name' => 'staff'
        ], ['status' => 'approved']);
        $staffRole->syncPermissions([
            'create-category', 'edit-category', 'create-tariff', 'edit-tariff',
            'view-customers', 'view-customer', 'create-customer', 'edit-customer',
            'view-locations', 'view-categories', 'view-tariffs', 'view-bill', 'view-report',
            'view-staff', 'view-zones', 'view-districts', 'view-paypoints',
            'view-location-details', 'manage-tickets',
        ]);

        $customerRole = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'customer'
        ], ['status' => 'approved']);
        $customerRole->syncPermissions(['view-bill']);
    }
}
