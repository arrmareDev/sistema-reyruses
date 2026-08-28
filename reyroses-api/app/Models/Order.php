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

    /**
     * Marca la venta a crédito como pagada.
     */
    public function marcarComoPagado(): void
    {
        $this->update(['estado_pago' => 'Pagado']);
    }
}
