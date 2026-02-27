<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete(); // Null for service provider admins
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->enum('type', ['service_provider_admin', 'company_admin', 'property_manager', 'tenant'])->default('tenant');
            $table->string('id_number')->nullable(); // For tenants
            $table->string('student_number')->nullable(); // For student tenants
            $table->enum('employment_type', ['employed', 'self_employed', 'student', 'unemployed'])->nullable();
            $table->text('next_of_kin_details')->nullable(); // JSON for tenant
            $table->string('source_of_funding')->nullable(); // For student tenants
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
