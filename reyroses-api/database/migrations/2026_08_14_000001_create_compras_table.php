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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->string('proveedor')->nullable();

            // Pendiente -> Recibida (dispara el aumento de stock) -> Cancelada
            $table->string('estado')->default('Pendiente');

            // Tipo de cambio USD -> PEN usado para esta compra
            $table->decimal('tipo_cambio', 8, 4);

            // Nullable: se llena cuando la agencia confirma cuánto descontó
            $table->decimal('comision_western', 10, 2)->nullable();

            // Monto libre, un solo número por pedido de compra
            $table->decimal('pago_trabajadores', 10, 2)->default(0);

            // Cuánto de esta compra salió del fondo de inversión
            $table->decimal('saldo_inversion_usado', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
