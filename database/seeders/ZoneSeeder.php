<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing zones
        DB::table('zones')->delete();
        
        // Sample zones data
        $zones = [
            [
                'code' => 'ZN001',
                'name' => 'Northern Zone',
                'status' => 'approved'
            ],
            [
                'code' => 'ZN002',
                'name' => 'Southern Zone',
                'status' => 'approved'
            ],
            [
                'code' => 'ZN003',
                'name' => 'Central Zone',
                'status' => 'approved'
            ]
        ];
        
        // Insert zones
        foreach ($zones as $zoneData) {
            Zone::create($zoneData);
        }
        
        $this->command->info('Zones seeded successfully!');
    }
}