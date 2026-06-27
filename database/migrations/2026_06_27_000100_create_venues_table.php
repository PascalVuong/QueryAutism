<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('status', 30)->default('active')->index();
            $table->string('timezone', 100)->default('Europe/Amsterdam');
            $table->string('address_line_1')->nullable();
            $table->string('city', 150)->nullable();
            $table->char('country_code', 2)->default('NL');
            $table->jsonb('settings')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique(['organization_id', 'slug']);
            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
