<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('stock_location_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->integer('quantity_on_hand')->default(0);
            $table->integer('quantity_reserved')->default(0);
            $table->integer('reorder_point')->default(0);
            $table->timestampsTz();

            $table->unique([
                'product_variant_id',
                'stock_location_id',
            ], 'inventory_levels_variant_location_unique');
            $table->index([
                'stock_location_id',
                'quantity_on_hand',
            ], 'inventory_levels_location_quantity_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_levels');
    }
};
