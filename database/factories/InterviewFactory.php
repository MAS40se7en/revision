<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Interview;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Interview>
 */
class InterviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'scheduled_at' => fake()->dateTimeBetween('now', '+1 month'),
            'type' => fake()->randomElement(['phone screen', 'technical', 'onsite', 'culture fit']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
