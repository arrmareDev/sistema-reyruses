<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('caja_movimientos', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->string('tipo'); // ingreso | egreso
            $table->decimal('monto', 10, 2);
            $table->string('concepto');

            // Referencia opcional: de dónde vino el movimiento (una Compra, una Order/venta, u otro)
            $table->nullableMorphs('referenciable');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_movimientos');
    }
};
