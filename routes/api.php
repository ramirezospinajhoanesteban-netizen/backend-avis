<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas Protegidas (Solo usuarios enviando Token de Login)
Route::middleware('auth:sanctum')->group(function () {
    // Endpoints del Chatbot
    Route::post('/chat', [ChatController::class, 'sendMessage']);
    Route::get('/chat/history', [ChatController::class, 'getHistory']);
    Route::delete('/chat/history', [ChatController::class, 'clearHistory']);
});


Route::get('/knowledge', [KnowledgeController::class, 'index']);