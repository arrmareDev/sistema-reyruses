<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // 👇 Laravel solo guardará las columnas que estén escritas en esta lista 👇
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'price_50',
        'price_60',
        'price_70',
        'price_80',
        'price_90',
        'stock',
        'image_path'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
