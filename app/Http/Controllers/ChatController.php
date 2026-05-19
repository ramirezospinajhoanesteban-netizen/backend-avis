<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatbotService;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    protected $chatbot;

    public function __construct(ChatbotService $chatbot)
    {
        $this->chatbot = $chatbot;
    }

    /**
     * Obtiene o genera el ID de sesión para identificar a los usuarios invitados.
     * El frontend puede enviarlo en un header (X-Session-ID) para mantener la misma
     * conversación inclusive si se recarga la página.
     */
    private function getSessionId(Request $request): string
    {
        // Prioridad 1: Header enviado por el frontend
        if ($headerSession = $request->header('X-Session-ID')) {
            return $headerSession;
        }

        // Prioridad 2: Sesión de Laravel
        if (!session()->has('chat_session_id')) {
            session()->put('chat_session_id', Str::uuid()->toString());
        }
        
        return session('chat_session_id');
    }

    /**
     * Recibe un mensaje y devuelve la respuesta del bot.
     */
    public function sendMessage(Request $request)
    {
        try {
            // 1. Validar el input (soporta que envíen 'pregunta' o 'message' para mitigar inconsistencias)
            $validated = $request->validate([
                'pregunta' => 'required_without:message|string|max:1000',
                'message'  => 'required_without:pregunta|string|max:1000',
            ]);

            $mensaje = $request->input('pregunta') ?? $request->input('message');
            $userId = auth('sanctum')->id(); // Retorna null si es invitado
            $sessionId = $this->getSessionId($request);

            // 2. Procesar con el servicio
            $respuesta = $this->chatbot->procesar($mensaje, $userId, $sessionId);

            // 3. Retornar respuesta JSON en formato estándar
            return response()->json([
                'success' => true,
                'data' => [
                    'respuesta' => $respuesta,
                    'session_id' => $sessionId // Útil para que el frontend lo guarde
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar el mensaje.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Obtiene el historial del chat actual (Autenticado o Invitado).
     */
    public function getHistory(Request $request)
    {
        try {
            $userId = auth('sanctum')->id();
            $sessionId = $this->getSessionId($request);

            $historial = $this->chatbot->getHistory($userId, $sessionId);
            $sessionInfo = \App\Models\ChatSession::where('session_id', $sessionId)->first();

            return response()->json([
                'success' => true,
                'data' => $historial,
                'session_id' => $sessionId,
                'session' => $sessionInfo
            ]);
        } catch (\Exception $e) {
             return response()->json([
                'success' => false,
                'message' => 'Error al obtener el historial.',
            ], 500);
        }
    }

    /**
     * Limpia el historial actual de la base de datos.
     */
    public function clearHistory(Request $request)
    {
        try {
            $userId = auth('sanctum')->id();
            $sessionId = $this->getSessionId($request);

            $this->chatbot->clearHistory($userId, $sessionId);

            return response()->json([
                'success' => true,
                'message' => 'Historial de chat eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al limpiar el historial.',
            ], 500);
        }
    }

    /**
     * Obtiene todas las sesiones del usuario actual.
     */
    public function getSessions(Request $request)
    {
        try {
            $userId = auth('sanctum')->id();
            $sessionId = $this->getSessionId($request);

            $query = \App\Models\ChatSession::query();
            
            if ($userId) {
                // Si está logueado, traer sus sesiones (no archivadas por defecto)
                $query->where('user_id', $userId);
            } else {
                // Si es invitado, traer solo la sesión actual
                $query->where('session_id', $sessionId);
            }

            // Si el request pide archivados
            if ($request->has('archived')) {
                $query->where('is_archived', true);
            } else {
                $query->where('is_archived', false);
            }

            return response()->json([
                'success' => true,
                'data' => $query->orderBy('updated_at', 'desc')->get()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener sesiones'], 500);
        }
    }

    /**
     * Actualiza el título o el estado de archivado de una sesión.
     */
    public function updateSession(Request $request, $sessionId)
    {
        try {
            $session = \App\Models\ChatSession::where('session_id', $sessionId)->firstOrFail();
            
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'is_archived' => 'sometimes|boolean'
            ]);

            $session->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sesión actualizada correctamente',
                'data' => $session
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar sesión'], 500);
        }
    }
}
