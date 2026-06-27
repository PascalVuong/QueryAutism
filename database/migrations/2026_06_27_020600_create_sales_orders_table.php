<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('venue_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('reservation_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('stock_location_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('order_number', 80);
            $table->string('status', 30)->default('draft');
            $table->timestampTz('ordered_at')->nullable();
            $table->timestampTz('fulfilled_at')->nullable();
            $table->timestampTz('cancelled_at')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->char('currency', 3)->default('EUR');
            $table->text('notes')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique(['organization_id', 'order_number']);
            $table->index(['organization_id', 'status']);
            $table->index(['venue_id', 'status']);
            $table->index(['customer_id', 'ordered_at']);
            $table->index(['reservation_id', 'status']);
            $table->index(['stock_location_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
