<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\MonthSerial;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RolePermissionSeeder::class);
        $this->call(HrmDataSeeder::class);
        $this->call(StaffManagementSeeder::class);

        $this->call(CategorySeeder::class);
        $this->call(TariffSeeder::class);
        $this->call(LgaWardSeeder::class);
        $this->call(AreaSeeder::class);
      
        $domestic = \App\Models\Category::where('code', 'DC')->first();
        $industrial = \App\Models\Category::where('code', 'IC')->first();
        $public = \App\Models\Category::where('code', 'PI')->first();

        Customer::factory()->count(400)->state(['category_id' => $domestic->id])->create();
        Customer::factory()->count(70)->state(['category_id' => $industrial->id])->create();
        Customer::factory()->count(30)->state(['category_id' => $public->id])->create();
        
        
        
        $this->call(ZoneSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(PaypointSeeder::class);
        $this->call(VendorSeeder::class);
        // Manually set month_serials counts
        $monthSerials = [
            ['year_month' => '2501', 'count' => 2],
            ['year_month' => '2502', 'count' => 2],
            ['year_month' => '2503', 'count' => 2],
            ['year_month' => '2504', 'count' => 2],
            ['year_month' => '2505', 'count' => 2],
            ['year_month' => '2506', 'count' => 2],
            ['year_month' => '2507', 'count' => 2],
        ];

        foreach ($monthSerials as $serialData) {
            MonthSerial::firstOrCreate(['year_month' => $serialData['year_month']], $serialData);
        }

        // Assign billing_id for approved customers
        $approvedCustomers = Customer::where('status', 'approved')->get();
        foreach ($approvedCustomers as $customer) {
            $customer->billing_id = Customer::generateBillingId($customer);
            $customer->save();
        }

        // Seed bills and payments
        $this->call(BillingSeeder::class);
    }
}
