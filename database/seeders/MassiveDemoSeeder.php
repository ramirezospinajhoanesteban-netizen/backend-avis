<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MassiveDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Genera una gran cantidad de datos de prueba distribuidos por días para presentaciones.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $days = 30; // Generar datos para los últimos 30 días

        $rutas = ['/api/chat', '/api/knowledge', '/api/users', '/api/dashboard', '/api/login', '/', '/dashboard', '/register'];
        $agentes = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/122.0.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) Firefox/123.0',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15',
        ];

        $errores_tipos = [
            ['codigo' => 404, 'mensaje' => 'Ruta no encontrada (Endpoint inválido)'],
            ['codigo' => 500, 'mensaje' => 'Error interno del servidor (Fallo en conexión DB)'],
            ['codigo' => 401, 'mensaje' => 'Token de autenticación expirado'],
            ['codigo' => 403, 'mensaje' => 'Acceso denegado (Permisos insuficientes)'],
            ['codigo' => 422, 'mensaje' => 'Error de validación (Campos obligatorios vacíos)'],
            ['codigo' => 503, 'mensaje' => 'Servicio no disponible (Mantenimiento)'],
        ];

        $preguntas_ejemplo = [
            '¿Cómo me inscribo al SENA?', 
            '¿Cuáles son los requisitos para técnicos?', 
            '¿Dónde puedo ver los resultados de las pruebas?', 
            '¿Cómo recupero mi contraseña de Sofia Plus?', 
            '¿Qué es la etapa productiva?', 
            '¿Hay cursos de inglés disponibles?', 
            '¿Cómo contacto a Bienestar al Aprendiz?', 
            '¿Cuándo abren nuevas convocatorias?',
            '¿Cómo descargar certificados?',
            '¿Qué beneficios tiene el carnet?',
            '¿Cómo solicito un traslado de centro?',
            '¿Puedo estudiar virtualmente?',
            '¿Qué es el Fondo Emprender?',
            '¿Cómo aplico a monitorías?'
        ];

        $categorias = ['General', 'Registro', 'Programas', 'Soporte', 'Plataforma', 'Inscripción'];

        $this->command->info("Generando datos de prueba para los últimos $days días...");

        for ($i = $days; $i >= 0; $i--) {
            $date = (clone $now)->subDays($i);
            $dateString = $date->toDateString();

            // 1. Generar Visitas (Volumen alto para gráficas bonitas)
            $numVisitas = rand(80, 250);
            $visitas = [];
            for ($j = 0; $j < $numVisitas; $j++) {
                $visitas[] = [
                    'ip_address' => rand(180, 200) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 254),
                    'user_agent' => $agentes[array_rand($agentes)],
                    'url' => $rutas[array_rand($rutas)],
                    'user_id' => rand(0, 10) > 8 ? rand(1, 10) : null,
                    'created_at' => (clone $date)->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'updated_at' => (clone $date),
                ];
            }
            DB::table('visitas')->insert($visitas);

            // 2. Generar Errores (Realistas)
            $numErrores = rand(2, 12);
            $errores = [];
            for ($j = 0; $j < $numErrores; $j++) {
                $err = $errores_tipos[array_rand($errores_tipos)];
                $errores[] = [
                    'mensaje' => $err['mensaje'] . ' (Simulado #' . rand(100, 999) . ')',
                    'codigo' => $err['codigo'],
                    'ruta' => $rutas[array_rand($rutas)],
                    'created_at' => (clone $date)->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'updated_at' => (clone $date),
                ];
            }
            DB::table('errores')->insert($errores);

            // 3. Generar Nuevos Usuarios (Ocasionales)
            if (rand(0, 10) > 6) {
                $numNuevos = rand(1, 4);
                for($j = 0; $j < $numNuevos; $j++) {
                    $nombreReal = fake('es_ES')->name();
                    $emailReal = fake()->unique()->safeEmail();
                    
                    User::create([
                        'name' => $nombreReal,
                        'email' => $emailReal,
                        'password' => bcrypt('password123'),
                        'role' => rand(0, 10) > 8 ? 'instructor' : 'aprendiz',
                        'is_active' => true,
                        'created_at' => (clone $date)->addHours(rand(0, 23)),
                        'updated_at' => (clone $date),
                    ]);
                }
            }

            // 4. Generar Preguntas de Chatbot (Knowledge)
            $numPreguntas = rand(3, 10);
            $knowledgeData = [];
            for ($j = 0; $j < $numPreguntas; $j++) {
                $isPending = rand(0, 10) > 7; // 20% pendientes
                $pregunta = $preguntas_ejemplo[array_rand($preguntas_ejemplo)];
                
                $knowledgeData[] = [
                    'pregunta' => $pregunta . ' (' . Str::random(4) . '?)',
                    'respuesta' => $isPending ? null : 'Esta es una respuesta automática generada para la demo sobre: ' . $pregunta,
                    'status' => $isPending ? 'pendiente' : 'respondida',
                    'categoria' => $categorias[array_rand($categorias)],
                    'created_at' => (clone $date)->addHours(rand(0, 23)),
                    'updated_at' => (clone $date),
                ];
            }
            DB::table('knowledge')->insert($knowledgeData);

            // 5. Actualizar Estadísticas Diarias (Resumen para Dashboard)
            $totalVisitas = $numVisitas + rand(200, 800); // Inflar un poco para que se vea tráfico masivo
            $reg = rand(50, 150);
            DB::table('estadisticas_diarias')->updateOrInsert(
                ['fecha' => $dateString],
                [
                    'visitas' => $totalVisitas,
                    'usuarios_registrados' => $reg,
                    'usuarios_no_registrados' => $totalVisitas - $reg,
                    'errores' => $numErrores,
                    'created_at' => (clone $date),
                    'updated_at' => (clone $date),
                ]
            );
        }

        $this->command->info("¡Datos de prueba generados exitosamente!");
    }
}
