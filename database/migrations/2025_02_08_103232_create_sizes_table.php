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
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pcode_id');
            $table->foreign('pcode_id')->references('id')->on('pcodes')->onDelete('cascade');
            $table->string('schar')->nullable();
            $table->string('item_name')->nullable();
            $table->string('ssize')->nullable();
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
        Schema::dropIfExists('sizes');
    }
};
