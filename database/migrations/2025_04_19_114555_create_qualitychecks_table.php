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
        Schema::create('qualitychecks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karigar_id');
            $table->foreign('karigar_id')->references('id')->on('karigars')->onDelete('cascade');


            $table->string('karigar_name')->nullable();
            $table->string('type')->nullable();

            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->string('qc_voucher')->nullable();
            $table->date('qualitycheck_date')->nullable();

            $table->string('item_code')->nullable();
            $table->string('job_no')->nullable();
            $table->string('design')->nullable();
            $table->string('description')->nullable();
            $table->string('purity')->nullable();
            $table->string('size')->nullable();
            $table->string('uom')->nullable();
            $table->string('order_qty')->nullable();
            $table->string('receive_qty')->nullable();
            $table->string('bal_qty')->nullable();
            $table->enum('status', ['Processing', 'Complete'])->default('Processing');
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
        Schema::dropIfExists('qualitychecks');
    }
};
