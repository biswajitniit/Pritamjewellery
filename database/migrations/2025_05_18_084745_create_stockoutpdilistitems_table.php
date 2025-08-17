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
        Schema::create('stockoutpdilistitems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stockoutpdilist_id');
            $table->foreign('stockoutpdilist_id')->references('id')->on('stockoutpdilists')->onDelete('cascade');
            $table->unsignedBigInteger('finishedproductpdis_id');
            $table->foreign('finishedproductpdis_id')->references('id')->on('finishedproductpdis')->onDelete('cascade');
            $table->string('purity')->nullable();
            $table->string('qty')->nullable();
            $table->string('net_weight')->nullable();
            $table->string('stone_chg')->nullable();
            $table->string('lab_chg')->nullable();
            $table->string('add_lab_chg')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockoutpdilistitems');
    }
};
