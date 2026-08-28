<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventarioMovimiento;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = InventarioMovimiento::with('product')->orderBy('fecha', 'desc')->orderBy('id', 'desc');

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        return response()->json($query->paginate(12));
    }

    /**
     * Registra un ajuste manual de stock (merma, daño, conteo físico, etc.)
     * y aplica el cambio real al Product en la misma transacción.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'tallo' => 'required|integer|in:50,60,70,80,90',
            'tipo' => 'required|in:Merma,Daño,Conteo físico,Otro',
            'cantidad' => 'required|integer|not_in:0',
            'motivo' => 'nullable|string|max:500',
            'fecha' => 'required|date',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $columna = 'stock_' . $data['tallo'];
        $stockActual = (int) $product->{$columna};
        $stockResultante = $stockActual + $data['cantidad'];

        if ($stockResultante < 0) {
            return response()->json([
                'message' => "No se puede aplicar: quedaría en {$stockResultante} (stock actual: {$stockActual}). El stock no puede ser negativo.",
            ], 422);
        }

        $movimiento = DB::transaction(function () use ($data, $product, $columna, $stockResultante) {
            $product->update([$columna => $stockResultante]);

            return InventarioMovimiento::create([
                'product_id' => $data['product_id'],
                'tallo' => $data['tallo'],
                'tipo' => $data['tipo'],
                'cantidad' => $data['cantidad'],
                'stock_resultante' => $stockResultante,
                'motivo' => $data['motivo'] ?? null,
                'fecha' => $data['fecha'],
            ]);
        });

        $movimiento->load('product');

        return response()->json(['message' => 'Ajuste de inventario registrado con éxito', 'movimiento' => $movimiento], 201);
    }
}
