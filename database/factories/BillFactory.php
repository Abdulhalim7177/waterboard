<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\Tariff;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bill>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'tariff_id' => Tariff::factory(),
            'billing_id' => fake()->unique()->numerify('##########'),
            'amount' => fake()->randomFloat(2, 100, 10000),
            'due_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'year_month' => fake()->date('Ym'),
            'billing_date' => fake()->date(),
            'status' => fake()->randomElement(['pending', 'overdue', 'paid']),
            'balance' => fake()->randomFloat(2, 0, 10000),
            'approval_status' => 'approved',
        ];
    }
}
