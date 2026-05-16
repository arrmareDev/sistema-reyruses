<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Agregamos las 5 columnas para los precios
            $table->decimal('price_50', 8, 2)->nullable();
            $table->decimal('price_60', 8, 2)->nullable();
            $table->decimal('price_70', 8, 2)->nullable();
            $table->decimal('price_80', 8, 2)->nullable();
            $table->decimal('price_90', 8, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price_50', 'price_60', 'price_70', 'price_80', 'price_90']);
        });
    }
};
