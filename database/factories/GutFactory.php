<?php

namespace Database\Factories;

use App\Models\T5w2h;
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
            '5w2h_id' => T5w2h::all()->random()->id,
            'gravidade' => fake()->numberBetween(1, 5),
            'urgencia' => fake()->numberBetween(1, 5),
            'tendencia' => fake()->numberBetween(1, 5),
        ];
    }
}
