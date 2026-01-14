<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConnectionType;
use App\Models\ConnectionSize;
use App\Models\ConnectionFee;
use App\Models\CustomerConnection;
use App\Models\Customer;

class ConnectionManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create connection types
        $privateWaterConnection = ConnectionType::firstOrCreate([
            'slug' => 'private_water_connection',
        ], [
            'name' => 'Private Water Connection',
            'description' => 'Private water connection for domestic customers',
        ]);

        $commercialWaterConnection = ConnectionType::firstOrCreate([
            'slug' => 'commercial_water_connection',
        ], [
            'name' => 'Commercial Water Connection',
            'description' => 'Commercial water connection for business customers',
        ]);

        $legalisation = ConnectionType::firstOrCreate([
            'slug' => 'legalisation',
        ], [
            'name' => 'Legalisation',
            'description' => 'Legalisation fee for illegal connections',
        ]);

        $reconnectionFee = ConnectionType::firstOrCreate([
            'slug' => 'reconnection_fee',
        ], [
            'name' => 'Reconnection Fee',
            'description' => 'Fee for reconnecting disconnected service',
        ]);

        // Create connection sizes
        $size12mm = ConnectionSize::firstOrCreate([
            'size_mm' => '12.5',
            'size_inches' => '1/2',
        ], [
            'name' => '12.5mm (1/2 inch)',
        ]);

        $size20mm = ConnectionSize::firstOrCreate([
            'size_mm' => '20',
            'size_inches' => '3/4',
        ], [
            'name' => '20mm (3/4 inch)',
        ]);

        $size25mm = ConnectionSize::firstOrCreate([
            'size_mm' => '25',
            'size_inches' => '1',
        ], [
            'name' => '25mm (1 inch)',
        ]);

        $size37mm = ConnectionSize::firstOrCreate([
            'size_mm' => '37',
            'size_inches' => '1 1/2',
        ], [
            'name' => '37mm (1 1/2 inch)',
        ]);

        $size50mm = ConnectionSize::firstOrCreate([
            'size_mm' => '50',
            'size_inches' => '2',
        ], [
            'name' => '50mm (2 inch)',
        ]);

        // Create connection fees for private water connections
        ConnectionFee::firstOrCreate([
            'connection_type_id' => $privateWaterConnection->id,
            'connection_size_id' => $size12mm->id,
        ], [
            'fee_amount' => 5100.00,
        ]);

        ConnectionFee::firstOrCreate([
            'connection_type_id' => $privateWaterConnection->id,
            'connection_size_id' => $size20mm->id,
        ], [
            'fee_amount' => 7800.00,
        ]);

        ConnectionFee::firstOrCreate([
            'connection_type_id' => $privateWaterConnection->id,
            'connection_size_id' => $size25mm->id,
        ], [
            'fee_amount' => 10000.00,
        ]);

        ConnectionFee::firstOrCreate([
            'connection_type_id' => $privateWaterConnection->id,
            'connection_size_id' => $size37mm->id,
        ], [
            'fee_amount' => 123550.00,
        ]);

        ConnectionFee::firstOrCreate([
            'connection_type_id' => $privateWaterConnection->id,
            'connection_size_id' => $size50mm->id,
        ], [
            'fee_amount' => 125905.00,
        ]);

        // Create connection fees for commercial water connections
        ConnectionFee::firstOrCreate([
            'connection_type_id' => $commercialWaterConnection->id,
            'connection_size_id' => $size20mm->id,
        ], [
            'fee_amount' => 45000.00,
        ]);

        ConnectionFee::firstOrCreate([
            'connection_type_id' => $commercialWaterConnection->id,
            'connection_size_id' => $size25mm->id,
        ], [
            'fee_amount' => 65000.00,
        ]);

        // Note: The original data shows two entries for 37mm (1 1/4 inch and 1 1/2 inch)
        // Creating separate entries for each as they have different prices
        $size37mmOneQuarter = ConnectionSize::firstOrCreate([
            'size_mm' => '37',
            'size_inches' => '1 1/4',
        ], [
            'name' => '37mm (1 1/4 inch)',
        ]);

        ConnectionFee::firstOrCreate([
            'connection_type_id' => $commercialWaterConnection->id,
            'connection_size_id' => $size37mmOneQuarter->id,
        ], [
            'fee_amount' => 95000.00,
        ]);

        ConnectionFee::firstOrCreate([
            'connection_type_id' => $commercialWaterConnection->id,
            'connection_size_id' => $size37mm->id, // 1 1/2 inch
        ], [
            'fee_amount' => 145000.00,
        ]);

        ConnectionFee::firstOrCreate([
            'connection_type_id' => $commercialWaterConnection->id,
            'connection_size_id' => $size50mm->id,
        ], [
            'fee_amount' => 250000.00,
        ]);

        // Create fees for legalisation and reconnection (no size dependency)
        ConnectionFee::firstOrCreate([
            'connection_type_id' => $legalisation->id,
            'connection_size_id' => null, // No size dependency
        ], [
            'fee_amount' => 10000.00,
        ]);

        ConnectionFee::firstOrCreate([
            'connection_type_id' => $reconnectionFee->id,
            'connection_size_id' => null, // No size dependency
        ], [
            'fee_amount' => 2000.00,
        ]);

        // Create sample customer connections if customers exist
        $customers = Customer::limit(5)->get();
        if ($customers->count() > 0) {
            foreach ($customers as $index => $customer) {
                CustomerConnection::firstOrCreate([
                    'customer_id' => $customer->id,
                    'connection_type_id' => $privateWaterConnection->id,
                    'connection_size_id' => $size20mm->id,
                ], [
                    'status' => ['pending', 'approved', 'active'][$index % 3],
                    'notes' => 'Sample private connection for customer ' . $customer->first_name,
                    'installation_date' => now()->subDays(rand(1, 30)),
                ]);

                if ($index % 2 === 0) {
                    CustomerConnection::firstOrCreate([
                        'customer_id' => $customer->id,
                        'connection_type_id' => $legalisation->id,
                        'connection_size_id' => null,
                    ], [
                        'status' => 'approved',
                        'notes' => 'Legalisation for customer ' . $customer->first_name,
                        'installation_date' => now()->subDays(rand(1, 60)),
                    ]);
                }

                // Add a commercial connection for some customers
                if ($index % 3 === 0) {
                    CustomerConnection::firstOrCreate([
                        'customer_id' => $customer->id,
                        'connection_type_id' => $commercialWaterConnection->id,
                        'connection_size_id' => $size25mm->id,
                    ], [
                        'status' => 'pending',
                        'notes' => 'Sample commercial connection for customer ' . $customer->first_name,
                        'installation_date' => now()->subDays(rand(1, 45)),
                    ]);
                }
            }
        }
    }
}