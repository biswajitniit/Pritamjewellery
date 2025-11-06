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
        Schema::create('customerordertempitems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('customerordertemps')->onDelete('cascade');
            $table->string('sl_no');
            $table->string('item_code');
            $table->string('kid')->nullable();
            $table->string('design')->nullable();
            $table->string('description')->nullable();
            $table->string('size')->nullable();
            $table->string('finding')->nullable();
            $table->string('uom')->nullable();
            $table->string('kt');
            $table->decimal('std_wt', 8, 2)->nullable();
            $table->decimal('conv_wt', 8, 2)->nullable();
            $table->decimal('ord_qty', 8, 2)->nullable();
            $table->decimal('total_wt', 8, 2)->nullable();
            $table->decimal('lab_chg', 8, 2)->nullable();
            $table->decimal('stone_chg', 8, 2)->nullable();
            $table->decimal('add_l_chg', 8, 2)->nullable();
            $table->decimal('total_value', 10, 2)->nullable();
            $table->decimal('loss_percent', 6, 2)->nullable();
            $table->decimal('min_wt', 6, 2)->nullable();
            $table->decimal('max_wt', 6, 2)->nullable();
            $table->string('ord')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customerordertempitems');
    }
};
