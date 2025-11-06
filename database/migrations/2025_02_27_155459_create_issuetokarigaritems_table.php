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
        Schema::create('issuetokarigaritems', function (Blueprint $table) {
            $table->id();
            $table->string('job_no')->nullable();
            $table->unsignedBigInteger('issue_to_karigar_id');
            $table->foreign('issue_to_karigar_id')->references('id')->on('issuetokarigars')->onDelete('cascade');
            $table->foreignId('financial_year_id')
                ->nullable()
                ->constrained('financial_years')
                ->restrictOnDelete();
            $table->string('item_code')->nullable();
            $table->string('design')->nullable();
            $table->string('description')->nullable();
            $table->string('size')->nullable();
            $table->string('uom')->nullable();
            $table->string('st_weight')->nullable();
            $table->string('min_weight')->nullable();
            $table->string('max_weight')->nullable();
            $table->string('qty')->nullable();
            $table->string('bal_qty')->nullable();
            // $table->unsignedBigInteger('karigar_id');
            // $table->foreign('karigar_id')->references('id')->on('karigars')->onDelete('cascade');
            $table->string('kid')->nullable();
            $table->date('delivery_date')->nullable();
            $table->enum('finish_product_received', ['Yes', 'No'])->default('No');
            $table->enum('quality_check', ['Yes', 'No'])->default('No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issuetokarigaritems');
    }
};
