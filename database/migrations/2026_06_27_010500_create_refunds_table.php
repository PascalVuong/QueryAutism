<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('payment_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('reservation_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('requested_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('status', 30)->default('pending');
            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('EUR');
            $table->text('reason')->nullable();
            $table->string('provider_reference')->nullable();
            $table->timestampTz('processed_at')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['payment_id', 'status']);
            $table->index(['reservation_id', 'status']);
            $table->index('processed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
