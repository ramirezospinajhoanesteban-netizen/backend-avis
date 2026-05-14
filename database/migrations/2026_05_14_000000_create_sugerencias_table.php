<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sugerencias', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('email')->nullable();
            $table->enum('estado', ['nueva', 'revisada', 'resuelta'])->default('nueva');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sugerencias');
    }
};
