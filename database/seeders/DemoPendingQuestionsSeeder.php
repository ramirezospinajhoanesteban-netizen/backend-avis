<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoPendingQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $preguntas = [
            [
                'pregunta' => '¿Cómo me postulo a las monitorías del SENA?',
                'respuesta' => null,
                'status' => 'pendiente',
                'categoria' => 'Consultas Bot',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pregunta' => '¿Qué beneficios tiene el servicio de bienestar al aprendiz?',
                'respuesta' => null,
                'status' => 'pendiente',
                'categoria' => 'Consultas Bot',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pregunta' => '¿Dónde puedo solicitar el carnet de aprendiz si se me perdió?',
                'respuesta' => null,
                'status' => 'pendiente',
                'categoria' => 'Consultas Bot',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pregunta' => '¿Cuáles son los requisitos para el apoyo de sostenimiento?',
                'respuesta' => null,
                'status' => 'pendiente',
                'categoria' => 'Consultas Bot',
                'created_at' => now()->subHours(2),
                'updated_at' => now()
            ],
            [
                'pregunta' => '¿Cómo cambio mi correo electrónico en SOFIA Plus?',
                'respuesta' => null,
                'status' => 'pendiente',
                'categoria' => 'Consultas Bot',
                'created_at' => now()->subDay(),
                'updated_at' => now()
            ]
        ];

        foreach ($preguntas as $item) {
            DB::table('knowledge')->insert($item);
        }
    }
}
