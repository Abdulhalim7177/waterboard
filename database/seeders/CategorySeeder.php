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
                'name' => 'INDUSTRIAL/COMMERCIALS',
                'code' => 'IC',
                'description' => 'Industrial and Commercial Consumers',
                'status' => 'approved',
            ],
            [
                'name' => 'PUBLIC INSTITUTIONS',
                'code' => 'PI',
                'description' => 'Public Institutions',
                'status' => 'approved',
            ],
            [
                'name' => 'ROYALTIES',
                'code' => 'RY',
                'description' => 'Royalties for various services',
                'status' => 'approved',
            ],
            [
                'name' => 'RAW WATER',
                'code' => 'RW',
                'description' => 'Raw Water Supply',
                'status' => 'approved',
            ],
            [
                'name' => 'WATER TANKER',
                'code' => 'WT',
                'description' => 'Water Tanker Services',
                'status' => 'approved',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['code' => $category['code']], $category);
        }
    }
}
