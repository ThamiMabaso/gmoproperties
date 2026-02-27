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
        Schema::create('tenant_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('id_number')->unique();
            $table->enum('application_type', ['student', 'working_tenant'])->default('working_tenant');
            $table->string('student_number')->nullable(); // For student applications
            $table->enum('employment_type', ['employed', 'self_employed'])->nullable(); // For working tenants
            $table->text('next_of_kin_details')->nullable(); // JSON
            $table->string('source_of_funding')->nullable(); // For students
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'withdrawn'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index('id_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_applications');
    }
};
