<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        $tariff = \App\Models\Tariff::factory()->create();
        return [
            'first_name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'password' => Hash::make('password'),
            'area_id' => \App\Models\Area::factory(),
            'lga_id' => \App\Models\Lga::factory(),
            'ward_id' => \App\Models\Ward::factory(),
            'category_id' => $tariff->category_id,
            'tariff_id' => $tariff->id,
        ];
    }
}