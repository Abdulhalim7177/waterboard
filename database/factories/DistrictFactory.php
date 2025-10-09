<?php

namespace Database\Factories;

use App\Models\District;
use Illuminate\Database\Eloquent\Factories\Factory;

class DistrictFactory extends Factory
{
    protected $model = District::class;

    public function definition()
    {
        $districtNames = [
            'Dutsi', 'Faskari', 'Kankara', 'Bindawa', 'Danja', 'Kafur', 'Kaita', 'Kurfi', 
            'Zango', 'Batagarawa', 'Jibia', 'Katsina', 'Mashi', 'Musawa', 'Sabuwa', 'Dandume',
            'Matazu', 'Charanchi', 'Kankia', 'Rimi', 'Sandamu', 'Bakori', 'Dutsin Ma', 'Funtua'
        ];

        return [
            'code' => 'D' . $this->faker->unique()->randomNumber(4),
            'name' => $this->faker->randomElement($districtNames),
            'zone_id' => \App\Models\Zone::factory(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}