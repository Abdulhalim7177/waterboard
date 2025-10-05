<?php

namespace Database\Factories;

use App\Models\PendingCustomerUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendingCustomerUpdateFactory extends Factory
{
    protected $model = PendingCustomerUpdate::class;

    public function definition()
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'updated_by' => \App\Models\Staff::factory(),
            'field_name' => $this->faker->randomElement(['first_name', 'surname', 'email', 'phone_number', 'address', 'status']),
            'old_value' => $this->faker->text(100),
            'new_value' => $this->faker->text(100),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'reason' => $this->faker->optional()->sentence(),
        ];
    }
}