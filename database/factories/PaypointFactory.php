<?php

namespace Database\Factories;

use App\Models\Paypoint;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaypointFactory extends Factory
{
    protected $model = Paypoint::class;

    public function definition()
    {
        $paypointNames = [
            'Katsina Central Paypoint', 'Katsina East Paypoint', 'Katsina West Paypoint', 
            'Funtua Paypoint', 'Dutsin-Ma Paypoint', 'Kankara Paypoint', 'Mashi Paypoint',
            'Batagarawa Paypoint', 'Jibia Paypoint', 'Danja Paypoint', 'Kafur Paypoint'
        ];
        
        $types = ['zone', 'district'];

        return [
            'name' => $this->faker->randomElement($paypointNames),
            'code' => 'PP' . $this->faker->unique()->randomNumber(4),
            'type' => $this->faker->randomElement($types),
            'zone_id' => \App\Models\Zone::factory(),
            'district_id' => \App\Models\District::factory(),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}