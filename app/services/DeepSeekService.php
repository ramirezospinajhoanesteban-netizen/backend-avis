<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DeepSeekService
{
    public function askAI($pregunta)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer TU_API_KEY',
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/chat/completions', [

            "model" => "deepseek-chat",

            "messages" => [
                [
                    "role" => "system",
                    "content" => "Eres un asistente experto en el SENA Colombia."
                ],
                [
                    "role" => "user",
                    "content" => $pregunta
                ]
            ]
        ]);

        return $response['choices'][0]['message']['content'] ?? "No pude generar respuesta.";
    }
}
