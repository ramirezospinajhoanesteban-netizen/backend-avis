<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthController;

Route::Post('/register',[AuthController::class,'register']);
Route::Post('/login/',[AuthController::class,'login']);
Route::Post('/chat',[ChatController::class,'ask']);
Route::get('/knowledge', [KnowledgeController::class, 'index']);
Route::get('/test', function () {
    return response()->json([
        'message' => 'API funcionando'
    ]);
});

Route::middleware('auth:sanctum')->group(function(){
 Route::post('/chat',[ChatController::class,'ask']);
 Route::get('/Knowledge',[KnowledgeController::class,'index']);
});
