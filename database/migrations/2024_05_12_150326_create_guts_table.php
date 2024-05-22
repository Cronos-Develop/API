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
        Schema::create('guts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('5w2h_id')->references('id')->on('t5w2hs');
            $table->integer('gravidade');
            $table->integer('urgencia');
            $table->integer('tendencia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guts');
    }
};
