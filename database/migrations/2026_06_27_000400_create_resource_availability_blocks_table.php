<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_availability_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('type', 30)->default('unavailable');
            $table->timestampTz('starts_at');
            $table->timestampTz('ends_at');
            $table->text('reason')->nullable();
            $table->foreignId('created_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->jsonb('metadata')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index([
                'resource_id',
                'starts_at',
                'ends_at',
            ], 'resource_blocks_period_index');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_availability_blocks');
    }
};
