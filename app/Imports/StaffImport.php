<?php

namespace App\Imports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StaffImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Check if staff with this staff_id already exists
        $existingStaff = Staff::where('staff_id', $row['staff_id'])->first();
        
        // Process date fields
        $dateOfBirth = null;
        if (!empty($row['date_of_birth'])) {
            $dateOfBirth = $this->parseDate($row['date_of_birth']);
        }
        
        $dateOfFirstAppointment = null;
        if (!empty($row['date_of_first_appointment'])) {
            $dateOfFirstAppointment = $this->parseDate($row['date_of_first_appointment']);
        }
        
        $expectedNextPromotion = null;
        if (!empty($row['expected_next_promotion'])) {
            $expectedNextPromotion = $this->parseDate($row['expected_next_promotion']);
        }
        
        $expectedRetirementDate = null;
        if (!empty($row['expected_retirement_date'])) {
            $expectedRetirementDate = $this->parseDate($row['expected_retirement_date']);
        }
        
        if ($existingStaff) {
            // Update existing staff
            $existingStaff->update([
                'first_name' => $row['first_name'] ?? null,
                'surname' => $row['surname'] ?? null,
                'middle_name' => $row['middle_name'] ?? null,
                'gender' => $row['gender'] ?? null,
                'date_of_birth' => $dateOfBirth,
                'state_of_origin' => $row['state_of_origin'] ?? null,
                'lga_id' => $row['lga'] ?? null,
                'ward_id' => $row['ward'] ?? null,
                'nationality' => $row['nationality'] ?? null,
                'nin' => $row['nin'] ?? null,
                'mobile_no' => $row['mobile_no'] ?? null,
                'email' => $row['email'] ?? null,
                'address' => $row['address'] ?? null,
                'date_of_first_appointment' => $dateOfFirstAppointment,
                'rank' => $row['rank'] ?? null,
                'staff_no' => $row['staff_no'] ?? null,
                'department' => $row['department'] ?? null,
                'expected_next_promotion' => $expectedNextPromotion,
                'expected_retirement_date' => $expectedRetirementDate,
                'status' => $row['status'] ?? 'active',
                'highest_qualifications' => $row['highest_qualifications'] ?? null,
                'grade_level_limit' => $row['grade_level_limit'] ?? null,
                'appointment_type' => $row['appointment_type'] ?? null,
                'years_of_service' => $row['years_of_service'] ?? null,
            ]);
            
            return null; // Don't create new model
        }
        
        // Create new staff
        return new Staff([
            'staff_id' => $row['staff_id'],
            'first_name' => $row['first_name'] ?? null,
            'surname' => $row['surname'] ?? null,
            'middle_name' => $row['middle_name'] ?? null,
            'gender' => $row['gender'] ?? null,
            'date_of_birth' => $dateOfBirth,
            'state_of_origin' => $row['state_of_origin'] ?? null,
            'lga_id' => $row['lga'] ?? null,
            'ward_id' => $row['ward'] ?? null,
            'nationality' => $row['nationality'] ?? null,
            'nin' => $row['nin'] ?? null,
            'mobile_no' => $row['mobile_no'] ?? null,
            'email' => $row['email'] ?? null,
            'address' => $row['address'] ?? null,
            'password' => Hash::make('password'), // Default password
            'date_of_first_appointment' => $dateOfFirstAppointment,
            'rank' => $row['rank'] ?? null,
            'staff_no' => $row['staff_no'] ?? null,
            'department' => $row['department'] ?? null,
            'expected_next_promotion' => $expectedNextPromotion,
            'expected_retirement_date' => $expectedRetirementDate,
            'status' => $row['status'] ?? 'active',
            'highest_qualifications' => $row['highest_qualifications'] ?? null,
            'grade_level_limit' => $row['grade_level_limit'] ?? null,
            'appointment_type' => $row['appointment_type'] ?? null,
            'years_of_service' => $row['years_of_service'] ?? null,
        ]);
    }
    
    /**
     * Parse date from various formats
     */
    private function parseDate($date)
    {
        if (is_numeric($date)) {
            // Assume it's a timestamp
            return Carbon::createFromTimestamp($date);
        }
        
        // Try to parse as a string date
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'staff_id' => 'required',
            'first_name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'mobile_no' => 'required',
            'date_of_birth' => 'required',
            'date_of_first_appointment' => 'required',
        ];
    }
}
