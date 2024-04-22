<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    protected static ?string $password;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('pt_BR');
        $empresario = $faker->boolean();
        return [
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'telefone' => $faker->phoneNumber(),
            'senha' => static::$password ??= Hash::make('password'),
            'endereco' => $faker->address(),
            'cep' => $faker->numerify('######-###'),
            'nascimento' => $faker->date(),
            'empresario' => $empresario,
            'cpf_cnpj' => $empresario ? $faker->cnpj() : $faker->cpf(),
            'nome_da_empresa' => $empresario ? $faker->company() : NULL
        ];
    }
}
