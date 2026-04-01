<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KnowledgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $preguntas = [
            // Banco de preguntas originales
            [
                'pregunta' => '¿Qué es el SENA?',
                'respuesta' => 'El Servicio Nacional de Aprendizaje (SENA) es un establecimiento público del orden nacional en Colombia que ofrece formación gratuita a millones de colombianos en programas técnicos, tecnológicos y complementarios.',
                'categoria' => 'general'
            ],
            [
                'pregunta' => '¿Cómo me registro en el SENA?',
                'respuesta' => 'Para registrarte en el SENA debes ingresar al portal de SOFIA Plus (senasofiaplus.edu.co), hacer clic en el botón "Registrarse", validar tu identidad eligiendo el tipo/número de documento y llenar el formulario para generar una contraseña.',
                'categoria' => 'registro'
            ],
            [
                'pregunta' => '¿Qué programas ofrece el SENA?',
                'respuesta' => 'El SENA ofrece formación presencial y virtual en niveles de Operario, Auxiliar, Técnico, Tecnólogo y Especialización Tecnológica, además de cursos cortos en áreas de Salud, Sistemas, Comercio, Agro, etc.',
                'categoria' => 'programas'
            ],
            [
                'pregunta' => '¿Cómo recuperar mi contraseña?',
                'respuesta' => 'Si olvidaste tu contraseña de SOFIA Plus, ingresa al portal, ve a "Olvidó su contraseña", digita tu tipo y número de documento, y revisa el correo electrónico registrado para las instrucciones de recuperación.',
                'categoria' => 'soporte'
            ],
            [
                'pregunta' => '¿Qué es Sofia Plus?',
                'respuesta' => 'SOFIA Plus (Sistema Optimizado para la Formación Integral del Aprendizaje Activo) es la plataforma principal tecnológica del SENA, donde se gestionan los procesos formativos, inscripciones, certificados y novedades.',
                'categoria' => 'plataforma'
            ],
            [
                'pregunta' => '¿Cómo inscribirme a un curso?',
                'respuesta' => '1. Ingresa a SOFIA Plus. 2. Usa el buscador para encontrar un curso. 3. Verifica los requisitos y horarios. 4. Haz clic en "Inscripción". 5. Confirma tu registro ingresando tu usuario y contraseña.',
                'categoria' => 'inscripcion'
            ],

            // NUEVAS PREGUNTAS AÑADIDAS
            [
                'pregunta' => '¿Qué es la etapa productiva?',
                'respuesta' => 'Es la fase de la formación en la que el aprendiz SENA aplica, complementa y consolida sus competencias en un entorno laboral real para la resolución de problemas y desempeño productivo en una empresa.',
                'categoria' => 'general'
            ],
            [
                'pregunta' => '¿Cómo busco ofertas de empleo en la Agencia Pública de Empleo APE o sena empleo?',
                'respuesta' => 'Ingresa a ape.sena.edu.co, regístrate o inicia sesión, completa tu hoja de vida y usa el buscador de vacantes por palabra clave o departamento para postularte.',
                'categoria' => 'plataforma'
            ],
            [
                'pregunta' => '¿Qué es un contrato de aprendizaje?',
                'respuesta' => 'Es una forma especial de vinculación dentro del código laboral sin subordinación, donde el estudiante recibe formación con el patrocinio de una empresa que le otorga apoyo de sostenimiento.',
                'categoria' => 'general'
            ],
            [
                'pregunta' => '¿Puedo estudiar dos carreras al mismo tiempo en el SENA?',
                'respuesta' => 'No es posible cursar dos programas de formación titulada al mismo tiempo (ej. dos tecnólogos). Sin embargo, sí puedes cursar un programa titulado y simultáneamente inscribirte en cursos cortos de formación complementaria virtual.',
                'categoria' => 'programas'
            ],
            [
                'pregunta' => '¿Cómo descargar mi certificado del SENA?',
                'respuesta' => 'Ingresa a certificados.sena.edu.co, selecciona tu tipo de documento, digita el número, haz clic en "Consultar" y podrás descargar tus certificados de formación aprobada.',
                'categoria' => 'soporte'
            ],
            [
                'pregunta' => '¿Cuales son los requisitos de ingreso al sena?',
                'respuesta' => 'Los requisitos varían según el nivel: para operarios/auxiliares piden grado 5 o 9 aprobado. Para técnicos, ser bachiller o noveno grado. Para tecnólogos, tener diploma de bachiller y haber presentado las pruebas ICFES saber 11.',
                'categoria' => 'registro'
            ],
            [
                'pregunta' => '¿Qué es el Fondo Emprender?',
                'respuesta' => 'El Fondo Emprender es un modelo de capital semilla del SENA creado para financiar iniciativas empresariales de aprendices y diferentes colombianos, buscando transformar ideas en negocios y empresas.',
                'categoria' => 'general'
            ],

            // NUEVAS PREGUNTAS AÑADIDAS DESDE EL FRONTEND
            [
                'pregunta' => '¿Cuando abren las inscripciones?',
                'respuesta' => 'Las fechas de inscripción a los programas de formación del SENA varían según la convocatoria (normalmente trimestral). Te invitamos a consultar el calendario académico oficial en el portal SOFIA Plus para conocer las fechas exactas.',
                'categoria' => 'inscripcion'
            ],
            [
                'pregunta' => '¿Ubicacion de instalaciones?',
                'respuesta' => 'El SENA cuenta con múltiples centros de formación en todo el país. Para encontrar la sede más cercana a ti, puedes consultar el directorio de la sección de "Sedes" en el portal oficial del SENA.',
                'categoria' => 'general'
            ],
            [
                'pregunta' => '¿Cómo puedo crear una cuenta?',
                'respuesta' => 'Para crear una cuenta en la plataforma AVIS, dirígete a la opción de registro que se encuentra en la pantalla de inicio de sesión. Completa y envía tus datos para que un administrador pueda habilitar tu perfil.',
                'categoria' => 'plataforma'
            ],
            [
                'pregunta' => '¿Cuáles son los planes disponibles?',
                'respuesta' => 'Actualmente el acceso a todos los servicios de la plataforma AVIS del SENA es completamente gratuito, por lo cual no existen modalidades ni planes de pago.',
                'categoria' => 'general'
            ],
            [
                'pregunta' => '¿Cómo contacto a soporte?',
                'respuesta' => 'Para temas relacionados con las plataformas del SENA que AVIS no haya podido resolver, te puedes acercar a la oficina de atención al ciudadano de tu centro de formación o comunicarte a las líneas gratuitas nacionales del contact center.',
                'categoria' => 'soporte'
            ],
            [
                'pregunta' => '¿Qué es AVIS?',
                'respuesta' => 'AVIS es el Asistente Virtual Inteligente de Soporte diseñado para resolver tus dudas e inquietudes de manera inmediata sobre los procesos, plataformas y programas de formación de nuestra institución.',
                'categoria' => 'plataforma'
            ]
        ];

        // Insertar registros, actualizando si ya existen (updateOrInsert evita duplicados y actualiza campos)
        foreach ($preguntas as $item) {
            DB::table('knowledge')->updateOrInsert(
                ['pregunta' => $item['pregunta']],
                $item
            );
        }
    }
}
