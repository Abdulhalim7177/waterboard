<?php

namespace Database\Factories;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    protected $model = Bill::class;

    public function definition()
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'bill_number' => $this->faker->unique()->randomNumber(8),
            'bill_amount' => $this->faker->randomFloat(2, 5000, 50000),
            'balance' => $this->faker->randomFloat(2, 0, 50000),
            'due_date' => $this->faker->dateTimeBetween('+1 month', '+3 months'),
            'issue_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'period_start' => $this->faker->dateTimeBetween('-2 months', '-1 month'),
            'period_end' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['pending', 'overdue', 'paid']),
            'approval_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'payment_status' => $this->faker->randomElement(['pending', 'partial', 'paid']),
            'payment_reference' => $this->faker->uuid(),
            'description' => $this->faker->sentence(),
            'meter_reading_start' => $this->faker->randomNumber(6),
            'meter_reading_end' => $this->faker->randomNumber(6),
            'consumption' => $this->faker->randomNumber(4),
        ];
    }
}