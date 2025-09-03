<?php

namespace Database\Factories;

use App\Models\Tariff;
use Illuminate\Database\Eloquent\Factories\Factory;

class TariffFactory extends Factory
{
    protected $model = Tariff::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'rate' => $this->faker->randomFloat(2, 10, 100),
            'category_id' => \App\Models\Category::factory(),
            'catcode' => $this->faker->unique()->randomNumber(3),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}