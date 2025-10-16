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
        $tariff = \App\Models\Tariff::inRandomOrder()->first();
        $area = \App\Models\Area::inRandomOrder()->first();
        $lga = \App\Models\Lga::inRandomOrder()->first();
        $ward = \App\Models\Ward::inRandomOrder()->first();
        
        // Katsina State is roughly between latitude 12.0°N to 13.5°N and longitude 7.0°E to 8.5°E
        $latitude = $this->faker->randomFloat(6, 12.0, 13.5);
        $longitude = $this->faker->randomFloat(6, 7.0, 8.5);

        return [
            'first_name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'alternate_phone_number' => $this->faker->optional()->phoneNumber(),
            'street_name' => $this->faker->streetName(),
            'house_number' => $this->faker->buildingNumber(),
            'landmark' => $this->faker->optional()->sentence(3),
            'area_id' => $area->id,
            'lga_id' => $lga->id,
            'ward_id' => $ward->id,
            'category_id' => $tariff->category_id,
            'tariff_id' => $tariff->id,
            'delivery_code' => 'DEL' . $this->faker->unique()->randomNumber(6),
            'billing_id' => 'BILL' . $this->faker->unique()->randomNumber(8),
            'billing_condition' => $this->faker->randomElement(['Residential', 'Commercial', 'Industrial', 'Institutional']),
            'water_supply_status' => $this->faker->randomElement(['Connected', 'Disconnected', 'Scheduled']),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'altitude' => $this->faker->randomFloat(2, 200, 800), // Altitude in meters above sea level
            'polygon_coordinates' => json_encode([
                ['lat' => $latitude + $this->faker->randomFloat(6, -0.001, 0.001), 'lng' => $longitude + $this->faker->randomFloat(6, -0.001, 0.001)],
                ['lat' => $latitude + $this->faker->randomFloat(6, -0.001, 0.001), 'lng' => $longitude + $this->faker->randomFloat(6, 0.001, 0.002)],
                ['lat' => $latitude + $this->faker->randomFloat(6, 0.001, 0.002), 'lng' => $longitude + $this->faker->randomFloat(6, 0.001, 0.002)],
                ['lat' => $latitude + $this->faker->randomFloat(6, 0.001, 0.002), 'lng' => $longitude + $this->faker->randomFloat(6, -0.001, 0.001)],
            ]),
            'pipe_path' => json_encode([
                ['lat' => $latitude - 0.0005, 'lng' => $longitude - 0.0005],
                ['lat' => $latitude, 'lng' => $longitude],
            ]),
            'password' => Hash::make('password'),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'meter_reading' => $this->faker->randomFloat(2, 0, 10000),
            'account_balance' => $this->faker->randomFloat(2, -1000, 10000),
        ];
    }
}