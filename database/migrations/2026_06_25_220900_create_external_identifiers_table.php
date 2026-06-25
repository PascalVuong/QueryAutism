<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_identifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('provider', 100);
            $table->string('identifier_type', 100);
            $table->string('identifier_value');
            $table->string('normalized_value');
            $table->timestampTz('verified_at')->nullable();
            $table->string('verification_source', 50)->nullable();
            $table->timestampTz('last_checked_at')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->softDeletesTz();

            $table->index(['organization_id', 'customer_id']);
            $table->index([
                'organization_id',
                'provider',
                'identifier_type',
            ], 'external_identifiers_provider_type_index');
            $table->index('normalized_value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_identifiers');
    }
};
