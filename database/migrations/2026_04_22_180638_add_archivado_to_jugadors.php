<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 👇 Tabla jugadors
        Schema::table('jugadors', function (Blueprint $table) {
            $table->boolean('archivado')->default(false)->after('telefono');
        });

        // 👇 Tabla quinielas
        Schema::table('quinielas', function (Blueprint $table) {
            $table->boolean('archivado')->default(false)->after('jornada_id');
        });

        // 👇 Tabla pagos
        Schema::table('pagos', function (Blueprint $table) {
            $table->boolean('archivado')->default(false)->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('jugadors', function (Blueprint $table) {
            $table->dropColumn('archivado');
        });

        Schema::table('quinielas', function (Blueprint $table) {
            $table->dropColumn('archivado');
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn('archivado');
        });
    }
};
