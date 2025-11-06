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
        Schema::create('finishedproductpdis', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->string('vou_no')->nullable();
            $table->date('date')->nullable();

            $table->string('job_no')->nullable();
            $table->string('item_code')->nullable();
            $table->string('qty')->nullable();
            $table->string('uom')->nullable();
            $table->string('size')->nullable();
            $table->string('net_wt')->nullable();
            $table->string('purity')->nullable();
            $table->string('rate')->nullable();
            $table->string('a_lab')->nullable();
            $table->string('stone_chg')->nullable();
            $table->string('loss')->nullable();
            $table->string('kid')->nullable();
            $table->enum('delivered_stock_out', ['Yes', 'No'])->default('No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finishedproductpdis');
    }
};
