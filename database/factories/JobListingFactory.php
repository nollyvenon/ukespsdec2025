<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobListing>
 */
class JobListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'posted_by' => \App\Models\User::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraph(),
            'requirements' => fake()->paragraph(),
            'responsibilities' => fake()->paragraph(),
            'salary_min' => fake()->randomFloat(2, 30000, 50000),
            'salary_max' => fake()->randomFloat(2, 60000, 120000),
            'job_type' => fake()->randomElement(['full_time', 'part_time', 'contract', 'internship', 'remote']),
            'experience_level' => fake()->randomElement(['entry', 'mid', 'senior', 'executive']),
            'location' => fake()->city(),
            'job_status' => fake()->randomElement(['draft', 'published', 'closed', 'cancelled']),
            'application_deadline' => fake()->dateTimeBetween('+1 month', '+3 months'),
        ];
    }
}