<?php

namespace Database\Factories;

use App\Models\Lga;
use Illuminate\Database\Eloquent\Factories\Factory;

class LgaFactory extends Factory
{
    protected $model = Lga::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city(),
            'code' => $this->faker->unique()->randomNumber(5),
        ];
    }
}