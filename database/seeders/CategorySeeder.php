<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'DOMESTIC CONSUMERS',
                'code' => 'DC',
                'description' => 'Domestic Consumers',
                'status' => 'approved',
            ],
            [
                'name' => 'PRIVATE WATER CONNECTIONS',
                'code' => 'PWC',
                'description' => 'Private Water Connections',
                'status' => 'approved',
            ],
            [
                'name' => 'COMMERCIAL WATER CONNECTIONS',
                'code' => 'CWC',
                'description' => 'Commercial Water Connections',
                'status' => 'approved',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['code' => $category['code']], $category);
        }
    }
}
