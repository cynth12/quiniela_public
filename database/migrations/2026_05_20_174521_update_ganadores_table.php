<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ganadores', function (Blueprint $table) {

            $table->integer('posicion')->nullable()->change();

        });
    }

    public function down(): void
    {
        Schema::table('ganadores', function (Blueprint $table) {

            $table->enum('posicion', ['primer', 'segundo', 'tercero'])->nullable()->change();

        });
    }
};