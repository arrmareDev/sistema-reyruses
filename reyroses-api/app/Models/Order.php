<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_address',
        'total_amount',
        'status',
        'tipo_pago',        // Contado | Credito
        'fecha_limite_pago', // solo si tipo_pago = Credito
        'estado_pago',      // Pagado | Pendiente
        'voucher_path',
    ];

    protected function casts(): array
    {
        return [
            'fecha_limite_pago' => 'date',
        ];
    }

    // Un pedido tiene muchos items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function esCredito(): bool
    {
        return $this->tipo_pago === 'Credito';
    }

    public function movimientosCaja()
    {
        return $this->morphMany(CajaMovimiento::class, 'referenciable');
    }

    /**
     * Registra el ingreso de caja por esta venta (una sola vez).
     */
    public function registrarIngresoCaja(): void
    {
        if ($this->movimientosCaja()->exists()) {
            return;
        }

        $this->movimientosCaja()->create([
            'fecha' => now()->toDateString(),
            'tipo' => 'ingreso',
            'monto' => $this->total_amount,
            'concepto' => 'Venta pedido #' . $this->id,
        ]);
    }

    /**
     * Marca la venta a crédito como pagada y registra el ingreso de caja.
     */
    public function marcarComoPagado(): void
    {
        $this->update(['estado_pago' => 'Pagado']);
        $this->registrarIngresoCaja();
    }
}
