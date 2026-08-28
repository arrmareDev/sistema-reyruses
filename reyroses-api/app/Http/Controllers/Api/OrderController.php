<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // Función para ver los pedidos (Para el Panel Admin)
    public function index(Request $request)
    {
        $esFiltroCredito = $request->tipo_pago === 'Credito' && $request->estado_pago === 'Pendiente';

        $query = Order::with('items');

        // Cuentas por Cobrar quiere ver primero lo más urgente (fecha límite más próxima)
        if ($esFiltroCredito) {
            $query->orderBy('fecha_limite_pago', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Filtros opcionales: usados por la vista de Cuentas por Cobrar
        // (tipo_pago=Credito&estado_pago=Pendiente) y por las pestañas de Pedidos (status).
        if ($request->filled('tipo_pago')) {
            $query->where('tipo_pago', $request->tipo_pago);
        }
        if ($request->filled('estado_pago')) {
            $query->where('estado_pago', $request->estado_pago);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // La suma tiene que ser de TODAS las filas que matchean el filtro,
        // no solo de la página actual de 12 — por eso se calcula antes de paginar.
        $sumQuery = clone $query;
        $paginated = $query->paginate(12)->toArray();

        if ($esFiltroCredito) {
            $paginated['total_pendiente'] = (float) $sumQuery->sum('total_amount');
        }

        return response()->json($paginated);
    }

    // Función para crear un pedido (Landing Page o panel admin)
    public function store(Request $request)
    {
        $request->validate([
            'customer.name' => 'required|string',
            'customer.phone' => 'required|string',
            'customer.address' => 'required|string',
            'total' => 'required|numeric',
            'items' => 'required|array',
            'tipo_pago' => 'nullable|in:Contado,Credito',
            'fecha_limite_pago' => 'required_if:tipo_pago,Credito|nullable|date',
        ]);

        $tipoPago = $request->tipo_pago ?? 'Contado';

        DB::beginTransaction();
        try {
            // 1. Guardar el Pedido Principal
            $order = Order::create([
                'customer_name' => $request->customer['name'],
                'customer_phone' => $request->customer['phone'],
                'customer_address' => $request->customer['address'],
                'total_amount' => $request->total,
                'status' => 'Pendiente',
                'tipo_pago' => $tipoPago,
                'fecha_limite_pago' => $tipoPago === 'Credito' ? $request->fecha_limite_pago : null,
                'estado_pago' => $tipoPago === 'Credito' ? 'Pendiente' : 'Pagado',
            ]);

            // 2. Guardar los Detalles del pedido (las rosas)
            foreach ($request->items as $item) {
                // Vue manda el ID como "12-50" (ID-Tallo). Extraemos el ID real (12).
                $realProductId = explode('-', $item['id'])[0];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $realProductId,
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            // Si es al contado, el ingreso de caja se registra de una vez (ya está pagado)
            if ($tipoPago === 'Contado') {
                $order->registrarIngresoCaja();
            }

            try {
                // Buscamos al primer administrador que tenga un token válido
                $admin = User::whereNotNull('fcm_token')->first();

                if ($admin && $admin->fcm_token) {
                    $messaging = app('firebase.messaging');

                    // Armamos el contenido visual de la notificación
                    $notification = Notification::create(
                        'Nuevo Pedido Ingresado',
                        'Cliente: ' . $order->customer_name . ' | Total: S/ ' . $order->total_amount
                    );

                    // Preparamos el mensaje dirigido al celular/PC del admin
                    $message = CloudMessage::new()
                        ->withToken($admin->fcm_token)
                        ->withNotification($notification);

                    // Enviamos la alerta a los servidores de Google
                    $messaging->send($message);
                }
            } catch (\Throwable $e) {
                // Si Firebase explota, lo anotamos pero NO detenemos la compra
                Log::error('Error enviando notificación Push de FCM: ' . $e->getMessage());
            }

            DB::commit();
            return response()->json(['message' => 'Pedido guardado con éxito', 'order' => $order], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al guardar el pedido',
                'detalle' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
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

    /**
     * Marca una venta al crédito como pagada y registra el ingreso de caja.
     */
    public function marcarPagado($id)
    {
        $order = Order::findOrFail($id);

        if (! $order->esCredito()) {
            return response()->json(['message' => 'Esta venta no es al crédito, ya se registró como pagada.'], 422);
        }

        $order->marcarComoPagado();

        return response()->json(['message' => 'Venta marcada como pagada', 'order' => $order->fresh()]);
    }

    /**
     * Sube (o reemplaza) el voucher de pago de un pedido.
     * Pública a propósito: la landing también debe poder usarla sin login.
     */
    public function uploadVoucher(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'voucher' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        // Si ya tenía uno, lo reemplazamos sin dejar basura en el disco
        if ($order->voucher_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($order->voucher_path);
        }

        $path = $request->file('voucher')->store('vouchers', 'public');
        $order->update(['voucher_path' => $path]);

        return response()->json(['message' => 'Voucher subido con éxito', 'order' => $order->fresh()]);
    }
}
