<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseEnrollment>
 */
class CourseEnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => \App\Models\User::factory(),
            'course_id' => \App\Models\Course::factory(),
            'enrollment_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'enrollment_status' => fake()->randomElement(['enrolled', 'in_progress', 'completed', 'dropped', 'pending']),
            'completion_percentage' => fake()->numberBetween(0, 100),
            'grade' => fake()->randomElement(['A', 'B', 'C', 'D', 'F', null]),
        ];
    }
}