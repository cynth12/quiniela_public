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
        Schema::table('resultados', function (Blueprint $table) {
            // Elimina las columnas viejas si existen
            if (Schema::hasColumn('resultados', 'jornada_id')) {
                $table->dropForeign(['jornada_id']);
                $table->dropColumn('jornada_id');
            }
            if (Schema::hasColumn('resultados', 'partido_id')) {
                $table->dropForeign(['partido_id']);
                $table->dropColumn('partido_id');
            }

            // Agrega las nuevas columnas
            $table->unsignedInteger('numero')->after('id');          // Jornada visible
            $table->unsignedInteger('partido_numero')->after('numero'); // Número del partido dentro de la jornada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resultados', function (Blueprint $table) {
            //
        });
    }
};
