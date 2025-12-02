<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\University>
 */
class UniversityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'acronym' => fake()->lexify('???'),
            'logo' => fake()->url(),
            'location' => fake()->city(),
            'country_id' => \App\Models\Country::factory(),
            'description' => fake()->paragraph(),
            'website' => fake()->url(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'rating' => fake()->randomFloat(2, 1, 5),
            'programs' => json_encode(fake()->words(5)),
            'admissions' => json_encode(fake()->words(5)),
            'facilities' => json_encode(fake()->words(5)),
        ];
    }
}