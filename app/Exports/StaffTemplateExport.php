<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffTemplateExport implements FromArray, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return [
            // Sample data
            [
                'STF001',           // staff_id
                'John',             // first_name
                'Doe',              // surname
                'Michael',          // middle_name
                'male',             // gender
                '1985-05-15',       // date_of_birth
                'Katsina',          // state_of_origin
                '1',                // lga
                '1',                // ward
                'Nigerian',         // nationality
                '12345678901',      // nin
                '08012345678',      // mobile_no
                'john.doe@example.com', // email
                '123 Main St, Katsina', // address
                '2010-01-15',       // date_of_first_appointment
                'Senior Officer',    // rank
                'STF001',           // staff_no
                'Human Resources',  // department
                '2025-01-15',       // expected_next_promotion
                '2045-05-15',       // expected_retirement_date
                'active',           // status
                'B.Sc Computer Science', // highest_qualifications
                '12',               // grade_level_limit
                'Permanent',        // appointment_type
                '14',               // years_of_service
            ]
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'staff_id',
            'first_name',
            'surname',
            'middle_name',
            'gender',
            'date_of_birth',
            'state_of_origin',
            'lga',
            'ward',
            'nationality',
            'nin',
            'mobile_no',
            'email',
            'address',
            'date_of_first_appointment',
            'rank',
            'staff_no',
            'department',
            'expected_next_promotion',
            'expected_retirement_date',
            'status',
            'highest_qualifications',
            'grade_level_limit',
            'appointment_type',
            'years_of_service',
        ];
    }
}