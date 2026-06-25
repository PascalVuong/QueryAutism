<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 150);
            $table->string('phone', 30)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('avatar_path', 500)->nullable();
            $table->string('preferred_contact_method', 30)->default('email');
            $table->boolean('marketing_consent')->default(false);
            $table->jsonb('preferences')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
