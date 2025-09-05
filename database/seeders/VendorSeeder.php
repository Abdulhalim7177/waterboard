<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Vendor;

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
                'password' => Hash::make('password123'),
                'approved' => true,
            ]
        );

        // Create second vendor
        Vendor::updateOrCreate(
            ['email' => 'demo.vendor@example.com'],
            [
                'name' => 'Demo Vendor',
                'password' => Hash::make('password123'),
                'approved' => true,
            ]
        );
    }
}
