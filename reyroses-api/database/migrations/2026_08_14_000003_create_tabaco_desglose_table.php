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
        Schema::create('tabaco_desglose', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tabaco_id')
                ->constrained('tabacos')
                ->onDelete('cascade');

            // 50, 60, 70, 80 o 90
            $table->unsignedSmallInteger('tallo');

            // Cuántos paquetes de este tallo trae el tabaco.
            // La suma de todas las filas de un mismo tabaco_id debe dar 14.
            $table->unsignedSmallInteger('cantidad_paquetes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabaco_desglose');
    }
};
