<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city(),
            'ward_id' => \App\Models\Ward::factory(),
            'code' => $this->faker->unique()->randomNumber(5),
        ];
    }
}