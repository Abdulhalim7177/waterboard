<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing districts
        DB::table('districts')->delete();
        
        // Get all zones
        $zones = Zone::all();
        
        // Sample districts data
        $districts = [
            [
                'code' => 'DT001',
                'name' => 'Northern District',
                'zone_id' => $zones->firstWhere('code', 'ZN001')->id ?? $zones->first()->id,
                'status' => 'approved'
            ],
            [
                'code' => 'DT002',
                'name' => 'Southern District',
                'zone_id' => $zones->firstWhere('code', 'ZN002')->id ?? $zones->first()->id,
                'status' => 'approved'
            ],
            [
                'code' => 'DT003',
                'name' => 'Central District',
                'zone_id' => $zones->firstWhere('code', 'ZN003')->id ?? $zones->first()->id,
                'status' => 'approved'
            ]
        ];
        
        // Insert districts
        foreach ($districts as $districtData) {
            District::create($districtData);
        }
        
        $this->command->info('Districts seeded successfully!');
    }
}