<?php

namespace Database\Factories;

use App\Models\T5w2h;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subtarefa>
 */
class SubtarefaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::all()->random()->id,
            '5w2h_id' => T5w2h::all()->random()->id,
            'subtarefa' => fake()->sentence()
        ];
    }
}