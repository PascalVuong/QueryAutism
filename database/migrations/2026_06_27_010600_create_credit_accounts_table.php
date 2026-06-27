<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('customer_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('status', 30)->default('active');
            $table->decimal('balance', 12, 2)->default(0);
            $table->char('currency', 3)->default('EUR');
            $table->timestampTz('expires_at')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique([
                'organization_id',
                'customer_id',
                'currency',
            ], 'credit_accounts_customer_currency_unique');
            $table->index(['organization_id', 'status']);
            $table->index(['customer_id', 'status']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_accounts');
    }
};
