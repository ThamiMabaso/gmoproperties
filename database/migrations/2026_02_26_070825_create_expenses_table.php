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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('building_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('maintenance_ticket_id')->nullable()->constrained()->nullOnDelete();
            $table->string('expense_number')->unique();
            $table->enum('category', ['maintenance', 'utilities', 'insurance', 'taxes', 'management_fee', 'other'])->default('other');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->string('vendor')->nullable();
            $table->string('receipt_path')->nullable(); // Path to receipt document
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'expense_date']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
