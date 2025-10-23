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
            'tariff_id' => \App\Models\Tariff::factory(),

            'amount' => $this->faker->randomFloat(2, 5000, 50000),
            'year_month' => $this->faker->date('ym'),
            'billing_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'due_date' => $this->faker->dateTimeBetween('+1 month', '+3 months'),
            'status' => $this->faker->randomElement(['pending', 'overdue', 'paid']),
            'balance' => $this->faker->randomFloat(2, 0, 50000),
            'approval_status' => 'approved',
        ];
    }
}