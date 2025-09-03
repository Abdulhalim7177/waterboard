<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample LGA
        $lga = Lga::create([
            'name' => 'Test LGA',
            'code' => 'TLGA001',
        ]);

        // Create a sample Ward
        $ward = Ward::create([
            'name' => 'Test Ward',
            'lga_id' => $lga->id,
            'code' => 'TWARD001',
        ]);

        // Create a sample Area
        $area = Area::create([
            'name' => 'Test Area',
            'ward_id' => $ward->id,
            'code' => 'TAREA001',
        ]);
    }
}