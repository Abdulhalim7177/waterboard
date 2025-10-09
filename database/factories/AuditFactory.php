<?php

namespace Database\Factories;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditFactory extends Factory
{
    protected $model = Audit::class;

    public function definition()
    {
        $models = ['Customer', 'Staff', 'Vendor', 'Lga', 'Ward', 'Area', 'Bill', 'Payment'];
        
        return [
            'user_type' => $this->faker->randomElement(['Staff', 'Customer', 'Vendor']),
            'user_id' => $this->faker->numberBetween(1, 100),
            'action' => $this->faker->randomElement(['created', 'updated', 'deleted', 'viewed']),
            'auditable_type' => $this->faker->randomElement($models),
            'auditable_id' => $this->faker->numberBetween(1, 100),
            'old_values' => $this->faker->optional()->text(200),
            'new_values' => $this->faker->optional()->text(200),
            'url' => $this->faker->url(),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'tags' => $this->faker->optional()->word(),
        ];
    }
}