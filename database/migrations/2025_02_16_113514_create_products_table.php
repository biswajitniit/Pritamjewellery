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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('vendorsite')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('item_code')->nullable();
            $table->string('design_num')->nullable();
            $table->longText('description')->nullable();
            $table->string('pattern')->nullable();
            $table->string('size')->nullable();
            $table->string('uom')->nullable();
            $table->decimal('standard_wt', 10, 2)->default(0.00);
            $table->unsignedBigInteger('kid');
            $table->foreign('kid')->references('id')->on('karigars')->onDelete('cascade');
            $table->string('lead_time_karigar')->nullable();
            $table->string('product_lead_time')->nullable();
            $table->string('stone_charge')->nullable();
            $table->string('lab_charge')->nullable();
            $table->string('loss')->nullable();
            $table->decimal('purity', 10, 2)->default(0.00);
            $table->string('item_pic')->nullable();
            $table->string('kt')->nullable();
            $table->string('pcodechar')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('bulk_upload', ['Yes', 'No'])->default('No');
            $table->enum('customer_order', ['Yes', 'No'])->default('No');
            $table->string('karigar_loss')->nullable();
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
        Schema::dropIfExists('products');
    }
};
