<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CompraController;
use App\Http\Controllers\Api\CajaController;
use App\Http\Controllers\Api\InversionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\InventarioController;

// --- RUTAS PÚBLICAS (Cualquiera las puede ver o usar) ---

// Login para el admin
Route::post('/login', [AuthController::class, 'login']);

// Ver productos (Esto es lo que usará tu Landing Page)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Ver categorías (para los selects del panel y de la landing)
Route::get('/categories', [CategoryController::class, 'index']);

// Ruta pública para que la Landing guarde el pedido silenciosamente
Route::post('/orders', [OrderController::class, 'store']);

// Subir el voucher de pago — pública también, para que el cliente pueda
// adjuntarlo desde la landing sin necesitar una sesión de admin
Route::post('/orders/{id}/voucher', [OrderController::class, 'uploadVoucher']);


// --- RUTAS PROTEGIDAS (Solo el admin con Token puede entrar) ---

Route::middleware('auth:sanctum')->group(function () {

    // Solo permitimos crear, actualizar y borrar aquí adentro
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Gestión de categorías (crear/editar/eliminar)
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Ruta protegida para que el panel de administrador lea todos los pedidos
    Route::get('/orders', [OrderController::class, 'index']);

    // Ruta para actualizar el estado del pedido
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);

    // Marcar una venta al crédito como pagada
    Route::put('/orders/{id}/marcar-pagado', [OrderController::class, 'marcarPagado']);

    // Cerrar sesión (revoca el token actual)
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    });

    // --- Compras (tabacos desde Ecuador) ---
    Route::get('/compras', [CompraController::class, 'index']);
    Route::post('/compras', [CompraController::class, 'store']);
    Route::get('/compras/{id}', [CompraController::class, 'show']);
    Route::put('/compras/{id}', [CompraController::class, 'update']);
    Route::delete('/compras/{id}', [CompraController::class, 'destroy']);
    Route::post('/compras/{id}/recibir', [CompraController::class, 'recibir']);

    // --- Caja ---
    Route::get('/caja/movimientos', [CajaController::class, 'movimientos']);
    Route::post('/caja/movimientos', [CajaController::class, 'guardarMovimiento']);
    Route::get('/caja/arqueos', [CajaController::class, 'arqueos']);
    Route::post('/caja/arqueos', [CajaController::class, 'guardarArqueo']);

    // --- Fondo de inversión ---
    Route::get('/inversion', [InversionController::class, 'index']);
    Route::post('/inversion', [InversionController::class, 'store']);

    // --- Inventario (ajustes manuales de stock) ---
    Route::get('/inventario', [InventarioController::class, 'index']);
    Route::post('/inventario', [InventarioController::class, 'store']);

    Route::post('/save-fcm-token', function (Request $request) {
        $request->validate([
            'fcm_token' => 'required|string'
        ]);

        $request->user()->update([
            'fcm_token' => $request->fcm_token
        ]);

        return response()->json([
            'message' => 'Token guardado con éxito.'
        ]);
    });
});
