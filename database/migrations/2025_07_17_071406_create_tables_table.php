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
    Schema::create('tables', function (Blueprint $table) {
        $table->id();
        
        // Standardized naming (snake_case for columns)
        $table->string('table_number')->unique();
        $table->unsignedInteger('seats')->default(4);
        $table->enum('status', ['available', 'reserved', 'occupied', 'out_of_service'])
              ->default('available');
              
        // New professional fields
        $table->enum('chair_type', ['standard', 'barstool', 'booth', 'armchair'])
              ->default('standard');
        $table->string('location')->default('main_dining');
        $table->unsignedInteger('min_guests')->default(1);
        $table->unsignedInteger('max_guests');
        $table->boolean('is_smoking_allowed')->default(false);
        $table->decimal('price_per_hour', 8, 2)->nullable();
        $table->text('description')->nullable();

        // Timestamps with precision
        $table->timestamps(6); // Microsecond precision
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
