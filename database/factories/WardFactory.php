<?php

namespace Database\Factories;

use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;

class WardFactory extends Factory
{
    protected $model = Ward::class;

    public function definition()
    {
        $wardNames = [
            'Katsina Urban', 'Katsina Rural', 'Dorowa', 'Fada', 'Maika', 'Jingine', 
            'Kankara Central', 'Kankara East', 'Kankara West', 'Dutsin-Ma North', 
            'Dutsin-Ma South', 'Faskari East', 'Faskari West', 'Bindawa Central', 
            'Bindawa North', 'Danja Central', 'Danja East', 'Kafur Central', 
            'Kafur East', 'Kaita Central', 'Kurfi Central', 'Zango Central', 
            'Batagarawa Central', 'Jibia Central', 'Katsina Central', 'Mashi Central', 
            'Musawa Central', 'Sabuwa Central', 'Dandume Central', 'Matazu Central', 
            'Charanchi Central', 'Kankia Central', 'Rimi Central', 'Sandamu Central', 
            'Bakori Central', 'Dutsin-Ma Central', 'Funtua Central', 'Ingawa Central'
        ];

        // Katsina State is roughly between latitude 12.0°N to 13.5°N and longitude 7.0°E to 8.5°E
        return [
            'name' => $this->faker->randomElement($wardNames),
            'lga_id' => \App\Models\Lga::factory(),
            'code' => $this->faker->unique()->randomNumber(5),
            'latitude' => $this->faker->randomFloat(6, 12.0, 13.5),
            'longitude' => $this->faker->randomFloat(6, 7.0, 8.5),
        ];
    }
}