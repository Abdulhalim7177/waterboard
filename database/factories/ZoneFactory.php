<?php

namespace Database\Factories;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZoneFactory extends Factory
{
    protected $model = Zone::class;

    public function definition()
    {
        $zoneNames = ['North', 'South', 'East', 'West', 'Central', 'Katsina Zone 1', 'Katsina Zone 2', 'Katsina Zone 3', 'Katsina Zone 4', 'Katsina Zone 5'];

        return [
            'code' => 'Z' . $this->faker->unique()->randomNumber(4),
            'name' => $this->faker->randomElement($zoneNames),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'pending_delete']),
        ];
    }
}