<?php

namespace Database\Factories;

use App\Models\Empresa;
use App\Models\Pergunta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\T5w2h>
 */
class T5w2hFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //'empresa_id' => Empresa::all()->random()->id,
            'resposta' => fake()->sentence(),
        ];
    }
}
