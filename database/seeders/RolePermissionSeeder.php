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
            // Billing Officer permissions
            'manage-billing', 'generate-bills', 'view-bills', 'edit-bill-status', 'print-bill',
            // Customer Care permissions
            'manage-customer-care', 'view-customer-requests', 'respond-to-customer', 'update-customer-info',
            // Zonal/District Manager permissions
            'manage-zonal', 'view-zonal-reports', 'manage-district-operations', 'view-district-reports',
            // GIS permissions
            'manage-gis', 'view-gis-data', 'update-gis-data', 'export-gis',
            // Supplier/Assets Manager permissions
            'manage-assets', 'manage-vendors', 'manage-suppliers', 'create-asset', 'edit-asset', 'delete-asset', 'view-assets',
            'create-vendor', 'edit-vendor', 'delete-vendor', 'view-vendors', 'approve-vendor', 'reject-vendor',
            'create-supplier', 'edit-supplier', 'delete-supplier', 'view-suppliers',
            // Connection Management permissions
            'view-connections', 'view-connection', 'create-connection', 'edit-connection', 'delete-connection',
            'approve-connection', 'reject-connection', 'view-connection-fees', 'view-connection-fee',
            'create-connection-fee', 'edit-connection-fee', 'delete-connection-fee',
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

        // Refresh permissions array to include newly added ones
        $allPermissions = [
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
            // Billing Officer permissions
            'manage-billing', 'generate-bills', 'view-bills', 'edit-bill-status', 'print-bill',
            // Customer Care permissions
            'manage-customer-care', 'view-customer-requests', 'respond-to-customer', 'update-customer-info',
            // Zonal/District Manager permissions
            'manage-zonal', 'view-zonal-reports', 'manage-district-operations', 'view-district-reports',
            // GIS permissions
            'manage-gis', 'view-gis-data', 'update-gis-data', 'export-gis',
            // Supplier/Assets Manager permissions
            'manage-assets', 'manage-vendors', 'manage-suppliers', 'create-asset', 'edit-asset', 'delete-asset', 'view-assets',
            'create-vendor', 'edit-vendor', 'delete-vendor', 'view-vendors', 'approve-vendor', 'reject-vendor',
            'create-supplier', 'edit-supplier', 'delete-supplier', 'view-suppliers',
            // Connection Management permissions
            'view-connections', 'view-connection', 'create-connection', 'edit-connection', 'delete-connection',
            'approve-connection', 'reject-connection', 'view-connection-fees', 'view-connection-fee',
            'create-connection-fee', 'edit-connection-fee', 'delete-connection-fee',
        ];

        $superAdmin->syncPermissions($allPermissions);

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
            // Connection Management permissions
            'view-connections', 'view-connection', 'create-connection', 'edit-connection', 'delete-connection',
            'approve-connection', 'reject-connection', 'view-connection-fees', 'view-connection-fee',
            'create-connection-fee', 'edit-connection-fee', 'delete-connection-fee',
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
            // Connection Management permissions
            'view-connections', 'view-connection', 'create-connection', 'edit-connection',
            'view-connection-fees', 'view-connection-fee', 'create-connection-fee', 'edit-connection-fee',
        ]);

        // New Role: Billing Officer
        $billingOfficer = Role::firstOrCreate([
            'name' => 'billing-officer',
            'guard_name' => 'staff'
        ], ['status' => 'approved']);
        $billingOfficer->syncPermissions([
            'manage-billing', 'generate-bills', 'view-bills', 'edit-bill-status', 'print-bill',
            'view-customers', 'view-customer', 'view-report',
            'view-locations', 'view-categories', 'view-tariffs', 'view-bill',
            // Connection Management permissions
            'view-connections', 'view-connection', 'create-connection', 'edit-connection',
            'view-connection-fees', 'view-connection-fee', 'create-connection-fee', 'edit-connection-fee',
        ]);

        // New Role: Customer Care
        $customerCare = Role::firstOrCreate([
            'name' => 'customer-care',
            'guard_name' => 'staff'
        ], ['status' => 'approved']);
        $customerCare->syncPermissions([
            'manage-customer-care', 'view-customer-requests', 'respond-to-customer', 'update-customer-info',
            'view-customers', 'view-customer', 'view-locations', 'view-categories', 'view-tariffs',
            'manage-tickets', 'view-customers', 'view-customer',
            // Connection Management permissions
            'view-connections', 'view-connection',
        ]);

        // New Role: Zonal/District Manager
        $zonalManager = Role::firstOrCreate([
            'name' => 'zonal-manager',
            'guard_name' => 'staff'
        ], ['status' => 'approved']);
        $zonalManager->syncPermissions([
            'manage-zonal', 'view-zonal-reports', 'manage-district-operations', 'view-district-reports',
            'view-customers', 'view-customer', 'view-locations', 'view-categories', 'view-tariffs',
            'view-report', 'view-analytics',
            'create-district', 'edit-district', 'view-districts',
            'create-zone', 'edit-zone', 'view-zones', 'view-paypoints',
            // Connection Management permissions
            'view-connections', 'view-connection', 'create-connection', 'edit-connection',
            'view-connection-fees', 'view-connection-fee',
        ]);

        // New Role: GIS
        $gisRole = Role::firstOrCreate([
            'name' => 'gis',
            'guard_name' => 'staff'
        ], ['status' => 'approved']);
        $gisRole->syncPermissions([
            'manage-gis', 'view-gis-data', 'update-gis-data', 'export-gis',
            'view-customers', 'view-customer', 'view-locations', 'view-categories', 'view-tariffs',
            'view-zones', 'view-districts', 'view-paypoints', 'view-gis',
            // Connection Management permissions
            'view-connections', 'view-connection',
        ]);

        // New Role: Supplier/Assets Manager
        $assetsManager = Role::firstOrCreate([
            'name' => 'assets-manager',
            'guard_name' => 'staff'
        ], ['status' => 'approved']);
        $assetsManager->syncPermissions([
            'manage-assets', 'manage-vendors', 'manage-suppliers', 'create-asset', 'edit-asset', 'delete-asset', 'view-assets',
            'create-vendor', 'edit-vendor', 'delete-vendor', 'view-vendors', 'approve-vendor', 'reject-vendor',
            'create-supplier', 'edit-supplier', 'delete-supplier', 'view-suppliers',
            'view-locations', 'view-categories', 'view-tariffs',
            // Connection Management permissions
            'view-connections', 'view-connection', 'create-connection', 'edit-connection',
            'view-connection-fees', 'view-connection-fee', 'create-connection-fee', 'edit-connection-fee',
        ]);

        $customerRole = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'customer'
        ], ['status' => 'approved']);
        $customerRole->syncPermissions(['view-bill']);
    }
}
