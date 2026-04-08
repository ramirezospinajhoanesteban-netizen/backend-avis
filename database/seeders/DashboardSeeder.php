<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        // 🧹 Limpiar tablas para evitar duplicados y datos viejos
        DB::table('estadisticas_diarias')->truncate();
        DB::table('visitas')->truncate();
        DB::table('errores')->truncate();

        $now = Carbon::now();

        // =========================
        // 🔹 GENERAR VISITAS Y ERRORES REALES (Para conteo en Controller)
        // =========================
        for ($i = 0; $i < 7; $i++) {
            $date = (clone $now)->subDays(6 - $i);
            
            // Generar entre 100 y 300 visitas por día
            $numVisitas = rand(100, 300);
            $visitasBatch = [];
            for ($j = 0; $j < $numVisitas; $j++) {
                $visitasBatch[] = [
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'url' => '/dashboard',
                    'created_at' => (clone $date)->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'updated_at' => $date
                ];
            }
            DB::table('visitas')->insert($visitasBatch);

            // Generar entre 5 y 15 errores por día
            $numErrores = rand(5, 15);
            $erroresBatch = [];
            for ($j = 0; $j < $numErrores; $j++) {
                $erroresBatch[] = [
                    'mensaje' => 'Error de prueba ' . $j . ' en ' . $date->toDateString(),
                    'codigo' => rand(400, 500),
                    'ruta' => '/api/test',
                    'created_at' => (clone $date)->addHours(rand(0, 23)),
                    'updated_at' => $date
                ];
            }
            DB::table('errores')->insert($erroresBatch);
        }

        // =========================
        // 🔹 ESTADÍSTICAS DIARIAS (Resumen)
        // =========================
        $estadisticas = [];
        for ($i = 0; $i < 7; $i++) {
            $date = (clone $now)->subDays(6 - $i);
            $v = rand(500, 1500);
            $ur = rand(100, 500);
            
            $estadisticas[] = [
                'fecha' => $date->toDateString(),
                'visitas' => $v,
                'usuarios_registrados' => $ur,
                'usuarios_no_registrados' => $v - $ur,
                'errores' => rand(5, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('estadisticas_diarias')->insert($estadisticas);

        // =========================
        // 🔹 USUARIOS DE PRUEBA
        // =========================
        if (User::count() < 10) {
            $users = [
                ['name' => 'Admin AVIS', 'email' => 'admin@avis.com', 'role' => 'admin', 'is_active' => true],
                ['name' => 'Pepito Perez', 'email' => 'pepito@test.com', 'role' => 'admin', 'is_active' => true],
                ['name' => 'Juanita Gomez', 'email' => 'juanita@test.com', 'role' => 'aprendiz', 'is_active' => true],
                ['name' => 'Carlos Rodriguez', 'email' => 'carlos@test.com', 'role' => 'aprendiz', 'is_active' => false],
                ['name' => 'Valentina Zuarez', 'email' => 'valentina@test.com', 'role' => 'instructor', 'is_active' => true],
                ['name' => 'Kamila Garcia', 'email' => 'kamila@test.com', 'role' => 'admin', 'is_active' => true],
            ];

            foreach ($users as $u) {
                User::updateOrCreate(
                    ['email' => $u['email']],
                    array_merge($u, ['password' => bcrypt('password')])
                );
            }
        }
    }
}
