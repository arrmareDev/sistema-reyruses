<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Función para ver los pedidos (Para el Panel Admin)
    public function index()
    {
        // Traemos los pedidos con sus items, ordenados por los más recientes
        $orders = Order::with('items')->orderBy('created_at', 'desc')->get();
        return response()->json($orders);
    }

    // Función para crear un pedido (Para la Landing Page)
    public function store(Request $request)
    {
        $request->validate([
            'customer.name' => 'required|string',
            'customer.phone' => 'required|string',
            'customer.address' => 'required|string',
            'total' => 'required|numeric',
            'items' => 'required|array',
        ]);

        // Usamos una transacción para que si algo falla, no se guarde por la mitad
        DB::beginTransaction();
        try {
            // 1. Guardar el Pedido Principal
            $order = Order::create([
                'customer_name' => $request->customer['name'],
                'customer_phone' => $request->customer['phone'],
                'customer_address' => $request->customer['address'],
                'total_amount' => $request->total,
                'status' => 'Pendiente',
            ]);

            // 2. Guardar los Detalles del pedido (las rosas)
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Pedido guardado con éxito'], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar el pedido'], 500);
        }
    }
    // Función para actualizar el estado del pedido
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pendiente,Pagado,Cancelado'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json(['message' => 'Estado actualizado con éxito']);
    }
}
