<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrmDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name' => 'Human Resources'],
            ['name' => 'IT'],
            ['name' => 'Finance'],
        ]);

        DB::table('ranks')->insert([
            ['name' => 'Junior Staff'],
            ['name' => 'Senior Staff'],
            ['name' => 'Manager'],
        ]);

        DB::table('cadres')->insert([
            ['name' => 'Administrative'],
            ['name' => 'Technical'],
            ['name' => 'Financial'],
        ]);

        DB::table('grade_levels')->insert([
            ['name' => 'GL01'],
            ['name' => 'GL02'],
            ['name' => 'GL03'],
        ]);

        DB::table('steps')->insert([
            ['name' => 'Step 1', 'grade_level_id' => 1],
            ['name' => 'Step 2', 'grade_level_id' => 1],
            ['name' => 'Step 1', 'grade_level_id' => 2],
        ]);

        DB::table('appointment_types')->insert([
            ['name' => 'Permanent'],
            ['name' => 'Contract'],
            ['name' => 'Internship'],
        ]);
    }
};