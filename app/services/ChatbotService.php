<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Knowledge;
use Illuminate\Support\Str;

class ChatbotService
{
    /**
     * Procesa el mensaje del usuario y genera una respuesta basada en la base de conocimiento o la por defecto.
     */
    public function procesar(string $mensaje, ?int $userId, string $sessionId): string
    {
        // 1. Guardar el mensaje del usuario
        Message::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'role' => 'user',
            'content' => $mensaje,
        ]);

        // 2. Obtener contexto básico (últimos 5 mensajes, opcional para uso avanzado posterior)
        $contexto = $this->obtenerContextoBot($userId, $sessionId);

        // 3. Determinar la intención y generar respuesta
        $respuesta = $this->generarRespuesta($mensaje, $contexto);

        // 4. Guardar la respuesta del bot
        Message::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'role' => 'assistant',
            'content' => $respuesta,
        ]);

        return $respuesta;
    }

    /**
     * Lógica principal para separar cómo se decide qué responder
     */
    protected function generarRespuesta(string $mensaje, $contexto): string
    {
        // 1. Intentar buscar primero en la base de datos (Knowledge SENA)
        $respuestaBd = $this->buscarEnBaseDeDatos($mensaje);

        if ($respuestaBd) {
            return $respuestaBd;
        }

        // 2. Si no encuentra nada, usar la lógica por defecto estructurada (match)
        return $this->respuestaPorDefecto($mensaje);
    }

    /**
     * Busca coincidencias en la tabla knowledge usando "keyword matching" o palabras clave
     */
    protected function buscarEnBaseDeDatos(string $mensaje): ?string
    {
        // 1. Limpiar y obtener palabras clave del mensaje del usuario
        $palabrasUsuario = $this->extraerPalabrasClave($mensaje);

        if (empty($palabrasUsuario)) {
            return null;
        }

        // 2. Traer todas las preguntas de la base de conocimientos
        // Para bases de datos gigantes se usa SQL FullText. Para un número razonable de FAQs, iterar es perfecto en PHP.
        $conocimientos = Knowledge::all();
        
        $mejorMatch = null;
        $maxPuntaje = 0;

        // 3. Evaluar cada pregunta
        foreach ($conocimientos as $item) {
            $palabrasPregunta = $this->extraerPalabrasClave($item->pregunta);
            
            // 4. Calcular el puntaje de similitud (cuántas palabras clave coinciden)
            $puntaje = 0;
            foreach ($palabrasUsuario as $palabra) {
                if (in_array($palabra, $palabrasPregunta)) {
                    $puntaje++;
                }
            }

            // Actualizar si esta pregunta tiene mayor puntaje que la anterior
            if ($puntaje > $maxPuntaje) {
                $maxPuntaje = $puntaje;
                $mejorMatch = $item;
            }
        }

        // 5. Validar umbral mínimo.
        // Requerimos al menos 2 coincidencias, salvo que la pregunta del usuario sea muy corta (de 1 palabra clave).
        $umbral = count($palabrasUsuario) > 1 ? 2 : 1;
        
        if ($mejorMatch && $maxPuntaje >= $umbral) {
            return $mejorMatch->respuesta;
        }

        // 6. Si no alcanza el puntaje, retornar null (continúa a la lógica por defecto)
        return null;
    }

    /**
     * Helper: Limpia un texto, remueve signos y filtra "stop words" o palabras vacías en español
     */
    protected function extraerPalabrasClave(string $texto): array
    {
        // Convertir a minúsculas
        $texto = mb_strtolower(trim($texto), 'UTF-8');
        
        // Quitar signos de puntuación usando un reemplazo global (regex)
        $textoLimpio = preg_replace('/[^\w\s\á\é\í\ó\ú\ñ]/u', '', $texto);

        // Separar en un arreglo de palabras
        $palabras = explode(' ', $textoLimpio);

        // Lista enriquecida de Stop Words en español
        $stopWords = ['el', 'la', 'los', 'las', 'de', 'en', 'para', 'por', 'y', 'a', 'un', 'una', 'que', 'como', 'es', 'son', 'mi', 'tu', 'su', 'con', 'del', 'al', 'se', 'me', 'te'];

        $palabrasClave = [];

        foreach ($palabras as $palabra) {
            $palabra = trim($palabra);
            // Agregar solo si no está vacía y no hace parte de la lista de stopwords
            if (!empty($palabra) && !in_array($palabra, $stopWords)) {
                $palabrasClave[] = $palabra;
            }
        }

        return $palabrasClave;
    }

    /**
     * Respuesta cuando NO se encontró ninguna coincidencia en la Base de Conocimiento
     */
    protected function respuestaPorDefecto(string $mensaje): string
    {
        $mensajeLimpio = mb_strtolower(trim($mensaje), 'UTF-8');

        // Lógica limpia y escalable usando MATCH de PHP
        return match (true) {
            str_contains($mensajeLimpio, 'hola') || str_contains($mensajeLimpio, 'saludos') => "Hola 👋 Soy el asistente virtual de AVIS. ¿En qué te puedo ayudar sobre el SENA, SOFIA Plus o inscripción de programas?",
            str_contains($mensajeLimpio, 'ayuda') => "Puedo ayudarte con información de registro, programas del SENA o recuperación de contraseñas. Intenta escribir '¿Qué es el SENA?' o algo similar.",
            default => "No encontré información específica sobre tu consulta en mi base de conocimientos 🤔. Por favor, intenta reformular tu pregunta, por ejemplo: '¿Qué es el SENA?' o '¿Cómo me registro?'."
        };
    }

    /**
     * (Opcional) Toma los últimos 5 mensajes de la conversación y puede retornarlos
     * para usarlos como contexto extendido en futuras implementaciones (ej. con DeepSeek).
     */
    protected function obtenerContextoBot(?int $userId, string $sessionId)
    {
        $query = Message::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        // Retorna los más recientes, tomamos los últimos 5
        return $query->latest('created_at')->take(5)->pluck('content')->toArray();
    }

    /**
     * Obtiene todo el historial completo de la chat por BD, ordenado de más antiguo a más nuevo
     */
    public function getHistory(?int $userId, string $sessionId)
    {
        $query = Message::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        return $query->orderBy('created_at', 'asc')->get();
    }

    /**
     * Limpia todo el historial actual para empezar una charla de cero
     */
    public function clearHistory(?int $userId, string $sessionId): void
    {
        $query = Message::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        $query->delete();
    }
}
