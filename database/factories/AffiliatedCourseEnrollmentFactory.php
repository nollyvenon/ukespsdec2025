<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AffiliatedCourseEnrollment>
 */
class AffiliatedCourseEnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'affiliated_course_id' => \App\Models\AffiliatedCourse::factory(),
            'user_id' => \App\Models\User::factory(),
            'enrollment_status' => fake()->randomElement(['enrolled', 'completed', 'dropped', 'in_progress']),
            'completion_percentage' => fake()->numberBetween(0, 100),
            'grade' => fake()->randomElement(['A', 'B', 'C', 'D', 'F', null]),
            'certificate_path' => fake()->url(),
        ];
    }
}