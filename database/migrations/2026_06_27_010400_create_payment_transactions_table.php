<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('type', 30);
            $table->string('status', 30);
            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('EUR');
            $table->string('provider_reference')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestampTz('occurred_at');
            $table->jsonb('metadata')->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->index(['payment_id', 'occurred_at']);
            $table->index(['type', 'status']);
            $table->index('provider_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
