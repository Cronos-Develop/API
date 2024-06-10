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
        Schema::create('subtarefas', function (Blueprint $table) {
            $table->id();
            $table->string('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->foreignId('5w2h_id')->references('id')->on('t5w2hs');
            $table->string('subtarefa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtarefas');
    }
};
