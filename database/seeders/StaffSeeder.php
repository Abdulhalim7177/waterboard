<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first location records
        $lga = Lga::first();
        $ward = Ward::first();
        $area = Area::first();
        
        Staff::create([
            'staff_id' => 'STAFF001',
            'first_name' => 'Test',
            'middle_name' => 'Admin',
            'surname' => 'User',
            'email' => 'staff@example.com',
            'password' => Hash::make('password123'),
            'mobile_no' => '1234567890',
            'phone_number' => '1234567890',
            'lga_id' => $lga->id,
            'ward_id' => $ward->id,
            'area_id' => $area->id,
            'status' => 'approved',
            'employment_status' => 'active',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'date_of_first_appointment' => '2020-01-01',
        ]);
    }
}