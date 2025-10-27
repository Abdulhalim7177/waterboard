<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'approved' => $this->faker->boolean(),
            'account_balance' => $this->faker->randomFloat(2, 0, 1000000),
            'street_name' => $this->faker->streetName(),
            'vendor_code' => $this->faker->unique()->numerify('VC##########'),
            'lga_id' => Lga::inRandomOrder()->first()->id,
            'ward_id' => Ward::inRandomOrder()->first()->id,
            'area_id' => Area::inRandomOrder()->first()->id,
        ];
    }
}