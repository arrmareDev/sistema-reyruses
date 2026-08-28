<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabacoDesglose extends Model
{
    use HasFactory;

    protected $table = 'tabaco_desglose';

    protected $fillable = [
        'tabaco_id',
        'tallo',
        'cantidad_paquetes',
    ];

    protected function casts(): array
    {
        return [
            'tallo' => 'integer',
            'cantidad_paquetes' => 'integer',
        ];
    }

    public function tabaco()
    {
        return $this->belongsTo(Tabaco::class);
    }
}
