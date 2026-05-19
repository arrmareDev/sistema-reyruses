<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; // <-- ¡MUY IMPORTANTE! Agregamos el modelo Product
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Función para ver los pedidos (Para el Panel Admin)
    public function index()
    {
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

                // Vue manda el ID como "12-50" (ID-Tallo). Extraemos el ID real (12).
                $realProductId = explode('-', $item['id'])[0];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $realProductId,
                    'product_name' => $item['name'], // Viene como "Rosa Explorer (50cm)"
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

    // Función para actualizar el estado del pedido y MANEJAR EL STOCK
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pendiente,Pagado,Cancelado'
        ]);

        // Traemos el pedido con sus items para saber qué descontar
        $order = Order::with('items')->findOrFail($id);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        DB::beginTransaction();
        try {
            // Actualizamos el estado del pedido a Pagado o Cancelado
            $order->update(['status' => $newStatus]);

            // ESCENARIO 1: DESCONTAR STOCK (Si pasa a Pagado y antes no lo estaba)
            if ($newStatus === 'Pagado' && $oldStatus !== 'Pagado') {
                foreach ($order->items as $item) {

                    // Magia Regex: Extraer los cm del nombre (ej: "Rosa (50cm)" -> "50")
                    preg_match('/\((\d+)cm\)/', $item->product_name, $matches);

                    if (count($matches) >= 2) {
                        $stemLength = $matches[1]; // Acá tenemos el 50, 60, etc.
                        $product = Product::find($item->product_id);

                        if ($product) {
                            // Le restamos la cantidad comprada a la columna exacta (ej: stock_50)
                            $product->decrement('stock_' . $stemLength, $item->quantity);
                        }
                    }
                }
            }

            // ESCENARIO 2: DEVOLVER STOCK (Si se cancela un pedido que YA estaba pagado)
            if ($newStatus === 'Cancelado' && $oldStatus === 'Pagado') {
                foreach ($order->items as $item) {

                    preg_match('/\((\d+)cm\)/', $item->product_name, $matches);

                    if (count($matches) >= 2) {
                        $stemLength = $matches[1];
                        $product = Product::find($item->product_id);

                        if ($product) {
                            // Le devolvemos el stock al inventario
                            $product->increment('stock_' . $stemLength, $item->quantity);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Estado y stock actualizados con éxito']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el estado y stock'], 500);
        }
    }
}
