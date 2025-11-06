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
        Schema::create('rolepermissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rolepermissionuser_id');
            $table->foreign('rolepermissionuser_id')->references('id')->on('rolepermissionusers')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('menu_name')->nullable();
            $table->string('menu_permissions')->nullable();
            $table->enum('permissions_add', ['1', '0'])->nullable(); // 1 = Permission, 0 = No
            $table->enum('permissions_edit', ['1', '0'])->nullable(); // 1 = Permission, 0 = No
            $table->enum('permissions_delete', ['1', '0'])->nullable(); // 1 = Permission, 0 = No
            $table->enum('permissions_view', ['1', '0'])->nullable(); // 1 = Permission, 0 = No
            $table->enum('permissions_print', ['1', '0'])->nullable(); // 1 = Permission, 0 = No
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rolepermissions');
    }
};
