<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            // $table->primary("id");
            $table->string('name');
            $table->string('email')->unique();
            $table->string('telefone');
            $table->string('senha');
            $table->string('endereco');
            $table->string('cep');
            $table->date('nascimento');
            $table->boolean('empresario');
            $table->string('cpf_cnpj');
            $table->string('nome_da_empresa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};