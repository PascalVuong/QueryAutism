<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('stock_location_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('sales_order_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('sales_order_item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('type', 30);
            $table->integer('quantity');
            $table->integer('quantity_after');
            $table->text('reason')->nullable();
            $table->timestampTz('occurred_at');
            $table->jsonb('metadata')->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->index([
                'product_variant_id',
                'stock_location_id',
                'occurred_at',
            ], 'inventory_movements_variant_location_time_index');
            $table->index(['sales_order_id', 'type']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
