<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tariff;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $domestic = Category::where('code', 'DC')->first();
        $private = Category::where('code', 'PWC')->first();
        $commercial = Category::where('code', 'CWC')->first();

        $tariffs = [
            // DOMESTIC CONSUMERS
            [
                'name' => 'House with single tap',
                'category_id' => $domestic->id,
                'amount' => 550,
            ],
            [
                'name' => 'House with internal water system',
                'category_id' => $domestic->id,
                'amount' => 1600,
            ],
            [
                'name' => 'House with integral water & garden',
                'category_id' => $domestic->id,
                'amount' => 1900,
            ],

            // PRIVATE WATER CONNECTIONS
            [
                'name' => '12.5mm (1/2 inch)',
                'category_id' => $private->id,
                'amount' => 5100,
            ],
            [
                'name' => '20mm (3/4 inch)',
                'category_id' => $private->id,
                'amount' => 7800,
            ],
            [
                'name' => '25mm (1 inch)',
                'category_id' => $private->id,
                'amount' => 10000,
            ],
            [
                'name' => '37mm (11/2 inch)',
                'category_id' => $private->id,
                'amount' => 123550,
            ],
            [
                'name' => '50mm (2 inch)',
                'category_id' => $private->id,
                'amount' => 125905,
            ],

            // COMMERCIAL WATER CONNECTIONS
            [
                'name' => '20mm (3/4 inch)',
                'category_id' => $commercial->id,
                'amount' => 45000,
            ],
            [
                'name' => '25mm (1 inch)',
                'category_id' => $commercial->id,
                'amount' => 65000,
            ],
            [
                'name' => '37mm (11/4 inch)',
                'category_id' => $commercial->id,
                'amount' => 95000,
            ],
            [
                'name' => '37mm (11/2 inch)',
                'category_id' => $commercial->id,
                'amount' => 145000,
            ],
            [
                'name' => '50mm (2 inch)',
                'category_id' => $commercial->id,
                'amount' => 250000,
            ],
        ];

        foreach ($tariffs as $index => $tariff) {
            Tariff::firstOrCreate(['catcode' => $tariff['category_id'] . str_pad($index + 1, 2, '0', STR_PAD_LEFT)], [
                'name' => $tariff['name'],
                'category_id' => $tariff['category_id'],
                'amount' => $tariff['amount'],
                'rate' => 0,
                'fixed_charge' => $tariff['amount'],
                'billing_type' => 'Flat',
                'description' => $tariff['name'],
                'status' => 'approved',
            ]);
        }
    }
}
