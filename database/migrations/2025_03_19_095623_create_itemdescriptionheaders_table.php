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
        Schema::create('itemdescriptionheaders', function (Blueprint $table) {
            $table->id();
            $table->string('itemdescriptionheaders_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('customers')->onDelete('cascade');

            $table->string('number_of_digits')->nullable();
            $table->string('value')->nullable();
            $table->string('description')->nullable();

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
        Schema::dropIfExists('itemdescriptionheaders');
    }
};
