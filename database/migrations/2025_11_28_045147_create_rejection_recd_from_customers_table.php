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
        Schema::create('rejection_recd_from_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->string('vou_no')->nullable();
            $table->date('vou_date')->nullable();
            $table->string('job_no')->nullable();
            $table->string('item_code')->nullable();
            $table->string('kid')->nullable();
            $table->string('qty')->nullable();
            $table->string('gross_wt')->nullable();
            $table->string('net_wt')->nullable();
            $table->string('reason')->nullable();
            $table->string('rej_lab_chg')->nullable();
            $table->string('rej_st_chg')->nullable();
            $table->string('rej_add_lab')->nullable();
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
        Schema::dropIfExists('rejection_recd_from_customers');
    }
};
