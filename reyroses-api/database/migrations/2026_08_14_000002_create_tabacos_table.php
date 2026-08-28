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
        Schema::create('tabacos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('compra_id')
                ->constrained('compras')
                ->onDelete('cascade');

            // Un tabaco es una sola variedad (el Product define tipo nacional/exportación vía su categoría)
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('restrict');

            // Costo de este tabaco en particular, en dólares
            $table->decimal('costo_tabaco_usd', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabacos');
    }
};
