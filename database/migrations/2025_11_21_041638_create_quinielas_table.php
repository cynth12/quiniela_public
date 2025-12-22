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
        Schema::create('quinielas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('jugador_id'); // vínculo con jugador
        $table->unsignedBigInteger('numero'); // número de jornada
        $table->unsignedBigInteger('numero_quiniela')->unique(); // identificador único de la quiniela
        $table->boolean('pagada')->default(false); // pagada o pendiente
        $table->timestamps();

        $table->foreign('jugador_id')->references('id')->on('jugadors')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quinielas');
    }
};
