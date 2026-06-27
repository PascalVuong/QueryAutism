<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 150);
            $table->string('email')->nullable();
            $table->string('role', 30)->default('guest');
            $table->string('status', 30)->default('registered');
            $table->timestampTz('checked_in_at')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['reservation_id', 'status']);
            $table->index(['customer_id', 'status']);
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_participants');
    }
};
