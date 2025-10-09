<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition()
    {
        return [
            'staff_id' => $this->faker->unique()->randomNumber(6),
            'first_name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'middle_name' => $this->faker->firstName(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->date('Y-m-d', '-20 years'),
            'state_of_origin' => 'Katsina',
            'lga_id' => \App\Models\Lga::factory(),
            'ward_id' => \App\Models\Ward::factory(),
            'area_id' => \App\Models\Area::factory(),
            'zone_id' => \App\Models\Zone::factory(),
            'district_id' => \App\Models\District::factory(),
            'paypoint_id' => \App\Models\Paypoint::factory(),
            'nationality' => 'Nigerian',
            'nin' => $this->faker->randomNumber(11, true),
            'mobile_no' => $this->faker->phoneNumber(),
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'password' => Hash::make('password'),
            'date_of_first_appointment' => $this->faker->date('Y-m-d', '-10 years'),
            'rank' => $this->faker->randomElement(['Level 8', 'Level 10', 'Level 12', 'Level 13', 'Level 15', 'Level 17']),
            'staff_no' => $this->faker->unique()->randomNumber(7),
            'department' => $this->faker->randomElement(['Operations', 'Finance', 'Customer Service', 'Technical', 'Logistics', 'Management']),
            'expected_next_promotion' => $this->faker->date('Y-m-d', '+5 years'),
            'expected_retirement_date' => $this->faker->date('Y-m-d', '+30 years'),
            'status' => $this->faker->randomElement(['active', 'inactive', 'suspended']),
            'employment_status' => $this->faker->randomElement(['Permanent', 'Contract', 'Probation']),
            'highest_qualifications' => $this->faker->randomElement(['BSc', 'MSc', 'PhD', 'OND', 'HND', 'Certificate']),
            'grade_level_limit' => $this->faker->randomElement(['GL.08', 'GL.10', 'GL.12', 'GL.13', 'GL.15', 'GL.17']),
            'appointment_type' => $this->faker->randomElement(['Direct Entry', 'Indirect Entry']),
            'photo_path' => null,
            'years_of_service' => $this->faker->numberBetween(1, 35),
        ];
    }
}