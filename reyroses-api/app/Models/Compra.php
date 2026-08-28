<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'proveedor',
        'estado',
        'tipo_cambio',
        'comision_western',
        'pago_trabajadores',
        'saldo_inversion_usado',
    ];

    // Los cálculos son accessors (no columnas) — hay que declararlos para que salgan en el JSON
    protected $appends = [
        'subtotal_tabacos_usd',
        'subtotal_tabacos_pen',
        'comision_tarjeta',
        'costo_total',
        'ingreso_proyectado',
        'ganancia_estimada',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'tipo_cambio' => 'decimal:4',
            'comision_western' => 'decimal:2',
            'pago_trabajadores' => 'decimal:2',
            'saldo_inversion_usado' => 'decimal:2',
        ];
    }

    public function tabacos()
    {
        return $this->hasMany(Tabaco::class);
    }

    public function movimientosInversion()
    {
        return $this->hasMany(SaldoInversionMovimiento::class);
    }

    // --- Cálculos (nada se guarda duplicado, todo sale de los tabacos/desglose) ---

    /**
     * Suma de lo que costaron todos los tabacos de esta compra, en dólares.
     */
    public function getSubtotalTabacosUsdAttribute(): float
    {
        return (float) $this->tabacos->sum('costo_tabaco_usd');
    }

    /**
     * El subtotal en dólares, convertido a soles con el tipo de cambio de la compra.
     */
    public function getSubtotalTabacosPenAttribute(): float
    {
        return round($this->subtotal_tabacos_usd * (float) $this->tipo_cambio, 2);
    }

    /**
     * Comisión de tarjeta: 5% solo sobre el subtotal de los tabacos (en soles).
     */
    public function getComisionTarjetaAttribute(): float
    {
        return round($this->subtotal_tabacos_pen * 0.05, 2);
    }

    /**
     * Costo real de la compra. El saldo de inversión usado NO entra aquí:
     * es de dónde sale la plata, no un costo adicional.
     */
    public function getCostoTotalAttribute(): float
    {
        return round(
            $this->subtotal_tabacos_pen
                + $this->comision_tarjeta
                + (float) ($this->comision_western ?? 0)
                + (float) $this->pago_trabajadores,
            2
        );
    }

    /**
     * Lo que valen todos los paquetes de esta compra si se vendieran
     * al precio de catálogo actual de cada variedad/tallo.
     */
    public function getIngresoProyectadoAttribute(): float
    {
        $total = 0;

        foreach ($this->tabacos as $tabaco) {
            foreach ($tabaco->desglose as $item) {
                $precioTallo = $tabaco->product->precioPorTallo($item->tallo);
                $total += (float) ($precioTallo ?? 0) * $item->cantidad_paquetes;
            }
        }

        return round($total, 2);
    }

    /**
     * Ganancia estimada = ingreso proyectado - costo total.
     */
    public function getGananciaEstimadaAttribute(): float
    {
        return round($this->ingreso_proyectado - $this->costo_total, 2);
    }

    /**
     * Marca la compra como recibida: aumenta el stock de cada Product por tallo
     * y registra el retiro del fondo de inversión si corresponde.
     * No hace nada si la compra ya estaba Recibida (evita duplicar el stock).
     */
    public function recibir(): void
    {
        if ($this->estado === 'Recibida') {
            return;
        }

        DB::transaction(function () {
            $this->load('tabacos.desglose', 'tabacos.product');

            foreach ($this->tabacos as $tabaco) {
                foreach ($tabaco->desglose as $item) {
                    $tabaco->product->increment('stock_' . $item->tallo, $item->cantidad_paquetes);
                }
            }

            if ((float) $this->saldo_inversion_usado > 0) {
                $this->movimientosInversion()->create([
                    'fecha' => now()->toDateString(),
                    'tipo' => 'retiro',
                    'monto' => $this->saldo_inversion_usado,
                    'descripcion' => 'Retiro para financiar compra #' . $this->id,
                ]);
            }

            $this->update(['estado' => 'Recibida']);
        });
    }
}
