<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use App\Models\Usuario;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(10)->create();
        Usuario::factory(10)->create();
        Empresa::factory(10)->create();

    }
}
