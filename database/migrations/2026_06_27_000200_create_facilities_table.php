<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('venue_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('code', 50);
            $table->string('name');
            $table->string('type', 50);
            $table->string('status', 30)->default('active');
            $table->jsonb('settings')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique(['venue_id', 'code']);
            $table->index(['venue_id', 'status']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
