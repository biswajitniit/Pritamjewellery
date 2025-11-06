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
        Schema::create('vouchertypes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->enum('voucher_type', ['gold_receipt_entry', 'gold_issue_entry', 'quality_check', 'finished_goods_entry', 'return_gold_from_karigar', 'stock_transfer', 'finished_product_pdi_list','delivery_challan_no'])->default('gold_receipt_entry');
            $table->unsignedBigInteger('financial_year_id')->nullable();
            $table->foreign('financial_year_id')->references('id')->on('financial_years')->onDelete('set null');
            $table->string('applicable_year')->nullable();
            $table->date('applicable_date')->nullable();
            $table->string('startno')->nullable();
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('lastno')->nullable();
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
        Schema::dropIfExists('vouchertypes');
    }
};