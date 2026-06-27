<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('reservation_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('customer_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('status', 30)->default('pending');
            $table->string('method', 30);
            $table->decimal('amount', 12, 2);
            $table->decimal('refunded_amount', 12, 2)->default(0);
            $table->char('currency', 3)->default('EUR');
            $table->string('provider_reference')->nullable();
            $table->timestampTz('paid_at')->nullable();
            $table->timestampTz('failed_at')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['organization_id', 'status']);
            $table->index(['reservation_id', 'status']);
            $table->index(['customer_id', 'status']);
            $table->index('provider_reference');
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
