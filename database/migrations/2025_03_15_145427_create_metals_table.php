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
        Schema::create('metals', function (Blueprint $table) {
            $table->id('metal_id');
            $table->string('metal_name')->nullable();
            $table->string('metal_category')->nullable();
            $table->string('metal_hsn')->nullable();
            $table->string('metal_sac')->nullable();
            $table->longText('description')->nullable();
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metals');
    }
};
