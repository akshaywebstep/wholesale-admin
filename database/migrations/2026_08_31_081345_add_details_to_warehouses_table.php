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
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('manager_name')->nullable()->after('location');
            $table->string('contact_phone')->nullable()->after('manager_name');
            $table->string('contact_email')->nullable()->after('contact_phone');
            $table->string('operating_hours')->nullable()->after('contact_email');
            $table->text('dispatch_notes')->nullable()->after('operating_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn([
                'manager_name',
                'contact_phone',
                'contact_email',
                'operating_hours',
                'dispatch_notes',
            ]);
        });
    }
};
