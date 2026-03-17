<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/chat', [ChatController::class, 'ask']); // 👈 pública

Route::get('/knowledge', [KnowledgeController::class, 'index']);