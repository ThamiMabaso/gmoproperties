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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject')->nullable();
            $table->text('body');
            $table->enum('type', ['message', 'notification', 'system'])->default('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->foreignId('related_entity_id')->nullable(); // For linking to contracts, invoices, etc.
            $table->string('related_entity_type')->nullable(); // Contract, Invoice, MaintenanceTicket, etc.
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'recipient_id', 'is_read']);
            $table->index(['sender_id', 'recipient_id']);
            $table->index(['related_entity_type', 'related_entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
