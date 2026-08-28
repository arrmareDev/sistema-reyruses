<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SaldoInversionMovimiento;
use Illuminate\Http\Request;

class InversionController extends Controller
{
    public function index()
    {
        $movimientos = SaldoInversionMovimiento::with('compra')
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'movimientos' => $movimientos,
            'saldo_actual' => SaldoInversionMovimiento::saldoActual(),
        ]);
    }

    /**
     * Deposita capital al fondo. Los retiros no se hacen manualmente aquí:
     * se generan solos cuando una Compra se marca como Recibida.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0.01',
            'descripcion' => 'nullable|string',
        ]);

        $movimiento = SaldoInversionMovimiento::create([
            'fecha' => $data['fecha'],
            'tipo' => 'deposito',
            'monto' => $data['monto'],
            'descripcion' => $data['descripcion'] ?? null,
        ]);

        return response()->json([
            'message' => 'Depósito registrado con éxito',
            'movimiento' => $movimiento,
            'saldo_actual' => SaldoInversionMovimiento::saldoActual(),
        ], 201);
    }
}
