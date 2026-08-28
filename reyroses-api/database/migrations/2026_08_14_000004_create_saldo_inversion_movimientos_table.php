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
        Schema::create('saldo_inversion_movimientos', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->string('tipo'); // deposito | retiro
            $table->decimal('monto', 10, 2);

            // Nullable: solo se llena cuando el retiro viene de una compra
            $table->foreignId('compra_id')
                ->nullable()
                ->constrained('compras')
                ->onDelete('set null');

            $table->text('descripcion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_inversion_movimientos');
    }
};
