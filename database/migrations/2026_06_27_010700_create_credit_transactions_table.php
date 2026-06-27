<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_account_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('reservation_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('type', 30);
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->text('description')->nullable();
            $table->timestampTz('occurred_at');
            $table->jsonb('metadata')->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->index([
                'credit_account_id',
                'occurred_at',
            ], 'credit_transactions_account_time_index');
            $table->index(['reservation_id', 'type']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
    }
};
