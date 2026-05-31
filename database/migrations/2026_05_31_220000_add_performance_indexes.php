<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega índices en columnas created_at y user_id que son
     * consultadas frecuentemente en el Dashboard y panel de usuarios.
     * Mejora drásticamente el rendimiento sobre tablas con miles de filas.
     */
    public function up(): void
    {
        // Índice en visitas.created_at → para GROUP BY DATE(created_at)
        Schema::table('visitas', function (Blueprint $table) {
            $table->index('created_at', 'idx_visitas_created_at');
            $table->index('user_id',    'idx_visitas_user_id');
        });

        // Índice en errores.created_at → para GROUP BY DATE(created_at)
        Schema::table('errores', function (Blueprint $table) {
            $table->index('created_at', 'idx_errores_created_at');
        });

        // Índice en knowledge.status → para filtros WHERE status = '...'
        Schema::table('knowledge', function (Blueprint $table) {
            $table->index('status', 'idx_knowledge_status');
        });

        // Índice en users.created_at → para GROUP BY DATE(created_at)
        Schema::table('users', function (Blueprint $table) {
            $table->index('created_at', 'idx_users_created_at');
        });
    }

    public function down(): void
    {
        Schema::table('visitas', function (Blueprint $table) {
            $table->dropIndex('idx_visitas_created_at');
            $table->dropIndex('idx_visitas_user_id');
        });

        Schema::table('errores', function (Blueprint $table) {
            $table->dropIndex('idx_errores_created_at');
        });

        Schema::table('knowledge', function (Blueprint $table) {
            $table->dropIndex('idx_knowledge_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_created_at');
        });
    }
};
