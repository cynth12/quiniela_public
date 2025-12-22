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
        Schema::create('respuestas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('quiniela_id'); // vínculo con la quiniela
        $table->unsignedBigInteger('partido_numero'); // número del partido dentro de la jornada
        $table->string('respuesta'); // 'l' = local, 'e' = empate, 'v' = visitante
        $table->timestamps();

        $table->foreign('quiniela_id')->references('id')->on('quinielas')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuestas');
    }
};
