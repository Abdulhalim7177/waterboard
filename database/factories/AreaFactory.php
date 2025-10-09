<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition()
    {
        $areaNames = [
            'Katsina Township', 'Dutsin-Ma Township', 'Faskari Township', 'Bindawa Township',
            'Danja Township', 'Kafur Township', 'Kaita Township', 'Kurfi Township', 
            'Zango Township', 'Batagarawa Township', 'Jibia Township', 'Mashi Township',
            'Musawa Township', 'Sabuwa Township', 'Dandume Township', 'Matazu Township',
            'Charanchi Township', 'Kankia Township', 'Rimi Township', 'Sandamu Township',
            'Bakori Township', 'Funtua Township', 'Ingawa Township', 'Mani Township',
            'Malumfashi Township', 'Kusada Township', 'Mai Adua Township', 'Safana Township'
        ];

        // Katsina State is roughly between latitude 12.0°N to 13.5°N and longitude 7.0°E to 8.5°E
        return [
            'name' => $this->faker->randomElement($areaNames),
            'ward_id' => \App\Models\Ward::factory(),
            'code' => $this->faker->unique()->randomNumber(5),
            'latitude' => $this->faker->randomFloat(6, 12.0, 13.5),
            'longitude' => $this->faker->randomFloat(6, 7.0, 8.5),
        ];
    }
}