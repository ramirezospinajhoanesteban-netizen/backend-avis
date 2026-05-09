<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
Route::post('/verify-recovery-code', [ForgotPasswordController::class, 'verifyCode']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

// Rutas Protegidas — Usuarios Autenticados
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/chat', [ChatController::class, 'sendMessage']);
    Route::get('/chat/history', [ChatController::class, 'getHistory']);
    Route::delete('/chat/history', [ChatController::class, 'clearHistory']);
    
    // Sesiones de Chat (Renombrar/Archivar)
    Route::get('/chat/sessions', [ChatController::class, 'getSessions']);
    Route::put('/chat/sessions/{sessionId}', [ChatController::class, 'updateSession']);
});

// Rutas Protegidas — Solo Administradores
Route::middleware(['auth:sanctum', \App\Http\Middleware\CheckAdmin::class])->group(function () {
    // Gestión de Usuarios
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Gestión de Base de Conocimiento (Escritura)
    Route::post('/knowledge', [KnowledgeController::class, 'store']);
    Route::put('/knowledge/{id}', [KnowledgeController::class, 'update']);
    Route::delete('/knowledge/{id}', [KnowledgeController::class, 'destroy']);
    
    // Estadísticas
    Route::get('/dashboard', [DashboardController::class, 'stats']);
});

// Rutas Públicas — Solo Lectura
Route::get('/knowledge', [KnowledgeController::class, 'index']);