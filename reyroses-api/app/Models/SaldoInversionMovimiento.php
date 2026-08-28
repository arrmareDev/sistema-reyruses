<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoInversionMovimiento extends Model
{
    use HasFactory;

    protected $table = 'saldo_inversion_movimientos';

    protected $fillable = [
        'fecha',
        'tipo', // deposito | retiro
        'monto',
        'compra_id',
        'descripcion',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'monto' => 'decimal:2',
        ];
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    /**
     * Saldo disponible: suma de depósitos menos suma de retiros, a la fecha.
     */
    public static function saldoActual(): float
    {
        $depositos = static::where('tipo', 'deposito')->sum('monto');
        $retiros = static::where('tipo', 'retiro')->sum('monto');

        return round((float) $depositos - (float) $retiros, 2);
    }
}
