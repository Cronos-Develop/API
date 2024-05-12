<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Pergunta;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Sequence;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Popula tabela 'usuarios' e 'empresas'
        $usuarios = Usuario::factory(10)->create();
        $empresas = Empresa::factory(10)->create();

        // popula a tabela de junção 'empresa_usuario'
        $usuarios->each(function (Usuario $usr) use ($empresas) {
            $usr->empresasParceiras()->syncWithoutDetaching($empresas->random()->id);
        });
        $empresas->each(function (Empresa $emp) use ($usuarios) {
            $emp->usuariosParceiros()->syncWithoutDetaching($usuarios->random()->id);
        });

        //Define as perguntas na tabela 'perguntas'
        Pergunta::factory()->count(7)->sequence(
            ['pergunta' => 'O quê'],
            ['pergunta' => 'Por que'],
            ['pergunta' => 'Quem'],
            ['pergunta' => 'Quanto'],
            ['pergunta' => 'Como'],
            ['pergunta' => 'Quando'],
            ['pergunta' => 'Onde']
        )->create();
    }
}
