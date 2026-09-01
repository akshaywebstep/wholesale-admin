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
        Schema::create('email_configs', function (Blueprint $table) {
            $table->id();
            $table->string('module', 100)->default('customer'); // e.g. customer, admin
            $table->string('action', 100); // e.g. forgot-password
            $table->string('subject', 255);
            $table->longText('html_template');
            $table->string('smtp_host', 255)->nullable();
            $table->string('smtp_secure', 50)->nullable(); // e.g. ssl, tls, 1
            $table->integer('smtp_port')->nullable(); // e.g. 465, 587
            $table->string('smtp_username', 255)->nullable();
            $table->string('smtp_password', 255)->nullable();
            $table->string('from_email', 255)->nullable();
            $table->string('from_name', 255)->nullable();
            $table->tinyInteger('status')->default(1); // 1: active, 0: inactive
            $table->text('variables')->nullable(); // JSON or text of supported placeholders
            $table->string('to', 255)->nullable(); // Optional override or default recipient
            $table->text('cc')->nullable(); // JSON or comma-separated emails
            $table->text('bcc')->nullable(); // JSON or comma-separated emails
            $table->timestamps();

            $table->index(['module', 'action', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_configs');
    }
};
