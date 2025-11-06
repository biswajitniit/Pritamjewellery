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
        Schema::create('finishproductreceivedentries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karigar_id');
            $table->foreign('karigar_id')->references('id')->on('karigars')->onDelete('cascade');
            $table->string('karigar_name')->nullable();



            $table->string('bal')->nullable();
            $table->date('voucher_date')->nullable();

            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');

            $table->string('voucher_no')->nullable();
            $table->string('voucher_purity')->nullable();
            $table->string('voucher_net_wt')->nullable();
            $table->string('voucher_loss')->nullable();
            $table->string('voucher_total_wt')->nullable();
            $table->string('voucher_stone_wt')->nullable();
            $table->string('voucher_mina')->nullable();
            $table->string('voucher_kundan')->nullable();

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
        Schema::dropIfExists('finishproductreceivedentries');
    }
};