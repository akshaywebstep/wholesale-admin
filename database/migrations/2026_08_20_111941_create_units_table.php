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
        // create_units_table
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');       // e.g. Kilogram, Litre, Piece, Pack, Box
            $table->string('short_code'); // e.g. kg, L, pc, pack, box
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
