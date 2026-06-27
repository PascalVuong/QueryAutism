<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('code', 50);
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('status', 30)->default('draft');
            $table->string('billing_interval', 30)->default('none');
            $table->decimal('price', 12, 2)->default(0);
            $table->char('currency', 3)->default('EUR');
            $table->integer('booking_window_days')->default(0);
            $table->integer('max_active_reservations')->nullable();
            $table->integer('max_guests_per_reservation')->nullable();
            $table->integer('priority')->default(0);
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->jsonb('settings')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->softDeletesTz();

            $table->unique(['organization_id', 'code']);
            $table->index(['organization_id', 'status']);
            $table->index('priority');
            $table->index(['valid_from', 'valid_until']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }
};
