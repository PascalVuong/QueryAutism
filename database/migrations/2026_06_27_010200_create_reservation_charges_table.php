<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('reservation_item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('price_rule_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('type', 30);
            $table->string('direction', 20)->default('debit');
            $table->string('description');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_amount', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->char('currency', 3)->default('EUR');
            $table->jsonb('metadata')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['reservation_id', 'type']);
            $table->index('reservation_item_id');
            $table->index('price_rule_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_charges');
    }
};
