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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('building_id')->constrained()->cascadeOnDelete();
            $table->string('unit_number');
            $table->string('unit_type')->nullable(); // e.g., 'studio', '1-bedroom', '2-bedroom'
            $table->decimal('monthly_rent', 10, 2);
            $table->decimal('deposit', 10, 2)->default(0);
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            $table->decimal('square_meters', 8, 2)->nullable();
            $table->json('amenities')->nullable(); // Unit-specific amenities
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'building_id']);
            $table->index('status');
            $table->unique(['building_id', 'unit_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
