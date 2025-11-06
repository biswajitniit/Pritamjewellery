<?php

use App\Enums\ItemCategoryEnum;
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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors', 'id')->restrictOnDelete();
            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->string('invoice_no');
            $table->date('purchase_on');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases', 'id')->restrictOnDelete();
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
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
    }
};
