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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->string('description', 255);
            $table->double('price');
            $table->enum('category', ['shoes', 'shirts', 'trousers', 'shorts', 'skirts', 'socks', 'accessories']);
            $table->enum('gender', ['men', 'women', 'unisex']);
            $table->integer('discount_rate')->unsigned()->default(0);
            $table->integer('tax_rate')->unsigned()->default(10);
            $table->bigInteger('inventory_count')->unsigned();
            $table->string('image_url');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('products');
    }
};
