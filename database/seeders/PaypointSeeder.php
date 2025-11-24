<?php

namespace Database\Seeders;

use App\Models\Paypoint;
use App\Models\Zone;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaypointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing paypoints
        DB::table('paypoints')->delete();
        
        // Get zones and districts
        $zones = Zone::all();
        $districts = District::all();
        
        // Sample paypoints data (mix of zone and district types)
        $paypoints = [
            [
                'code' => 'PP001',
                'name' => 'Zone Paypoint 1',
                'type' => 'zone',
                'zone_id' => $zones->firstWhere('code', 'ZN001')->id ?? $zones->first()->id,
                'district_id' => null,
                'description' => 'Paypoint for Northern Zone',
                'status' => 'approved'
            ],
            [
                'code' => 'PP002',
                'name' => 'District Paypoint 1',
                'type' => 'district',
                'zone_id' => null,
                'district_id' => $districts->firstWhere('code', 'DT001')->id ?? $districts->first()->id,
                'description' => 'Paypoint for Northern District',
                'status' => 'approved'
            ],
            [
                'code' => 'PP003',
                'name' => 'Zone Paypoint 2',
                'type' => 'zone',
                'zone_id' => $zones->firstWhere('code', 'ZN002')->id ?? $zones->get(1)->id,
                'district_id' => null,
                'description' => 'Paypoint for Southern Zone',
                'status' => 'approved'
            ]
        ];
        
        // Insert paypoints
        foreach ($paypoints as $paypointData) {
            Paypoint::create($paypointData);
        }
        
        $this->command->info('Paypoints seeded successfully!');
    }
}