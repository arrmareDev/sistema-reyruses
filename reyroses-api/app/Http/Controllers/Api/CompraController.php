<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with('tabacos.desglose', 'tabacos.product')
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json($compras);
    }

    public function show($id)
    {
        $compra = Compra::with('tabacos.desglose', 'tabacos.product', 'movimientosInversion')
            ->findOrFail($id);

        return response()->json($compra);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'proveedor' => 'nullable|string|max:255',
            'tipo_cambio' => 'required|numeric|min:0',
            'comision_western' => 'nullable|numeric|min:0',
            'pago_trabajadores' => 'nullable|numeric|min:0',
            'saldo_inversion_usado' => 'nullable|numeric|min:0',

            'tabacos' => 'required|array|min:1',
            'tabacos.*.product_id' => 'required|exists:products,id',
            'tabacos.*.costo_tabaco_usd' => 'required|numeric|min:0',
            'tabacos.*.desglose' => 'required|array|min:1',
            'tabacos.*.desglose.*.tallo' => 'required|integer|in:50,60,70,80,90',
            'tabacos.*.desglose.*.cantidad_paquetes' => 'required|integer|min:1',
        ]);

        // Regla de negocio: cada tabaco debe sumar exactamente 14 paquetes entre sus tallos
        foreach ($data['tabacos'] as $i => $tabaco) {
            $total = collect($tabaco['desglose'])->sum('cantidad_paquetes');

            if ($total !== 14) {
                return response()->json([
                    'message' => "El tabaco #" . ($i + 1) . " suma {$total} paquetes, debe sumar exactamente 14.",
                ], 422);
            }
        }

        $compra = DB::transaction(function () use ($data) {
            $compra = Compra::create([
                'fecha' => $data['fecha'],
                'proveedor' => $data['proveedor'] ?? null,
                'tipo_cambio' => $data['tipo_cambio'],
                'comision_western' => $data['comision_western'] ?? null,
                'pago_trabajadores' => $data['pago_trabajadores'] ?? 0,
                'saldo_inversion_usado' => $data['saldo_inversion_usado'] ?? 0,
            ]);

            foreach ($data['tabacos'] as $tabacoData) {
                $tabaco = $compra->tabacos()->create([
                    'product_id' => $tabacoData['product_id'],
                    'costo_tabaco_usd' => $tabacoData['costo_tabaco_usd'],
                ]);

                foreach ($tabacoData['desglose'] as $item) {
                    $tabaco->desglose()->create([
                        'tallo' => $item['tallo'],
                        'cantidad_paquetes' => $item['cantidad_paquetes'],
                    ]);
                }
            }

            return $compra;
        });

        $compra->load('tabacos.desglose', 'tabacos.product');

        return response()->json(['message' => 'Compra registrada con éxito', 'compra' => $compra], 201);
    }

    /**
     * Solo permite editar los campos financieros (ej: comisión del Western
     * cuando la agencia la confirma). Los tabacos no se tocan aquí.
     */
    public function update(Request $request, $id)
    {
        $compra = Compra::findOrFail($id);

        $data = $request->validate([
            'proveedor' => 'nullable|string|max:255',
            'tipo_cambio' => 'sometimes|numeric|min:0',
            'comision_western' => 'nullable|numeric|min:0',
            'pago_trabajadores' => 'sometimes|numeric|min:0',
            'saldo_inversion_usado' => 'sometimes|numeric|min:0',
        ]);

        $compra->update($data);

        return response()->json(['message' => 'Compra actualizada con éxito', 'compra' => $compra]);
    }

    /**
     * Marca la compra como recibida: aumenta el stock de cada Product por tallo
     * y registra el retiro del fondo de inversión si corresponde.
     */
    public function recibir($id)
    {
        $compra = Compra::with('tabacos.desglose', 'tabacos.product')->findOrFail($id);

        if ($compra->estado === 'Recibida') {
            return response()->json(['message' => 'Esta compra ya fue marcada como recibida.'], 422);
        }

        $compra->recibir();

        return response()->json(['message' => 'Compra recibida: stock actualizado con éxito', 'compra' => $compra->fresh(['tabacos.desglose', 'tabacos.product'])]);
    }

    public function destroy($id)
    {
        $compra = Compra::findOrFail($id);

        if ($compra->estado === 'Recibida') {
            return response()->json(['message' => 'No se puede eliminar una compra ya recibida (el stock ya fue actualizado).'], 422);
        }

        $compra->delete();

        return response()->json(['message' => 'Compra eliminada correctamente']);
    }
}
