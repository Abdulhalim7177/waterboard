<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StaffExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Staff::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Staff ID',
            'First Name',
            'Surname',
            'Middle Name',
            'Gender',
            'Date of Birth',
            'State of Origin',
            'LGA',
            'Ward',
            'Nationality',
            'NIN',
            'Mobile No',
            'Email',
            'Address',
            'Date of First Appointment',
            'Rank',
            'Staff No',
            'Department',
            'Expected Next Promotion',
            'Expected Retirement Date',
            'Status',
            'Highest Qualifications',
            'Grade Level Limit',
            'Appointment Type',
            'Years of Service',
        ];
    }

    /**
     * @param Staff $staff
     * @return array
     */
    public function map($staff): array
    {
        return [
            $staff->staff_id,
            $staff->first_name,
            $staff->surname,
            $staff->middle_name,
            $staff->gender,
            $staff->date_of_birth,
            $staff->state_of_origin,
            $staff->lga_id,
            $staff->ward_id,
            $staff->nationality,
            $staff->nin,
            $staff->mobile_no,
            $staff->email,
            $staff->address,
            $staff->date_of_first_appointment,
            $staff->rank,
            $staff->staff_no,
            $staff->department,
            $staff->expected_next_promotion,
            $staff->expected_retirement_date,
            $staff->status,
            $staff->highest_qualifications,
            $staff->grade_level_limit,
            $staff->appointment_type,
            $staff->years_of_service,
        ];
    }
}
