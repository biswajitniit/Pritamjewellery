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
        Schema::create('metalissueentries', function (Blueprint $table) {
            $table->id('metal_issue_entries_id');
            $table->string('metalissueentries_id')->nullable();

            //$table->string('metal_category')->nullable();

            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->string('voucher_no')->nullable();
            $table->date('metal_issue_entries_date')->nullable();

            $table->unsignedBigInteger('karigar_id');
            $table->foreign('karigar_id')->references('id')->on('karigars')->onDelete('cascade');
            $table->string('karigar_name')->nullable();

            // $table->unsignedBigInteger('metal_id');
            // $table->foreign('metal_id')->references('metal_id')->on('metals')->onDelete('cascade');

            // $table->unsignedBigInteger('purity_id');
            // $table->foreign('purity_id')->references('purity_id')->on('metalpurities')->onDelete('cascade');
            $table->string('item_type')->nullable()->comment('Allowed values: Metal, Stone, Miscellaneous');
            $table->unsignedBigInteger('item');
            $table->foreignId('purity_id')->nullable()->constrained('metalpurities', 'purity_id')->restrictOnDelete();

            $table->string('converted_purity')->nullable();

            $table->string('weight')->nullable();
            $table->string('alloy_gm')->nullable();
            $table->string('netweight_gm')->nullable();


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
        Schema::dropIfExists('metalissueentries');
    }
};
