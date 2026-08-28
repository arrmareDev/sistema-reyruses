<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_movimientos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');

            // 50, 60, 70, 80 o 90
            $table->unsignedSmallInteger('tallo');

            // Merma | Daño | Conteo físico | Otro
            $table->string('tipo');

            // Con signo: negativo resta del stock, positivo suma (ej: conteo físico que encontró de más)
            $table->integer('cantidad');

            // Cuánto quedó el stock justo después de este movimiento — foto histórica,
            // útil para auditar sin tener que recalcular sumando movimientos viejos.
            $table->integer('stock_resultante');

            $table->text('motivo')->nullable();
            $table->date('fecha');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_movimientos');
    }
};
