<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class StaffManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Staff Management Permissions
        $this->createStaffPermissions();
        
        // Update Roles with Staff Management Permissions
        $this->updateRolePermissions();
        
        // Create Sample Staff Members
        $this->createStaffMembers();
        
        $this->command->info('Staff Management data seeded successfully!');
    }
    
    /**
     * Create all staff management permissions
     */
    private function createStaffPermissions(): void
    {
        $staffPermissions = [
            // Zone Management Permissions
            'create-zone', 'edit-zone', 'delete-zone', 'approve-zone', 'reject-zone', 'view-zones',
            
            // District Management Permissions
            'create-district', 'edit-district', 'delete-district', 'approve-district', 'reject-district', 'view-districts',
            
            // Paypoint Management Permissions
            'create-paypoint', 'edit-paypoint', 'delete-paypoint', 'approve-paypoint', 'reject-paypoint', 'view-paypoints',
            
            // Staff Management Permissions
            'create-staff', 'edit-staff', 'delete-staff', 'approve-staff', 'reject-staff', 'view-staff',
            'assign-staff-role', 'revoke-staff-role', 'manage-staff-permissions',
            
            // Location Management Permissions
            'manage-district-wards', 'view-location-details',
        ];
        
        // Add staff management permissions to the existing permissions
        foreach ($staffPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'staff'
            ], ['status' => 'approved']);
        }
    }
    
    /**
     * Update roles with appropriate permissions
     */
    private function updateRolePermissions(): void
    {
        $superAdmin = Role::where('name', 'super-admin')->where('guard_name', 'staff')->first();
        if ($superAdmin) {
            // Give super admin all staff management permissions
            $allStaffPermissions = [
                'create-zone', 'edit-zone', 'delete-zone', 'approve-zone', 'reject-zone', 'view-zones',
                'create-district', 'edit-district', 'delete-district', 'approve-district', 'reject-district', 'view-districts',
                'create-paypoint', 'edit-paypoint', 'delete-paypoint', 'approve-paypoint', 'reject-paypoint', 'view-paypoints',
                'create-staff', 'edit-staff', 'delete-staff', 'approve-staff', 'reject-staff', 'view-staff',
                'assign-staff-role', 'revoke-staff-role', 'manage-staff-permissions',
                'manage-district-wards', 'view-location-details',
            ];
            
            $superAdmin->givePermissionTo($allStaffPermissions);
        }
        
        $manager = Role::where('name', 'manager')->where('guard_name', 'staff')->first();
        if ($manager) {
            // Give manager appropriate staff management permissions
            $managerPermissions = [
                'create-staff', 'edit-staff', 'delete-staff', 'view-staff',
                'view-zones', 'view-districts', 'view-paypoints',
                'manage-district-wards', 'view-location-details',
            ];
            
            $manager->givePermissionTo($managerPermissions);
        }
        
        $staffRole = Role::where('name', 'staff')->where('guard_name', 'staff')->first();
        if ($staffRole) {
            // Give regular staff basic permissions
            $basicStaffPermissions = [
                'view-staff', 'view-zones', 'view-districts', 'view-paypoints',
                'view-location-details',
            ];
            
            $staffRole->givePermissionTo($basicStaffPermissions);
        }
    }
    
    /**
     * Create sample staff members
     */
    private function createStaffMembers(): void
    {
        $staffMembers = [
            [
                'staff_id' => 'ADMIN001',
                'first_name' => 'Super',
                'middle_name' => '',
                'surname' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'mobile_no' => '1234567890',
                'phone_number' => '1234567890',
                'status' => 'approved',
                'employment_status' => 'active',
                'date_of_birth' => '1980-01-01',
                'gender' => 'male',
                'date_of_first_appointment' => '2020-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'staff_id' => 'MGR001',
                'first_name' => 'Manager',
                'middle_name' => '',
                'surname' => 'User',
                'email' => 'manager@example.com',
                'password' => Hash::make('password123'),
                'mobile_no' => '1234567891',
                'phone_number' => '1234567891',
                'status' => 'approved',
                'employment_status' => 'active',
                'date_of_birth' => '1985-05-15',
                'gender' => 'female',
                'date_of_first_appointment' => '2021-05-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'staff_id' => 'STF001',
                'first_name' => 'Regular',
                'middle_name' => '',
                'surname' => 'Staff',
                'email' => 'staff@example.com',
                'password' => Hash::make('password123'),
                'mobile_no' => '1234567892',
                'phone_number' => '1234567892',
                'status' => 'approved',
                'employment_status' => 'active',
                'date_of_birth' => '1990-10-20',
                'gender' => 'male',
                'date_of_first_appointment' => '2022-10-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Insert or update staff members
        foreach ($staffMembers as $staffData) {
            // Check if staff member already exists
            $existingStaff = Staff::where('email', $staffData['email'])->first();
            
            if ($existingStaff) {
                // Update existing staff member
                $existingStaff->update($staffData);
                $staff = $existingStaff;
            } else {
                // Create new staff member
                $staff = Staff::create($staffData);
            }
            
            // Assign roles based on email
            $staff->syncRoles([]); // Clear existing roles
            
            if ($staffData['email'] === 'admin@example.com') {
                $staff->assignRole('super-admin');
            } elseif ($staffData['email'] === 'manager@example.com') {
                $staff->assignRole('manager');
            } else {
                $staff->assignRole('staff');
            }
        }
    }
}