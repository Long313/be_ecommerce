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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fullname', 100);
            $table->string('email')->unique();
            $table->string('phone_number', 20)->unique();
            $table->string('password');
            $table->enum('gender', ['men', 'women', 'unisex'])->default('unisex');
            $table->enum('role', ['admin', 'customer']);
            $table->string('status');
            $table->string('refresh_token');
            $table->timestamp('created_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
