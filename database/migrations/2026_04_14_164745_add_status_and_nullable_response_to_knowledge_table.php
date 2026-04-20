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
        Schema::table('knowledge', function (Blueprint $table) {
            // Añadimos el estado. Por defecto 'respondida' para los datos existentes.
            $table->string('status')->default('respondida')->after('categoria');
            // Hacemos que la respuesta sea opcional para permitir preguntas pendientes.
            $table->text('respuesta')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledge', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->text('respuesta')->nullable(false)->change();
        });
    }
};
