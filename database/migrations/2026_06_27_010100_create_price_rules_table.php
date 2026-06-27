<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('venue_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('resource_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('code', 80);
            $table->string('name');
            $table->string('type', 30);
            $table->string('status', 30)->default('draft');
            $table->decimal('amount', 12, 2)->nullable();
            $table->decimal('percentage', 7, 4)->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('is_stackable')->default(false);
            $table->timestampTz('starts_at')->nullable();
            $table->timestampTz('ends_at')->nullable();
            $table->jsonb('conditions')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique(['organization_id', 'code']);
            $table->index(['organization_id', 'status']);
            $table->index(['venue_id', 'status']);
            $table->index(['resource_id', 'status']);
            $table->index(['starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_rules');
    }
};
