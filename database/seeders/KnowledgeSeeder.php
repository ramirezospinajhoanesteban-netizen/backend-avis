<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Knowledge;

class KnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $preguntas = [

            [
                'pregunta' => 'que es el sena',
                'respuesta' => 'El SENA es una institución pública de Colombia que ofrece formación técnica y tecnológica gratuita para el trabajo.',
                'categoria' => 'informacion'
            ],

            [
                'pregunta' => 'que programas ofrece el sena',
                'respuesta' => 'El SENA ofrece programas técnicos, tecnólogos y cursos cortos en áreas como software, administración, salud, logística y más.',
                'categoria' => 'programas'
            ],

            [
                'pregunta' => 'que es sofia plus',
                'respuesta' => 'Sofia Plus es la plataforma del SENA donde los aspirantes pueden inscribirse a los programas de formación.',
                'categoria' => 'plataforma'
            ],

            [
                'pregunta' => 'cuando se abren las inscripciones del sena',
                'respuesta' => 'Las inscripciones del SENA se abren varias veces al año. Debes consultar las convocatorias en la página oficial o en Sofia Plus.',
                'categoria' => 'inscripciones'
            ],

            [
                'pregunta' => 'como inscribirse en el sena',
                'respuesta' => 'Para inscribirte debes ingresar a la plataforma Sofia Plus, buscar el programa de interés y completar el proceso de inscripción.',
                'categoria' => 'inscripciones'
            ],

            [
                'pregunta' => 'que documentos necesito para inscribirme en el sena',
                'respuesta' => 'Generalmente necesitas tu documento de identidad y cumplir con los requisitos del programa de formación.',
                'categoria' => 'inscripciones'
            ],

            [
                'pregunta' => 'donde queda el sena',
                'respuesta' => 'El SENA tiene centros de formación en todo Colombia. Puedes consultar la sede más cercana en la página oficial del SENA.',
                'categoria' => 'ubicacion'
            ],

            [
                'pregunta' => 'como encontrar un centro de formacion del sena',
                'respuesta' => 'Puedes encontrar el centro de formación más cercano consultando la página oficial del SENA o la plataforma Sofia Plus.',
                'categoria' => 'ubicacion'
            ],

            [
                'pregunta' => 'que es adso',
                'respuesta' => 'ADSO significa Análisis y Desarrollo de Software, un programa tecnólogo del SENA enfocado en programación, bases de datos y desarrollo de aplicaciones.',
                'categoria' => 'programas'
            ],

            [
                'pregunta' => 'cuanto dura el programa adso',
                'respuesta' => 'El programa ADSO del SENA generalmente tiene una duración aproximada de 27 meses incluyendo etapa lectiva y práctica.',
                'categoria' => 'programas'
            ],

            [
                'pregunta' => 'que se aprende en adso',
                'respuesta' => 'En ADSO se aprende programación, desarrollo web, bases de datos, análisis de sistemas y metodologías de desarrollo de software.',
                'categoria' => 'programas'
            ],

        ];

        foreach ($preguntas as $pregunta) {
            Knowledge::create($pregunta);
        }
    }
}
