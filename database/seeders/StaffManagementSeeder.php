<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createStaffMembers();
        
        $this->command->info('Staff Management data seeded successfully!');
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
                'department_id' => 1,
                'rank_id' => 3,
                'cadre_id' => 1,
                'grade_level_id' => 3,
                'step_id' => 3,
                'appointment_type_id' => 1,
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
                'department_id' => 2,
                'rank_id' => 2,
                'cadre_id' => 2,
                'grade_level_id' => 2,
                'step_id' => 2,
                'appointment_type_id' => 1,
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
                'department_id' => 3,
                'rank_id' => 1,
                'cadre_id' => 3,
                'grade_level_id' => 1,
                'step_id' => 1,
                'appointment_type_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'staff_id' => 'STF002',
                'first_name' => 'Another',
                'middle_name' => '',
                'surname' => 'Staff',
                'email' => 'staff2@example.com',
                'password' => Hash::make('password123'),
                'mobile_no' => '1234567893',
                'phone_number' => '1234567893',
                'status' => 'approved',
                'employment_status' => 'active',
                'date_of_birth' => '1991-11-21',
                'gender' => 'female',
                'date_of_first_appointment' => '2023-11-21',
                'department_id' => 1,
                'rank_id' => 1,
                'cadre_id' => 1,
                'grade_level_id' => 1,
                'step_id' => 1,
                'appointment_type_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'staff_id' => 'STF003',
                'first_name' => 'Yet Another',
                'middle_name' => '',
                'surname' => 'Staff',
                'email' => 'staff3@example.com',
                'password' => Hash::make('password123'),
                'mobile_no' => '1234567894',
                'phone_number' => '1234567894',
                'status' => 'approved',
                'employment_status' => 'active',
                'date_of_birth' => '1992-12-22',
                'gender' => 'male',
                'date_of_first_appointment' => '2024-12-22',
                'department_id' => 2,
                'rank_id' => 2,
                'cadre_id' => 2,
                'grade_level_id' => 2,
                'step_id' => 2,
                'appointment_type_id' => 3,
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
