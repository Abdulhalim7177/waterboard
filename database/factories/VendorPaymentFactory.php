<?php

namespace Database\Factories;

use App\Models\VendorPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorPaymentFactory extends Factory
{
    protected $model = VendorPayment::class;

    public function definition()
    {
        return [
            'vendor_id' => \App\Models\Vendor::factory(),
            'amount' => $this->faker->randomFloat(2, 1000, 100000),
            'payment_method' => $this->faker->randomElement(['bank_transfer', 'cash', 'check', 'online']),
            'transaction_ref' => $this->faker->uuid(),
            'payment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'cancelled']),
            'description' => $this->faker->sentence(),
            'transaction_type' => $this->faker->randomElement(['credit', 'debit']),
        ];
    }
}