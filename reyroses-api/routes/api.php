<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;

// --- RUTAS PÚBLICAS (Cualquiera las puede ver o usar) ---

// Login para el admin
Route::post('/login', [AuthController::class, 'login']);

// Ver productos (Esto es lo que usará tu Landing Page)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Ruta pública para que la Landing guarde el pedido silenciosamente
Route::post('/orders', [OrderController::class, 'store']);


// --- RUTAS PROTEGIDAS (Solo el admin con Token puede entrar) ---

Route::middleware('auth:sanctum')->group(function () {

    // Solo permitimos crear, actualizar y borrar aquí adentro
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Ruta protegida para que el panel de administrador lea todos los pedidos
    Route::get('/orders', [OrderController::class, 'index']);

    // Ruta para actualizar el estado del pedido
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);

    // NUEVO: Ruta para guardar el Token de Firebase del administrador
Route::post('/save-fcm-token', function (Request $request) {
    $request->validate([
        'fcm_token' => 'required|string'  // 👈 cambiar 'token' por 'fcm_token'
    ]);

    $request->user()->update([
        'fcm_token' => $request->fcm_token  // 👈 igual aquí
    ]);

    return response()->json([
        'message' => 'Token guardado con éxito.'
    ]);
});
});
