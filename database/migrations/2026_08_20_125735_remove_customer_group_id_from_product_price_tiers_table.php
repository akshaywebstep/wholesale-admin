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
        Schema::table('product_price_tiers', function (Blueprint $table) {
            if (Schema::hasColumn('product_price_tiers', 'customer_group_id')) {
                $table->dropForeign(['customer_group_id']);
                $table->dropColumn('customer_group_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_price_tiers', function (Blueprint $table) {
            $table->foreignId('customer_group_id')->nullable()->after('product_id')->constrained('customer_groups')->nullOnDelete();
        });
    }
};
