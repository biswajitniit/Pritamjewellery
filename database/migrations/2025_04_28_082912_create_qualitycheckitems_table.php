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
        Schema::create('qualitycheckitems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qualitychecks_id');
            $table->foreign('qualitychecks_id')->references('id')->on('qualitychecks')->onDelete('cascade');

            $table->unsignedBigInteger('karigar_id');
            $table->foreign('karigar_id')->references('id')->on('karigars')->onDelete('cascade');

            $table->string('job_no')->nullable();
            $table->string('item_code')->nullable();
            $table->string('design')->nullable();
            $table->string('description')->nullable();
            $table->string('purity')->nullable();
            $table->string('size')->nullable();
            $table->string('uom')->nullable();
            $table->string('order_qty')->nullable();
            $table->string('receive_qty')->nullable();
            $table->string('bal_qty')->nullable();

            $table->string('net_wt')->nullable();
            $table->string('rate')->nullable();
            $table->string('a_lab')->nullable();
            $table->string('stone_chg')->nullable();
            $table->string('loss')->nullable();

            $table->string('gross_wt_items')->nullable();
            $table->string('design_items')->nullable();
            $table->string('solder_items')->nullable();
            $table->string('polish_items')->nullable();
            $table->string('finish_items')->nullable();
            $table->string('mina_items')->nullable();
            $table->string('other_items')->nullable();
            $table->string('remark_items')->nullable();
            $table->enum('pdi_list', ['Yes', 'No'])->default('No');

            $table->enum('status', ['Processing', 'Complete'])->default('Processing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualitycheckitems');
    }
};
