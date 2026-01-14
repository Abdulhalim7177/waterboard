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
        $industrial = Category::where('code', 'IC')->first();
        $public = Category::where('code', 'PI')->first();
        $royalties = Category::where('code', 'RY')->first();
        $rawWater = Category::where('code', 'RW')->first();
        $waterTanker = Category::where('code', 'WT')->first();

        $tariffs = [
            // DOMESTIC CONSUMERS
            [
                'name' => 'House with single tap',
                'category_id' => $domestic->id,
                'amount' => 1650,
            ],
            [
                'name' => 'House with internal water system',
                'category_id' => $domestic->id,
                'amount' => 3536,
            ],
            [
                'name' => 'House with integral water & garden',
                'category_id' => $domestic->id,
                'amount' => 4199,
            ],

            // INDUSTRIAL/COMMERCIALS
            [
                'name' => 'Domestic with floor',
                'category_id' => $industrial->id,
                'amount' => 1650,
            ],
            [
                'name' => 'Commercial with floor',
                'category_id' => $industrial->id,
                'amount' => 3536,
            ],
            [
                'name' => 'Commercial',
                'category_id' => $industrial->id,
                'amount' => 4199,
            ],
            [
                'name' => 'Industrial with floor',
                'category_id' => $industrial->id,
                'amount' => 0, // Amount not specified, setting to 0
            ],
            [
                'name' => 'Standing PAIPE (Public) PSP',
                'category_id' => $industrial->id,
                'amount' => 0, // Amount not specified, setting to 0
            ],

            // PUBLIC INSTITUTIONS
            [
                'name' => 'Federal Government',
                'category_id' => $public->id,
                'amount' => 300,
            ],
            [
                'name' => 'State Government',
                'category_id' => $public->id,
                'amount' => 350,
            ],
            [
                'name' => 'Local Government',
                'category_id' => $public->id,
                'amount' => 450,
            ],

            // ROYALTIES
            [
                'name' => 'Company/Commercial venture borehole',
                'category_id' => $royalties->id,
                'amount' => 5000.0,
            ],
            [
                'name' => 'Demand charge on industrial & construction site',
                'category_id' => $royalties->id,
                'amount' => 10000,
            ],
            [
                'name' => 'NAFDAC registered pure water producer',
                'category_id' => $royalties->id,
                'amount' => 10000,
            ],

            // RAW WATER
            [
                'name' => 'Raw Water',
                'category_id' => $rawWater->id,
                'amount' => 250,
            ],

            // WATER TANKER
            [
                'name' => '4,500 lifter capacity/trip at lifting point',
                'category_id' => $waterTanker->id,
                'amount' => 320,
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
