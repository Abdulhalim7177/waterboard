<?php

namespace Database\Factories;

use App\Models\MonthSerial;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonthSerialFactory extends Factory
{
    protected $model = MonthSerial::class;

    public function definition()
    {
        return [
            'year_month' => $this->faker->date('Ym'),
            'count' => $this->faker->numberBetween(1, 10000),
        ];
    }
}