<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'price_50',
        'stock_50',
        'price_60',
        'stock_60',
        'price_70',
        'stock_70',
        'price_80',
        'stock_80',
        'price_90',
        'stock_90',
        'stock',
        'image_path'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tabacos()
    {
        return $this->hasMany(Tabaco::class);
    }

    /**
     * Precio de venta actual para un tallo dado (50, 60, 70, 80 o 90).
     * Usado para proyectar la ganancia de una Compra.
     */
    public function precioPorTallo(int $tallo): ?float
    {
        $campo = "price_{$tallo}";

        return isset($this->{$campo}) ? (float) $this->{$campo} : null;
    }
}
