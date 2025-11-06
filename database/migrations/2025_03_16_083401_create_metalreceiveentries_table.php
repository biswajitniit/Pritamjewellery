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
        Schema::create('metalreceiveentries', function (Blueprint $table) {
            $table->id('metal_receive_entries_id');
            $table->string('metalreceiveentries_id')->nullable();

            $table->foreignId('financial_year_id')
                ->nullable()
                ->constrained('financial_years')
                ->restrictOnDelete();

            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');

            $table->string('vou_no')->nullable();
            $table->date('metal_receive_entries_date')->nullable();

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('cust_name')->nullable();
            $table->longText('cust_address')->nullable();

            //$table->enum('metal_category', ['Gold', 'Finding', 'Alloy', 'Miscellaneous'])->nullable()->default(null);

            //$table->unsignedBigInteger('metal_id');
            //$table->foreign('metal_id')->references('metal_id')->on('metals')->onDelete('cascade');

            //$table->unsignedBigInteger('purity_id');
            //$table->foreign('purity_id')->references('purity_id')->on('metalpurities')->onDelete('cascade');

            $table->string('item_type')->nullable()->comment('Allowed values: Metal, Stone, Miscellaneous');

            $table->unsignedBigInteger('item');

            $table->foreignId('purity_id')->nullable()->constrained('metalpurities', 'purity_id')->restrictOnDelete();

            $table->string('weight')->nullable();
            $table->string('dv_no')->nullable();
            $table->date('dv_date')->nullable();


            $table->string('issue_qty')->nullable();
            $table->string('balance_qty')->nullable();
            $table->date('last_entry_issue_date')->nullable();
            $table->string('last_entry_issue_by')->nullable();


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
        Schema::dropIfExists('metalreceiveentries');
    }
};
