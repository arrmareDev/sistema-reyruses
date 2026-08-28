<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CajaArqueo;
use App\Models\CajaMovimiento;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function movimientos()
    {
        $movimientos = CajaMovimiento::orderBy('fecha', 'desc')->orderBy('id', 'desc')->paginate(12);

        return response()->json([
            'movimientos' => $movimientos,
            'saldo_actual' => CajaMovimiento::saldoActual(),
            'total_ingresos' => (float) CajaMovimiento::where('tipo', 'ingreso')->sum('monto'),
            'total_egresos' => (float) CajaMovimiento::where('tipo', 'egreso')->sum('monto'),
        ]);
    }

    public function guardarMovimiento(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso',
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:255',
        ]);

        $movimiento = CajaMovimiento::create($data);

        return response()->json([
            'message' => 'Movimiento registrado con éxito',
            'movimiento' => $movimiento,
            'saldo_actual' => CajaMovimiento::saldoActual(),
        ], 201);
    }

    public function arqueos()
    {
        $arqueos = CajaArqueo::orderBy('fecha', 'desc')->orderBy('id', 'desc')->paginate(12);

        return response()->json($arqueos);
    }

    public function guardarArqueo(Request $request)
    {
        $data = $request->validate([
            'saldo_contado' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $arqueo = CajaArqueo::registrar($data['saldo_contado'], $data['observaciones'] ?? null);

        return response()->json(['message' => 'Arqueo registrado con éxito', 'arqueo' => $arqueo], 201);
    }
}
