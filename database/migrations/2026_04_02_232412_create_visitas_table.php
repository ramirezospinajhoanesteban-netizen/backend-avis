<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();

            // Relación con usuario (puede ser null si es visitante)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // IP del visitante
            $table->string('ip_address')->nullable();

            // Navegador / dispositivo
            $table->text('user_agent')->nullable();

            // URL visitada
            $table->string('url')->nullable();

            $table->timestamps();

            // Índices (IMPORTANTE 🚀)
            $table->index('created_at');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitas');
    }
};
