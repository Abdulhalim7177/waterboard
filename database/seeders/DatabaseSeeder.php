<?php

namespace Database\Seeders;

use App\Models\Lga;
use App\Models\Area;
use App\Models\Bill;
use App\Models\Ward;
use App\Models\Staff;
use App\Models\Tariff;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\MonthSerial;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Permissions
        $permissions = [
            'create-staff', 'edit-staff', 'delete-staff', 'approve-staff', 'reject-staff',
            'create-role', 'edit-role', 'delete-role',
            'create-permission', 'edit-permission', 'delete-permission',
            'create-lga', 'edit-lga', 'delete-lga', 'approve-lga', 'reject-lga',
            'create-ward', 'edit-ward', 'delete-ward', 'approve-ward', 'reject-ward',
            'create-area', 'edit-area', 'delete-area', 'approve-area', 'reject-area',
            'create-category', 'edit-category', 'delete-category', 'approve-category', 'reject-category',
            'create-tariff', 'edit-tariff', 'delete-tariff', 'approve-tariff', 'reject-tariff',
            'view-customers', 'view-customer', 'create-customer', 'edit-customer', 'delete-customer',
            'approve-customer', 'reject-customer',
            'view-locations', 'view-categories', 'view-tariffs', 'manage-users', 'view-audit-trail',
            'create-bill', 'approve-bill', 'reject-bill', 'delete-bill', 'view-bill', 'view-report',
            'view-payment', 'view-analytics'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'staff'
            ], ['status' => 'approved']);
        }

        Permission::firstOrCreate([
            'name' => 'view-bill',
            'guard_name' => 'customer'
        ], ['status' => 'approved']);

        // Create Roles
        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'staff'
        ], ['status' => 'approved']);
        $superAdmin->syncPermissions($permissions);

        $manager = Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'staff'
        ], ['status' => 'approved']);
        $manager->syncPermissions([
            'create-staff', 'edit-staff', 'delete-staff',
            'create-lga', 'edit-lga', 'delete-lga',
            'create-ward', 'edit-ward', 'delete-ward',
            'create-area', 'edit-area', 'delete-area',
            'create-category', 'edit-category', 'delete-category',
            'create-tariff', 'edit-tariff', 'delete-tariff',
            'view-customers', 'view-customer', 'create-customer', 'edit-customer', 'delete-customer',
            'view-locations', 'view-categories', 'view-tariffs', 'view-audit-trail',
            'create-bill', 'view-bill', 'view-report','view-analytics'
        ]);

        $staffRole = Role::firstOrCreate([
            'name' => 'staff',
            'guard_name' => 'staff'
        ], ['status' => 'approved']);
        $staffRole->syncPermissions([
            'create-category', 'edit-category', 'create-tariff', 'edit-tariff',
            'view-customers', 'view-customer', 'create-customer', 'edit-customer',
            'view-locations', 'view-categories', 'view-tariffs', 'view-bill', 'view-report',
            'view-analytics'
        ]);

        $customerRole = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'customer'
        ], ['status' => 'approved']);
        $customerRole->syncPermissions(['view-bill']);

        // Seed LGAs with coordinates (Katsina-specific)
        $lgas = [
            ['name' => 'Bakori', 'code' => '30917C', 'status' => 'approved', 'latitude' => 11.5556, 'longitude' => 7.4243],
            ['name' => 'Batagarawa', 'code' => '48330C', 'status' => 'approved', 'latitude' => 12.9061, 'longitude' => 7.6051],
            ['name' => 'Batsari', 'code' => '30918C', 'status' => 'approved', 'latitude' => 12.7555, 'longitude' => 7.2481],
            ['name' => 'Baure', 'code' => '48331C', 'status' => 'pending', 'latitude' => 12.8378, 'longitude' => 8.7452],
            ['name' => 'Bindawa', 'code' => '30919C', 'status' => 'approved', 'latitude' => 12.6703, 'longitude' => 7.8054],
            ['name' => 'Charanchi', 'code' => '30920C', 'status' => 'approved', 'latitude' => 12.6714, 'longitude' => 7.7296],
            ['name' => 'Dandume', 'code' => '30921C', 'status' => 'approved', 'latitude' => 11.4588, 'longitude' => 7.1260],
            ['name' => 'Danja', 'code' => '30922C', 'status' => 'approved', 'latitude' => 11.3770, 'longitude' => 7.5609],
            ['name' => 'Danmusa', 'code' => '48332C', 'status' => 'pending', 'latitude' => 12.2604, 'longitude' => 7.3252],
            ['name' => 'Daura', 'code' => '30923C', 'status' => 'approved', 'latitude' => 13.0330, 'longitude' => 8.3235],
        ];

        $lgaIds = [];
        foreach ($lgas as $lgaData) {
            $lga = Lga::firstOrCreate(['code' => $lgaData['code']], $lgaData);
            $lgaIds[$lgaData['code']] = $lga->id;
        }

        // Seed Wards with coordinates
        $wards = [
            ['lga_code' => '30917C', 'name' => 'Bakori A', 'code' => 'W001', 'status' => 'approved', 'latitude' => 11.5600, 'longitude' => 7.4200],
            ['lga_code' => '30917C', 'name' => 'Bakori B', 'code' => 'W002', 'status' => 'approved', 'latitude' => 11.5500, 'longitude' => 7.4300],
            ['lga_code' => '30917C', 'name' => 'Kandara', 'code' => 'W003', 'status' => 'approved', 'latitude' => 11.5650, 'longitude' => 7.4250],
            ['lga_code' => '48330C', 'name' => 'Barde', 'code' => 'W005', 'status' => 'approved', 'latitude' => 12.9100, 'longitude' => 7.6000],
            ['lga_code' => '48330C', 'name' => 'Kwantakwaran', 'code' => 'W006', 'status' => 'approved', 'latitude' => 12.9000, 'longitude' => 7.6100],
            ['lga_code' => '30918C', 'name' => 'Dawan Musa', 'code' => 'W008', 'status' => 'approved', 'latitude' => 12.7600, 'longitude' => 7.2400],
            ['lga_code' => '30919C', 'name' => 'Bindawa North', 'code' => 'W014', 'status' => 'approved', 'latitude' => 12.6750, 'longitude' => 7.8000],
            ['lga_code' => '30920C', 'name' => 'Charanchi Central', 'code' => 'W017', 'status' => 'approved', 'latitude' => 12.6700, 'longitude' => 7.7300],
            ['lga_code' => '30921C', 'name' => 'Dandume East', 'code' => 'W019', 'status' => 'approved', 'latitude' => 11.4600, 'longitude' => 7.1200],
            ['lga_code' => '30923C', 'name' => 'Daura Central', 'code' => 'W026', 'status' => 'approved', 'latitude' => 13.0300, 'longitude' => 8.3200],
        ];

        $wardIds = [];
        foreach ($wards as $wardData) {
            $ward = Ward::firstOrCreate(
                ['lga_id' => $lgaIds[$wardData['lga_code']], 'code' => $wardData['code']],
                [
                    'lga_id' => $lgaIds[$wardData['lga_code']],
                    'name' => $wardData['name'],
                    'code' => $wardData['code'],
                    'status' => $wardData['status'],
                    'latitude' => $wardData['latitude'],
                    'longitude' => $wardData['longitude']
                ]
            );
            $wardIds[$wardData['code']] = $ward->id;
        }

        // Seed Areas with coordinates
        $areas = [
            ['ward_code' => 'W001', 'code' => 'A001', 'name' => 'Bakori Central Area', 'status' => 'approved', 'latitude' => 11.5605, 'longitude' => 7.4205],
            ['ward_code' => 'W001', 'code' => 'A002', 'name' => 'Bakori East Area', 'status' => 'approved', 'latitude' => 11.5610, 'longitude' => 7.4210],
            ['ward_code' => 'W002', 'code' => 'A003', 'name' => 'Bakori West Area', 'status' => 'approved', 'latitude' => 11.5505, 'longitude' => 7.4305],
            ['ward_code' => 'W005', 'code' => 'A006', 'name' => 'Barde Central Area', 'status' => 'approved', 'latitude' => 12.9105, 'longitude' => 7.6005],
            ['ward_code' => 'W008', 'code' => 'A010', 'name' => 'Dawan Musa Area', 'status' => 'approved', 'latitude' => 12.7605, 'longitude' => 7.2405],
            ['ward_code' => 'W014', 'code' => 'A016', 'name' => 'Bindawa North Area', 'status' => 'approved', 'latitude' => 12.6755, 'longitude' => 7.8005],
            ['ward_code' => 'W017', 'code' => 'A019', 'name' => 'Charanchi Central Area', 'status' => 'approved', 'latitude' => 12.6705, 'longitude' => 7.7305],
            ['ward_code' => 'W019', 'code' => 'A021', 'name' => 'Dandume East Area', 'status' => 'approved', 'latitude' => 11.4605, 'longitude' => 7.1205],
            ['ward_code' => 'W026', 'code' => 'A028', 'name' => 'Daura Central Area', 'status' => 'approved', 'latitude' => 13.0305, 'longitude' => 8.3205],
        ];

        $areaIds = [];
        foreach ($areas as $areaData) {
            $area = Area::firstOrCreate(
                ['code' => $areaData['code']],
                [
                    'ward_id' => $wardIds[$areaData['ward_code']],
                    'code' => $areaData['code'],
                    'name' => $areaData['name'],
                    'status' => $areaData['status'],
                    'latitude' => $areaData['latitude'],
                    'longitude' => $areaData['longitude']
                ]
            );
            $areaIds[$areaData['code']] = $area->id;
        }

        // Seed Categories
        $categories = [
            ['name' => 'Residential', 'code' => 'CAT001', 'description' => 'Residential water usage', 'status' => 'approved'],
            ['name' => 'Commercial', 'code' => 'CAT002', 'description' => 'Commercial water usage', 'status' => 'approved'],
            ['name' => 'Industrial', 'code' => 'CAT003', 'description' => 'Industrial water usage', 'status' => 'approved'],
            ['name' => 'Agricultural', 'code' => 'CAT004', 'description' => 'Agricultural water usage', 'status' => 'approved'],
            ['name' => 'Institutional', 'code' => 'CAT005', 'description' => 'Institutional water usage', 'status' => 'approved'],
        ];

        $categoryIds = [];
        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(['code' => $categoryData['code']], $categoryData);
            $categoryIds[$categoryData['code']] = $category->id;
        }

        // Seed Tariffs
        $tariffs = [
            ['name' => 'Residential Basic', 'catcode' => '101', 'category_id' => $categoryIds['CAT001'], 'amount' => 100.00, 'rate' => 10.00, 'fixed_charge' => 50.00, 'billing_type' => 'Flat', 'description' => 'Basic residential tariff', 'status' => 'approved', 'created_at' => now()->setMonth(1)->setYear(2025)],
            ['name' => 'Residential Metered', 'catcode' => '102', 'category_id' => $categoryIds['CAT001'], 'amount' => 0.00, 'rate' => 15.00, 'fixed_charge' => 30.00, 'billing_type' => 'Metered', 'description' => 'Metered residential tariff', 'status' => 'approved', 'created_at' => now()->setMonth(1)->setYear(2025)],
            ['name' => 'Commercial Standard', 'catcode' => '201', 'category_id' => $categoryIds['CAT002'], 'amount' => 200.00, 'rate' => 20.00, 'fixed_charge' => 100.00, 'billing_type' => 'Flat', 'description' => 'Standard commercial tariff', 'status' => 'approved', 'created_at' => now()->setMonth(1)->setYear(2025)],
            ['name' => 'Commercial Premium', 'catcode' => '202', 'category_id' => $categoryIds['CAT002'], 'amount' => 300.00, 'rate' => 25.00, 'fixed_charge' => 150.00, 'billing_type' => 'Flat', 'description' => 'Premium commercial tariff', 'status' => 'approved', 'created_at' => now()->setMonth(1)->setYear(2025)],
            ['name' => 'Industrial Standard', 'catcode' => '301', 'category_id' => $categoryIds['CAT003'], 'amount' => 400.00, 'rate' => 30.00, 'fixed_charge' => 200.00, 'billing_type' => 'Flat', 'description' => 'Standard industrial tariff', 'status' => 'approved', 'created_at' => now()->setMonth(1)->setYear(2025)],
            ['name' => 'Agricultural Standard', 'catcode' => '401', 'category_id' => $categoryIds['CAT004'], 'amount' => 120.00, 'rate' => 12.00, 'fixed_charge' => 60.00, 'billing_type' => 'Flat', 'description' => 'Standard agricultural tariff', 'status' => 'approved', 'created_at' => now()->setMonth(1)->setYear(2025)],
            ['name' => 'Institutional Basic', 'catcode' => '501', 'category_id' => $categoryIds['CAT005'], 'amount' => 180.00, 'rate' => 18.00, 'fixed_charge' => 90.00, 'billing_type' => 'Flat', 'description' => 'Basic institutional tariff', 'status' => 'approved', 'created_at' => now()->setMonth(1)->setYear(2025)],
            ['name' => 'Institutional Metered', 'catcode' => '502', 'category_id' => $categoryIds['CAT005'], 'amount' => 0.00, 'rate' => 20.00, 'fixed_charge' => 100.00, 'billing_type' => 'Metered', 'description' => 'Metered institutional tariff', 'status' => 'approved', 'created_at' => now()->setMonth(1)->setYear(2025)],
        ];

        $tariffIds = [];
        foreach ($tariffs as $tariffData) {
            $tariff = Tariff::firstOrCreate(['catcode' => $tariffData['catcode']], $tariffData);
            $tariffIds[$tariffData['catcode']] = $tariff->id;
        }

        // Seed Customers with varied creation dates
        $customers = [
            // January 2025
            [
                'first_name' => 'Alice', 'surname' => 'Johnson', 'email' => 'alice.johnson@example.com', 'phone_number' => '1234567891', 'alternate_phone_number' => '9876543210',
                'street_name' => 'Main Street', 'house_number' => '101', 'landmark' => 'Near Market', 'lga_id' => $lgaIds['30917C'], 'ward_id' => $wardIds['W001'], 'area_id' => $areaIds['A001'],
                'category_id' => $categoryIds['CAT001'], 'tariff_id' => $tariffIds['101'], 'delivery_code' => 'DEL001', 'billing_condition' => 'Normal', 'water_supply_status' => 'Functional',
                'latitude' => 11.5606, 'longitude' => 7.4206, 'polygon_coordinates' => json_encode([[11.5606, 7.4206], [11.5616, 7.4216], [11.5626, 7.4206], [11.5606, 7.4196]]),
                'pipe_path' => json_encode([[11.5606, 7.4206], [11.5596, 7.4196], [11.5586, 7.4186]]), 'password' => bcrypt('password123'), 'status' => 'pending', 'account_balance' => 0.00,
                'created_at' => now()->setMonth(1)->setYear(2025),
            ],
            [
                'first_name' => 'Bob', 'surname' => 'Smith', 'email' => 'bob.smith@example.com', 'phone_number' => '1234567892', 'alternate_phone_number' => '9876543211',
                'street_name' => 'Herbert Macaulay Way', 'house_number' => '102', 'landmark' => 'Opposite Bank', 'lga_id' => $lgaIds['48330C'], 'ward_id' => $wardIds['W005'], 'area_id' => $areaIds['A006'],
                'category_id' => $categoryIds['CAT002'], 'tariff_id' => $tariffIds['201'], 'delivery_code' => 'DEL002', 'billing_condition' => 'Special', 'water_supply_status' => 'Functional',
                'latitude' => 12.9106, 'longitude' => 7.6006, 'polygon_coordinates' => json_encode([[12.9106, 7.6006], [12.9116, 7.6016], [12.9126, 7.6006]]),
                'pipe_path' => json_encode([[12.9106, 7.6006], [12.9096, 7.5996], [12.9086, 7.5986]]), 'password' => bcrypt('password123'), 'status' => 'approved', 'account_balance' => 50.00,
                'created_at' => now()->setMonth(1)->setYear(2025),
            ],
            // February 2025
            [
                'first_name' => 'Emma', 'surname' => 'Davis', 'email' => 'emma.davis@example.com', 'phone_number' => '1234567895', 'alternate_phone_number' => '9876543214',
                'street_name' => 'Sabo Road', 'house_number' => '105', 'landmark' => 'Near Church', 'lga_id' => $lgaIds['30920C'], 'ward_id' => $wardIds['W017'], 'area_id' => $areaIds['A019'],
                'category_id' => $categoryIds['CAT005'], 'tariff_id' => $tariffIds['501'], 'delivery_code' => 'DEL005', 'billing_condition' => 'Special', 'water_supply_status' => 'Functional',
                'latitude' => 12.6706, 'longitude' => 7.7306, 'polygon_coordinates' => json_encode([[12.6706, 7.7306], [12.6716, 7.7316], [12.6726, 7.7306], [12.6706, 7.7296]]),
                'pipe_path' => json_encode([[12.6706, 7.7306], [12.6696, 7.7296], [12.6686, 7.7286]]), 'password' => bcrypt('password123'), 'status' => 'approved', 'account_balance' => 100.00,
                'created_at' => now()->setMonth(2)->setYear(2025),
            ],
            [
                'first_name' => 'Chukwudi', 'surname' => 'Okeke', 'email' => 'chukwudi.okeke@example.com', 'phone_number' => '1234567896', 'alternate_phone_number' => '9876543215',
                'street_name' => 'Katsina Road', 'house_number' => '201', 'landmark' => 'Near School', 'lga_id' => $lgaIds['30918C'], 'ward_id' => $wardIds['W008'], 'area_id' => $areaIds['A010'],
                'category_id' => $categoryIds['CAT004'], 'tariff_id' => $tariffIds['401'], 'delivery_code' => 'DEL006', 'billing_condition' => 'Normal', 'water_supply_status' => 'Functional',
                'latitude' => 12.7606, 'longitude' => 7.2406, 'polygon_coordinates' => json_encode([[12.7606, 7.2406], [12.7616, 7.2416], [12.7626, 7.2406], [12.7606, 7.2396]]),
                'pipe_path' => json_encode([[12.7606, 7.2406], [12.7596, 7.2396], [12.7586, 7.2386]]), 'password' => bcrypt('password123'), 'status' => 'approved', 'account_balance' => 20.00,
                'created_at' => now()->setMonth(2)->setYear(2025),
            ],
            // March 2025
            [
                'first_name' => 'Fatima', 'surname' => 'Ibrahim', 'email' => 'fatima.ibrahim@example.com', 'phone_number' => '1234567897', 'alternate_phone_number' => '9876543216',
                'street_name' => 'Daura Lane', 'house_number' => '301', 'landmark' => 'Opposite Mosque', 'lga_id' => $lgaIds['30923C'], 'ward_id' => $wardIds['W026'], 'area_id' => $areaIds['A028'],
                'category_id' => $categoryIds['CAT001'], 'tariff_id' => $tariffIds['102'], 'delivery_code' => 'DEL007', 'billing_condition' => 'Metered', 'water_supply_status' => 'Functional',
                'latitude' => 13.0306, 'longitude' => 8.3206, 'polygon_coordinates' => json_encode([[13.0306, 8.3206], [13.0316, 8.3216], [13.0326, 8.3206]]),
                'pipe_path' => json_encode([[13.0306, 8.3206], [13.0296, 8.3196], [13.0286, 8.3186]]), 'password' => bcrypt('password123'), 'status' => 'pending', 'account_balance' => 0.00,
                'created_at' => now()->setMonth(3)->setYear(2025),
            ],
            [
                'first_name' => 'David', 'surname' => 'Wilson', 'email' => 'david.wilson@example.com', 'phone_number' => '1234567898', 'alternate_phone_number' => '9876543217',
                'street_name' => 'Central Avenue', 'house_number' => '401', 'landmark' => 'Near Clinic', 'lga_id' => $lgaIds['30921C'], 'ward_id' => $wardIds['W019'], 'area_id' => $areaIds['A021'],
                'category_id' => $categoryIds['CAT002'], 'tariff_id' => $tariffIds['202'], 'delivery_code' => 'DEL008', 'billing_condition' => 'Special', 'water_supply_status' => 'Non-functional',
                'latitude' => 11.4606, 'longitude' => 7.1206, 'polygon_coordinates' => json_encode([[11.4606, 7.1206], [11.4616, 7.1216], [11.4626, 7.1206], [11.4606, 7.1196]]),
                'pipe_path' => json_encode([[11.4606, 7.1206], [11.4596, 7.1196], [11.4586, 7.1186]]), 'password' => bcrypt('password123'), 'status' => 'approved', 'account_balance' => 75.00,
                'created_at' => now()->setMonth(3)->setYear(2025),
            ],
            // April 2025
            [
                'first_name' => 'Aisha', 'surname' => 'Yusuf', 'email' => 'aisha.yusuf@example.com', 'phone_number' => '1234567899', 'alternate_phone_number' => '9876543218',
                'street_name' => 'Market Road', 'house_number' => '501', 'landmark' => 'Near Market Square', 'lga_id' => $lgaIds['30919C'], 'ward_id' => $wardIds['W014'], 'area_id' => $areaIds['A016'],
                'category_id' => $categoryIds['CAT004'], 'tariff_id' => $tariffIds['401'], 'delivery_code' => 'DEL009', 'billing_condition' => 'Normal', 'water_supply_status' => 'Functional',
                'latitude' => 12.6756, 'longitude' => 7.8006, 'polygon_coordinates' => json_encode([[12.6756, 7.8006], [12.6766, 7.8016], [12.6776, 7.8006]]),
                'pipe_path' => json_encode([[12.6756, 7.8006], [12.6746, 7.7996], [12.6736, 7.7986]]), 'password' => bcrypt('password123'), 'status' => 'approved', 'account_balance' => 30.00,
                'created_at' => now()->setMonth(4)->setYear(2025),
            ],
            [
                'first_name' => 'James', 'surname' => 'Brown', 'email' => 'james.brown@example.com', 'phone_number' => '1234567900', 'alternate_phone_number' => '9876543219',
                'street_name' => 'School Lane', 'house_number' => '601', 'landmark' => 'Near Primary School', 'lga_id' => $lgaIds['30922C'], 'ward_id' => $wardIds['W019'], 'area_id' => $areaIds['A021'],
                'category_id' => $categoryIds['CAT005'], 'tariff_id' => $tariffIds['502'], 'delivery_code' => 'DEL010', 'billing_condition' => 'Special', 'water_supply_status' => 'Functional',
                'latitude' => 11.3776, 'longitude' => 7.5609, 'polygon_coordinates' => json_encode([[11.3776, 7.5609], [11.3786, 7.5619], [11.3796, 7.5609], [11.3776, 7.5599]]),
                'pipe_path' => json_encode([[11.3776, 7.5609], [11.3766, 7.5599], [11.3756, 7.5589]]), 'password' => bcrypt('password123'), 'status' => 'pending', 'account_balance' => 0.00,
                'created_at' => now()->setMonth(4)->setYear(2025),
            ],
            // May 2025
            [
                'first_name' => 'Sarah', 'surname' => 'Adams', 'email' => 'sarah.adams@example.com', 'phone_number' => '1234567901', 'alternate_phone_number' => '9876543220',
                'street_name' => 'Central Road', 'house_number' => '701', 'landmark' => 'Near Hospital', 'lga_id' => $lgaIds['30917C'], 'ward_id' => $wardIds['W001'], 'area_id' => $areaIds['A001'],
                'category_id' => $categoryIds['CAT003'], 'tariff_id' => $tariffIds['301'], 'delivery_code' => 'DEL011', 'billing_condition' => 'Normal', 'water_supply_status' => 'Functional',
                'latitude' => 11.5607, 'longitude' => 7.4207, 'polygon_coordinates' => json_encode([[11.5607, 7.4207], [11.5617, 7.4217], [11.5627, 7.4207]]),
                'pipe_path' => json_encode([[11.5607, 7.4207], [11.5597, 7.4197], [11.5587, 7.4187]]), 'password' => bcrypt('password123'), 'status' => 'approved', 'account_balance' => 200.00,
                'created_at' => now()->setMonth(5)->setYear(2025),
            ],
            [
                'first_name' => 'Mohammed', 'surname' => 'Sani', 'email' => 'mohammed.sani@example.com', 'phone_number' => '1234567902', 'alternate_phone_number' => '9876543221',
                'street_name' => 'Katsina Avenue', 'house_number' => '801', 'landmark' => 'Near Mosque', 'lga_id' => $lgaIds['30923C'], 'ward_id' => $wardIds['W026'], 'area_id' => $areaIds['A028'],
                'category_id' => $categoryIds['CAT001'], 'tariff_id' => $tariffIds['101'], 'delivery_code' => 'DEL012', 'billing_condition' => 'Normal', 'water_supply_status' => 'Functional',
                'latitude' => 13.0307, 'longitude' => 8.3207, 'polygon_coordinates' => json_encode([[13.0307, 8.3207], [13.0317, 8.3217], [13.0327, 8.3207]]),
                'pipe_path' => json_encode([[13.0307, 8.3207], [13.0297, 8.3197], [13.0287, 8.3187]]), 'password' => bcrypt('password123'), 'status' => 'approved', 'account_balance' => 50.00,
                'created_at' => now()->setMonth(5)->setYear(2025),
            ],
            // June 2025
            [
                'first_name' => 'Zainab', 'surname' => 'Musa', 'email' => 'zainab.musa@example.com', 'phone_number' => '1234567903', 'alternate_phone_number' => '9876543222',
                'street_name' => 'Market Street', 'house_number' => '901', 'landmark' => 'Near Town Hall', 'lga_id' => $lgaIds['30920C'], 'ward_id' => $wardIds['W017'], 'area_id' => $areaIds['A019'],
                'category_id' => $categoryIds['CAT002'], 'tariff_id' => $tariffIds['201'], 'delivery_code' => 'DEL013', 'billing_condition' => 'Normal', 'water_supply_status' => 'Functional',
                'latitude' => 12.6707, 'longitude' => 7.7307, 'polygon_coordinates' => json_encode([[12.6707, 7.7307], [12.6717, 7.7317], [12.6727, 7.7307]]),
                'pipe_path' => json_encode([[12.6707, 7.7307], [12.6697, 7.7297], [12.6687, 7.7287]]), 'password' => bcrypt('password123'), 'status' => 'rejected', 'account_balance' => 0.00,
                'created_at' => now()->setMonth(6)->setYear(2025),
            ],
            [
                'first_name' => 'Ibrahim', 'surname' => 'Hassan', 'email' => 'ibrahim.hassan@example.com', 'phone_number' => '1234567904', 'alternate_phone_number' => '9876543223',
                'street_name' => 'Central Lane', 'house_number' => '1001', 'landmark' => 'Near School', 'lga_id' => $lgaIds['30918C'], 'ward_id' => $wardIds['W008'], 'area_id' => $areaIds['A010'],
                'category_id' => $categoryIds['CAT004'], 'tariff_id' => $tariffIds['401'], 'delivery_code' => 'DEL014', 'billing_condition' => 'Normal', 'water_supply_status' => 'Functional',
                'latitude' => 12.7607, 'longitude' => 7.2407, 'polygon_coordinates' => json_encode([[12.7607, 7.2407], [12.7617, 7.2417], [12.7627, 7.2407]]),
                'pipe_path' => json_encode([[12.7607, 7.2407], [12.7597, 7.2397], [12.7587, 7.2387]]), 'password' => bcrypt('password123'), 'status' => 'approved', 'account_balance' => 40.00,
                'created_at' => now()->setMonth(6)->setYear(2025),
            ],
        ];

        Customer::withoutEvents(function () use ($customers) {
            foreach ($customers as $customerData) {
                Customer::create($customerData);
            }
        });

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

        // Seed Bills for approved customers (January to July 2025) with varied amounts
        $bills = [
            // January 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'tariff_id' => $tariffIds['201'], 'billing_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->billing_id,
                'amount' => 200.00, 'balance' => 150.00, 'year_month' => '2501', 'billing_date' => now()->setMonth(1)->setYear(2025),
                'due_date' => now()->setMonth(1)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(1)->setYear(2025),
            ],
            // February 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'tariff_id' => $tariffIds['201'], 'billing_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->billing_id,
                'amount' => 220.00, 'balance' => 170.00, 'year_month' => '2502', 'billing_date' => now()->setMonth(2)->setYear(2025),
                'due_date' => now()->setMonth(2)->setYear(2025)->endOfMonth(), 'status' => 'overdue', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(2)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'tariff_id' => $tariffIds['501'], 'billing_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->billing_id,
                'amount' => 180.00, 'balance' => 80.00, 'year_month' => '2502', 'billing_date' => now()->setMonth(2)->setYear(2025),
                'due_date' => now()->setMonth(2)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(2)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->billing_id,
                'amount' => 120.00, 'balance' => 100.00, 'year_month' => '2502', 'billing_date' => now()->setMonth(2)->setYear(2025),
                'due_date' => now()->setMonth(2)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(2)->setYear(2025),
            ],
            // March 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'tariff_id' => $tariffIds['201'], 'billing_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->billing_id,
                'amount' => 250.00, 'balance' => 200.00, 'year_month' => '2503', 'billing_date' => now()->setMonth(3)->setYear(2025),
                'due_date' => now()->setMonth(3)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(3)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'tariff_id' => $tariffIds['501'], 'billing_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->billing_id,
                'amount' => 200.00, 'balance' => 100.00, 'year_month' => '2503', 'billing_date' => now()->setMonth(3)->setYear(2025),
                'due_date' => now()->setMonth(3)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(3)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->billing_id,
                'amount' => 140.00, 'balance' => 120.00, 'year_month' => '2503', 'billing_date' => now()->setMonth(3)->setYear(2025),
                'due_date' => now()->setMonth(3)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(3)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id,
                'tariff_id' => $tariffIds['202'], 'billing_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->billing_id,
                'amount' => 300.00, 'balance' => 225.00, 'year_month' => '2503', 'billing_date' => now()->setMonth(3)->setYear(2025),
                'due_date' => now()->setMonth(3)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(3)->setYear(2025),
            ],
            // April 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'tariff_id' => $tariffIds['201'], 'billing_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->billing_id,
                'amount' => 180.00, 'balance' => 130.00, 'year_month' => '2504', 'billing_date' => now()->setMonth(4)->setYear(2025),
                'due_date' => now()->setMonth(4)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(4)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'tariff_id' => $tariffIds['501'], 'billing_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->billing_id,
                'amount' => 190.00, 'balance' => 90.00, 'year_month' => '2504', 'billing_date' => now()->setMonth(4)->setYear(2025),
                'due_date' => now()->setMonth(4)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(4)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->billing_id,
                'amount' => 130.00, 'balance' => 110.00, 'year_month' => '2504', 'billing_date' => now()->setMonth(4)->setYear(2025),
                'due_date' => now()->setMonth(4)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(4)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id,
                'tariff_id' => $tariffIds['202'], 'billing_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->billing_id,
                'amount' => 320.00, 'balance' => 240.00, 'year_month' => '2504', 'billing_date' => now()->setMonth(4)->setYear(2025),
                'due_date' => now()->setMonth(4)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(4)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->billing_id,
                'amount' => 120.00, 'balance' => 90.00, 'year_month' => '2504', 'billing_date' => now()->setMonth(4)->setYear(2025),
                'due_date' => now()->setMonth(4)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(4)->setYear(2025),
            ],
            // May 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'tariff_id' => $tariffIds['201'], 'billing_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->billing_id,
                'amount' => 230.00, 'balance' => 180.00, 'year_month' => '2505', 'billing_date' => now()->setMonth(5)->setYear(2025),
                'due_date' => now()->setMonth(5)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'tariff_id' => $tariffIds['501'], 'billing_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->billing_id,
                'amount' => 210.00, 'balance' => 110.00, 'year_month' => '2505', 'billing_date' => now()->setMonth(5)->setYear(2025),
                'due_date' => now()->setMonth(5)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->billing_id,
                'amount' => 150.00, 'balance' => 130.00, 'year_month' => '2505', 'billing_date' => now()->setMonth(5)->setYear(2025),
                'due_date' => now()->setMonth(5)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id,
                'tariff_id' => $tariffIds['202'], 'billing_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->billing_id,
                'amount' => 350.00, 'balance' => 270.00, 'year_month' => '2505', 'billing_date' => now()->setMonth(5)->setYear(2025),
                'due_date' => now()->setMonth(5)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->billing_id,
                'amount' => 140.00, 'balance' => 110.00, 'year_month' => '2505', 'billing_date' => now()->setMonth(5)->setYear(2025),
                'due_date' => now()->setMonth(5)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id,
                'tariff_id' => $tariffIds['301'], 'billing_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->billing_id,
                'amount' => 400.00, 'balance' => 200.00, 'year_month' => '2505', 'billing_date' => now()->setMonth(5)->setYear(2025),
                'due_date' => now()->setMonth(5)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->id,
                'tariff_id' => $tariffIds['101'], 'billing_id' => $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->billing_id,
                'amount' => 120.00, 'balance' => 70.00, 'year_month' => '2505', 'billing_date' => now()->setMonth(5)->setYear(2025),
                'due_date' => now()->setMonth(5)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025),
            ],
            // June 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'tariff_id' => $tariffIds['201'], 'billing_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->billing_id,
                'amount' => 200.00, 'balance' => 150.00, 'year_month' => '2506', 'billing_date' => now()->setMonth(6)->setYear(2025),
                'due_date' => now()->setMonth(6)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'tariff_id' => $tariffIds['501'], 'billing_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->billing_id,
                'amount' => 170.00, 'balance' => 70.00, 'year_month' => '2506', 'billing_date' => now()->setMonth(6)->setYear(2025),
                'due_date' => now()->setMonth(6)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->billing_id,
                'amount' => 110.00, 'balance' => 90.00, 'year_month' => '2506', 'billing_date' => now()->setMonth(6)->setYear(2025),
                'due_date' => now()->setMonth(6)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id,
                'tariff_id' => $tariffIds['202'], 'billing_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->billing_id,
                'amount' => 280.00, 'balance' => 200.00, 'year_month' => '2506', 'billing_date' => now()->setMonth(6)->setYear(2025),
                'due_date' => now()->setMonth(6)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->billing_id,
                'amount' => 130.00, 'balance' => 100.00, 'year_month' => '2506', 'billing_date' => now()->setMonth(6)->setYear(2025),
                'due_date' => now()->setMonth(6)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id,
                'tariff_id' => $tariffIds['301'], 'billing_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->billing_id,
                'amount' => 420.00, 'balance' => 220.00, 'year_month' => '2506', 'billing_date' => now()->setMonth(6)->setYear(2025),
                'due_date' => now()->setMonth(6)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->id,
                'tariff_id' => $tariffIds['101'], 'billing_id' => $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->billing_id,
                'amount' => 110.00, 'balance' => 60.00, 'year_month' => '2506', 'billing_date' => now()->setMonth(6)->setYear(2025),
                'due_date' => now()->setMonth(6)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'ibrahim.hassan@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'ibrahim.hassan@example.com')->first()->billing_id,
                'amount' => 130.00, 'balance' => 90.00, 'year_month' => '2506', 'billing_date' => now()->setMonth(6)->setYear(2025),
                'due_date' => now()->setMonth(6)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025),
            ],
            // July 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'tariff_id' => $tariffIds['201'], 'billing_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->billing_id,
                'amount' => 240.00, 'balance' => 190.00, 'year_month' => '2507', 'billing_date' => now()->setMonth(7)->setYear(2025),
                'due_date' => now()->setMonth(7)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'tariff_id' => $tariffIds['501'], 'billing_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->billing_id,
                'amount' => 200.00, 'balance' => 100.00, 'year_month' => '2507', 'billing_date' => now()->setMonth(7)->setYear(2025),
                'due_date' => now()->setMonth(7)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->billing_id,
                'amount' => 140.00, 'balance' => 120.00, 'year_month' => '2507', 'billing_date' => now()->setMonth(7)->setYear(2025),
                'due_date' => now()->setMonth(7)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id,
                'tariff_id' => $tariffIds['202'], 'billing_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->billing_id,
                'amount' => 310.00, 'balance' => 230.00, 'year_month' => '2507', 'billing_date' => now()->setMonth(7)->setYear(2025),
                'due_date' => now()->setMonth(7)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->billing_id,
                'amount' => 150.00, 'balance' => 120.00, 'year_month' => '2507', 'billing_date' => now()->setMonth(7)->setYear(2025),
                'due_date' => now()->setMonth(7)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id,
                'tariff_id' => $tariffIds['301'], 'billing_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->billing_id,
                'amount' => 430.00, 'balance' => 230.00, 'year_month' => '2507', 'billing_date' => now()->setMonth(7)->setYear(2025),
                'due_date' => now()->setMonth(7)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->id,
                'tariff_id' => $tariffIds['101'], 'billing_id' => $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->billing_id,
                'amount' => 130.00, 'balance' => 80.00, 'year_month' => '2507', 'billing_date' => now()->setMonth(7)->setYear(2025),
                'due_date' => now()->setMonth(7)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'ibrahim.hassan@example.com')->first()->id,
                'tariff_id' => $tariffIds['401'], 'billing_id' => $approvedCustomers->where('email', 'ibrahim.hassan@example.com')->first()->billing_id,
                'amount' => 140.00, 'balance' => 100.00, 'year_month' => '2507', 'billing_date' => now()->setMonth(7)->setYear(2025),
                'due_date' => now()->setMonth(7)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025),
            ],
            // Additional Bills for Metered Tariffs (to increase variability)
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'tariff_id' => $tariffIds['502'], 'billing_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->billing_id,
                'amount' => 250.00, 'balance' => 200.00, 'year_month' => '2503', 'billing_date' => now()->setMonth(3)->setYear(2025)->addDays(15),
                'due_date' => now()->setMonth(3)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(3)->setYear(2025),
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id,
                'tariff_id' => $tariffIds['301'], 'billing_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->billing_id,
                'amount' => 450.00, 'balance' => 300.00, 'year_month' => '2504', 'billing_date' => now()->setMonth(4)->setYear(2025)->addDays(10),
                'due_date' => now()->setMonth(4)->setYear(2025)->endOfMonth(), 'status' => 'pending', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(4)->setYear(2025),
            ],
        ];

        foreach ($bills as $billData) {
            Bill::create($billData);
        }
        $bills = Bill::all();
        $staff = Staff::where('email', 'john.doe@example.com')->first(); // For assigned_to_id in complaints
        $payments = [
            // January 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id)->where('year_month', '2501')->first()->id,
                'amount' => 50.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(1)->setYear(2025)->addDays(15), 'transaction_ref' => 'TXN001', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(1)->setYear(2025), 'balance' => 150.00,
            ],
            // February 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id)->where('year_month', '2502')->first()->id,
                'amount' => 50.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(2)->setYear(2025)->addDays(10), 'transaction_ref' => 'TXN002', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(2)->setYear(2025), 'balance' => 170.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id)->where('year_month', '2502')->first()->id,
                'amount' => 100.00, 'method' => 'Mobile App', 'channel' => 'App', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(2)->setYear(2025)->addDays(12), 'transaction_ref' => 'TXN003', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(2)->setYear(2025), 'balance' => 80.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id)->where('year_month', '2502')->first()->id,
                'amount' => 20.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'failed', 'payment_status' => 'failed',
                'payment_date' => now()->setMonth(2)->setYear(2025)->addDays(15), 'transaction_ref' => 'TXN004', 'approval_status' => 'rejected',
                'created_at' => now()->setMonth(2)->setYear(2025), 'balance' => 100.00,
            ],
            // March 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id)->where('year_month', '2503')->first()->id,
                'amount' => 50.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(3)->setYear(2025)->addDays(10), 'transaction_ref' => 'TXN005', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(3)->setYear(2025), 'balance' => 200.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id)->where('year_month', '2503')->first()->id,
                'amount' => 100.00, 'method' => 'Mobile App', 'channel' => 'App', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(3)->setYear(2025)->addDays(15), 'transaction_ref' => 'TXN006', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(3)->setYear(2025), 'balance' => 100.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id)->where('year_month', '2503')->first()->id,
                'amount' => 20.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(3)->setYear(2025)->addDays(20), 'transaction_ref' => 'TXN007', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(3)->setYear(2025), 'balance' => 120.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id)->where('year_month', '2503')->first()->id,
                'amount' => 75.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(3)->setYear(2025)->addDays(22), 'transaction_ref' => 'TXN008', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(3)->setYear(2025), 'balance' => 225.00,
            ],
            // April 2025 (dip in payments)
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id)->where('year_month', '2504')->first()->id,
                'amount' => 50.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(4)->setYear(2025)->addDays(10), 'transaction_ref' => 'TXN009', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(4)->setYear(2025), 'balance' => 130.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id)->where('year_month', '2504')->first()->id,
                'amount' => 100.00, 'method' => 'Mobile App', 'channel' => 'App', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(4)->setYear(2025)->addDays(12), 'transaction_ref' => 'TXN010', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(4)->setYear(2025), 'balance' => 90.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id)->where('year_month', '2504')->first()->id,
                'amount' => 30.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'failed', 'payment_status' => 'failed',
                'payment_date' => now()->setMonth(4)->setYear(2025)->addDays(15), 'transaction_ref' => 'TXN011', 'approval_status' => 'rejected',
                'created_at' => now()->setMonth(4)->setYear(2025), 'balance' => 90.00,
            ],
            // May 2025 (peak in payments)
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id)->where('year_month', '2505')->first()->id,
                'amount' => 50.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(5)->setYear(2025)->addDays(10), 'transaction_ref' => 'TXN012', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025), 'balance' => 180.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id)->where('year_month', '2505')->first()->id,
                'amount' => 100.00, 'method' => 'Mobile App', 'channel' => 'App', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(5)->setYear(2025)->addDays(12), 'transaction_ref' => 'TXN013', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025), 'balance' => 110.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id)->where('year_month', '2505')->first()->id,
                'amount' => 20.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(5)->setYear(2025)->addDays(15), 'transaction_ref' => 'TXN014', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025), 'balance' => 130.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id)->where('year_month', '2505')->first()->id,
                'amount' => 80.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(5)->setYear(2025)->addDays(18), 'transaction_ref' => 'TXN015', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025), 'balance' => 270.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id)->where('year_month', '2505')->first()->id,
                'amount' => 30.00, 'method' => 'Mobile App', 'channel' => 'App', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(5)->setYear(2025)->addDays(20), 'transaction_ref' => 'TXN016', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025), 'balance' => 110.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id)->where('year_month', '2505')->first()->id,
                'amount' => 200.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(5)->setYear(2025)->addDays(22), 'transaction_ref' => 'TXN017', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025), 'balance' => 200.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->id)->where('year_month', '2505')->first()->id,
                'amount' => 50.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(5)->setYear(2025)->addDays(25), 'transaction_ref' => 'TXN018', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(5)->setYear(2025), 'balance' => 70.00,
            ],
            // June 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id)->where('year_month', '2506')->first()->id,
                'amount' => 50.00, 'method' => 'Mobile App', 'channel' => 'App', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(6)->setYear(2025)->addDays(10), 'transaction_ref' => 'TXN019', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025), 'balance' => 150.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id)->where('year_month', '2506')->first()->id,
                'amount' => 100.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(6)->setYear(2025)->addDays(12), 'transaction_ref' => 'TXN020', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025), 'balance' => 70.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id)->where('year_month', '2506')->first()->id,
                'amount' => 20.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'failed', 'payment_status' => 'failed',
                'payment_date' => now()->setMonth(6)->setYear(2025)->addDays(15), 'transaction_ref' => 'TXN021', 'approval_status' => 'rejected',
                'created_at' => now()->setMonth(6)->setYear(2025), 'balance' => 90.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id)->where('year_month', '2506')->first()->id,
                'amount' => 80.00, 'method' => 'Mobile App', 'channel' => 'App', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(6)->setYear(2025)->addDays(18), 'transaction_ref' => 'TXN022', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025), 'balance' => 200.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id)->where('year_month', '2506')->first()->id,
                'amount' => 30.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(6)->setYear(2025)->addDays(20), 'transaction_ref' => 'TXN023', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025), 'balance' => 100.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id)->where('year_month', '2506')->first()->id,
                'amount' => 200.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(6)->setYear(2025)->addDays(22), 'transaction_ref' => 'TXN024', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025), 'balance' => 220.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->id)->where('year_month', '2506')->first()->id,
                'amount' => 50.00, 'method' => 'Mobile App', 'channel' => 'App', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(6)->setYear(2025)->addDays(25), 'transaction_ref' => 'TXN025', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025), 'balance' => 60.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'ibrahim.hassan@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'ibrahim.hassan@example.com')->first()->id)->where('year_month', '2506')->first()->id,
                'amount' => 40.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(6)->setYear(2025)->addDays(20), 'transaction_ref' => 'TXN026', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(6)->setYear(2025), 'balance' => 90.00,
            ],
            // July 2025
            [
                'customer_id' => $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'bob.smith@example.com')->first()->id)->where('year_month', '2507')->first()->id,
                'amount' => 50.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(7)->setYear(2025)->addDays(10), 'transaction_ref' => 'TXN027', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025), 'balance' => 190.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id)->where('year_month', '2507')->first()->id,
                'amount' => 100.00, 'method' => 'Mobile App', 'channel' => 'App', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(7)->setYear(2025)->addDays(12), 'transaction_ref' => 'TXN028', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025), 'balance' => 100.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'chukwudi.okeke@example.com')->first()->id)->where('year_month', '2507')->first()->id,
                'amount' => 20.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(7)->setYear(2025)->addDays(15), 'transaction_ref' => 'TXN029', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025), 'balance' => 120.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'david.wilson@example.com')->first()->id)->where('year_month', '2507')->first()->id,
                'amount' => 80.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(7)->setYear(2025)->addDays(18), 'transaction_ref' => 'TXN030', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025), 'balance' => 230.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'aisha.yusuf@example.com')->first()->id)->where('year_month', '2507')->first()->id,
                'amount' => 30.00, 'method' => 'Mobile App', 'channel' => 'App', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(7)->setYear(2025)->addDays(20), 'transaction_ref' => 'TXN031', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025), 'balance' => 120.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id)->where('year_month', '2507')->first()->id,
                'amount' => 200.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(7)->setYear(2025)->addDays(22), 'transaction_ref' => 'TXN032', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025), 'balance' => 230.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'mohammed.sani@example.com')->first()->id)->where('year_month', '2507')->first()->id,
                'amount' => 50.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(7)->setYear(2025)->addDays(25), 'transaction_ref' => 'TXN033', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(7)->setYear(2025), 'balance' => 80.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'ibrahim.hassan@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'ibrahim.hassan@example.com')->first()->id)->where('year_month', '2507')->first()->id,
                'amount' => 40.00, 'method' => 'Mobile App', 'channel' => 'App', 'status' => 'failed', 'payment_status' => 'failed',
                'payment_date' => now()->setMonth(7)->setYear(2025)->addDays(20), 'transaction_ref' => 'TXN034', 'approval_status' => 'rejected',
                'created_at' => now()->setMonth(7)->setYear(2025), 'balance' => 100.00,
            ],
            // Additional Payments for Variability
            [
                'customer_id' => $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'sarah.adams@example.com')->first()->id)->where('year_month', '2504')->first()->id,
                'amount' => 150.00, 'method' => 'Bank Transfer', 'channel' => 'Online', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(4)->setYear(2025)->addDays(18), 'transaction_ref' => 'TXN035', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(4)->setYear(2025), 'balance' => 300.00,
            ],
            [
                'customer_id' => $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id,
                'bill_id' => $bills->where('customer_id', $approvedCustomers->where('email', 'emma.davis@example.com')->first()->id)->where('year_month', '2503')->first()->id,
                'amount' => 50.00, 'method' => 'Cash', 'channel' => 'In-Person', 'status' => 'successful', 'payment_status' => 'successful',
                'payment_date' => now()->setMonth(3)->setYear(2025)->addDays(25), 'transaction_ref' => 'TXN036', 'approval_status' => 'approved',
                'created_at' => now()->setMonth(3)->setYear(2025), 'balance' => 200.00,
            ],
        ];

        foreach ($payments as $paymentData) {
            Payment::create($paymentData);
        }

        // Seed Staff
        $staffData = [
            [
                'staff_id' => 'STAFF001',
                'first_name' => 'John',
                'middle_name' => 'Michael',
                'surname' => 'Doe',
                'email' => 'john.doe@example.com',
                'password' => bcrypt('password123'),
                'mobile_no' => '1234567890',
                'phone_number' => '1234567890',
                'lga_id' => $lgaIds['30917C'],
                'ward_id' => $wardIds['W001'],
                'area_id' => $areaIds['A001'],
                'status' => 'approved',
                'employment_status' => 'active',
                'date_of_birth' => '1985-06-15',
                'gender' => 'male',
                'date_of_first_appointment' => '2020-01-15',
                'created_at' => now()->setMonth(1)->setYear(2025),
            ],
            [
                'staff_id' => 'STAFF002',
                'first_name' => 'Jane',
                'middle_name' => 'Mary',
                'surname' => 'Smith',
                'email' => 'jane.smith@example.com',
                'password' => bcrypt('password123'),
                'mobile_no' => '1234567891',
                'phone_number' => '1234567891',
                'lga_id' => $lgaIds['48330C'],
                'ward_id' => $wardIds['W005'],
                'area_id' => $areaIds['A006'],
                'status' => 'pending',
                'employment_status' => 'active',
                'date_of_birth' => '1990-03-22',
                'gender' => 'female',
                'date_of_first_appointment' => '2021-03-22',
                'created_at' => now()->setMonth(2)->setYear(2025),
            ],
            [
                'staff_id' => 'STAFF003',
                'first_name' => 'Aminu',
                'middle_name' => '',
                'surname' => 'Bello',
                'email' => 'aminu.bello@example.com',
                'password' => bcrypt('password123'),
                'mobile_no' => '1234567892',
                'phone_number' => '1234567892',
                'lga_id' => $lgaIds['30923C'],
                'ward_id' => $wardIds['W026'],
                'area_id' => $areaIds['A028'],
                'status' => 'approved',
                'employment_status' => 'active',
                'date_of_birth' => '1988-11-10',
                'gender' => 'male',
                'date_of_first_appointment' => '2019-11-10',
                'created_at' => now()->setMonth(3)->setYear(2025),
            ],
        ];

        foreach ($staffData as $staffMember) {
            $staff = Staff::create($staffMember);
            if ($staffMember['email'] === 'john.doe@example.com') {
                $staff->assignRole('super-admin');
            } elseif ($staffMember['email'] === 'jane.smith@example.com') {
                $staff->assignRole('manager');
            } else {
                $staff->assignRole('staff');
            }
        }
        
        // Run the LGA and Ward seeder from Excel file
        $this->call(LgaWardSeeder::class);
        
        // Run the customer revalidation seeder from Excel file
        $this->call(CustomerRevalidationSeeder::class);
    }
}