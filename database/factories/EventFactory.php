<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_by' => \App\Models\User::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'start_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'end_date' => fake()->dateTimeBetween('+2 weeks', '+2 months'),
            'location' => fake()->address(),
            'max_participants' => fake()->numberBetween(10, 500),
            'registration_deadline' => fake()->dateTimeBetween('+1 week', '+3 weeks'),
            'event_status' => fake()->randomElement(['draft', 'published', 'cancelled', 'completed']),
        ];
    }
}