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
        Schema::create('stock_effects', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('vou_no', 50)->nullable()->index()->comment('Voucher number or document reference');
            $table->date('metal_receive_entries_date')->nullable()->comment('Date of metal receipt or transaction');

            // Location and Ledger info
            $table->string('location_name', 100)->nullable()->index()->comment('Branch or location name');
            $table->string('ledger_name', 150)->nullable()->index()->comment('Ledger account name');
            $table->string('ledger_code', 50)->nullable()->comment('Ledger code reference');

            // Type of party
            $table->enum('ledger_type', ['Company', 'Vendor', 'Customer', 'Karigar'])
                ->default('Customer')
                ->comment('Type of associated party');

            // Metal details
            $table->string('metal_category')->nullable()->comment('Allowed values: Metal, Stone, Miscellaneous');
            $table->string('metal_name', 100)->nullable();

            // Quantities (using DECIMAL instead of string for numeric operations)
            $table->decimal('net_wt', 10, 2)->nullable()->default(0.00)->comment('Net weight of item');
            $table->decimal('purity', 6, 3)->nullable()->default(0.000)->comment('Purity percentage');
            $table->decimal('pure_wt', 10, 2)->nullable()->default(0.00)->comment('Calculated pure weight');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_effects');
    }
};
