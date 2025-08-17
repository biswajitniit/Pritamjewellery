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
        Schema::create('karigars', function (Blueprint $table) {
            $table->id();
            $table->string('kid')->nullable();
            $table->string('kname')->nullable();
            $table->string('kfather')->nullable();
            $table->longText('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('pan')->nullable();
            $table->string('remark')->nullable();
            $table->string('introducer')->nullable();
            $table->string('gstin')->nullable();
            $table->string('statecode')->nullable();
            $table->string('karigar_loss')->nullable();
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
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
        Schema::dropIfExists('karigars');
    }
};
