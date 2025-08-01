<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $product = new Product;
            $product->id = Str::uuid()->toString();
            $product->name = "Product $i";
            $product->description = $i % 2 === 0 ? "hot" : "new";
            $product->price = rand(10, 100);
            $product->category = 'shoes';
            $product->gender = 'unisex';
            $product->discount_rate = rand(0, 30);
            $product->tax_rate = 10;
            $product->inventory_count = rand(10, 100);
            $product->image_url = '';
            $product->star = $i % 6;
            $product->created_at = now();
            $product->created_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3';
            $product->is_active = true;
            $product->updated_at = now();
            $product->deleted_at = null;
            $product->deleted_by = null;
            $product->save();
        }
    }
}
