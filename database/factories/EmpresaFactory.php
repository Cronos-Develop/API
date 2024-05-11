<?php

namespace Database\Factories;

use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $usr_id = Usuario::all()->random()->id;
        return [
            'usuario_id' => $usr_id,
            // 'usuario_parceiro_id' => Usuario::all()->except($usr_id)->random()->id,
            'nome_da_empresa' => $this->faker->company(),
            'nicho' => $this->faker->jobTitle(),
            'resumo' => $this->faker->realText($maxNbChars = 200, $indexSize = 2)
        ];
    }
}
