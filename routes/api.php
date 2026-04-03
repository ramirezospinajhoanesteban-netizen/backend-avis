<?php

use Illuminate\Support\Facades\Route;
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

// Rutas Protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/chat', [ChatController::class, 'sendMessage']);
    Route::get('/chat/history', [ChatController::class, 'getHistory']);
    Route::delete('/chat/history', [ChatController::class, 'clearHistory']);
});

Route::middleware(['auth:sanctum', \App\Http\Middleware\CheckAdmin::class])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});

Route::get('/knowledge', [KnowledgeController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'stats']);
