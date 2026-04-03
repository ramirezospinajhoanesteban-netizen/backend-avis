<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('errores', function (Blueprint $table) {
            $table->id();

            // Usuario (si estaba logeado)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Mensaje de error
            $table->text('mensaje');

            // Código HTTP (404, 500, etc)
            $table->integer('codigo')->nullable();

            // Ruta donde ocurrió
            $table->string('ruta')->nullable();

            // Stack trace (opcional)
            $table->longText('trace')->nullable();

            $table->timestamps();

            // Índices
            $table->index('created_at');
            $table->index('codigo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('errores');
    }
};