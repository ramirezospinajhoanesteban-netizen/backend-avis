<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estadisticas_diarias', function (Blueprint $table) {
            $table->id();

            // Fecha única
            $table->date('fecha')->unique();

            // Métricas
            $table->integer('visitas')->default(0);
            $table->integer('usuarios_registrados')->default(0);
            $table->integer('usuarios_no_registrados')->default(0);
            $table->integer('errores')->default(0);

            $table->timestamps();

            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadisticas_diarias');
    }
};
