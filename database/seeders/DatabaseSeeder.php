<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear usuarios administradores por defecto
        \App\Models\User::updateOrCreate(
            ['email' => 'jhoan@admin.com'],
            [
                'name' => 'jhoan',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'admin'
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'jose@admin.com'],
            [
                'name' => 'jose',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'admin'
            ]
        );

        // 2. Crear usuario aprendiz por defecto
        \App\Models\User::updateOrCreate(
            ['email' => 'porras@aprendiz.com'],
            [
                'name' => 'porras',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'aprendiz'
            ]
        );

        // 3. Poblar la base de conocimiento y estadísticas
        $this->call([
            KnowledgeSeeder::class,
            VisitasEroresSeeder::class
        ]);
    }
}
