<?php

namespace Database\Factories;

use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;

class WardFactory extends Factory
{
    protected $model = Ward::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city(),
            'lga_id' => \App\Models\Lga::factory(),
            'code' => $this->faker->unique()->randomNumber(5),
        ];
    }
}