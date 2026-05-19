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
}
