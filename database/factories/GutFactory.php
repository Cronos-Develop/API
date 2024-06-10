<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gut>
 */
class GutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gravidade' => fake()->numberBetween(1, 5),
            'urgencia' => fake()->numberBetween(1, 5),
            'tendencia' => fake()->numberBetween(1, 5),
        ];
    }
}
