<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use App\Models\Tariff;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have the necessary related data
        if (Lga::count() === 0) {
            $this->command->error('No LGAs found. Please run the default database seeder first.');
            return;
        }
        
        if (Ward::count() === 0) {
            $this->command->error('No Wards found. Please run the default database seeder first.');
            return;
        }
        
        if (Area::count() === 0) {
            $this->command->error('No Areas found. Please run the default database seeder first.');
            return;
        }
        
        if (Tariff::count() === 0) {
            $this->command->error('No Tariffs found. Please run the default database seeder first.');
            return;
        }
        
        if (Category::count() === 0) {
            $this->command->error('No Categories found. Please run the default database seeder first.');
            return;
        }

        $this->command->info('Creating 1000 customers with Katsina-centered GIS coordinates...');

        // Create 1000 customers using the factory
        Customer::factory()->count(1000)->create();

        $this->command->info('Successfully created 1000 customers with Katsina-centered GIS coordinates!');
    }
}