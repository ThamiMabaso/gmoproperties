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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('application_id')->nullable()->constrained('tenant_applications')->nullOnDelete();
            $table->string('contract_number')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('monthly_rent', 10, 2);
            $table->decimal('deposit', 10, 2)->default(0);
            $table->json('terms')->nullable(); // Contract terms and conditions
            $table->enum('status', ['draft', 'pending_signature', 'active', 'expired', 'terminated', 'renewed'])->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->string('signed_document_path')->nullable(); // Path to signed PDF
            $table->foreignId('signed_by_tenant')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('signed_by_company')->nullable()->constrained('users')->nullOnDelete();
            $table->text('termination_reason')->nullable();
            $table->date('termination_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index(['tenant_id', 'status']);
            $table->index('contract_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
