<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\TransaccionApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    // La ruta específica debe declararse antes de apiResource para evitar
    // que "filtrar" sea interpretado como el parámetro {transaccion}.
    Route::get('/transacciones/filtrar', [TransaccionApiController::class, 'filtrar']);
    Route::apiResource('transacciones', TransaccionApiController::class)->only(['index', 'show']);
});
