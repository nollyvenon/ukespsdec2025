<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_id' => \App\Models\JobListing::factory(),
            'applicant_id' => \App\Models\User::factory(),
            'application_status' => fake()->randomElement(['pending', 'reviewed', 'shortlisted', 'rejected', 'hired']),
            'cover_letter' => fake()->paragraph(),
            'resume_path' => fake()->url(),
            'application_date' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}