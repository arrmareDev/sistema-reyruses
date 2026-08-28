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
        Schema::create('caja_arqueos', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');

            // Saldo calculado a partir de los movimientos hasta ese momento (foto congelada)
            $table->decimal('saldo_sistema', 10, 2);

            // Lo que se contó físicamente
            $table->decimal('saldo_contado', 10, 2);

            // saldo_contado - saldo_sistema, guardado como registro histórico del cuadre
            $table->decimal('diferencia', 10, 2);

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_arqueos');
    }
};
