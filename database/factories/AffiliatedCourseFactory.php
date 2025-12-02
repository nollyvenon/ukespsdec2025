<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AffiliatedCourse>
 */
class AffiliatedCourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'university_id' => \App\Models\University::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced', 'all_levels']),
            'duration' => fake()->numberBetween(4, 24), // weeks
            'start_date' => fake()->dateTimeBetween('+1 month', '+3 months'),
            'end_date' => fake()->dateTimeBetween('+2 months', '+6 months'),
            'prerequisites' => fake()->paragraph(),
            'syllabus' => fake()->paragraph(),
            'fee' => fake()->randomFloat(2, 100, 5000),
            'skills_covered' => json_encode(fake()->words(5)),
            'career_outcomes' => json_encode(fake()->words(3)),
            'status' => fake()->randomElement(['draft', 'published', 'ongoing', 'completed', 'cancelled']),
            'max_enrollment' => fake()->numberBetween(10, 100),
        ];
    }
}