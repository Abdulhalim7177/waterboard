<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        $lga = \App\Models\Lga::inRandomOrder()->first();
        $ward = $lga ? \App\Models\Ward::where('lga_id', $lga->id)->inRandomOrder()->first() : null;
        $area = $ward ? \App\Models\Area::where('ward_id', $ward->id)->inRandomOrder()->first() : null;
        $tariff = \App\Models\Tariff::inRandomOrder()->first();
        
        // Katsina State is roughly between latitude 12.0°N to 13.5°N and longitude 7.0°E to 8.5°E
        $latitude = $this->faker->randomFloat(6, 12.0, 13.5);
        $longitude = $this->faker->randomFloat(6, 7.0, 8.5);

        return [
            'first_name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->numerify('##########'),
            'alternate_phone_number' => $this->faker->optional()->numerify('##########'),
            'street_name' => $this->faker->streetName(),
            'house_number' => $this->faker->buildingNumber(),
                        'area_id' => $area ? $area->id : null,
                        'lga_id' => $lga ? $lga->id : null,
                        'ward_id' => $ward ? $ward->id : null,
                        'category_id' => $tariff->category_id,
                        'tariff_id' => $tariff->id,
                        'delivery_code' => 'DEL' . $this->faker->unique()->randomNumber(6),
                        'billing_condition' => $this->faker->randomElement(['Metered', 'Non-Metered']),
                        'water_supply_status' => $this->faker->randomElement(['Functional', 'Non-Functional']),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'altitude' => $this->faker->randomFloat(2, 200, 800), // Altitude in meters above sea level
                        'polygon_coordinates' => json_encode([
                            [$latitude + $this->faker->randomFloat(6, -0.001, 0.001), $longitude + $this->faker->randomFloat(6, -0.001, 0.001)],
                            [$latitude + $this->faker->randomFloat(6, -0.001, 0.001), $longitude + $this->faker->randomFloat(6, 0.001, 0.002)],
                            [$latitude + $this->faker->randomFloat(6, 0.001, 0.002), $longitude + $this->faker->randomFloat(6, 0.001, 0.002)],
                            [$latitude + $this->faker->randomFloat(6, 0.001, 0.002), $longitude + $this->faker->randomFloat(6, -0.001, 0.001)],
                        ]),
                        'pipe_path' => json_encode([
                            [$latitude - 0.0005, $longitude - 0.0005],
                            [$latitude, $longitude],
                        ]),
            'password' => Hash::make('password'),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'meter_reading' => $this->faker->randomFloat(2, 0, 10000),
            'account_balance' => $this->faker->randomFloat(2, -1000, 10000),
        ];
    }
}