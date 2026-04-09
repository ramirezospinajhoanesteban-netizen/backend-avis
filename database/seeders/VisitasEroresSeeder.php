<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VisitasEroresSeeder extends Seeder
{
    public function run(): void
    {
        $rutas = ['/api/chat', '/api/knowledge', '/api/users', '/api/dashboard', '/api/login'];
        $agentes = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Safari/537.36',
            'Mozilla/5.0 (Linux; Android 13) Mobile Chrome/119.0',
        ];
        $ips = ['192.168.1.10', '192.168.1.22', '10.0.0.5', '172.16.0.3', '190.25.40.11'];

        // Generar 60 visitas en los últimos 30 días
        for ($i = 0; $i < 60; $i++) {
            DB::table('visitas')->insert([
                'user_id'    => rand(0, 1) ? rand(1, 3) : null,
                'ip_address' => $ips[array_rand($ips)],
                'user_agent' => $agentes[array_rand($agentes)],
                'url'        => $rutas[array_rand($rutas)],
                'created_at' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Generar 20 errores en los últimos 30 días
        $errores = [
            ['codigo' => 404, 'mensaje' => 'Ruta no encontrada'],
            ['codigo' => 500, 'mensaje' => 'Error interno del servidor'],
            ['codigo' => 401, 'mensaje' => 'No autenticado'],
            ['codigo' => 403, 'mensaje' => 'Acceso denegado'],
            ['codigo' => 422, 'mensaje' => 'Error de validación'],
        ];

        for ($i = 0; $i < 20; $i++) {
            $error = $errores[array_rand($errores)];
            DB::table('errores')->insert([
                'user_id'    => rand(0, 1) ? rand(1, 3) : null,
                'mensaje'    => $error['mensaje'],
                'codigo'     => $error['codigo'],
                'ruta'       => $rutas[array_rand($rutas)],
                'trace'      => null,
                'created_at' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
