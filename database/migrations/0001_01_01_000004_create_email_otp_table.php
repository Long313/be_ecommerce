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
        Schema::create('email_otps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->string('otp');
            $table->string('purpose');
            $table->string('reset_token');
            $table->timestamp('expired_at');
            $table->timestamp('created_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->unique(['email', 'purpose']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
