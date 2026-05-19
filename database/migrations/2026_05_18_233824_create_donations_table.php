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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->integer('amount'); // in cents
            $table->string('currency')->default('COP');
            $table->string('name');
            $table->string('email');
            $table->text('message')->nullable();
            $table->enum('status', ['PENDING', 'APPROVED', 'DECLINED', 'ERROR'])->default('PENDING');
            $table->string('wompi_transaction_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
