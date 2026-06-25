<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('organizations')
                ->nullOnDelete();
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('slug')->unique();
            $table->string('registration_number', 100)->nullable();
            $table->string('vat_number', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('status', 30)->default('trial')->index();
            $table->string('timezone', 100)->default('Europe/Amsterdam');
            $table->char('currency', 3)->default('EUR');
            $table->char('country_code', 2)->default('NL')->index();
            $table->jsonb('settings')->nullable();
            $table->timestampTz('onboarded_at')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->softDeletesTz();

            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
