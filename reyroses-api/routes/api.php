<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController; // <-- Aquí está tu controlador

// --- RUTAS PÚBLICAS (Cualquiera las puede ver o usar) ---

// Login para el admin
Route::post('/login', [AuthController::class, 'login']);

// Ver productos (Esto es lo que usará tu Landing Page)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// NUEVO: Ruta pública para que la Landing guarde el pedido silenciosamente
Route::post('/orders', [OrderController::class, 'store']);


// --- RUTAS PROTEGIDAS (Solo el admin con Token puede entrar) ---

Route::middleware('auth:sanctum')->group(function () {

    // Solo permitimos crear, actualizar y borrar aquí adentro
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    //Ruta protegida para que el panel de administrador lea todos los pedidos
    Route::get('/orders', [OrderController::class, 'index']);
    //ruta para actualizar el estado del pedido
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
});
