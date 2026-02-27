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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->morphs('documentable'); // Polymorphic: can belong to application, contract, invoice, etc.
            $table->string('name');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // MIME type
            $table->unsignedBigInteger('file_size')->nullable(); // In bytes
            $table->enum('document_type', [
                'id_document',
                'proof_of_registration',
                'proof_of_income',
                'contract',
                'invoice',
                'receipt',
                'maintenance_report',
                'other'
            ])->default('other');
            $table->boolean('is_encrypted')->default(false);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'document_type']);
            // Note: morphs() already creates an index on documentable_type and documentable_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
