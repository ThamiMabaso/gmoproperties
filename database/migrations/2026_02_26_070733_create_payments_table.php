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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->string('payment_reference')->unique();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'debit_order', 'payfast', 'paygate', 'other'])->default('bank_transfer');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('transaction_id')->nullable(); // External payment gateway transaction ID
            $table->json('payment_metadata')->nullable(); // Additional payment data
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index(['tenant_id', 'payment_date']);
            $table->index('payment_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
