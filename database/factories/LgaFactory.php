<?php

namespace Database\Factories;

use App\Models\Lga;
use Illuminate\Database\Eloquent\Factories\Factory;

class LgaFactory extends Factory
{
    protected $model = Lga::class;

    public function definition()
    {
        $katsinaLgas = [
            'Bakori', 'Batagarawa', 'Batsari', 'Baure', 'Bindawa', 'Charanchi', 
            'Dandume', 'Danja', 'Daura', 'Dutsi', 'Dutsin Ma', 'Faskari', 'Funtua', 
            'Ingawa', 'Jibia', 'Kafur', 'Kaita', 'Kankara', 'Kankia', 'Katsina', 
            'Kurfi', 'Kusada', 'Mai Adua', 'Malumfashi', 'Mani', 'Mashi', 'Matazu', 
            'Musawa', 'Rimi', 'Sabuwa', 'Safana', 'Sandamu', 'Zango'
        ];

        // Katsina State is roughly between latitude 12.0°N to 13.5°N and longitude 7.0°E to 8.5°E
        return [
            'name' => $this->faker->randomElement($katsinaLgas),
            'code' => $this->faker->unique()->randomNumber(5),
            'latitude' => $this->faker->randomFloat(6, 12.0, 13.5),
            'longitude' => $this->faker->randomFloat(6, 7.0, 8.5),
        ];
    }
}