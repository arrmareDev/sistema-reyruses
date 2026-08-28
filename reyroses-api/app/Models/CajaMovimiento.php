<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaMovimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'tipo', // ingreso | egreso
        'monto',
        'concepto',
        'referenciable_type',
        'referenciable_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'monto' => 'decimal:2',
        ];
    }

    /**
     * De dónde vino el movimiento: una Compra, una Order (venta), o nada (movimiento suelto).
     */
    public function referenciable()
    {
        return $this->morphTo();
    }

    /**
     * Saldo de caja: suma de ingresos menos egresos, a la fecha.
     */
    public static function saldoActual(): float
    {
        $ingresos = static::where('tipo', 'ingreso')->sum('monto');
        $egresos = static::where('tipo', 'egreso')->sum('monto');

        return round((float) $ingresos - (float) $egresos, 2);
    }
}
