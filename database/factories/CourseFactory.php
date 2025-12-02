<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'instructor_id' => \App\Models\User::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(4, 24), // weeks
            'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced', 'all_levels']),
            'start_date' => fake()->dateTimeBetween('+1 month', '+3 months'),
            'end_date' => fake()->dateTimeBetween('+2 months', '+6 months'),
            'course_status' => fake()->randomElement(['draft', 'published', 'ongoing', 'completed', 'cancelled']),
            'max_enrollment' => fake()->numberBetween(10, 100),
            'prerequisites' => fake()->paragraph(),
            'syllabus' => fake()->paragraph(),
        ];
    }
}