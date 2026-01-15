<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConnectionTariffsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tariffs')->insert([
            // Private Water Connections
            [
                'name' => 'Private Water Connection - 12.5mm (1/2 inch)',
                'amount' => 5100,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Private Water Connection - 20mm (3/4 inch)',
                'amount' => 7800,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Private Water Connection - 25mm (1 inch)',
                'amount' => 10000,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Private Water Connection - 37mm (1 1/2 inch)',
                'amount' => 123550,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Private Water Connection - 50mm (2 inch)',
                'amount' => 125905,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Commercial Water Connections
            [
                'name' => 'Commercial Water Connection - 20mm (3/4 inch)',
                'amount' => 45000,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Commercial Water Connection - 25mm (1 inch)',
                'amount' => 65000,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Commercial Water Connection - 37mm (1 1/4 inch)',
                'amount' => 95000,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Commercial Water Connection - 37mm (1 1/2 inch)',
                'amount' => 145000,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Commercial Water Connection - 50mm (2 inch)',
                'amount' => 250000,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Other Fees
            [
                'name' => 'Legalisation',
                'amount' => 10000,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reconnection Fee',
                'amount' => 2000,
                'type' => 'service',
                'billing_type' => 'fixed',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
