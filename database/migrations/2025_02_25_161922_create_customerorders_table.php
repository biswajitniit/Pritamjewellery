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
        Schema::create('customerorders', function (Blueprint $table) {
            $table->id();
            $table->string('jo_no')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('jo_date')->nullable();
            $table->string('vendor_site')->nullable();
            $table->enum('order_type', ['AutoUpload', 'ManualUpload'])->default('ManualUpload');
            $table->enum('type', ['Customer', 'Regular'])->default('Customer');
            $table->enum('issue_to_karigar', ['Processing', 'Complete'])->default('Processing');
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
        Schema::dropIfExists('customerorders');
    }
};
