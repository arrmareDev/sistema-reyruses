<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabaco extends Model
{
    use HasFactory;

    protected $fillable = [
        'compra_id',
        'product_id',
        'costo_tabaco_usd',
    ];

    protected $appends = ['total_paquetes'];

    protected function casts(): array
    {
        return [
            'costo_tabaco_usd' => 'decimal:2',
        ];
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function desglose()
    {
        return $this->hasMany(TabacoDesglose::class);
    }

    /**
     * Cuántos paquetes lleva repartidos este tabaco entre todos sus tallos.
     * Debe dar 14 para que el tabaco esté completo.
     */
    public function getTotalPaquetesAttribute(): int
    {
        return (int) $this->desglose->sum('cantidad_paquetes');
    }
}
