<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'school_name' => fake()->company(),
            'country'=> fake()->country(),
            'state_province' => fake()->state(),
            'school_board' => 'OCDSB',
            'address' => fake()->address(),
            'zip_postal' => 'K1J 7N4',
            'phone_number' => fake()->phoneNumber(),
            'fax' => fake()->randomNumber(),
            'teacher_name' => fake()->firstName() . ' ' . fake()->lastName(),
            'teacher_email' => fake()->email(),
            'teacher_cell' => fake()->phoneNumber(),
        ];
    }
}
