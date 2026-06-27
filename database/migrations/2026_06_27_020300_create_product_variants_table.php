<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('product_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('sku', 100)->unique();
            $table->string('name');
            $table->string('barcode')->nullable()->unique();
            $table->string('status', 30)->default('active');
            $table->decimal('price', 12, 2);
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->jsonb('attributes')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
