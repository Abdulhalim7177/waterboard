<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Vendor;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create first vendor
        Vendor::updateOrCreate(
            ['email' => 'vendor@example.com'],
            [
                'name' => 'Test Vendor',
                'password' => Hash::make('password'),
                'approved' => true,
                'street_name' => '123 Main St',
                'vendor_code' => 'VC1234567890',
                'lga_id' => Lga::inRandomOrder()->first()->id,
                'ward_id' => Ward::inRandomOrder()->first()->id,
                'area_id' => Area::inRandomOrder()->first()->id,
            ]
        );

        // Create second vendor
        Vendor::updateOrCreate(
            ['email' => 'demo.vendor@example.com'],
            [
                'name' => 'Demo Vendor',
                'password' => Hash::make('password'),
                'approved' => true,
                'street_name' => '456 Oak Ave',
                'vendor_code' => 'VC0987654321',
                'lga_id' => Lga::inRandomOrder()->first()->id,
                'ward_id' => Ward::inRandomOrder()->first()->id,
                'area_id' => Area::inRandomOrder()->first()->id,
            ]
        );
    }
}
