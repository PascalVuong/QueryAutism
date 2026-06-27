<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('customer_number', 50);
            $table->string('first_name', 100);
            $table->string('last_name', 150);
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('status', 30)->default('active');
            $table->string('source', 50);
            $table->boolean('marketing_consent')->default(false);
            $table->timestampTz('registered_at')->nullable();
            $table->timestampTz('last_activity_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->softDeletesTz();

            $table->unique(['organization_id', 'customer_number']);
            $table->index(['organization_id', 'status']);
            $table->index(['organization_id', 'email']);
            $table->index(['organization_id', 'last_activity_at']);
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
