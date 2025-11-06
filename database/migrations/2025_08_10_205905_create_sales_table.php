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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers', 'id')->restrictOnDelete();
            $table->string('invoice_no');
            $table->date('sold_on');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales', 'id')->restrictOnDelete();
            $table->morphs('itemable');
            $table->foreignId('purity_id')->nullable()->constrained('metalpurities', 'purity_id')->restrictOnDelete();
            $table->string('hsn')->nullable()->default(null);
            $table->unsignedInteger('quantity');
            $table->string('rate');
            $table->string('subtotal_amount');
            $table->string('gstin_percent');
            $table->string('gstin_amount');
            $table->string('total_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};
