<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = Customer::all();

        $customers->each(function ($customer) {
            if (is_null($customer->billing_id)) {
                return;
            }

            Bill::factory()->count(5)->state(function (array $attributes) use ($customer) {
                return [
                    'customer_id' => $customer->id,
                    'tariff_id' => $customer->tariff_id,
                    'billing_id' => $customer->billing_id,
                ];
            })->create()->each(function ($bill) {
                if (rand(0, 1)) {
                    Payment::factory()->create([
                        'customer_id' => $bill->customer_id,
                        'bill_id' => $bill->id,
                        'bill_ids' => (string) $bill->id,
                    ]);
                }
            });
        });
    }
}
