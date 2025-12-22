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
        Schema::table('partidos', function (Blueprint $table) {
        // Eliminar el campo resultado_oficial
        $table->dropColumn('resultado_oficial');

        // Agregar el campo partido_numero
        $table->unsignedInteger('partido_numero')->nullable()->after('jornada_id');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
