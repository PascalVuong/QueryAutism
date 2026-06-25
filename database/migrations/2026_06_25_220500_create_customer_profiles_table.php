<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('preferred_language', 10)->default('en');
            $table->string('preferred_timezone', 100)->default('Europe/Amsterdam');
            $table->char('preferred_currency', 3)->default('EUR');
            $table->decimal('average_booking_value', 12, 2)->default(0);
            $table->integer('total_reservations')->default(0);
            $table->decimal('total_spent', 14, 2)->default(0);
            $table->integer('no_show_count')->default(0);
            $table->integer('cancellation_count')->default(0);
            $table->string('loyalty_tier', 30)->default('none')->index();
            $table->decimal('risk_score', 5, 2)->default(0)->index();
            $table->jsonb('preferences')->nullable();
            $table->timestampTz('last_recalculated_at')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_profiles');
    }
};
