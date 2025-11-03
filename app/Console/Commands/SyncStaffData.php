<?php

namespace App\Console\Commands;

use App\Models\Staff;
use App\Services\HrmService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SyncStaffData extends Command
{
    protected $signature = 'app:sync-staff-data';
    protected $description = 'Synchronize staff data from the HRM system';

    public function handle(HrmService $hrmService)
    {
        $this->info('Synchronizing staff data...');

        $employees = $hrmService->getEmployees();

        if (!$employees || !isset($employees['data'])) {
            $this->error('Failed to fetch employees from the HRM system.');
            return;
        }

        $affectedStaff = [];

        foreach ($employees['data'] as $employeeData) {
            $staff = Staff::updateOrCreate(
                ['staff_id' => $employeeData['employee_id']],
                [
                    'first_name' => $employeeData['first_name'],
                    'surname' => $employeeData['surname'],
                    'middle_name' => $employeeData['middle_name'] ?? null,
                    'gender' => $employeeData['gender'] ?? null,
                    'date_of_birth' => $employeeData['date_of_birth'] ?? null,
                    'state_of_origin' => $employeeData['state_id'] ?? null, // Assuming state_id maps to state_of_origin
                    'lga_id' => \App\Models\Lga::find($employeeData['lga_id']) ? $employeeData['lga_id'] : null,
                    'ward_id' => \App\Models\Ward::find($employeeData['ward_id']) ? $employeeData['ward_id'] : null,
                    'nationality' => $employeeData['nationality'] ?? null,
                    'nin' => $employeeData['nin'] ?? null,
                    'mobile_no' => $employeeData['mobile_no'] ?? null,
                    'email' => $employeeData['email'] ?? null,
                    'address' => $employeeData['address'] ?? null,
                    'date_of_first_appointment' => $employeeData['date_of_first_appointment'] ?? null,
                    'staff_no' => $employeeData['reg_no'] ?? null, // Assuming reg_no maps to staff_no
                    'department' => $employeeData['department']['name'] ?? null, // Assuming department is an object with a name property
                    'status' => $this->mapStatus($employeeData['status'] ?? null),
                    'employment_status' => $employeeData['status'] ?? null,
                    'highest_qualifications' => $employeeData['highest_certificate'] ?? null,
                    'appointment_type' => $employeeData['appointment_type_id'] ?? null, // Assuming appointment_type_id maps to appointment_type
                    'photo_path' => $employeeData['photo'] ?? null,
                    'password' => Hash::make($employeeData['staff_id'] ?? Str::random(10)),
                ]
            );

            if ($staff->wasRecentlyCreated) {
                $staff->assignRole('staff');
            }

            $affectedStaff[] = $staff;
        }

        $this->info('Staff data synchronized successfully.');
        $this->table(['ID', 'Name', 'Email'], collect($affectedStaff)->map(function ($staff) {
            return ['id' => $staff->id, 'name' => $staff->first_name . ' ' . $staff->surname, 'email' => $staff->email];
        }));

        return $affectedStaff;
    }

    protected function mapStatus($status)
    {
        switch (strtolower($status)) {
            case 'active':
                return 'approved';
            case 'inactive':
            case 'suspended':
            case 'terminated':
                return 'rejected';
            default:
                return 'pending';
        }
    }
}
