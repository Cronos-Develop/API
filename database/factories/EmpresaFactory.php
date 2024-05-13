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
        $fake = fake('pt_br');
        return [
            'usuario_id' => $usr_id,
            'nome_da_empresa' => $fake->company(),
            'nicho' => $fake->jobTitle(),
            'resumo' => $fake->realText($maxNbChars = 200, $indexSize = 2)
        ];
    }
}
