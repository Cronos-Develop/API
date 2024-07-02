<?php

namespace Database\Seeders;

use App\Models\Pergunta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerguntasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
