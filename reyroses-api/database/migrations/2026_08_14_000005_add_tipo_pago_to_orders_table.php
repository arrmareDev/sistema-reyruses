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
        Schema::table('orders', function (Blueprint $table) {
            // Contado | Credito
            $table->string('tipo_pago')->default('Contado')->after('total_amount');

            // Solo aplica si tipo_pago = Credito
            $table->date('fecha_limite_pago')->nullable()->after('tipo_pago');

            // Pagado | Pendiente. Contado nace Pagado, Credito nace Pendiente.
            $table->string('estado_pago')->default('Pagado')->after('fecha_limite_pago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tipo_pago', 'fecha_limite_pago', 'estado_pago']);
        });
    }
};
