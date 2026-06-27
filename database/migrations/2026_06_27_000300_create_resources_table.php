<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('facility_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('code', 50);
            $table->string('name');
            $table->string('type', 50);
            $table->string('status', 30)->default('active');
            $table->unsignedInteger('capacity')->default(1);
            $table->boolean('is_bookable')->default(true);
            $table->jsonb('settings')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique(['facility_id', 'code']);
            $table->index(['facility_id', 'status']);
            $table->index(['is_bookable', 'status']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
