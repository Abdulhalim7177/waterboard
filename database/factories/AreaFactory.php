<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition()
    {
        return [
            'ward_id' => Ward::factory(),
            'name' => $this->faker->city(),

            'status' => $this->faker->randomElement(['approved', 'pending']),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }
}
