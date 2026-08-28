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
     * Marca la compra como recibida: aumenta el stock de cada Product por tallo,
     * registra el egreso de caja por el costo total, la reposición desde el fondo
     * de inversión si corresponde (tanto en caja como en el propio fondo).
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

            // Caja financia el costo total de la compra
            $this->movimientosCaja()->create([
                'fecha' => now()->toDateString(),
                'tipo' => 'egreso',
                'monto' => $this->costo_total,
                'concepto' => 'Compra #' . $this->id . ' recibida',
            ]);

            if ((float) $this->saldo_inversion_usado > 0) {
                // El fondo de inversión repone a caja lo que puso
                $this->movimientosInversion()->create([
                    'fecha' => now()->toDateString(),
                    'tipo' => 'retiro',
                    'monto' => $this->saldo_inversion_usado,
                    'descripcion' => 'Retiro para financiar compra #' . $this->id,
                ]);

                $this->movimientosCaja()->create([
                    'fecha' => now()->toDateString(),
                    'tipo' => 'ingreso',
                    'monto' => $this->saldo_inversion_usado,
                    'concepto' => 'Reposición fondo de inversión - Compra #' . $this->id,
                ]);
            }

            $this->update(['estado' => 'Recibida']);
        });
    }

    public function movimientosCaja()
    {
        return $this->morphMany(CajaMovimiento::class, 'referenciable');
    }
}
