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
        Schema::create('finishproductreceivedentryitems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fprentries_id');
            $table->foreign('fprentries_id')->references('id')->on('finishproductreceivedentries')->onDelete('cascade');
            $table->foreignId('financial_year_id')
                ->nullable()
                ->constrained('financial_years')
                ->restrictOnDelete();
            $table->string('barcode')->nullable();
            $table->string('job_no')->nullable();
            $table->string('item_code')->nullable();
            $table->string('design')->nullable();
            $table->string('description')->nullable();
            $table->string('size')->nullable();
            $table->string('uom')->nullable();
            $table->string('qty')->nullable();
            $table->string('receive_qty_from_karigar')->nullable();
            $table->string('purity')->nullable();
            $table->string('gross_wt')->nullable();
            $table->string('st_weight')->nullable();
            $table->string('k_excess')->nullable();
            $table->string('mina')->nullable();
            $table->string('loss_percentage')->nullable();
            $table->string('loss_wt')->nullable();
            $table->string('pure')->nullable();
            $table->string('net')->nullable();
            $table->enum('deliverery_status', ['pending', 'deliver'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finishproductreceivedentryitems');
    }
};
