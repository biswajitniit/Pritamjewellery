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
        Schema::create('metalpurities', function (Blueprint $table) {
            $table->id('purity_id');
            $table->unsignedBigInteger('metal_id');
            $table->foreign('metal_id')->references('metal_id')->on('metals')->onDelete('cascade');
            $table->string('purity')->nullable();
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
        Schema::dropIfExists('metalpurities');
    }
};
