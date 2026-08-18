<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->enum('panel', ['ADMIN', 'CUSTOMER']);
            $table->string('module'); // Product, Order, Stock, Customer
            $table->enum('action', ['CREATE', 'VIEW', 'UPDATE', 'DELETE', 'VIEW_ALL']);
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->timestamps();

            $table->index(['panel', 'module', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};