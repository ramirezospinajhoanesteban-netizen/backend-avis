<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Knowledge;
use App\Services\DeepSeekService;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{

    public function ask(Request $request, DeepSeekService $ai)
    {
        $pregunta = strtolower($request->input('pregunta'));

        // buscar similitud en la base de conocimiento
        $resultado = DB::select("
            SELECT *, similarity(pregunta, ?) as similitud
            FROM knowledge
            ORDER BY similitud DESC
            LIMIT 1
        ", [$pregunta]);

        if ($resultado && $resultado[0]->similitud > 0.3) {

            Message::create([
                'pregunta' => $pregunta,
                'respuesta' => $resultado[0]->respuesta,
                'source' => 'database'
            ]);

            return response()->json([
                'respuesta' => $resultado[0]->respuesta,
                'source' => 'database'
            ]);
        }

        // consultar IA
        $respuestaIA = $ai->askAI($pregunta);

        Message::create([
            'pregunta' => $pregunta,
            'respuesta' => $respuestaIA,
            'source' => 'ai'
        ]);

        return response()->json([
            'respuesta' => $respuestaIA,
            'source' => 'ai'
        ]);
    }
}
