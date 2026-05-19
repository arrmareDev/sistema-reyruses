<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->integer('stock_50')->default(0)->after('price_50');
        $table->integer('stock_60')->default(0)->after('price_60');
        $table->integer('stock_70')->default(0)->after('price_70');
        $table->integer('stock_80')->default(0)->after('price_80');
        $table->integer('stock_90')->default(0)->after('price_90');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['stock_50', 'stock_60', 'stock_70', 'stock_80', 'stock_90']);
    });
}
};
