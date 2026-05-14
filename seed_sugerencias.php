<?php

use App\Models\Suggestion;

$datos = [
  [
    'tipo'        => 'Reporte de error',
    'titulo'      => 'El chatbot no responde con palabras con tildes',
    'descripcion' => 'Al escribir palabras con acentos como información o después en el chatbot, a veces no genera respuesta y se queda cargando indefinidamente. El problema ocurre tanto en Chrome como en Safari desde dispositivos móviles.',
    'email'       => 'carlos.perez@sena.edu.co',
    'estado'      => 'nueva',
  ],
  [
    'tipo'        => 'Sugerencia de mejora',
    'titulo'      => 'Agregar modo oscuro en el chatbot',
    'descripcion' => 'Sería muy útil tener la opción de cambiar entre modo claro y oscuro directamente desde el panel del chatbot, especialmente para usarlo en ambientes con poca luz. Esto mejoraría bastante la experiencia de uso nocturno.',
    'email'       => 'maria.lopez@aprendiz.sena.edu.co',
    'estado'      => 'revisada',
  ],
  [
    'tipo'        => 'Solicitud de ayuda',
    'titulo'      => 'No puedo recuperar mi contraseña',
    'descripcion' => 'Intenté recuperar mi contraseña varias veces usando el correo registrado, pero nunca llega el correo de verificación. Ya revisé la carpeta de spam y tampoco está ahí. El problema persiste desde hace tres días.',
    'email'       => 'juan.gomez@sena.edu.co',
    'estado'      => 'resuelta',
  ],
  [
    'tipo'        => 'Sugerencia de mejora',
    'titulo'      => 'Permitir exportar el historial de chat en PDF',
    'descripcion' => 'Sería muy práctico poder descargar las conversaciones del chatbot en formato PDF para revisarlas sin necesidad de estar conectado. Muchos aprendices estudian en zonas con internet limitado y esto les ayudaría bastante.',
    'email'       => null,
    'estado'      => 'nueva',
  ],
  [
    'tipo'        => 'Reporte de error',
    'titulo'      => 'La página de registro no valida el formato del documento',
    'descripcion' => 'En el formulario de registro se puede ingresar letras en el campo de número de documento sin que el sistema muestre ningún error. Esto genera confusión y permite crear cuentas con datos incorrectos.',
    'email'       => 'soporte@sena.edu.co',
    'estado'      => 'nueva',
  ],
  [
    'tipo'        => 'Otra',
    'titulo'      => 'Felicitaciones por la plataforma AVIS',
    'descripcion' => 'Quiero destacar el excelente trabajo del equipo de desarrollo. La plataforma AVIS ha mejorado mucho la forma en que accedemos a información del SENA. Es intuitiva, rápida y muy útil para los aprendices de todas las regiones.',
    'email'       => 'instructor.avis@sena.edu.co',
    'estado'      => 'revisada',
  ],
];

foreach ($datos as $d) {
    Suggestion::create($d);
}

echo 'OK: ' . count($datos) . ' sugerencias insertadas correctamente.' . PHP_EOL;
