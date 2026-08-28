<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioMovimiento extends Model
{
    use HasFactory;

    protected $table = 'inventario_movimientos';

    protected $fillable = [
        'product_id',
        'tallo',
        'tipo',
        'cantidad',
        'stock_resultante',
        'motivo',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'tallo' => 'integer',
            'cantidad' => 'integer',
            'stock_resultante' => 'integer',
            'fecha' => 'date',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
