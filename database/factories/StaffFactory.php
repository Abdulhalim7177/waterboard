<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition()
    {
        return [
            'staff_id' => 'STAFF' . Str::random(5),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->lastName(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // Default password
            'mobile_no' => $this->faker->phoneNumber(),
            'phone_number' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'date_of_first_appointment' => $this->faker->date(),
            'status' => 'approved',
            'employment_status' => $this->faker->randomElement(['active', 'inactive', 'on_leave', 'suspended', 'terminated']),
        ];
    }
}