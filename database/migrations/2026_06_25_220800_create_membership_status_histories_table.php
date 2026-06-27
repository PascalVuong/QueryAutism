<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->text('reason')->nullable();
            $table->foreignId('changed_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestampTz('effective_at');
            $table->jsonb('metadata')->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->index('to_status');
            $table->index('effective_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_status_histories');
    }
};
