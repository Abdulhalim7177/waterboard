<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'bill_id' => \App\Models\Bill::factory(),
            'payer_ref_no' => $this->faker->unique()->uuid(),
            'bill_ids' => implode(',', $this->faker->randomElements(\App\Models\Bill::pluck('id')->toArray(), $this->faker->numberBetween(1, 5))),
            'amount' => $this->faker->randomFloat(2, 1000, 50000),
            'payment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'method' => $this->faker->randomElement(['NABRoll', 'Account Balance']),
            'status' => $this->faker->randomElement(['successful', 'pending', 'failed']),
            'transaction_ref' => $this->faker->uuid(),
            'payment_code' => 'PC_' . $this->faker->unique()->randomNumber(8),
            'payment_status' => $this->faker->randomElement(['SUCCESSFUL', 'FAILED', 'PENDING']),
            'channel' => $this->faker->randomElement(['web', 'mobile', 'bank', 'cashier']),
            'balance' => $this->faker->randomFloat(2, 0, 50000),
            'approval_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}