<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('product_category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('code', 80);
            $table->string('name');
            $table->string('status', 30)->default('draft');
            $table->string('product_type', 30)->default('physical');
            $table->text('description')->nullable();
            $table->decimal('tax_rate', 5, 2)->default(21);
            $table->boolean('is_stock_tracked')->default(true);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique(['organization_id', 'code']);
            $table->index(['organization_id', 'status']);
            $table->index('product_category_id');
            $table->index(['product_type', 'is_stock_tracked']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
