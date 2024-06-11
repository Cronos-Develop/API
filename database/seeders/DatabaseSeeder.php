<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Gut;
use App\Models\Pergunta;
use App\Models\Subtarefa;
use App\Models\T5w2h;
use App\Models\Tarefa;
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
        //Popula tabelas 'usuarios' e 'empresas'
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

        // Cria 7 registros na tabela 5w2h para cada empressa representando as 7 perguntas
        $empresas->each(function (Empresa $emp) {
            $gut = Gut::factory()->create();
            $tarefa = Tarefa::factory()->create();
            $t5w2hs = T5w2h::factory(7)->sequence(
                fn (Sequence $sequence) => ['pergunta_id' => $sequence->index + 1]
            )->for($gut)->for($tarefa)->for($emp)->create();
            //Tarefa::factory()->has($t5w2hs)->create();
            //$emp->t5w2hs()->saveMany($t5w2hs);
        });


        Subtarefa::factory(25)->create();
        //Gut::factory(5)->create();
    }
}
