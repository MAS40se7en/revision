<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company' => fake()->company(),
            'role' => fake()->jobTitle(),
            'status' => fake()->randomElement(ApplicationStatus::cases()),
            'applied_at' => fake()->optional()->date(),
            'url' => fake()->optional()->url(),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the application has the given status
     */
    public function withStatus(ApplicationStatus $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }
}
