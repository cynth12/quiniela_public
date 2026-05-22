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
        Schema::table('ganadores', function (Blueprint $table) {
            $table->integer('posicion')->nullable()->after('jugador_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ganadores', function (Blueprint $table) {
            $table->dropColumn('posicion');
        });
    }
};