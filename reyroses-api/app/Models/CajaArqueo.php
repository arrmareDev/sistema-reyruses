<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaArqueo extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'saldo_sistema',
        'saldo_contado',
        'diferencia',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'saldo_sistema' => 'decimal:2',
            'saldo_contado' => 'decimal:2',
            'diferencia' => 'decimal:2',
        ];
    }

    /**
     * Crea un arqueo tomando el saldo del sistema en este momento
     * y comparándolo contra lo contado físicamente.
     */
    public static function registrar(float $saldoContado, ?string $observaciones = null): self
    {
        $saldoSistema = CajaMovimiento::saldoActual();

        return static::create([
            'fecha' => now()->toDateString(),
            'saldo_sistema' => $saldoSistema,
            'saldo_contado' => $saldoContado,
            'diferencia' => round($saldoContado - $saldoSistema, 2),
            'observaciones' => $observaciones,
        ]);
    }
}
