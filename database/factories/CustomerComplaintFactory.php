<?php

namespace Database\Factories;

use App\Models\CustomerComplaint;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerComplaintFactory extends Factory
{
    protected $model = CustomerComplaint::class;

    public function definition()
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'subject' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'resolved', 'closed']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'category' => $this->faker->randomElement(['billing', 'service', 'technical', 'complaint', 'suggestion']),
            'assigned_to' => \App\Models\Staff::factory(),
            'resolution_notes' => $this->faker->optional()->paragraph(),
            'resolved_at' => $this->faker->optional()->dateTime(),
        ];
    }
}