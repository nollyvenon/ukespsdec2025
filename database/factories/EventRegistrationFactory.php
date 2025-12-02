<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventRegistration>
 */
class EventRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => \App\Models\Event::factory(),
            'user_id' => \App\Models\User::factory(),
            'registration_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'attendance_status' => fake()->randomElement(['registered', 'confirmed', 'attended', 'cancelled']),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'refunded', 'free']),
        ];
    }
}