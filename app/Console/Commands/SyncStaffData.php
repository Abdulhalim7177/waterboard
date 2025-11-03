<?php

namespace App\Console\Commands;

use App\Models\Staff;
use App\Models\StaffBank;
use App\Models\NextOfKin;
use App\Services\HrmService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SyncStaffData extends Command
{
    protected $signature = 'app:sync-staff-data {--refresh : Completely refresh all staff data from HRM}';
    protected $description = 'Synchronize staff data from the HRM system';

    public function handle(HrmService $hrmService)
    {
        $this->info('Synchronizing staff data...');
        
        $refresh = $this->option('refresh');
        
        if ($refresh) {
            $this->info('Performing complete refresh of staff data...');
        }

        $employees = $hrmService->getEmployees();

        if (!$employees || !isset($employees['data'])) {
            $this->error('Failed to fetch employees from the HRM system.');
            return Command::FAILURE;
        }

        $hrmStaffIds = collect($employees['data'])->pluck('employee_id')->toArray();
        $affectedStaff = [];
        $newStaff = [];
        $updatedStaff = [];

        if ($refresh) {
            // Completely refresh the data: delete all local staff not present in HRM
            $this->info('Removing staff not present in HRM...');
            $filteredHrmStaffIds = array_filter($hrmStaffIds, function($id) {
                return $id !== null && $id !== '';
            });
            $filteredHrmStaffIds = array_map('strval', $filteredHrmStaffIds); // Ensure all IDs are treated as strings
            $deletedCount = Staff::whereNotIn('staff_id', $filteredHrmStaffIds)->delete();
            $this->info("Deleted {$deletedCount} staff members not present in HRM system.");
        }

        foreach ($employees['data'] as $employeeData) {
            // Create or update staff member
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
                    'department_id' => $this->getDepartmentId($employeeData['department']['id'] ?? null),
                    'rank_id' => $this->getRankId($employeeData['rank_id'] ?? null),
                    'cadre_id' => $this->getCadreId($employeeData['cadre_id'] ?? null), 
                    'grade_level_id' => $this->getGradeLevelId($employeeData['grade_level_id'] ?? null),
                    'step_id' => $this->getStepId($employeeData['step_id'] ?? null),
                    'appointment_type_id' => $this->getAppointmentTypeId($employeeData['appointment_type_id'] ?? null),
                    'status' => $this->mapStatus($employeeData['status'] ?? null),
                    'employment_status' => $employeeData['status'] ?? null,
                    'highest_qualifications' => $employeeData['highest_certificate'] ?? null,
                    'photo_path' => $employeeData['photo'] ?? null,
                    'password' => Hash::make($employeeData['staff_id'] ?? Str::random(10)),
                ]
            );

            if ($staff->wasRecentlyCreated) {
                $staff->assignRole('staff');
                $newStaff[] = $staff;
            } else {
                $updatedStaff[] = $staff;
            }

            $affectedStaff[] = $staff;
            
            // Update related information if available in HRM data
            if (isset($employeeData['bank'])) {
                $staff->bank()->updateOrCreate([], $employeeData['bank']);
            }
            
            if (isset($employeeData['next_of_kin'])) {
                $staff->nextOfKin()->updateOrCreate([], $employeeData['next_of_kin']);
            }
        }

        $this->info('Staff data synchronized successfully.');
        $this->info("New staff: " . count($newStaff) . ", Updated staff: " . count($updatedStaff) . ", Total affected: " . count($affectedStaff));
        
        if ($this->output->isVerbose()) {
            $this->table(['ID', 'Name', 'Email', 'Status'], collect($affectedStaff)->map(function ($staff) {
                return [
                    'id' => $staff->staff_id,
                    'name' => $staff->first_name . ' ' . $staff->surname,
                    'email' => $staff->email,
                    'status' => $staff->wasRecentlyCreated ? 'New' : 'Updated'
                ];
            }));
        }

        return Command::SUCCESS;
    }
    
    /**
     * Get department ID from department name
     */
    private function getDepartmentId($hrmDeptId) 
    {
        if (!$hrmDeptId) return null;
        
        // You may need to adjust this based on how department IDs are managed between systems
        $department = \App\Models\Department::where('id', $hrmDeptId)->first();
        return $department ? $department->id : null;
    }
    
    /**
     * Get rank ID from rank name
     */
    private function getRankId($hrmRankId) 
    {
        if (!$hrmRankId) return null;
        
        $rank = \App\Models\Rank::where('id', $hrmRankId)->first();
        return $rank ? $rank->id : null;
    }
    
    /**
     * Get cadre ID from cadre name
     */
    private function getCadreId($hrmCadreId) 
    {
        if (!$hrmCadreId) return null;
        
        $cadre = \App\Models\Cadre::where('id', $hrmCadreId)->first();
        return $cadre ? $cadre->id : null;
    }
    
    /**
     * Get grade level ID from grade level name
     */
    private function getGradeLevelId($hrmGradeLevelId) 
    {
        if (!$hrmGradeLevelId) return null;
        
        $gradeLevel = \App\Models\GradeLevel::where('id', $hrmGradeLevelId)->first();
        return $gradeLevel ? $gradeLevel->id : null;
    }
    
    /**
     * Get step ID from step name
     */
    private function getStepId($hrmStepId) 
    {
        if (!$hrmStepId) return null;
        
        $step = \App\Models\Step::where('id', $hrmStepId)->first();
        return $step ? $step->id : null;
    }
    
    /**
     * Get appointment type ID from appointment type name
     */
    private function getAppointmentTypeId($hrmAppointmentTypeId) 
    {
        if (!$hrmAppointmentTypeId) return null;
        
        $appointmentType = \App\Models\AppointmentType::where('id', $hrmAppointmentTypeId)->first();
        return $appointmentType ? $appointmentType->id : null;
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
