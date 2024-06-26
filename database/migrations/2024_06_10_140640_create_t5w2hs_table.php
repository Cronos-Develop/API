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
        Schema::create('t5w2hs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->references('id')->on('empresas');
            $table->foreignId('tarefa_id')->nullable()->references('id')->on('tarefas');
            $table->foreignId('pergunta_id')->references('id')->on('perguntas');
            $table->foreignId('gut_id')->nullable()->references('id')->on('guts');
            $table->string('resposta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t5w2hs');
    }
};
